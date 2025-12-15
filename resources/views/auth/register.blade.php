<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - Utsava Cinema</title>
    
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
        <div class="absolute inset-0 bg-gradient-to-br from-red-900/20 via-gray-900 to-black"></div>
    </div>

    <!-- Register Container -->
    <div class="relative min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-12">
                <a href="{{ route('home') }}" class="text-5xl font-extrabold tracking-tight text-red-600">
                    Utsava
                </a>
                <h1 class="text-3xl font-bold mt-8">Create Account</h1>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('auth.register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/30 text-white"
                        placeholder="John Doe"
                        required
                    >
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                    <p class="text-gray-500 text-xs mt-1">Minimum 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600/30 text-white"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        name="agree_terms" 
                        id="agree_terms"
                        class="w-4 h-4 rounded bg-gray-800 border-gray-700 accent-red-600 mt-1"
                        required
                    >
                    <label for="agree_terms" class="ml-2 text-sm text-gray-400">
                        I agree to the Terms of Service and Privacy Policy
                    </label>
                </div>

                <!-- Sign Up Button -->
                <button 
                    type="submit"
                    class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 font-bold text-white rounded-lg transition duration-200"
                >
                    Create Account
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

                <!-- Sign In Link -->
                <p class="text-center text-gray-400">
                    Already have an account?
                    <a href="{{ route('auth.login') }}" class="text-red-600 hover:text-red-500 font-medium">
                        Sign In
                    </a>
                </p>
            </form>
        </div>
    </div>

    <!-- Error Message -->
    @if ($errors->any())
        <div class="fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg max-w-xs">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</body>
</html>
