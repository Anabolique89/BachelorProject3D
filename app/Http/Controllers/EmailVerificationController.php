<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);


        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Invalid verification link.'
            ], 403);
        }


        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
                'redirect' => config('app.frontend_url') . '/login?verified=already'
            ]);
        }


        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Email verified successfully!',
            'redirect' => config('app.frontend_url') . '/login?verified=success'
        ]);
    }


    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.'
            ], 400);
        }

        $user->notify(new VerifyEmailNotification());

        return response()->json([
            'message' => 'Verification email sent! Please check your inbox.'
        ]);
    }
}
