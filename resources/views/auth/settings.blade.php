<x-layout title="Pengaturan Akun">
    <main class="flex-1 container mx-auto max-w-4xl px-4 md:px-6 py-10 text-white min-h-screen">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-8">
                Pengaturan Akun
            </h2>

            <div class="bg-white/5 backdrop-blur-md p-6 md:p-8 rounded-2xl shadow-lg border border-white/10">
                <form method="POST" action="#">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1">
                            Nama
                        </label>
                        <input
                            name="name"
                            type="text"
                            class="w-full rounded-lg px-4 py-2.5 bg-white/10 border border-white/10
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                            value="{{ old('name', auth()->user()->name) }}"
                        >
                    </div>

                    <!-- Email -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1">
                            Email
                        </label>
                        <input
                            name="email"
                            type="email"
                            class="w-full rounded-lg px-4 py-2.5 bg-white/10 border border-white/10
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                            value="{{ old('email', auth()->user()->email) }}"
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1">
                            Password Baru
                        </label>
                        <input
                            name="password"
                            type="password"
                            class="w-full rounded-lg px-4 py-2.5 bg-white/10 border border-white/10
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 text-white"
                        >
                        <p class="text-xs text-gray-400 mt-2">
                            Kosongkan jika tidak ingin mengubah password.
                        </p>
                    </div>

                    <!-- Button -->
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 transition
                                   text-white font-medium px-6 py-2.5 rounded-lg"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layout>
