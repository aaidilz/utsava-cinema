<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuthController extends Controller
{
    /**
     * Verify Firebase ID token from frontend and sync to local SQL users table.
     */
    public function verifyToken(Request $request)
    {
        $validated = $request->validate([
            'idToken' => ['required', 'string'],
        ]);

        try {
            $verifiedIdToken = Firebase::auth()->verifyIdToken($validated['idToken']);
        } catch (\Throwable $e) {
            Log::warning('Firebase ID token verification failed', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            $clientMessage = 'Invalid or expired token.';
            if (config('app.debug')) {
                $clientMessage = $clientMessage.' '.$e->getMessage();
            }

            throw ValidationException::withMessages([
                'idToken' => $clientMessage,
            ]);
        }

        $claims = $verifiedIdToken->claims();

        $firebaseUid = (string) $claims->get('sub');
        $email = $claims->has('email') ? (string) $claims->get('email') : null;
        $name = $claims->has('name') ? (string) $claims->get('name') : null;

        if ($firebaseUid === '') {
            throw ValidationException::withMessages([
                'idToken' => 'Token is missing subject (sub).',
            ]);
        }

        if (!$email) {
            throw ValidationException::withMessages([
                'idToken' => 'Token is missing email claim.',
            ]);
        }

        // Prefer attaching firebase_uid to an existing local account by email
        // to avoid violating the unique email constraint.
        $user = User::where('firebase_uid', $firebaseUid)->first();

        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            $user->fill([
                'firebase_uid' => $firebaseUid,
                'email' => $email,
                'name' => $name ?? $user->name,
            ]);
            $user->save();
        } else {
            $user = User::create([
                'firebase_uid' => $firebaseUid,
                'email' => $email,
                'name' => $name ?? 'User',
                'password' => null,
                'role' => 'user',
            ]);
        }

        Auth::login($user);

        return response()->json([
            'ok' => true,
            'redirect' => route('home'),
        ]);
    }
}
