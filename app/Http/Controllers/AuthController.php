<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Customer;
use App\Models\UserLevel;
use App\Notifications\VerifyEmailNotification;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::beginTransaction();
        try {
            // Split name into first and last name
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Get default user level (Thrall - level 1)
            $defaultLevel = UserLevel::where('level', 1)->first();

            // Create customer profile
            Customer::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'role' => 'customer',
                'status' => 'active',
                'user_level_id' => $defaultLevel?->id,
                'total_tokens' => 0,
                'used_tokens' => 0,
                'available_tokens' => 0,
            ]);

            // Send verification email
            $user->notify(new VerifyEmailNotification());

            DB::commit();

            return response()->json([
                'message' => 'Registration successful! Please check your email to verify your account.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
 /**
 * Login user
 */
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'remember' => ['boolean']
    ]);

    $remember = $credentials['remember'] ?? false;
    unset($credentials['remember']);

    if (!Auth::attempt($credentials, $remember)) {
        return response()->json([
            'message' => 'Email or password is incorrect'
        ], 422);
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    //?email is verified
    if (!$user->hasVerifiedEmail()) {
        Auth::logout();
        return response()->json([
            'message' => 'Please verify your email address before logging in.',
            'email_verified' => false,
            'email' => $user->email
        ], 403);
    }

    $customer = $user->customer;

    if (!$customer) {
        Auth::logout();
        return response()->json([
            'message' => 'No customer profile found for this account'
        ], 403);
    }

    if ($customer->status === 'banned') {
        Auth::logout();
        return response()->json([
            'message' => 'Your account has been banned'
        ], 403);
    }

    if ($customer->status === 'inactive') {
        Auth::logout();
        return response()->json([
            'message' => 'Your account is inactive'
        ], 403);
    }

    $user->load(['customer.userLevel']);

    // Allow login for ALL users, but mark if they're admin
    $token = $user->createToken('main')->plainTextToken;

    return response()->json([
        'user' => new UserResource($user),
        'token' => $token,
        'is_admin' => $customer->isAdmin(), 
    ]);
}

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

/**
 * Get current authenticated user
 */
public function me(Request $request)
{
    $user = $request->user();
    
    $user->load(['customer.userLevel']);
    
    return response()->json([
        'data' => new \App\Http\Resources\UserResource($user)
    ]);
}
}
