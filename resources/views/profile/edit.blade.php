<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-black text-white font-sans antialiased selection:bg-indigo-500 selection:text-white">
    <x-navbar />

    {{-- Pesan Error Validasi (Muncul jika ada kesalahan input) --}}
    @if($errors->any())
        <div class="fixed top-20 right-4 z-50 bg-red-500/10 border border-red-500/30 rounded-xl p-4 max-w-md space-y-2">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2 text-red-400 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="min-h-screen pt-24 pb-12" x-data="{ activeTab: 'overview' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative group">
                <div class="w-full h-48 md:h-64 rounded-3xl overflow-hidden relative bg-zinc-900 border border-white/10">
                    @if($user->banner)
                        <img src="{{ Storage::url($user->banner) }}" alt="Banner" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-indigo-900/50 to-purple-900/50"></div>
                    @endif
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                </div>

                <div class="absolute -bottom-16 left-8 flex items-end gap-6">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full ring-4 ring-black bg-zinc-800 overflow-hidden relative">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-indigo-600 text-3xl font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-20 px-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-zinc-400">{{ $user->email }}</p>
                </div>
                <div class="flex gap-2">
                    <button @click="activeTab = 'settings'"
                        :class="activeTab === 'settings' ? 'bg-white text-black' : 'bg-white/10 text-white hover:bg-white/20'"
                        class="px-4 py-2 rounded-lg font-bold transition-colors">
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="flex border-b border-white/10">
                <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-zinc-400 hover:text-white'"
                    class="px-6 py-4 border-b-2 font-medium transition-colors">Overview</button>
                <button @click="activeTab = 'settings'"
                    :class="activeTab === 'settings' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-zinc-400 hover:text-white'"
                    class="px-6 py-4 border-b-2 font-medium transition-colors">Settings</button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-show="activeTab === 'overview'" x-transition.opacity>
                <section>
                    <div class="flex items-center justify-between mb-6 text-xl font-bold">Daftar Tontonan</div>
                    @if($watchlist->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($watchlist as $item)
                                <a href="{{ route('anime.show', $item->identifier_id) }}" class="group relative rounded-lg overflow-hidden aspect-[2/3]">
                                    <img src="{{ $item->poster_path ?? 'https://via.placeholder.com/300x450' }}" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-300">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-zinc-900/50 rounded-xl border border-dashed border-zinc-800 text-zinc-500">Daftar tontonan kosong.</div>
                    @endif
                </section>
            </div>

            <div x-show="activeTab === 'settings'" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-zinc-900 border border-white/5 rounded-2xl p-6 md:p-8">
                        <h2 class="text-xl font-bold mb-6">Profile Details</h2>
                        <form id="updateProfileForm" action="{{ route('auth.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-2">Avatar</label>
                                    <input type="file" name="avatar" class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-2">Banner</label>
                                    <input type="file" name="banner" class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Display Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white">
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="button" onclick="confirmUpdate()" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-red-500/5 border border-red-500/10 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-red-500 mb-2">Danger Zone</h3>
                        <p class="text-sm text-zinc-500 mb-4">Aksi ini permanen. Mohon berhati-hati.</p>
                        <button type="button" onclick="openDeleteAccountModal()" class="w-full border border-red-500/30 text-red-500 font-medium py-2 rounded-lg hover:bg-red-500/10 transition-colors">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form id="deleteAccountForm" action="{{ route('auth.profile.destroy') }}" method="POST" class="hidden">
            @csrf @method('DELETE')
            <input type="hidden" name="password" id="passwordInput">
        </form>
    </div>

    <script>
        // Konfigurasi SweetAlert2 Toast (Meniru React Toastify / Toast.success)
        const Toast = Swal.mixin({
            toast: true,
            position: "top-right",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // Jalankan Toast jika ada session success dari Laravel
        @if(session('success'))
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}",
                background: '#10b981', // Tema warna hijau
                color: '#ffffff'
            });
        @endif

        // Konfirmasi Update Profil
        function confirmUpdate() {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Informasi profil Anda akan diperbarui.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                background: '#0d0d0f',
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('updateProfileForm').submit();
                }
            });
        }

        // Konfirmasi Hapus Akun
        function openDeleteAccountModal() {
            Swal.fire({
                title: 'Hapus Akun?',
                html: `
                    <div class="text-left space-y-4">
                        <p class="text-red-500 font-semibold">⚠️ Peringatan: Aksi ini tidak dapat dibatalkan!</p>
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Masukkan password:</label>
                            <input type="password" id="swalPasswordInput" class="w-full bg-black/50 border border-red-500/30 rounded-lg px-4 py-2 text-white" placeholder="Password Anda">
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus Akun',
                cancelButtonText: 'Batal',
                background: '#0d0d0f',
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    const password = document.getElementById('swalPasswordInput').value;
                    if (!password.trim()) {
                        Swal.fire({ title: 'Error', text: 'Password wajib diisi!', icon: 'error', background: '#0d0d0f', color: '#ffffff' });
                        return;
                    }
                    document.getElementById('passwordInput').value = password;
                    document.getElementById('deleteAccountForm').submit();
                }
            });
        }
    </script>
</body>
</html>