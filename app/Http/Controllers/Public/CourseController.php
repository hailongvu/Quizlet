<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Font;
use PhpParser\Node\Stmt\TryCatch;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
            ]);
            // add new course to user
            $course = auth()->user()->courses()->create([
                'name' => $request->name,
                'description' => $request->description,
                'password' => $request->password,
            ]);
            $course->save();
            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /// test commit
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response: JsonResponse
     */
    public function show($id)
    {
        try {
            $course = Course::findOrFail($id);
            // check course is deleted
            if ($course->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Course not found'
                ], 404);
            }
            // check course is public
            if ($course->is_public == false && auth()->user()->id != $course->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this course'
                ], 403);
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get course successfully!',
                'data' => [
                    'course' => $course,
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'is_public' => 'required|in:1,0',
                'password' => 'nullable|string',
            ]);
            $course = Course::findOrFail($id);
            // update course
            $course->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_public' => $request->is_public,
                'password' => $request->password,
            ]);
            $course->save();

            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Update course successfully!',
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            // Soft delete course
            $course->update([
                'status' => 'inactive'
            ]);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete course successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //add lesson to course by lesson id and course id
    public function addLessonToCourse(Request $request, $id, $lesson_id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            // check course is deleted
            if ($course->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Course not found'
                ], 404);
            }
            // check course is public
            if ($course->is_public == false && auth()->user()->id != $course->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this course'
                ], 403);
            }
            // check lesson is deleted
            $lesson = Lesson::findOrFail($lesson_id);
            if ($lesson->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found'
                ], 404);
            }
            // check lesson is public
            if ($lesson->is_public == false && auth()->user()->id != $lesson->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this lesson'
                ], 403);
            }
            // check lesson is exist in course
            $checkLesson = $course->lessons()->where('lesson_id', $lesson_id)->first();
            if ($checkLesson) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Lesson is exist in course'
                ], 400);
            }
            // add lesson to course
            $course->lessons()->attach($lesson_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Add lesson to course successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    // remove lesson from course by lesson id and course id
    public function removeLessonFromCourse(Request $request, $id, $lesson_id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            // check course is deleted
            if ($course->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Course not found'
                ], 404);
            }
            // check course is public
            if ($course->is_public == false && auth()->user()->id != $course->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this course'
                ], 403);
            }
            // check lesson is deleted
            $lesson = Lesson::findOrFail($lesson_id);
            if ($lesson->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found'
                ], 404);
            }
            // check lesson is public
            if ($lesson->is_public == false && auth()->user()->id != $lesson->user_id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You don\'t have permission to access this lesson'
                ], 403);
            }
            // check lesson is exist in course
            $checkLesson = $course->lessons()->where('lesson_id', $lesson_id)->first();
            if (!$checkLesson) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Lesson is not exist in course'
                ], 400);
            }
            // remove lesson from course
            $course->lessons()->detach($lesson_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Remove lesson from course successfully!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage(),
            ],
                500);
        }
    }
}
