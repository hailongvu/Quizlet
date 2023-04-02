<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Learn;
use App\Models\LessonDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class LessonController extends Controller
{
    public function learn(Request $request, $lesson_id)
    {
        try {
            $reverse = $request->input('reverse', false); //request reverse term and definition
            $mixAnswers = $request->input('mix_answers', false); //request mix answer part
            $mixDetails = $request->input('mix_details', false); //request mix lesson details

            $lesson = Lesson::findOrFail($lesson_id);
            //get learned lesson details from learn table
            $learn = Learn::where('user_id', auth()->user()->id)->where('lesson_id', $lesson_id)->first();
            //get id of learned lesson details
            $learned = $learn ? explode(',', $learn->learned) : [];
            //get id of relearn lesson details
            $relearn = $learn ? explode(',', $learn->relearn) : [];
            // $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn);
            $lessonDetails = [];
            //get relearn lesson details
            if ($relearn) {
                $lessonDetails = LessonDetail::whereIn('id', $relearn)->get();
            }
            if ($mixDetails) {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->inRandomOrder()->take(8)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->inRandomOrder()->take(8 - count($lessonDetails))->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            } else {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->take(8)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->take(8 - count($lessonDetails))->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            }
            $response = ['lesson_id' => $lesson_id, 'lesson_details' => []];
            foreach ($lessonDetails as $lessonDetail) {
                try {
                    $term = $lessonDetail->term;
                    $definition = $lessonDetail->definition;
                    if ($reverse) {
                        $temp = $term;
                        $term = $definition;
                        $definition = $temp;
                    }
                    $answers = [];
                    // replace special characters with a space character
                    $term = preg_replace('/[\n\r\t]+/', ' ', $term);

                    // check if the term is a multiple choice question
                    if (preg_match('/^(.*?)\s*[A-Ma-m]\.\s*(.*)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $options_str = $matches[2];

                        // split the options into arrays
                        $options = preg_split('/\s*[a-m]\.\s*/i', $options_str, -1, PREG_SPLIT_NO_EMPTY);
                        $answers = array_map('trim', $options);

                        // determine the correct answer
                        switch (trim($definition)) {
                            case 'A':
                                $correct_answer = $answers[0];
                                break;
                            case 'B':
                                $correct_answer = $answers[1];
                                break;
                            case 'C':
                                $correct_answer = $answers[2];
                                break;
                            case 'D':
                                $correct_answer = $answers[3];
                                break;
                            default:
                                $correct_answer = '';
                                break;
                        }

                        $correct_answer = trim($correct_answer);
                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // check if the term is a true/false question
                    else if (preg_match('/^(.*)\s*[a-z]\.\s*(True|False)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $answers = array_map('trim', [$matches[2], $matches[3]]);

                        // determine the correct answer
                        $correct_answer = ($definition === 'True') ? 'True' : 'False';

                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // if the term is neither multiple choice nor true/false
                    else {
                        $question = $term;
                        $correct_answer = trim($definition);
                        
                        //get current $response and get the and $answers of other lessonDetail except true/false
                        $otherAnswers = $response['lesson_details'];
                        $otherAnswers = array_filter($otherAnswers, function ($item) {
                            return count($item['answers']) > 2;
                        });
                    
                        //check if there are enough answers in other answers or not
                        if (count($otherAnswers) >= 3) {
                            //get random 3 answers from other otherAnswers
                            $otherAnswers = array_map(function ($item) {
                                return $item['answers'];
                            }, $otherAnswers);
                            $otherAnswers = array_merge(...$otherAnswers);
                            shuffle($otherAnswers);
                            $otherAnswers = array_slice($otherAnswers, 0, 3);
                    
                            //add correct answer to otherAnswers
                            array_push($otherAnswers, $correct_answer);
                            // shuffle the answer options
                            shuffle($otherAnswers);
                            // lesson the answer options to the shuffled $otherAnswers
                            $answers = $otherAnswers;
                        } else {
                            //if there are not enough answers in other answers, generate random answers
                            $answers = [$correct_answer];
                            while (count($answers) < 4) {
                                $randomAnswer = LessonDetail::inRandomOrder()->first();
                                if (!in_array($randomAnswer->definition, $answers)) {
                                    array_push($answers, trim($randomAnswer->definition));
                                }
                            }
                            shuffle($answers);
                        }
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 500,
                        'message' => $th->getMessage()
                    ], 500);
                }
                $response['lesson_details'][] = [
                    'id' => $lessonDetail->id,
                    'question' => $question,
                    'answers' => $answers,
                    'correct_answer' => $correct_answer ?? '',
                    'relearn' => in_array($lessonDetail->id, $relearn) ? true : false
                ];
            }
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:text,file',
                'name' => 'required|string',
                'description' => 'required|string',
                'text' => 'required_if:type,text|nullable',
                'term_separator' => 'required_if:type,text',
                'set_separator' => 'required_if:type,text',
                'file' => 'required_if:type,file|mimes:xls,xlsx,csv',
            ]);
            $type = $request->input('type');
            // Tao lesson moi
            $lesson = new Lesson();
            $lesson->name = $request->name;
            $lesson->description = $request->description;
            $lesson->user_id = auth()->user()->id;
            $lesson->save();
            if ($type == 'text') {
                $raw_detail = explode($request->set_separator, $request->text);
                if (count($raw_detail) < 3) {
                    // delete lesson
                    $lesson->delete();
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 400,
                        'message' => 'Text must have more than 3 sets'
                    ], 400);
                }
                foreach ($raw_detail as $item) {
                    try {
                        $raw = explode($request->term_separator, $item);
                        $term = $raw[0];
                        $definition = $raw[1];
                        $lesson->lessonDetail()->create([
                            'term' => $term,
                            'definition' => $definition
                        ]);
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            } else if ($type == 'file') {
                $file = $request->file('file');
                $file_extension = $file->getClientOriginalExtension();
                if ($file_extension == 'xls' || $file_extension == 'xlsx') {
                    $data = Excel::toArray((object)[], $file);
                    // check data have more than 4 rows
                    if (count($data[0]) > 4) {
                        // check data have more than 2 columns
                        if (count($data[0][0]) > 2) {
                            foreach ($data[0] as $key => $item) {
                                if ($key >= 1) {
                                    $lesson->lessonDetail()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete lesson
                            $lesson->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns'
                            ], 400);
                        }
                    } else {
                        // delete lesson
                        $lesson->delete();
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'File must have more than 4 rows'
                        ], 400);
                    }
                } else {
                    $csv = array_map('str_getcsv', file($file));
                    // check data have more than 4 rows
                    if (count($csv) > 4) {
                        // check data have 2 columns
                        if (count($csv[0]) > 2) {
                            foreach ($csv as $key => $item) {
                                if ($key >= 1) {
                                    $lesson->lessonDetail()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete lesson
                            $lesson->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns ( no,term, definition,... )'
                            ], 400);
                        }
                    } else {
                        // delete lesson
                        $lesson->delete();
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'File must have more than 4 rows'
                        ], 400);
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson created successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function export(Request $request, $id): BinaryFileResponse|JsonResponse
    {
        try {
            $lesson = Lesson::findOrfail($id);
            $lessonData = $lesson->lessonDetail()->get()->toArray();
            if (empty($lessonData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            // write lesson data to file
            $file_name = 'set_' . $id . '_' . date('Ymd_His') . '.csv';
            $file = fopen($file_name, 'w');
            fputcsv($file, ['id', 'term', 'definition', 'image', 'audio', 'video', 'status', 'created_at', 'updated_at']);
            foreach ($lessonData as $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            // lesson header
            $headers = [
                'Content-Type' => 'text/csv',
            ];
            // download file
            return response()->download($file_name, $file_name, $headers)->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'password' => 'nullable|string',
                'data' => 'required|array',
            ]);
            // add new lesson to user
            $user = auth()->user();
            $lesson = $user->lesson()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => $request->password,
            ]);
            // add lesson details
            foreach ($request->data as $detail) {
                $lesson->lessonDetail()->create([
                    'term' => $detail['term'],
                    'definition' => $detail['definition'],
                ]);
            }
            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Create lesson successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            // check lesson is deleted
            if ($lesson->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found!'
                ], 404);
            }
            $lessonDetail = $lesson->lessonDetail()->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => [
                    'lesson' => $lesson,
                    'detail' => $lessonDetail
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // get lesson
            $lesson = Lesson::findOrFail($id);
            // check lesson is deleted
            if ($lesson->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found!'
                ], 404);
            }
            // check user is owner of lesson
            if ($lesson->user_id != auth()->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not owner of this lesson!'
                ], 403);
            }
            // update lesson
            $lessonController = new \App\Http\Controllers\Admin\LessonController();
            return $lessonController->update($request, $id);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            // Soft delete lesson
            $lesson->update([
                'status' => 'inactive'
            ]);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete lesson successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //show all lesson by user id
    public function showAllLessonByUserId(): JsonResponse
    {
        try {
            $user = auth()->user();
            $lesson = $user->lesson()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => $lesson
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show all lesson by course id
    public function showAlllessonByCourseId($id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            $lesson = $course->lessons()->where('status', 'active')->get();
            //map to get name, description, status of lesson
            $lesson = $lesson->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'username' => $item->user->username,
                    'detail_count' => $item->lessonDetail()->count(),
                ];
            });
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => $lesson
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show progress by lesson id
    public function showProgressByLessonId($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            //count the number of lesson detail in lesson
            $countLessonDetail = $lesson->lessonDetail()->count();

            //Find learn by learn id
            $learn = Learn::where('lesson_id', $id)->where('user_id', auth()->user()->id)->first();
            //Get learned from learn
            $countLearned = count(explode(',', $learn->learned));

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get progress successfully!',
                'data' => [
                    // 'countLessonDetail' => $countLessonDetail,
                    // 'learn' => $learn,
                    // 'countLerned' => $countLearned,
                    'progress' => ($countLearned / $countLessonDetail) * 100 . '%',
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function learnForImport(Request $request, $lesson_id)
    {
        try {
            $reverse = $request->input('reverse', false); //request reverse term and definition
            $mixAnswers = $request->input('mix_answers', false); //request mix answer part
            $mixDetails = $request->input('mix_details', false); //request mix lesson details
            
            $lesson = Lesson::findOrFail($lesson_id);
            //get learned lesson details from learn table
            $learn = Learn::where('user_id', auth()->user()->id)->where('lesson_id', $lesson_id)->first();
            //get id of learned lesson details
            $learned = $learn ? explode(',', $learn->learned) : [];
            //get id of relearn lesson details
            $relearn = $learn ? explode(',', $learn->relearn) : [];
            // $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn);
            $lessonDetails = [];
            //get relearn lesson details
            if ($relearn) {
                $lessonDetails = LessonDetail::whereIn('id', $relearn)->get();
            }
            if ($mixDetails) {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->inRandomOrder()->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->inRandomOrder()->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            } else {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            }
            $response = ['lesson_id' => $lesson_id, 'lesson_details' => []];
            foreach ($lessonDetails as $lessonDetail) {
                try {
                    $term = $lessonDetail->term;
                    $definition = $lessonDetail->definition;
                    if ($reverse) {
                        $temp = $term;
                        $term = $definition;
                        $definition = $temp;
                    }
                    $answers = [];
                    // replace special characters with a space character
                    $term = preg_replace('/[\n\r\t]+/', ' ', $term);

                    // check if the term is a multiple choice question
                    if (preg_match('/^(.*?)\s*[A-Ma-m]\.\s*(.*)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $options_str = $matches[2];

                        // split the options into arrays
                        $options = preg_split('/\s*[a-m]\.\s*/i', $options_str, -1, PREG_SPLIT_NO_EMPTY);
                        $answers = array_map('trim', $options);

                        // determine the correct answer
                        switch (trim($definition)) {
                            case 'A':
                                $correct_answer = $answers[0];
                                break;
                            case 'B':
                                $correct_answer = $answers[1];
                                break;
                            case 'C':
                                $correct_answer = $answers[2];
                                break;
                            case 'D':
                                $correct_answer = $answers[3];
                                break;
                            default:
                                $correct_answer = '';
                                break;
                        }

                        $correct_answer = trim($correct_answer);
                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // check if the term is a true/false question
                    else if (preg_match('/^(.*)\s*[a-z]\.\s*(True|False)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $answers = array_map('trim', [$matches[2], $matches[3]]);

                        // determine the correct answer
                        $correct_answer = ($definition === 'True') ? 'True' : 'False';

                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // if the term is neither multiple choice nor true/false
                    else {
                        $question = $term;
                        $correct_answer = trim($definition);
                        
                        //get current $response and get the and $answers of other lessonDetail except true/false
                        $otherAnswers = $response['lesson_details'];
                        $otherAnswers = array_filter($otherAnswers, function ($item) {
                            return count($item['answers']) > 2;
                        });
                    
                        //check if there are enough answers in other answers or not
                        if (count($otherAnswers) >= 3) {
                            //get random 3 answers from other otherAnswers
                            $otherAnswers = array_map(function ($item) {
                                return $item['answers'];
                            }, $otherAnswers);
                            $otherAnswers = array_merge(...$otherAnswers);
                            shuffle($otherAnswers);
                            $otherAnswers = array_slice($otherAnswers, 0, 3);
                    
                            //add correct answer to otherAnswers
                            array_push($otherAnswers, $correct_answer);
                            // shuffle the answer options
                            shuffle($otherAnswers);
                            // lesson the answer options to the shuffled $otherAnswers
                            $answers = $otherAnswers;
                        } else {
                            //if there are not enough answers in other answers, generate random answers
                            $answers = [$correct_answer];
                            while (count($answers) < 4) {
                                $randomAnswer = LessonDetail::inRandomOrder()->first();
                                if (!in_array($randomAnswer->definition, $answers)) {
                                    array_push($answers, trim($randomAnswer->definition));
                                }
                            }
                            shuffle($answers);
                        }
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 500,
                        'message' => $th->getMessage()
                    ], 500);
                }
                $response['lesson_details'][] = [
                    'id' => $lessonDetail->id,
                    'question' => $question,
                    'answers' => $answers,
                    'correct_answer' => $correct_answer ?? '',
                    'relearn' => in_array($lessonDetail->id, $relearn) ? true : false
                ];
            }
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
