<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class CourseController extends Controller
{
    /**
     * Get the list of folders
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Get the list of folders
            $courses = Course::all();
            $courses = $courses->map(function ($folder) {
                return [
                    $folder->id,
                    $folder->name,
                    $folder->description,
                    $folder->user->username,
                    $folder->is_public ? 'yes' : 'no',
                    Carbon::parse($folder->created_at)->format('d/m/Y'),
                    Carbon::parse($folder->updated_at)->format('d/m/Y'),
                    $folder->status,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
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
        // validate the request
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            // Create the folder
            $course = auth()->user()->courses()->create([
                'name' => $request->name,
                'description' => $request->description,
                'is_public' => true,
            ]);
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course created successfully',
                'data' => [
                    $course->id,
                    $course->name,
                    $course->description,
                    $course->user->username,
                    $course->is_public ? 'yes' : 'no',
                    Carbon::parse($course->created_at)->format('d/m/Y'),
                    Carbon::parse($course->updated_at)->format('d/m/Y'),
                    $course->status,
                ]
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource by type.
     *
     * @param $data
     * @param Request $request
     * @return JsonResponse
     */
    public function show($data,Request $request): JsonResponse
    {
        try{
            $request->validate([
                'type' => 'required|in:find_lesson',
            ]);
            return match($request->type) {
                'find_lesson' => $this->findLesson($data),
            };
        }catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Find the lesson
     *
     * @param $data
     * @return JsonResponse
     */
    private function findLesson($data): JsonResponse
    {
        try {
            // find the lesson by name or description or id
            $lesson = Lesson::where('name', 'like', '%' . $data . '%')
                ->orWhere('description', 'like', '%' . $data . '%')
                ->orWhere('id', 'like', '%' . $data . '%')
                ->take(10)
                ->get();
            $lesson = $lesson->map(function ($lesson) {
                return [
                    $lesson->id,
                    $lesson->name,
                    $lesson->user->username,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $lesson
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get the folder details by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            // Get the folder details
            $course = Course::findOrFail($id);
            // Get lesson
            $course->lessons->map(function ($lesson) {
                return [
                    $lesson->user->username,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $course
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
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
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:course,add_lesson,remove_lesson',
            ]);
            return match($request->type) {
                'course' => $this->updateCourse($request, $id),
                'add_lesson' => $this->addLesson($request, $id),
                'remove_lesson' => $this->removeLesson($request, $id),
            };
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the course
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    private function updateCourse(Request $request, int $id): JsonResponse
    {
        try {
            // validate the request
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'is_public' => 'required|boolean',
                'status' => 'required|in:active,inactive',
                'password' => 'nullable',
            ]);

            // Update the folder
            $course = Course::findOrFail($id);
            $course->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_public' => $request->is_public,
                'status' => $request->status,
                'password' => $request->password,
            ]);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course updated successfully',
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add the lesson to the course
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    private function addLesson(Request $request, int $id): JsonResponse
    {
        try {
            // validate the request
            $request->validate([
                'lesson_ids' => 'required|array',
            ]);
            // get the course
            $course = Course::findOrFail($id);
            // add the lesson to the course if not exist
            $course->lessons()->syncWithoutDetaching($request->lesson_ids);
            // get the lessons
            $lessons = $course->lessons->map(function ($lesson) {
                return [
                    $lesson->id,
                    $lesson->name,
                    $lesson->description,
                    $lesson->user->username,
                    $lesson->is_public,
                    $lesson->updated_at,
                    $lesson->status,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $lessons
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the lesson from the course
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    private function removeLesson(Request $request, int $id): JsonResponse
    {
        try {
            // validate the request
            $request->validate([
                'lesson_ids' => 'required|array',
            ]);
            // get the course
            $course = Course::findOrFail($id);
            // remove the lesson from the course
            $course->lessons()->detach($request->lesson_ids);
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson removed successfully',
            ], 200);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Delete the folder
            Course::destroy($id);

            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course deleted successfully',
            ]);
        } catch (\Exception $e) {
            // Return json
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
