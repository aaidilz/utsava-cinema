<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

final class UserController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = User::query();

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptionStatus = (string) $request->query('subscription', 'all');
        if ($subscriptionStatus === 'premium') {
            $query->where('premium_until', '>', now());
        } elseif ($subscriptionStatus === 'free') {
            $query->where(function ($q) {
                $q->whereNull('premium_until')->orWhere('premium_until', '<=', now());
            });
        }

        $users = $query
            ->with(['latestTransaction', 'activeSubscription'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.users.table_rows', compact('users'))->render();
        }

        $totalUsers = User::query()->count();
        $totalTransactions = Transaction::query()->count();
        $totalRevenue = (float) Transaction::query()
            ->where('status', 'success')
            ->sum('amount');
        $activeSubscribers = User::query()
            ->where('premium_until', '>', now())
            ->count();

        return view('admin.dashboard', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'activeSubscribers' => $activeSubscribers,
            'search' => $search,
            'subscriptionStatus' => $subscriptionStatus,
        ]);
    }

    public function show(User $user)
    {
        $user->loadMissing([
            'transactions.subscription',
            'activeSubscription',
        ]);

        return view('admin.dashboard_detail', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:user,admin'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'User updated successfully');
    }
}
