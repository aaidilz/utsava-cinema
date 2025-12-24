<x-layout title="Pengaturan Akun">
    <main class="flex-1 container mx-auto max-w-4xl p-4 md:p-6 text-white min-h-screen">
        <div class="py-12 px-6">
            <h2 class="text-2xl font-bold mb-6">Pengaturan Akun</h2>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl shadow border border-white/6">
                <form method="POST" action="{{ route('auth.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            @php
                                $avatarPath = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
                            @endphp
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-white/10 border border-white/10 flex items-center justify-center">
                                <img
                                    id="avatarPreview"
                                    src="{{ $avatarPath ?? '' }}"
                                    alt="Avatar"
                                    class="w-full h-full object-cover {{ $avatarPath ? '' : 'hidden' }}"
                                />
                                <span id="avatarFallback" class="text-white font-semibold {{ $avatarPath ? 'hidden' : '' }}">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>

                            <div class="flex-1">
                                <input
                                    id="avatarInput"
                                    name="avatar"
                                    type="file"
                                    accept="image/png,image/jpeg"
                                    class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white"
                                >
                                <p class="text-xs text-[#c7c4f3] mt-2">JPG/PNG, maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>

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

    @push('scripts')
        <script>
            (function () {
                const input = document.getElementById('avatarInput');
                const img = document.getElementById('avatarPreview');
                const fallback = document.getElementById('avatarFallback');

                if (!input || !img || !fallback) return;

                input.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;

                    const url = URL.createObjectURL(file);
                    img.src = url;
                    img.classList.remove('hidden');
                    fallback.classList.add('hidden');
                });
            })();
        </script>
    @endpush
</x-layout>
