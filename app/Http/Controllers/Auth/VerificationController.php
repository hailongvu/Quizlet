<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Routing\Redirector;

class VerificationController extends Controller
{
    // constructor
    public function __construct()
    {
    }

    /**
     * Show the email verification notice.
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function verifyEmail(Request $request, $id, $hash): Redirector|RedirectResponse|Application
    {
        // build the url to verify the email
        $url = '/auth/handle-verify/' . $id . '/' . $hash . '?expires=' . $request->expires . '&signature=' . $request->signature;
        // redirect to the url
        return redirect($url);
    }

    /**
     * Handle the email verification request.
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */
    public function handleVerifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        // mark the user as verified
        $request->fulfill();
        // redirect to the dashboard
        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * Resend verify email
     * @param Request $request
     * @return JsonResponse
     */
    public function resendVerifyEmail(Request $request): JsonResponse
    {
        try {
            $request->user()->sendEmailVerificationNotification();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Email verification link sent on your email id.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
