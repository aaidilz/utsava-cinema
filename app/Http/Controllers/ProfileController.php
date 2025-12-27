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
        return view('profile.edit');
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
            'password' => ['nullable', 'string', 'min:8'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
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

        $user->save();

        return back()->with('success', 'Pengaturan akun berhasil disimpan.');
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

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
