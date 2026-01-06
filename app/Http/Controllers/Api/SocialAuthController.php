<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\UserLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to provider
     */
    public function redirectToProvider($provider)
    {
        // Validate provider
        if (!in_array($provider, ['github', 'google'])) {
            return response()->json(['message' => 'Invalid provider'], 400);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Handle provider callback
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Get user from provider
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Check if user exists
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Create new user
                DB::beginTransaction();
                try {
                    $user = User::create([
                        'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Viking User',
                        'email' => $socialUser->getEmail(),
                        'password' => bcrypt(Str::random(16)),
                        'email_verified_at' => now(),
                    ]);

                    // Get default level
                    $defaultLevel = UserLevel::where('level', 1)->first();

                    // Split name
                    $nameParts = explode(' ', $user->name, 2);

                    // Create customer profile
                    Customer::create([
                        'user_id' => $user->id,
                        'first_name' => $nameParts[0],
                        'last_name' => $nameParts[1] ?? '',
                        'email' => $user->email,
                        'role' => 'customer',
                        'status' => 'active',
                        'user_level_id' => $defaultLevel?->id,
                        'total_tokens' => 0,
                        'used_tokens' => 0,
                        'available_tokens' => 0,
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            // Create token
            $token = $user->createToken('main')->plainTextToken;

            // Check if admin
            $isAdmin = $user->customer?->isAdmin() ?? false;

            // Redirect to frontend
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect($frontendUrl . '/auth/callback?token=' . $token . '&is_admin=' . ($isAdmin ? '1' : '0'));

        } catch (\Exception $e) {
            \Log::error('Social auth error: ' . $e->getMessage());
            
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect($frontendUrl . '/login?error=social_auth_failed');
        }
    }
}