<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPremium
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah user premium menggunakan method yang sudah ada di model User
        if (!Auth::user()->isPremium()) {
            // Redirect ke halaman pricing jika belum bayar/premium habis
            return redirect()->route('pages.pricing')
                ->with('error', 'Anda harus berlangganan untuk menonton konten ini.');
        }

        return $next($request);
    }
}