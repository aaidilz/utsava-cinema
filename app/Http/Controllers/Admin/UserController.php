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

        return view('auth.dashboard', compact('users'));
    }

    // ...existing code...
}