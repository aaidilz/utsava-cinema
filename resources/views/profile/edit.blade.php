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
                <form method="POST" action="{{ route('auth.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Foto Profil</label>
                        <div class="flex items-start gap-6">
                            @php
                                $avatarPath = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
                            @endphp

                            <!-- Avatar Preview -->
                            <div class="relative group">
                                <div
                                    class="w-24 h-24 rounded-full overflow-hidden bg-[#352c6a] border-2 border-white/10 flex items-center justify-center">
                                    <img id="avatarPreview" src="{{ $avatarPath ?? '' }}" alt="Avatar"
                                        class="w-full h-full object-cover {{ $avatarPath ? '' : 'hidden' }}" />
                                    <span id="avatarFallback"
                                        class="text-white text-2xl font-bold {{ $avatarPath ? 'hidden' : '' }}">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                                @if($avatarPath)
                                    <button type="button" onclick="document.getElementById('deleteAvatarForm').submit()"
                                        class="absolute -bottom-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full shadow-lg transition-colors"
                                        title="Hapus Foto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Input Area -->
                            <div class="flex-1">
                                <div class="relative border-2 border-dashed border-white/20 rounded-xl p-6 text-center hover:border-[#8b7cf6] hover:bg-white/5 transition-all group cursor-pointer"
                                    id="dropZone">
                                    <input id="avatarInput" name="avatar" type="file"
                                        accept="image/png,image/jpeg,image/jpg"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div class="space-y-2 pointer-events-none">
                                        <div class="text-[#8b7cf6]">
                                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium">Klik atau drop gambar di sini</p>
                                        <p class="text-xs text-[#c7c4f3]">JPG, JPEG, PNG (Max 2MB)</p>
                                    </div>
                                </div>
                                <div id="fileName" class="text-xs text-[#8b7cf6] mt-2 hidden"></div>
                                @error('avatar')
                                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                                @enderror
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

                <form id="deleteAvatarForm" action="{{ route('auth.profile.destroy-avatar') }}" method="POST"
                    class="hidden">
                    @csrf
                    @method('DELETE')
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
                const fileName = document.getElementById('fileName');

                if (!input || !img || !fallback) return;

                input.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) {
                        fileName.innerText = '';
                        fileName.classList.add('hidden');
                        return;
                    }

                    // Show filename
                    fileName.innerText = `Selected: ${file.name}`;
                    fileName.classList.remove('hidden');

                    const url = URL.createObjectURL(file);
                    img.src = url;
                    img.classList.remove('hidden');
                    fallback.classList.add('hidden');
                });
            })();
        </script>
    @endpush
</x-layout>