<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Login</title>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="w-full max-w-sm bg-white shadow-lg rounded-xl p-8">

        <h2 class="text-2xl font-bold text-gray-800 text-center">Login</h2>
        <p class="text-center text-gray-500 text-sm mb-6">
            Masuk ke akun Anda
        </p>

        
        <form action="/login" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    placeholder="nama@contoh.com"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-300 focus:outline-none"
                >

                @error('email')
                    <p class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-300 focus:outline-none"
                >

                @error('password')
                    <p class="text-sm text-red-500 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Tombol Login -->
            <button
                type="submit"
                class="w-full py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition"
            >
                Login
            </button>
        </form>

        <!-- Link Register -->
        <p class="text-center text-sm text-gray-600 mt-6">
            Belum punya akun?
            <a href="/register" class="text-indigo-600 hover:underline font-medium">
                Daftar di sini
            </a>
        </p>

    </div>

</body>
</html>
