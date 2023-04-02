<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function addTeacher(Request $request, $class_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            // Check if user is already assigned as a student
            if ($class->teachers->contains($request->user_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'User is already assigned as a teacher in this class.js!',
                ], 400);
            }
            // Check if user is already assigned as a student
            if ($class->students->contains($request->user_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'User is already assigned as a student in this class.js!',
                ], 400);
            }
            // Add teacher to class.js
            $class->teachers()->attach($request->user_id);

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Teacher added successfully to class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            if ($ex->getModel() === Classes::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
            } elseif ($ex->getModel() === User::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'User not found!',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //deleted Teacher  from class.js
    public function deleteTeacher($class_id, $teacher_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            // Check if dont have that teacher in class.js
            if (!$class->teachers()->find($teacher_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Teacher is not assigned to this class.js!',
                ], 400);
            }
            $class->teachers()->detach($teacher_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Teacher deleted successfully from class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            if ($ex->getModel() === Classes::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
            } elseif ($ex->getModel() === User::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'User not found!',
                ], 404);
            }
        }
    }

    //Delete Student from class.js
    public function deleteStudent($class_id, $student_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            // Check if dont have that student in class.js
            if (!$class->students()->find($student_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Student is not assigned to this class.js!',
                ], 400);
            }
            $class->students()->detach($student_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Student deleted successfully from class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            if ($ex->getModel() === Classes::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
            } elseif ($ex->getModel() === User::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'User not found!',
                ], 404);
            }
        }
    }

    //Delete Lesson from class.js
    public function deleteSet($class_id, $set_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            // Check if dont have that lesson in class.js
            if (!$class->sets()->find($set_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Lesson is not assigned to this class.js!',
                ], 400);
            }
            $class->sets()->detach($set_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson deleted successfully from class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            if ($ex->getModel() === Classes::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
            } elseif ($ex->getModel() === Lesson::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found!',
                ], 404);
            }
        }
    }

    //Delete folder from class.js
    public function deleteFolder($class_id, $folder_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            // Check if dont have that folder in class.js
            if (!$class->folders()->find($folder_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Course is not assigned to this class.js!',
                ], 400);
            }
            $class->folders()->detach($folder_id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course deleted successfully from class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            if ($ex->getModel() === Classes::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
            } elseif ($ex->getModel() === Course::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Course not found!',
                ], 404);
            }
        }
    }

    public function addStudent(Request $request, $class_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            // Check if user is already assigned as a teacher
            if ($class->teachers->contains($request->user_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'User is already assigned as a teacher in this class.js!',
                ], 400);
            }
            // Check if user is already assigned as a student
            if ($class->students->contains($request->user_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'User is already assigned as a student in this class.js!',
                ], 400);
            }

            // Add student to class.js
            $class->students()->attach($request->user_id);

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Student added successfully to class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            if ($ex->getModel() === Classes::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!',
                ], 404);
            } elseif ($ex->getModel() === User::class) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'User not found!',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function addFolder(Request $request, $class_id)
    {
        try {
            $class = Classes::findOrFail($class_id);
            $folder_id = $request->get('folder_id');
            if ($folder_id) {
                $exists = DB::table('class_folder')
                    ->where('class_id', $class_id)
                    ->where('folder_id', $folder_id)
                    ->exists();
                if ($exists) {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 400,
                        'message' => 'Course already exists in this class.js!'
                    ], 400);
                }
                $folder = Course::findOrFail($folder_id);
            } else {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'status' => 'required|in:active,inactive',
                    'is_public' => 'required|boolean',
                    'password' => 'nullable|string',

                ]);
                // Create a new folder and associate it with the class.js
                $folder = new Course();
                $folder->user_id = $request->user()->id;
                $folder->name = $request->name;
                $folder->description = $request->description;
                $folder->status = $request->status;
                $folder->is_public = $request->is_public;
                $folder->password = $request->password;
                //$folder->class_id = $class_id;
                $folder->save();
            }
            $class->folders()->attach($folder->id);
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Course added successfully to class.js!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class not found!',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function addSet(Request $request, $classId)
    {
        try {
            $class = Classes::findOrFail($classId);
            $set = Lesson::findOrFail($request->set_id);
            if ($class->sets->contains($request->set_id)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Lesson is already in this class.js!',
                ], 400);
            }
            $class->sets()->attach($set->id);
            return response()->json([
                'message' => 'Lesson added successfully to the class.js',
                'class.js' => $class,
                'lesson' => $set
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class or lesson not found!',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $classes = Classes::where('status', 'active')
                ->with(['students', 'teachers', 'sets', 'folders'])
                ->get();

            return response()->json($classes);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('class.js.create');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|in:active,inactive'
            ]);

            // Tao lesson moi
            $class = new Classes();
            $class->name = $request->name;
            $class->description = $request->description;
            $class->status = $request->status;
            $class->save();
            // return json response
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Create class.js successfully!',
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $class = Classes::findOrFail($id);
            if ($class->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Class not found!'
                ], 404);
            }
            $sets = $class->sets;
            $folders = $class->folders;
            $students = $class->students;
            $teachers = $class->teachers;

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => [
                    'class.js' => $class,
                    'sets' => $sets,
                    'folders' => $folders,
                    'students' => $students,
                    'teachers' => $teachers
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $class = Classes::findOrFail($id);

            return view('class.js.edit', compact('class'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class not found!',
            ], 404);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $class = Classes::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'status' => 'required|string|max:255',
            ]);

            $class->name = $validatedData['name'];
            $class->description = $validatedData['description'];
            $class->status = $validatedData['status'];
            $class->updated_at = now();
            $class->save();

            return response()->json([
                'message' => 'Class updated successfully',
                'class.js' => $class
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class not found!',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //Unactive Class by id
    public function destroy($id)
    {
        try {
            $class = Classes::findOrFail($id);
            $class->status = 'inactive';

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Class deleted successfully!',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'status_code' => 404,
                'message' => 'Class not found!',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
