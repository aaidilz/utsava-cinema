<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

final class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $watchlist = $user->watchlists()->orderBy('created_at', 'desc')->take(6)->get();

        return view('profile.edit', compact('user', 'watchlist'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'banner' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB for banner
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'] ?? null)) {
            $user->password = Hash::make((string) $validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $oldAvatar = (string) ($user->avatar ?? '');
            $path = $request->file('avatar')->store('avatars', 'public');
            if ($oldAvatar !== '' && $oldAvatar !== $path) {
                Storage::disk('public')->delete($oldAvatar);
            }
            $user->avatar = $path;
        }

        if ($request->hasFile('banner')) {
            $oldBanner = (string) ($user->banner ?? '');
            $path = $request->file('banner')->store('banners', 'public');
            if ($oldBanner !== '' && $oldBanner !== $path) {
                Storage::disk('public')->delete($oldBanner);
            }
            $user->banner = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function destroyAvatar()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return back()->with('success', 'Avatar removed.');
    }

    public function destroyBanner()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
            $user->banner = null;
            $user->save();
        }

        return back()->with('success', 'Banner removed.');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validasi password untuk konfirmasi
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Cek apakah password benar
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan tidak sesuai.'
            ])->withInput();
        }

        // Hapus avatar dan banner jika ada
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
        }

        // Hapus semua watchlist user
        $user->watchlists()->delete();

        // Logout user
        Auth::logout();

        // Hapus user
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Akun Anda berhasil dihapus.');
    }
}
