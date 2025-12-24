<x-layout title="Pengaturan Akun">
    <main class="flex-1 container mx-auto max-w-4xl p-4 md:p-6 text-white min-h-screen">
        <div class="py-12 px-6">
            <h2 class="text-2xl font-bold mb-6">Pengaturan Akun</h2>

            <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl shadow border border-white/6">
                <form method="POST" action="#">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nama</label>
                        <input name="name" type="text"
                               class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white"
                               value="{{ old('name', auth()->user()->name) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Email</label>
                        <input name="email" type="email"
                               class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white"
                               value="{{ old('email', auth()->user()->email) }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium">Password Baru</label>
                        <input name="password" type="password"
                               class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white">
                        <p class="text-xs text-[#c7c4f3] mt-2">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>

                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layout>
