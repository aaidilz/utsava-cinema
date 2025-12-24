<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

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
}
