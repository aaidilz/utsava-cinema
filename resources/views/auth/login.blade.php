<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Utsava Cinema</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="firebase-verify-url" content="{{ route('auth.firebase.verify') }}">
    <meta name="home-url" content="{{ route('home') }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Instrument Sans', sans-serif; background-color: #111; color: white; }
        </style>
    @endif
</head>
<body class="bg-gray-900 text-white">
    <!-- Background -->
    <div class="fixed inset-0 w-full h-full">
        <div class="absolute inset-0 bg-linear-to-br from-red-900/20 via-gray-900 to-black"></div>
    </div>

    <!-- Login Container -->
    <div class="relative min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-12">
                <a href="{{ route('home') }}" class="text-5xl font-extrabold tracking-tight text-red-600">
                    Utsava
                </a>
                <h1 class="text-3xl font-bold mt-8">Sign In</h1>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg">
                <p class="text-green-500 text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/30 text-white"
                        placeholder="your@email.com"
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/30 text-white"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        class="w-4 h-4 rounded bg-gray-800 border-gray-700 accent-red-600"
                    >
                    <label for="remember" class="ml-2 text-sm">Remember me</label>
                </div>

                <!-- Sign In Button -->
                <button 
                    type="submit"
                    class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 font-bold text-white rounded-lg transition duration-200"
                >
                    Sign In
                </button>

                <!-- Google Sign In -->
                <button
                    type="button"
                    id="googleSignInBtn"
                    class="w-full py-3 px-4 bg-gray-800 hover:bg-gray-700 font-bold text-white rounded-lg transition duration-200 border border-gray-700"
                >
                    Continue with Google
                </button>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-900 text-gray-400">or</span>
                    </div>
                </div>

                <!-- Sign Up Link -->
                <p class="text-center text-gray-400">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-red-600 hover:text-red-500 font-medium">
                        Sign Up
                    </a>
                </p>
            </form>

            <!-- Info -->
            <div class="mt-12 text-center text-gray-500 text-sm">
                <p>This page is protected. Demo credentials:</p>
                <p class="mt-2 text-gray-600">
                    Email: <span class="text-gray-400">demo@utsava.com</span><br>
                    Password: <span class="text-gray-400">password</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if ($errors->any())
        <div class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <script id="firebase-config" type="application/json">{!! json_encode(config('services.firebase.web'), JSON_UNESCAPED_SLASHES) !!}</script>

    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.5/firebase-app.js';
        import { getAuth, GoogleAuthProvider, GithubAuthProvider, signInWithPopup } from 'https://www.gstatic.com/firebasejs/10.12.5/firebase-auth.js';

        const firebaseConfigEl = document.getElementById('firebase-config');
        const firebaseConfig = firebaseConfigEl?.textContent ? JSON.parse(firebaseConfigEl.textContent) : null;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const verifyUrl = document.querySelector('meta[name="firebase-verify-url"]')?.getAttribute('content');
        const homeUrl = document.querySelector('meta[name="home-url"]')?.getAttribute('content');
        const googleSignInBtn = document.getElementById('googleSignInBtn');
        const githubSignInBtn = document.getElementById('githubSignInBtn');

        const missingFirebaseConfig = !firebaseConfig
            || !firebaseConfig.apiKey
            || !firebaseConfig.authDomain
            || !firebaseConfig.projectId
            || !firebaseConfig.appId;

        if (missingFirebaseConfig) {
            console.warn('Firebase web config is missing. Set FIREBASE_WEB_* variables in .env');
            if (googleSignInBtn) {
                googleSignInBtn.disabled = true;
                googleSignInBtn.classList.add('opacity-60', 'cursor-not-allowed');
                googleSignInBtn.textContent = 'Google Sign-In not configured';
            }

            if (githubSignInBtn) {
                githubSignInBtn.disabled = true;
                githubSignInBtn.classList.add('opacity-60', 'cursor-not-allowed');
                githubSignInBtn.textContent = 'GitHub Sign-In not configured';
            }
        }

        const app = missingFirebaseConfig ? null : initializeApp(firebaseConfig);
        const auth = app ? getAuth(app) : null;

        const googleProvider = new GoogleAuthProvider();
        const githubProvider = new GithubAuthProvider();
        // Helps ensure email is available for GitHub accounts.
        githubProvider.addScope('user:email');

        async function signInWithProvider(provider, providerLabel) {
            if (!csrfToken) {
                alert('Missing CSRF token.');
                return;
            }

            if (!verifyUrl) {
                alert('Missing verification endpoint URL.');
                return;
            }

            if (!auth) {
                alert(`${providerLabel} Sign-In is not configured.`);
                return;
            }

            try {
                const result = await signInWithPopup(auth, provider);
                const idToken = await result.user.getIdToken(true);

                const res = await fetch(verifyUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ idToken }),
                });

                const data = await res.json().catch(() => null);

                if (!res.ok) {
                    const message = data?.message || data?.errors?.idToken?.[0] || `${providerLabel} sign-in failed.`;
                    alert(message);
                    return;
                }

                window.location.href = data?.redirect || homeUrl || '/';
            } catch (err) {
                console.error(err);
                alert(`${providerLabel} sign-in cancelled or failed.`);
            }
        }

        googleSignInBtn?.addEventListener('click', () => signInWithProvider(googleProvider, 'Google'));
        githubSignInBtn?.addEventListener('click', () => signInWithProvider(githubProvider, 'GitHub'));
    </script>
</body>
</html>
