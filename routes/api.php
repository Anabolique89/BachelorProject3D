<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\SocialAuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact', [ContactController::class, 'store']);

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('/email/resend', [EmailVerificationController::class, 'resend'])
    ->name('verification.resend');

// Public product routes - ANYONE can view
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/categories', function () {
    return \App\Models\Category::where('is_active', true)
        ->orderBy('sort_order')
        ->get(['id', 'name', 'slug', 'parent_id']);
});

// Social Authentication
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);


Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Cart Management
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/count', [CartController::class, 'count']);
        Route::post('/add', [CartController::class, 'add']);
        Route::put('/items/{id}', [CartController::class, 'update']);
        Route::delete('/items/{id}', [CartController::class, 'remove']);
        Route::delete('/clear', [CartController::class, 'clear']);
        Route::post('/sync', [CartController::class, 'sync']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
    });

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::put('/password', [ProfileController::class, 'updatePassword']);
    });
    //stripe
    Route::get('/stripe/verify-session/{sessionId}', [OrderController::class, 'verifyStripeSession']);

    // Game/Quest endpoints
    Route::prefix('customer')->group(function () {
        Route::get('/check-popups', [GameController::class, 'checkPopups']);
        Route::post('/claim-spin-prize', [GameController::class, 'claimSpinPrize']);
        Route::post('/claim-daily-reward', [GameController::class, 'claimDailyReward']);
        Route::get('/check-welcome-spin', [GameController::class, 'checkWelcomeSpin']);
        Route::post('/complete-quest', [GameController::class, 'completeQuest']);

        Route::get('/quests', [GameController::class, 'getQuests']);
        Route::get('/stats', [GameController::class, 'getStats']);

        Route::get('/treasury', [GameController::class, 'getTreasury']); // ← MISSING
        Route::get('/runes', [GameController::class, 'getRunes']);
    });


    // ADMIN 
    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('/dashboard/stats', function () {
            return response()->json([
                'total_customers' => \App\Models\Customer::count(),
                'total_quests' => \App\Models\Quest::count(),
                'total_runes' => \App\Models\RuneType::count(),
                'total_discount_tiers' => \App\Models\DiscountTier::count(),
            ]);
        });

        // Admin product management
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);


        Route::get('/customer-levels', function () {
            $levels = \App\Models\UserLevel::withCount('customers')->get();
            return response()->json($levels);
        });


        Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);

        // Inside Route::middleware('admin')->prefix('admin')->group(function () {


        Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
        Route::get('/users/stats', [\App\Http\Controllers\Api\UserController::class, 'stats']);
        Route::post('/users', [\App\Http\Controllers\Api\UserController::class, 'store']);
        Route::get('/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'show']);
        Route::put('/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'update']);
        Route::delete('/users/{user}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);
        // Route::apiResource('orders', OrderController::class);
        // more routes....here
    });
});
