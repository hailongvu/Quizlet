<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;


class Authentication extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Login with username and password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);
            $credentials = request(['email', 'password', 'remember_me']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            }
            $user = $request->user();
            $token = $user->createToken('Personal Access Token');
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'permissions' => $user->getAllPermissions(),
                    'access_token' => $token->plainTextToken,
                    'token_type' => 'Bearer',
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
     * Logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Logout successful',
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
     * Logout from all devices.
     * @return JsonResponse
     */
    public function logoutAll(): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Logout from all devices successful',
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
     * Get the authenticated User.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'User details',
                'data' => $request->user()
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
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email|unique:users',
                'date_of_birth' => 'required|date|before:today',
                'password' => 'required|string|confirmed'
            ]);
            $user = new User([
                'username' => uniqid('llu_'),
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'password' => bcrypt($request->password),
                'role_id' => 3
            ]);
            $user->save();
            // Login user after registration
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            }
            $user = $request->user();
            // lesson roles name in user object
            $user->role;
            $token = $user->createToken('Personal Access Token');
            event(new Registered($user));
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Registration successful',
                'data' => [
                    'user' => $user,
                    'access_token' => $token->plainTextToken,
                    'token_type' => 'Bearer',
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
     * Forgot password.
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
            ]);
            $user = User::where('email', $request->email)->firstOrFail();
            $passwordReset = PasswordReset::updateOrCreate([
                'email' => $user->email,
            ], [
                'token' => Str::random(60),
            ]);
            if ($passwordReset) {
                $user->notify(new ResetPasswordRequest($passwordReset->token));
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'We have e-mailed your password reset link!'
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
     * Reset password.
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed',
                'token' => 'required|string'
            ]);
            $passwordReset = PasswordReset::where('token', $request->token)->firstOrFail();
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) {
                $passwordReset->delete();
                return response()->json([
                    'status' => 'error',
                    'status_code' => 422,
                    'message' => 'This password reset token is invalid.'
                ], 422);
            }
            $user = User::where('email', $passwordReset->email)->firstOrFail();
            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->save();
            $passwordReset->delete();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Password reset successfully'
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
