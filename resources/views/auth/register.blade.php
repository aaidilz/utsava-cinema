<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - Utsava Cinema</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-3xl mix-blend-screen animate-pulse">
        </div>
        <div
            class="absolute bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-pink-600/10 rounded-full blur-3xl mix-blend-screen">
        </div>
    </div>

    <!-- Register Container -->
    <div class="min-h-screen flex items-center justify-center p-4 py-10">
        <div class="w-full max-w-md">

            <div
                class="bg-zinc-900/50 backdrop-blur-xl border border-white/10 rounded-3xl p-8 md:p-10 shadow-2xl relative overflow-hidden">
                <!-- Decorative Top Border -->
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500">
                </div>

                <div class="text-center mb-8">
                    <a href="{{ route('home') }}"
                        class="inline-block text-3xl font-black italic bg-gradient-to-r from-pink-400 to-indigo-400 bg-clip-text text-transparent mb-2">
                        UTSAVA CINEMA
                    </a>
                    <h1 class="text-xl font-bold text-white italic">Join the Club!</h1>
                    <p class="text-xs text-zinc-500 mt-1">Create an account to start your journey.</p>
                </div>

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

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Full
                            Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-indigo-500/50 focus:bg-black/40 outline-none transition-all"
                            placeholder="e.g. Naruto Uzumaki">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Email
                            Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-indigo-500/50 focus:bg-black/40 outline-none transition-all"
                            placeholder="e.g. naruto@konoha.com">
                    </div>

                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-indigo-500/50 focus:bg-black/40 outline-none transition-all"
                            placeholder="Min. 8 characters">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest ml-1">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full bg-black/20 border border-white/5 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-indigo-500/50 focus:bg-black/40 outline-none transition-all"
                            placeholder="Repeat password">
                    </div>

                    <div class="flex items-start gap-2 ml-1 pt-2">
                        <input type="checkbox" name="agree_terms" id="agree_terms" required
                            class="mt-0.5 w-4 h-4 rounded bg-zinc-800 border-zinc-700 accent-indigo-500">
                        <label for="agree_terms" class="text-xs text-zinc-400 select-none">
                            I agree to the <a href="#" class="text-indigo-400 hover:text-indigo-300">Terms of
                                Service</a> and <a href="#" class="text-indigo-400 hover:text-indigo-300">Privacy
                                Policy</a>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 rounded-xl bg-gradient-to-r from-pink-600 to-purple-600 text-white font-bold text-sm uppercase tracking-wide hover:brightness-110 active:scale-95 transition-all shadow-lg shadow-pink-900/20 mt-2">
                        Create Account
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-zinc-500">
                        Already have an account?
                        <a href="{{ route('login') }}"
                            class="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">Sign In</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</body>

</html>