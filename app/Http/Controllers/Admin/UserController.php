<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends Controller
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
            // get all users
            $users = User::all();
            $users = $users->map(function ($user) {
                return [
                    $user->id,
                    $user->role->name,
                    $user->username,
                    $user->email,
                    Carbon::parse($user->date_of_birth)->format('d/m/Y'),
                    $user->status,
                    Carbon::parse($user->updated_at)->format('d/m/Y'),
                ];
            });
            // return response
            return response()->json([
                'status' => 'success',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
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
                'role_id' => 'required|exists:roles,id',
                'username' => 'nullable|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'date_of_birth' => 'required|date|before:today',
                'password' => 'required|confirmed',
            ]);
            $user = new User();
            $user->role_id = $request->role_id;
            $user->username = $request->username ?? uniqid('llu_');
            $user->email = $request->email;
            $user->date_of_birth = $request->date_of_birth;
            $user->password = bcrypt($request->password);
            $user->status = 'active';
            $user->email_verified_at = Carbon::now();
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => [
                    $user->id,
                    $user->role->name,
                    $user->username,
                    $user->email,
                    Carbon::parse($user->date_of_birth)->format('d/m/Y'),
                    $user->status,
                    Carbon::parse($user->updated_at)->format('d/m/Y'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
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
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'username' => 'nullable|unique:users,username,'.$id,
                'email' => 'required|email|unique:users,email,'.$id,
                'date_of_birth' => 'required|date|before:today',
                'email_verified_at' => 'nullable|date|before:today',
                'password' => 'nullable|confirmed',
            ]);
            $user = User::findOrFail($id);
            $user->role_id = $request->role_id;
            $user->username = $request->username ?? uniqid('llu_');
            $user->email = $request->email;
            $user->date_of_birth = $request->date_of_birth;
            $request->email_verified_at ? $user->email_verified_at = $request->email_verified_at : null;
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->status = 'active';
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    $user->id,
                    $user->role->name,
                    $user->username,
                    $user->email,
                    Carbon::parse($user->date_of_birth)->format('d/m/Y'),
                    $user->status,
                    Carbon::parse($user->updated_at)->format('d/m/Y'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
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
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
