<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::with(['customer.userLevel']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        $perPage = $request->get('per_page', 10);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($users);
    }

    /**
     * Get user statistics
     */
    public function stats()
    {
        $total = User::count();
        $admins = Customer::whereIn('role', ['admin', 'super_admin'])->count();
        $customers = Customer::where('role', 'customer')->count();
        $activeToday = User::whereDate('updated_at', today())->count();

        return response()->json([
            'total' => $total,
            'admins' => $admins,
            'customers' => $customers,
            'active_today' => $activeToday,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'customer.role' => 'required|in:customer,admin,super_admin',
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

            // Get the default user level (Thrall - level 1)
            $defaultLevel = \App\Models\UserLevel::where('level', 1)->first();

            // Create customer profile 
            $customer = Customer::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $validated['email'],
                'role' => $validated['customer']['role'],
                'user_level_id' => $defaultLevel?->id,
                'total_tokens' => 0,
                'used_tokens' => 0,
                'available_tokens' => 0,
            ]);

            DB::commit();

            // Load relationships
            $user->load('customer.userLevel');

            return response()->json($user, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display 
     */
    public function show(User $user)
    {
        $user->load(['customer.userLevel']);
        return response()->json($user);
    }

    /**
     * Update 
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|string|min:8',
            'customer.role' => 'sometimes|required|in:customer,admin,super_admin',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            // Split name into first and last name
            $nameParts = explode(' ', $userData['name'], 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            // Update customer role if provided
            if (isset($validated['customer']['role'])) {
                if ($user->customer) {
                    $user->customer->update([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $userData['email'],
                        'role' => $validated['customer']['role']
                    ]);
                } else {
                    // Create customer if doesn't exist
                    $defaultLevel = \App\Models\UserLevel::where('level', 1)->first();
                    Customer::create([
                        'user_id' => $user->id,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $userData['email'],
                        'role' => $validated['customer']['role'],
                        'user_level_id' => $defaultLevel?->id,
                        'total_tokens' => 0,
                        'used_tokens' => 0,
                        'available_tokens' => 0,
                    ]);
                }
            }

            DB::commit();

            // Reload relationships
            $user->load('customer.userLevel');

            return response()->json($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], 403);
        }

        DB::beginTransaction();
        try {
            // Delete customer profile first (if exists)
            if ($user->customer) {
                $user->customer->delete();
            }

            // Delete 
            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
