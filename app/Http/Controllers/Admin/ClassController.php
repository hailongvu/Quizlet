<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ClassController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // get all class.js
            $classes = Classes::all();
            $classes = $classes->map(function ($class) {
                return [
                    $class->id,
                    $class->name,
                    $class->description,
                    $class->status,
                    $class->school->name,
                ];
            });
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $classes
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
                'status' => 'required|in:active,inactive',
                'school_id' => 'required|exists:schools,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
            ]);
            $class = Classes::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'school_id' => $request->school_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => 'Class created successfully'
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
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:all,managers,teachers,students,search_user',
                'keyword' => 'required_if:type,search_user|string',
            ]);
            // get class.js
            $class = Classes::findOrFail($id);
            $data = match ($request->type) {
                'all' => [
                    'class.js' => $class,
                    //get school of class.js
                    'school' => $class->school,
                ],
                // 'search_user' => User::where('email', 'like', '%' . $request->keyword . '%')
                //     ->orWhere('username', 'like', '%' . $request->keyword . '%')
                //     ->get(),
                'teachers' => $class->teachers,
                'students' => $class->students,
                default => throw new \Exception('Invalid type'),
            };
            // Return json
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $data
            ]);
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
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
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:class.js,add_teacher,add_student',
            ]);
            $class = Classes::findOrFail($id);
            if ($this->checkPermiss($id) == false) {
                throw new \Exception('You do not have permission to edit this class.js');
            } else {
                switch ($request->type) {
                    case 'class.js':
                        $request->validate([
                            'name' => 'required|string',
                            'description' => 'required|string',
                            'status' => 'required|in:active,inactive',
                        ]);
                        $class->name = $request->name;
                        $class->description = $request->description;
                        $class->status = $request->status;
                        $class->save();
                        // Return json
                        return response()->json([
                            'status' => 'success',
                            'status_code' => 200,
                            'message' => 'Class updated successfully'
                        ], 200);
                        break;
                    case 'add_teacher':
                        //request input user_id
                        $request->validate([
                            'user_id' => 'required|exists:users,id',
                        ]);
                        $user = User::findOrFail($request->user_id);
                        //check if user is teacher
                        $role = Role::where('name', 'teacher')->first();
                        if (!$request->user()->hasRole($role)) {
                            throw new \Exception('User is not teacher');
                        } else {
                            //check if user is already teacher of class.js
                            if ($class->teachers->contains($request->user())) {
                                throw new \Exception('User is already teacher of class.js');
                            } else {
                                //add user to class.js
                                $class->teachers()->attach($request->user());
                                // Return json
                                return response()->json([
                                    'status' => 'success',
                                    'status_code' => 200,
                                    'message' => 'Teacher added successfully'
                                ], 200);
                            }
                        }
                        break;
                    case 'add_student':
                        $request->validate([
                            'user_id' => 'required|exists:users,id',
                        ]);
                        $user = User::findOrFail($request->user_id);
                        //check if user is student
                        $role = Role::where('name', 'student')->first();
                        if (!$request->user()->hasRole($role)) {
                            throw new \Exception('User is not student');
                        } else {
                            //check if user is already student of class.js
                            if ($class->students->contains($request->user())) {
                                throw new \Exception('User is already student of class.js');
                            } else {
                                //add user to class.js
                                $class->students()->attach($request->user());
                                // Return json
                                return response()->json([
                                    'status' => 'success',
                                    'status_code' => 200,
                                    'message' => 'Student added successfully'
                                ], 200);
                            }
                        }
                        break;
                    default:
                        throw new \Exception('Invalid type');
                        break;
                }
            }
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
    public function destroy(Request $request, $id)
    {
        try {
            //request input type delete class.js or delete student and teacher in class.js
            $request->validate([
                'type' => 'required|in:delete_class,delete_teacher,delete_student',
            ]);
            if ($this->checkPermiss($id) == false) {
                throw new \Exception('You do not have permission to delete this class.js');
            } else {
                //switch case check type
                switch ($request->type) {
                    case 'delete_class':
                        //delete class.js by id
                        $class = Classes::find($id);
                        if ($class) {
                            $class->delete();
                            return response()->json([
                                'status' => 'success',
                                'status_code' => 200,
                                'message' => 'Class deleted successfully'
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 404,
                                'message' => 'Class not found'
                            ], 404);
                        }
                        break;
                    case 'delete_teacher':
                        //delete teacher by user_id in class.js by id
                        $class = Classes::find($id);
                        if ($class) {
                            //check if user not exist
                            if (!$class->teachers->contains($request->user()) && !$class->students->contains($request->user())) {
                                throw new \Exception('User not exist in class.js');
                            }
                            $class->teachers()->detach($request->user());
                            return response()->json([
                                'status' => 'success',
                                'status_code' => 200,
                                'message' => 'Teacher deleted successfully'
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 404,
                                'message' => 'Class not found'
                            ], 404);
                        }
                        break;
                    case 'delete_student':
                        //delete student in class.js by id
                        $class = Classes::find($id);
                        if ($class) {
                            $class->students()->detach($request->user());
                            return response()->json([
                                'status' => 'success',
                                'status_code' => 200,
                                'message' => 'Student deleted successfully'
                            ], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 404,
                                'message' => 'Class not found'
                            ], 404);
                        }
                        break;
                    default:
                        throw new \Exception('Invalid type');
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //check permission
    public function checkPermiss($id)
    {
        try {
            $class = Classes::findOrFail($id);
            $user = auth()->user();
            $roles = $user->roles;
            return ($roles->contains('admin') || $roles->contains('super_admin') ||
                ($roles->contains('manager') && $class->school->managers->contains($user)));
        } catch (\Exception $e) {
            return false;
        }
    }
}
