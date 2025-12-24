<?php
// ...existing code...
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // load users with latest transaction for billing info
        $users = User::with(['transactions' => function($q) {
            $q->latest()->limit(1);
        }])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.dashboard', compact('users'));
    }

    public function show(User $user)
{
    // ambil transaksi terakhir user (optional)
    $user->load(['transactions' => function ($q) {
        $q->latest()->limit(1);
    }]);

    return view('admin.dashboarddetail', compact('user'));
}

    // ...existing code...
}