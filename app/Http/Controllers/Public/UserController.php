<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Learn;
use App\Models\LessonDetail;
use App\Models\School;
use App\Models\UserLog;

use function PHPSTORM_META\type;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'string|in:info,lesson,course,search,recent,detail,learn,detail_split',
            ]);
            // $user = User::findOrFail($id);
            // check user is user login
            if (!$request->user()) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You do not have permission to access this user'
                ], 403);
            }
            switch ($request->type) {
                case 'info':
                    //show all information of user
                    $user = $request->user();
                    //check user is active
                    if ($user->status == 0) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 401,
                            'message' => 'User is not active'
                        ], 401);
                    }
                    return response()->json([
                        'user' => $user,
                        'message' => 'Show information of user successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'lesson':
                    $request->validate([
                        'limit' => 'nullable|integer',
                    ]);
                    //show lesson by user id
                    if ($request->limit) {
                        $lessons = Lesson::where('user_id', $id)->where('status', 'active')->limit($request->limit)->get();
                    } else {
                        $lessons = Lesson::where('user_id', $id)->where('status', 'active')->get();
                    }

                    $lessons = $lessons->map(function ($lessons) {
                        return [
                            'id' => $lessons->id,
                            'name' => $lessons->name,
                            'detail_count' => count($lessons->lessonDetail),
                            'username' => $lessons->user->username,
                        ];
                    });

                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'data' => $lessons
                    ], 200);
                    break;

                case 'course':
                    $request->validate([
                        'limit' => 'nullable|integer',
                    ]);
                    //show course by user id
                    if ($request->limit) {
                        $courses = Course::where('user_id', $id)->where('status', 'active')->limit($request->limit)->get();
                    } else {
                        $courses = Course::where('user_id', $id)->where('status', 'active')->get();
                    }
                    $courses = $courses->map(function ($courses) {
                        return [
                            'id' => $courses->id,
                            'name' => $courses->name,
                            'lesson_count' => count($courses->lessons),
                            'username' => $courses->user->username,
                        ];
                    });
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get all course by user id successfully',
                        'data' => $courses
                    ], 200);
                    break;
                case 'search': //search lesson, course, school by name of lesson
                    $request->validate([
                        'name' => 'required|string',
                    ]);
                    $name = $request->name;
                    if ($name == '') {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'Not found'
                        ], 404);
                    }

                    //check if lesson, course, school name is exist
                    $lessons = Lesson::where('name', 'like', '%' . $name . '%')->get();
                    $courses = Course::where('name', 'like', '%' . $name . '%')->get();
                    $schools = School::where('name', 'like', '%' . $name . '%')->get();
                    if (count($lessons) == 0 && count($courses) == 0 && count($schools) == 0) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'Not found'
                        ], 404);
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Search result',
                        'data' => [
                            'lessons' => $lessons,
                            'courses' => $courses,
                            'schools' => $schools
                        ]
                    ], 200);
                    break;
                case 'recent':
                    $user_log = UserLog::where('user_id', $id)->orderBy('created_at', 'desc')->get();
                    //if dont have any lesson log
                    if (count($user_log) == 0) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'You dont learn any lesson!'
                        ], 404);
                    }
                    $recent_lessons = [];
                    foreach ($user_log as $log) {
                        $lesson = Lesson::find($log->lesson_id);
                        if ($lesson) {
                            $recent_lessons[] = $lesson;
                        }
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Recent lessons',
                        'data' => $recent_lessons
                    ], 200);
                    break;

                case 'detail':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    ///show lesson detail by lesson id and record user log
                    $lesson = Lesson::findOrFail($request->lesson_id);
                    // check user can view lesson (user is owner of lesson or lesson is public and active)
                    if ($lesson->user_id != $request->user()->id && ($lesson->status == 0 || $lesson->is_public == 0)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to access this lesson'
                        ], 403);
                    }

                    $lessonDetail = $lesson->lessonDetail()->get();
                    //map to get only name and content of lesson detail
                    $lessonDetail = $lessonDetail->map(function ($lessonDetail) {
                        return [
                            'id' => $lessonDetail->id,
                            'term' => $lessonDetail->term,
                            'definition' => $lessonDetail->definition,
                        ];
                    });
                    //check user log is exist, if exist update accessed_at, else create new user log
                    if (UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->exists()) {
                        $user_log = UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->first();
                        $user_log->accessed_at = now();
                        $user_log->save();
                    } else {
                        $user_log = new UserLog();
                        $user_log->user_id = $request->user()->id;
                        $user_log->lesson_id = $request->lesson_id;
                        $user_log->accessed_at = now();
                        $user_log->save();
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get lesson successfully!',
                        'data' => [
                            'lesson' => $lesson,
                            'lessonDetail' => $lessonDetail,
                        ]
                    ], 200);
                    break;

                case 'detail_split':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    ///show lesson detail by lesson id and record user log
                    $lesson = Lesson::findOrFail($request->lesson_id);
                    // check user can view lesson (user is owner of lesson or lesson is public and active)
                    if ($lesson->user_id != $request->user()->id && ($lesson->status == 0 || $lesson->is_public == 0)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to access this lesson'
                        ], 403);
                    }

                    //get lesson id from lesson
                    $lesson_id = $lesson->id;
                    //get learned lesson details
                    $learn = Learn::where('user_id', auth()->user()->id)->whereIn('lesson_id', [$lesson_id])->first();
                    //get id of learned lesson details
                    $learned = $learn ? explode(',', $learn->learned) : [];
                    //get id of relearn lesson details
                    $relearn = $learn ? explode(',', $learn->relearn) : [];
                    // $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn);
                    $lessonDetails = [];
                    //get relearn lesson details
                    if ($relearn) {
                        $lessonDetails = LessonDetail::whereIn('id', $relearn)->get();
                        $lessonDetails = $lessonDetails->map(function ($lessonDetails) {
                            return [
                                'id' => $lessonDetails->id,
                                'term' => $lessonDetails->term,
                                'definition' => $lessonDetails->definition,
                            ];
                        });
                    }
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->get();
                    //map to get only name and content of not learned lesson detail
                    $notLearn = $notLearn->map(function ($notLearn) {
                        return [
                            'id' => $notLearn->id,
                            'term' => $notLearn->term,
                            'definition' => $notLearn->definition,
                        ];
                    });
                    $lessonDetail = $lesson->lessonDetail()->get();
                    //check user log is exist, if exist update accessed_at, else create new user log
                    if (UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->exists()) {
                        $user_log = UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->first();
                        $user_log->accessed_at = now();
                        $user_log->save();
                    } else {
                        $user_log = new UserLog();
                        $user_log->user_id = $request->user()->id;
                        $user_log->lesson_id = $request->lesson_id;
                        $user_log->accessed_at = now();
                        $user_log->save();
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get lesson successfully!',
                        'data' => [
                            'lesson' => $lesson,
                            'relearn' => $lessonDetails,
                            'notLearn' => $notLearn,
                        ]
                    ], 200);
                    break;
                case 'learn':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    $lesson_id = $request->lesson_id;
                    //call to learn method from LessonController
                    $lessonController = new LessonController();
                    return $lessonController->learn($request, $lesson_id);
                    break;

                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Type is not valid',
                    ], 400);
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


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'string|in:username,password,learned',
            ]);
            switch ($request->type) {
                case 'username':
                    $request->validate([
                        'username' => 'required|string|unique:users',
                    ]);
                    //update username of user
                    $user = $request->user();
                    $user->username = $request->username;
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update username of user successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'password':
                    $request->validate([
                        'password' => 'required|string|min:6|confirmed',
                    ]);
                    //update password of user
                    $user = $request->user();
                    $user->password = $request->password;
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update password of user successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'learned':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                        'learned' => 'required|array',
                        'relearn' => 'required|array',
                    ]);
                    //update learned and relearn of user
                    $user = $request->user();
                    $learn = Learn::where('user_id', $user->id)->where('lesson_id', $request->lesson_id)->first();
                    if ($learn) {
                        //update learned and relearn of user
                        //get id of learned lesson details
                        $learned = $learn ? explode(',', $learn->learned) : [];
                        //get id of relearn lesson details
                        $relearn = $learn ? explode(',', $learn->relearn) : [];
                        //add new learned lesson detail to learned
                        $learned = array_merge($learned,$request->learned);
                        //remove learned lesson detail from relearn
                        $relearn = array_diff($relearn, $request->learned);
                        //add new relearn lesson detail to relearn
                        $relearn = array_merge($relearn, $request->relearn);
                        //remove duplicate learned and relearn
                        $learned = array_unique($learned);
                        $relearn = array_unique($relearn);
                        //convert array to string
                        $learned = implode(',', $learned);
                        $relearn = implode(',', $relearn);
                        //update learned and relearn of user
                        $learn->learned = $learned;
                        $learn->relearn = $relearn;
                        $learn->save();
                    } else {
                        $learn = new Learn();
                        $learn->user_id = $user->id;
                        $learn->lesson_id = $request->lesson_id;
                        $learn->learned = $request->learned;
                        $learn->relearn = $request->relearn;
                        $learn->save();
                    }
                    //count learned from learn
                    return response()->json([
                        'progress' => [
                            'learned' => count(explode(',', $learn->learned)),
                            'total' => LessonDetail::where('lesson_id', $request->lesson_id)->count(),
                        ],
                        'message' => 'Update learn progress successfully',
                        'status' => 200
                    ], 200);
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Type is not valid',
                    ], 400);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //change status of user to inactive
            $user = User::findOrFail($id);
            $user->status = 0;
            $user->save();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete user successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
