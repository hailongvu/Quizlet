<?php

namespace App\Http\Controllers\Public;

use App\Exports\ExportAnswerData;
use App\Exports\ExportQuizData;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class QuizController extends Controller
{
    public function index()
    {
        try {
            $quizzes = Quiz::with('questions')->get();

            return response()->json(['data' => $quizzes]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:quiz,question,answer,grade',
            ]);

            if (auth()->user()->role->name == 'teacher') {
                switch ($request->type) {
                    case 'quiz':
                        $quiz = Quiz::with('questions')->findOrFail($id);
                        return response()->json(['data' => $quiz]);
                        break;
                    case 'question':
                        $quiz = Quiz::with('questions')->findOrFail($id);
                        //get question belong to quiz
                        $questions = $quiz->questions;
                        return response()->json(['data' => $questions]);
                        break;
                    case 'answer': //for grade
                        $quiz = Quiz::with('questions')->findOrFail($id);
                        $answers = Answer::where('quiz_id', $id)->get();
                        $sumPoints = 0;
                        $answerData = [];
                        //get answer_text and points from answer_text by summing the points
                        // Loop through each answer and organize the data by user ID
                        foreach ($answers as $answer) {
                            $userId = $answer->user_id;
                            $answerText = json_decode($answer->answer_text, true);
                            if (!isset($answerData[$userId])) {
                                $answerData[$userId] = [
                                    'user_id' => $userId,
                                    'quiz_id' => $quiz->id,
                                    'answer_text' => [],
                                    'total_points' => 0
                                ];
                            }
                            $sumPoints = 0;

                            foreach ($answerText as $answer) {
                                $sumPoints += $answer['points'];
                            }

                            $answerData[$userId]['answer_text'] = $answerText;


                            $answerData[$userId]['total_points'] = $sumPoints;
                        }
                        return response()->json([
                            'data' => $answerData,
                            'message' => 'Retrieve answer data successfully',
                            'status' => 200
                        ], 200);
                        break;
                    default:
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'Invalid type'
                        ], 400);
                        break;
                }
            } else if (auth()->user()->role->name == 'student') {
                //check if value score_report of quiz is true
                $quiz = Quiz::findOrFail($id);
                if ($quiz->score_reporting == true) {
                    switch ($request->type) {
                        case 'quiz':
                            $quiz = Quiz::with('questions')->find($id);
                            $count_questions = $quiz->questions->count();
                            $data = [
                                'quiz_name' => $quiz->name,
                                'quiz_description' => $quiz->description,
                                'count_questions' => $count_questions,
                            ];
                            return response()->json(['data' => $data]);
                            break;
                        case 'question':
                            $quiz = Quiz::with('questions')->findOrFail($id);
                            $questions = $quiz->questions;
                            //return only question and answer_options
                            $questions = $questions->map(function ($question) {
                                return [
                                    'id' => $question->id,
                                    'question' => $question->question,
                                    'answer_option' => $question->answer_option,
                                ];
                            });
                            return response()->json(['data' => $questions]);
                            break;
                        case 'answer':
                            $quiz = Quiz::with('questions')->findOrFail($id);
                            $quiz = Quiz::with('questions')->findOrFail($id);
                            $answers = Answer::where('quiz_id', $id)->get();
                            $sumPoints = 0;
                            $answerData = [];
                            $userId = auth()->id();
                            //get answer_text and points from answer_text by summing the points
                            // return only answer_text of auth user
                            foreach ($answers as $answer) {
                                if ($answer->user_id == $userId) {
                                    $answerText = json_decode($answer->answer_text, true);
                                    $answerData = [
                                        'user_id' => $userId,
                                        'quiz_id' => $quiz->id,
                                        'answer_text' => $answerText,
                                        'total_points' => 0
                                    ];
                                    $sumPoints = 0;

                                    foreach ($answerText as $answer) {
                                        $sumPoints += $answer['points'];
                                    }

                                    $answerData['total_points'] = $sumPoints;
                                }
                            }
                            return response()->json([
                                'data' => $answerData,
                                'message' => 'Retrieve answer data successfully',
                                'status' => 200
                            ], 200);
                        default:
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'Invalid type'
                            ], 400);
                            break;
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 403,
                        'message' => 'You are not authorized to view this quiz.'
                    ], 403);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Invalid role'
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:quiz,answer,import',
            ]);

            // Check user role, allow only students to submit answers
            if (auth()->user()->role->name == 'student' && $request->type != 'answer') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not authorized to create quizzes.'
                ], 403);
            }
            switch ($request->type) {
                case 'import':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'status' => 'nullable|in:pending,inactive',
                        'score_reporting' => 'nullable|boolean',
                        'start_time' => 'nullable|date|after_or_equal:today',
                        'end_time' => 'nullable|date|after:start_date',
                        'quantity' => 'nullable|integer',

                    ]);
                    $lesson_id = $request->lesson_id;

                    // Call the learn method in the LessonController and get the response
                    $lessonController = new LessonController();
                    $response = $lessonController->learnForImport($request, $lesson_id);
                    // Get the data from the response
                    
                    $quantity = $request->input('quantity', 20);
                    // Check if the request was successful
                    if ($response->getStatusCode() == 200) {
                        $data = $response->getData();
                        //check input quantity 
                        // store data to new quiz
                        $quiz = Quiz::create([
                            'name' => $request->input('name'),
                            'description' => $request->input('description'),
                            'status' => $request->input('status', 'inactive'),
                            'score_reporting' => $request->input('score_reporting', true),
                            'start_time' => $request->input('start_time'),
                            'end_time' => $request->input('end_time')
                        ]);
                        //set data to question
                        // Add questions to the quiz from the $data, split part "lesson_details" from the $data


                        $lesson_details = $data->lesson_details;
                        $total_questions_added = 0;
                        foreach ($lesson_details as $questions) {
                            if($total_questions_added >= $quantity){
                                break; // Stop adding more questions
                            }
                            $question = new Question([
                                'question' => $questions->question,
                                'is_multiple_choice' => true,
                                'answer_option' => json_encode($questions->answers),
                                'correct_answer' => $questions->correct_answer,
                                'points' => 1,
                            ]);
                            
                            $quiz->questions()->save($question);
                            $total_questions_added++;
                        }

                        return response()->json([
                            'status' => 'success',
                            'status_code' => 200,
                            'message' => 'Quiz created successfully',
                            'data' => $quiz
                        ], 200);

                        //return response()->json(['message' => 'Lesson details retrieved successfully'], 200);
                    } else {
                        // return error message
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'Lesson details could not be retrieved'
                        ], 400);
                    }
                    break;
                case 'quiz':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'status' => 'required|in:pending,inactive',
                        'score_reporting' => 'nullable|boolean',
                        'start_time' => 'required|date|after_or_equal:today',
                        'end_time' => 'nullable|date|after:start_date',
                        'questions' => 'required|array',
                        'questions.*.question' => 'required|string',
                        'questions.*.is_multiple_choice' => 'nullable|boolean',
                        'questions.*.answer_option' => 'nullable|array',
                        'questions.*.correct_answer' => 'nullable|string',
                        'questions.*.points' => 'nullable|integer|',
                    ]);

                    $quiz = Quiz::create([
                        'name' => $request->input('name'),
                        'description' => $request->input('description'),
                        'status' => $request->input('status', 'inactive'),
                        'score_reporting' => $request->input('score_reporting', true),
                        'start_time' => $request->input('start_time'),
                        'end_time' => $request->input('end_time')
                    ]);

                    //create questions collection and map the questions to the newly created quiz id
                    $questions = collect($request->input('questions'))->map(function ($question) {
                        $answer_option = json_encode($question['answer_option']);
                        return new Question([
                            'question' => $question['question'],
                            'is_multiple_choice' => $question['is_multiple_choice'],
                            'answer_option' => $answer_option,
                            'correct_answer' => $question['correct_answer'],
                            //set points to 1 if points is null
                            'points' => $question['points'] ?? 1,
                        ]);
                    });

                    $quiz->questions()->saveMany($questions);

                    return response()->json([
                        'data' => $quiz,
                        'message' => 'Create new quiz with question successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'answer':
                    $request->validate([
                        'quiz_id' => 'required|integer',
                        'answers' => 'required|array',
                        'answers.*.question_id' => 'required|integer',
                        'answers.*.answer' => 'required|string',
                        'answers.*.is_correct' => 'nullable|boolean',
                        'answers.*.points' => 'nullable|integer|',
                    ]);

                    $answerData = collect($request->input('answers'))->map(function ($answer) {
                        //if question is multiple choice, is_correct is true set points to points from question table
                        //if question is multiple choice, is_correct is false set points to 0
                        //if answer is not multiple choice, is_correct will be false set points to 0
                        // if($answer['is_correct'] == true && Question::where('id', $answer['question_id'])->first()->is_multiple_choice == 1){
                        //     $points = Question::where('id', $answer['question_id'])->first()->points;
                        //     dd($points);
                        // }else{
                        //     $points = 0;
                        // }
                        return [
                            'question_id' => $answer['question_id'],
                            'answer' => $answer['answer'],
                            'is_correct' => ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])),
                            //get points from question table
                            //if is_correct is true and isMultipleChoice is true, set points to points from question table else set points to 0
                            'points' => (($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer']) && Question::where('id', $answer['question_id'])->first()->is_multiple_choice == 1)
                                ? Question::where('id', $answer['question_id'])->first()->points : 0),
                        ];
                    });
                    //store answerData to answer_text column as json text
                    $answerText = json_encode($answerData);

                    $answer = new Answer();
                    $answer->quiz_id = $request->input('quiz_id');
                    //get user id from auth
                    $answer->user_id = $request->user()->id;
                    $answer->answer_text = $answerText;
                    $answer->save();
                    return response()->json([
                        'data' => $answer,
                        'message' => 'Create new answer successfully',
                        'status' => 200
                    ], 200);
                    break;
                default:
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function exportToExcel($id)
    {
        $quiz = Quiz::findOrFail($id);;
        $answers = Answer::where('quiz_id', $quiz->id)->get();
        $data = [];
        $data_per_user = [];

        $maxPoints = Question::where('quiz_id', $quiz->id)->sum('points');
        foreach ($answers as $answer) {
            $user = User::findOrFail($answer->user_id);
            $ans = json_decode($answer->answer_text, true);

            $sumPoints = 0;
            $ques_ids = [];
            //$ans_by_user = [];
            foreach ($ans as $a) {
                $sumPoints += $a['points'];
                $ques_ids[] = $a['question_id'];
                //$ans_by_user = $a['answer'];
                $data_per_user[$a['answer']][] = [
                    'quiz_id' => $quiz->id,
                    'quiz_name' => $quiz->name,
                    'user_name' => $user->name,
                    'question' => Question::findOrFail($a['question_id'])->question,
                    'answer' => $a['answer'],
                    'is_correct' => $a['is_correct'],
                ];
            }
            $data[] = [
                'quiz_id' => $quiz->id,
                'quiz_name' => $quiz->name,
                'user_name' => $user->name,
                'quiz_total_points' => $sumPoints,
                'quiz_max_points' => $maxPoints,
                'questions' => Question::whereIn('id', $ques_ids)->get(),
                'answers' => $ans,
            ];
        }
        // Create a new Excel workbook
        $filename = 'quiz_data.xlsx';
        $filepath = storage_path('app/export' . $filename);

        Excel::download(new class($data, $data_per_user) implements WithMultipleSheets
        {
            private $data;
            private $dataPerUser;

            public function __construct($data, $dataPerUser)
            {
                $this->data = $data;
                $this->dataPerUser = $dataPerUser;
            }

            public function sheets(): array
            {
                $sheets = [];

                // Add the main quiz data sheet
                $sheets[] = new ExportQuizData(collect($this->data));

                // Loop through each answer and add a sheet for the answer data
                foreach ($this->dataPerUser as $answer => $group_data_by_user) {
                    $sheet_name = str_replace(['\\', '/', '?', '*', '[', ']'], '', $answer); // Clean up answer string for sheet name
                    $sheet_name = substr($sheet_name, 0, 30); // Truncate to 30 characters, maximum sheet name length
                    $sheet_name = trim($sheet_name); // Trim any whitespace

                    $sheets[] = new ExportAnswerData(collect($group_data_by_user), $sheet_name);
                }

                return $sheets;
            }
        }, $filename)->deleteFileAfterSend(true);
        // Return a success message
        return response()->json(['success' => true]);
    }
    //method to compare answer with correct answer
    public function compareAnswer($questionId, $correctAnswer, $answer)
    {

        //get question type from question table
        $questionType = Question::where('id', $questionId)->first()->is_multiple_choice;
        //if question type is multiple choice, get correct_answer from question table and compare with answer
        if ($questionType) {
            if ($correctAnswer == $answer) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    //method check if question is multiple choice
    public function isMultipleChoice($questionId)
    {
        $questionType = Question::where('id', $questionId)->first()->is_multiple_choice;
        if ($questionType) {
            return true;
        }
        return false;
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'type' => 'required|string|in:quiz,updateQuestions,grade,addQuestion,addAnswer',
            ]);
            if (auth()->user()->role->name == 'student') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not authorized to update.'
                ], 403);
            }
            if (auth()->user()->role->name == 'teacher' && $request->type == 'quiz') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not authorized to update quiz.'
                ], 403);
            }

            switch ($request->type) {
                case 'quiz':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'status' => 'required|in:active,pending,inactive',
                        'score_reporting' => 'nullable|boolean',
                        'start_time' => 'nullable|date',
                        'end_time' => 'nullable|date',
                    ]);

                    $quiz = Quiz::with('questions')->findOrFail($id);

                    $quiz->update([
                        'name' => $request->input('name', $quiz->name),
                        'description' => $request->input('description', $quiz->description),
                        'status' => $request->input('status', $quiz->status),
                        'score_reporting' => $request->input('score_reporting', $quiz->score_reporting),
                        'start_time' => $request->input('start_time', $quiz->start_time),
                        'end_time' => $request->input('end_time', $quiz->end_time),
                    ]);

                    return response()->json([
                        'data' => $quiz,
                        'message' => 'Update quiz successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'updateQuestions':
                    $request->validate([
                        'questions' => 'required|array',
                        'questions.*.question_id' => 'required|integer',
                        'questions.*.question' => 'nullable|string',
                        'questions.*.is_multiple_choice' => 'nullable|boolean',
                        'questions.*.answer_option' => 'nullable|array',
                        'questions.*.correct_answer' => 'nullable|string',
                        'questions.*.points' => 'nullable|integer|',
                    ]);
                    $questions = Question::where('quiz_id', $id)->get();
                    //loop through questions and update the questions by data from request following the question_id
                    foreach ($questions as $question) {
                        foreach ($request->input('questions') as $questionData) {
                            if ($question->id == $questionData['question_id']) {
                                $answer_option = json_encode($questionData['answer_option']);
                                $question->update([
                                    'question' => $questionData['question'] ?? $question->question,
                                    'is_multiple_choice' => $questionData['is_multiple_choice'] ?? $question->is_multiple_choice,
                                    'answer_option' => $answer_option ?? $question->answer_option,
                                    'correct_answer' => $questionData['correct_answer'] ?? $question->correct_answer,
                                    'points' => $questionData['points'] ?? $question->points,
                                ]);
                            }
                        }
                    }
                    return response()->json([
                        'data' => $questions,
                        'message' => 'Update questions successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'addQuestion':
                    $request->validate([
                        'questions' => 'required|array',
                        'questions.*.question' => 'required|string',
                        'questions.*.is_multiple_choice' => 'required|boolean',
                        'questions.*.answer_option' => 'nullable|array',
                        'questions.*.correct_answer' => 'nullable|string',
                        'questions.*.points' => 'nullable|integer|',
                    ]);
                    $quiz_id = Quiz::where('id', $id)->first();
                    //add new question to question table by quiz_id using map function
                    $questions = collect($request->input('questions'))->map(function ($question) use ($quiz_id) {
                        $answer_option = json_encode($question['answer_option']);
                        return new Question([
                            'question' => $question['question'],
                            'is_multiple_choice' => $question['is_multiple_choice'],
                            'answer_option' => $answer_option,
                            'correct_answer' => $question['correct_answer'],
                            'points' => $question['points'] ?? 1,
                        ]);
                    });
                    //save questions to question table
                    $quiz_id->questions()->saveMany($questions);
                    return response()->json([
                        'data' => $questions,
                        'message' => 'Add new questions successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'addAnswer':
                    $request->validate([
                        'answers' => 'required|array',
                        'answers.*.question_id' => 'required|integer',
                        'answers.*.answer' => 'required|string',
                        'answers.*.is_correct' => 'nullable|boolean',
                        'answers.*.points' => 'nullable|integer|',
                    ]);
                    $quiz_id = Quiz::where('id', $id)->first();
                    $answers = collect($request->input('answers'))->map(function ($answer) use ($quiz_id) {
                        return new Answer([
                            'quiz_id' => $quiz_id,
                            'question_id' => $answer['question_id'],
                            'answer' => $answer['answer'],
                            //check if answer is correct or not
                            'is_correct' => ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])),
                            'points' => ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])) ? Question::where('id', $answer['question_id'])->first()->points : 0,
                        ]);
                    });
                    $quiz_id->answers()->saveMany($answers);
                    return response()->json([
                        'data' => $answers,
                        'message' => 'Add new answers successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'grade': //grade (for not multiple choice question and return the total points) 
                    $request->validate([
                        'user_id' => 'required|integer',
                        'answers' => 'required|array',
                        'answers.*.question_id' => 'required|integer',
                        'answers.*.answer' => 'nullable|string',
                        'answers.*.is_correct' => 'nullable|boolean',
                        'answers.*.points' => 'required|integer|',
                    ]);
                    $quiz_id = Quiz::where('id', $id)->first();
                    // Get the existing answer text for the given quiz and user
                    $answer = Answer::where('quiz_id', $quiz_id->id)
                        ->where('user_id', $request->user_id)
                        ->firstOrFail();

                    // Decode the answer text from JSON to a PHP array
                    $answers = json_decode($answer->answer_text, true);
                    // $sumPoints to calculate total points of the answer
                    $sumPoints = 0;
                    // Loop through the answers array and update the points value for each matching question ID
                    foreach ($request->answers as $a) {
                        foreach ($answers as &$ans) {
                            $sumPoints += $ans['points'];
                            if ($ans['question_id'] == $a['question_id']) {
                                $ans['points'] = $a['points'] ?? 0;

                                break;
                            }
                        }
                    }
                    //get the total points of the quiz from question table
                    $maxPoints = Question::where('quiz_id', $quiz_id->id)->sum('points');
                    // Encode the updated array back to JSON format and save it as the new answer text
                    $answer->answer_text = json_encode($answers);
                    $answer->save();
                    return response()->json([
                        'data' => $answer,
                        'sumPoints' => $sumPoints,
                        'maxPoints' => $maxPoints,
                        'message' => 'Update answers successfully',
                        'status' => 200
                    ], 200);
                    break;
                default:
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:quiz,question,answer',
            ]);
            if (!in_array(auth()->user()->role->name, ['manager', 'teacher'])) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not authorized to delete.'
                ], 403);
            }
            if (auth()->user()->role->name == 'manager') {
                switch ($request->type) {
                    case 'quiz':
                        $quiz = Quiz::findOrFail($id);
                        $quiz->delete();

                        return response()->json([
                            'message' => 'Delete quiz successfully',
                            'status' => 200
                        ], 200);
                        break;

                    case 'question':
                        $request->validate([
                            'question_id' => 'required|integer',
                        ]);
                        $question = Question::findOrFail($request->question_id);
                        $question->delete();

                        return response()->json([
                            'message' => 'Delete question successfully',
                            'status' => 200
                        ], 200);
                        break;
                    case 'answer':
                        $request->validate([
                            'user_id' => 'required|integer',
                        ]);
                        $answer = Answer::where('quiz_id', $request->quiz_id)
                            ->where('user_id', $request->user_id)
                            ->firstOrFail();
                        $answer->delete();
                        return response()->json([
                            'message' => 'Delete answer successfully',
                            'status' => 200
                        ], 200);
                        break;
                    default:
                        break;
                }
            } else if (auth()->user()->role->name == 'teacher') {
                switch ($request->type) {
                    case 'quiz':
                        $quiz = Quiz::findOrFail($id);
                        // update the status of the quiz to in
                        $quiz->update([
                            'status' => 'inactive'
                        ]);
                        return response()->json([
                            'message' => 'Delete quiz successfully',
                            'status' => 200
                        ], 200);
                        break;
                    default:
                        break;
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not authorized to delete.'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
