<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In - Utsava Cinema</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="firebase-verify-url" content="{{ route('auth.firebase.verify') }}">
    <meta name="home-url" content="{{ route('home') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-[#0d0d0f] font-sans text-white antialiased selection:bg-indigo-500 selection:text-white relative overflow-hidden">

    <!-- Background Gradients -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div
            class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-3xl mix-blend-screen animate-pulse">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-violet-600/10 rounded-full blur-3xl mix-blend-screen">
        </div>
    </div>

    <!-- Login Container -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">

            <div
                class="bg-zinc-900/50 backdrop-blur-xl border border-white/10 rounded-3xl p-8 md:p-10 shadow-2xl relative overflow-hidden group">
                <!-- Decorative Top Border -->
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                </div>

                <div class="text-center mb-8">
                    <a href="{{ route('home') }}"
                        class="inline-block text-3xl font-black italic bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent mb-2">
                        ANIMETION
                    </a>
                    <h1 class="text-xl font-bold text-white italic">Welcome Back!</h1>
                    <p class="text-xs text-zinc-500 mt-1">Please sign in to continue watching.</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-emerald-500 text-xs font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2 text-red-400 text-xs">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="space-y-4">
                    <!-- Google Login -->
                    <button type="button" id="googleSignInBtn"
                        class="w-full flex items-center justify-center gap-3 py-3.5 rounded-xl bg-white text-black font-bold hover:bg-zinc-200 transition-all active:scale-95 group">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M3.58 13.55c-.32-.96-.45-1.99-.33-3.03l.03-.49-3.66-2.84C-1.58 10.32-1.28 15.63 1.25 20.3l2.84-2.19c-.58-1.52-.78-3.03-.51-4.56z" />
                            <path fill="#EA4335"
                                d="M12 4.47c1.55-.02 3.03.53 4.15 1.54l3.1-3.1C17.43 1.23 14.77-.02 12 0 7.7 0 3.99 2.47 2.18 6.51L5.75 9.28c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Continue with Google</span>
                    </button>

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/10"></div>
                        </div>
                        <div
                            class="relative flex justify-center text-[10px] uppercase tracking-widest text-zinc-500 font-bold bg-[#131316] px-2 rounded-full mx-auto w-fit z-10">
                            OR EMAIL</div>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Email
                                Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-indigo-500/50 focus:bg-black/40 outline-none transition-all"
                                placeholder="Enter your email">
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between ml-1">
                                <label
                                    class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Password</label>
                                {{-- <a href="#"
                                    class="text-[10px] text-indigo-400 hover:text-indigo-300 transition-colors">Forgot?</a>
                                --}}
                            </div>
                            <input type="password" name="password" required
                                class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-indigo-500/50 focus:bg-black/40 outline-none transition-all"
                                placeholder="Enter your password">
                        </div>

                        <div class="flex items-center gap-2 ml-1">
                            <input type="checkbox" name="remember" id="remember"
                                class="w-4 h-4 rounded bg-zinc-800 border-zinc-700 accent-indigo-500">
                            <label for="remember" class="text-xs text-zinc-400 select-none">Remember for 30 days</label>
                        </div>

                        <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold text-sm uppercase tracking-wide hover:brightness-110 active:scale-95 transition-all shadow-lg shadow-indigo-900/20">
                            Sign In
                        </button>
                    </form>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-zinc-500">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Create Account</a>
                    </p>
                </div>
            </div>

            <!-- Demo Info -->
            <div class="text-center mt-6 opacity-30 hover:opacity-100 transition-opacity">
                <p class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold">Demo Credentials</p>
                <div class="flex items-center justify-center gap-4 mt-2 text-xs text-zinc-400 font-mono">
                    <span>user@example.com</span>
                    <span>password</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts from original file -->
    <script id="firebase-config"
        type="application/json">{!! json_encode(config('services.firebase.web'), JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.5/firebase-app.js';
        import { getAuth, GoogleAuthProvider, signInWithPopup } from 'https://www.gstatic.com/firebasejs/10.12.5/firebase-auth.js';

        const firebaseConfigEl = document.getElementById('firebase-config');
        const firebaseConfig = firebaseConfigEl?.textContent ? JSON.parse(firebaseConfigEl.textContent) : null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const verifyUrl = document.querySelector('meta[name="firebase-verify-url"]')?.getAttribute('content');
        const homeUrl = document.querySelector('meta[name="home-url"]')?.getAttribute('content');
        const googleSignInBtn = document.getElementById('googleSignInBtn');

        const missingFirebaseConfig = !firebaseConfig
            || !firebaseConfig.apiKey
            || !firebaseConfig.authDomain
            || !firebaseConfig.projectId
            || !firebaseConfig.appId;

        if (missingFirebaseConfig) {
            console.warn('Firebase config missing');
            if (googleSignInBtn) {
                googleSignInBtn.innerHTML = '<span class="text-xs text-red-500">Google Login Not Configured</span>';
                googleSignInBtn.disabled = true;
            }
        }

        const app = missingFirebaseConfig ? null : initializeApp(firebaseConfig);
        const auth = app ? getAuth(app) : null;
        const googleProvider = new GoogleAuthProvider();

        async function signInWithProvider(provider) {
            if (!csrfToken || !verifyUrl || !auth) return;
            try {
                const result = await signInWithPopup(auth, provider);
                const idToken = await result.user.getIdToken(true);
                const res = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ idToken })
                });
                const data = await res.json();
                if (res.ok) window.location.href = data.redirect || homeUrl || '/';
                else alert(data.message || 'Login failed');
            } catch (err) {
                console.error(err);
            }
        }

        googleSignInBtn?.addEventListener('click', () => signInWithProvider(googleProvider));
    </script>
</body>

</html>