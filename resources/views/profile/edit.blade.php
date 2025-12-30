<x-layout title="Profile - {{ config('app.name') }}">
    <div class="min-h-screen pt-24 pb-12" x-data="{ activeTab: 'settings' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative group">
                <div class="w-full h-48 md:h-64 rounded-3xl overflow-hidden relative bg-zinc-900 border border-white/10">
                    @if($user->banner)
                        <img src="{{ Storage::url($user->banner) }}" alt="Banner" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-indigo-900/50 to-purple-900/50"></div>
                    @endif
                </div>

                <div class="absolute -bottom-16 left-8">
                    <div class="w-32 h-32 rounded-full ring-4 ring-black bg-zinc-800 overflow-hidden">
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

            <div class="mt-20 px-4">
                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                <p class="text-zinc-400">{{ $user->email }}</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-zinc-900 border border-white/5 rounded-2xl p-6 md:p-8">
                        <h2 class="text-xl font-bold mb-6">Profile Details</h2>

                        <form id="updateProfileForm" action="{{ route('auth.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-2">Profile Photo (Avatar)</label>
                                    <input type="file" name="avatar" class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-2">Profile Banner</label>
                                    <input type="file" name="banner" class="block w-full text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-zinc-800 file:text-zinc-300 hover:file:bg-zinc-700" />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Display Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white">
                                </div>
                            </div>

                            <div class="pt-6 border-t border-white/5">
                                <h3 class="text-lg font-bold mb-4">Security</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-400 mb-1">New Password</label>
                                        <input type="password" name="password" minlength="8" class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white" placeholder="Leave empty to keep current">
                                        <p class="text-[10px] text-zinc-500 mt-1">Min. 8 characters</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-400 mb-1">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white" placeholder="Confirm your new password">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="button" onclick="confirmUpdate()" class="bg-indigo-600 text-white font-bold py-2.5 px-8 rounded-lg hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar / Subscription -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-indigo-900/40 to-black border border-indigo-500/20 rounded-2xl p-6 relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-1 text-white">Subscription Status</h3>
                            @if($user->is_premium)
                                <div class="inline-block px-3 py-1 bg-indigo-500 text-white text-xs font-bold rounded-full mb-4 mt-2">PREMIUM</div>
                                <p class="text-zinc-300 text-sm mb-6">
                                    Your plan is active until <br>
                                    <span class="text-white font-bold text-lg">{{ $user->premium_until?->translatedFormat('d F Y') }}</span>
                                </p>
                                <a href="{{ route('pages.pricing') }}" class="block w-full text-center bg-white text-black font-bold py-2 rounded-lg hover:bg-zinc-200 transition-colors">Extend Plan</a>
                            @else
                                <div class="inline-block px-3 py-1 bg-zinc-700 text-zinc-300 text-xs font-bold rounded-full mb-4 mt-2">FREE PLAN</div>
                                <p class="text-zinc-400 text-sm mb-6">Upgrade to Premium to unlock all anime and remove ads.</p>
                                <a href="{{ route('pages.pricing') }}" class="block w-full text-center bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition-colors">Upgrade Now</a>
                            @endif
                        </div>
                    </div>

                    <div class="bg-red-500/5 border border-red-500/10 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-red-500 mb-2">Danger Zone</h3>
                        <p class="text-sm text-zinc-500 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                        <button type="button" onclick="openDeleteAccountModal()" class="w-full border border-red-500/30 text-red-500 font-medium py-2 rounded-lg hover:bg-red-500/10 transition-colors">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteAccountForm" action="{{ route('auth.profile.destroy') }}" method="POST" class="hidden">
        @csrf @method('DELETE')
        <input type="hidden" name="password" id="passwordInput">
    </form>

    @push('scripts')
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#18181b',
                color: '#ffffff'
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memperbarui',
                    text: 'Periksa kembali data yang Anda masukkan.',
                    background: '#0d0d0f',
                    color: '#ffffff',
                    confirmButtonColor: '#6366f1'
                });
            @endif

            function confirmUpdate() {
                Swal.fire({
                    title: 'Simpan Perubahan?',
                    text: "Data profil Anda akan diperbarui.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    cancelButtonColor: '#3f3f46',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal',
                    background: '#0d0d0f',
                    color: '#ffffff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }, background: '#0d0d0f', color: '#ffffff' });
                        document.getElementById('updateProfileForm').submit();
                    }
                });
            }

            function openDeleteAccountModal() {
                Swal.fire({
                    title: 'Hapus Akun?',
                    html: `
                        <div class="text-left space-y-4">
                            <p class="text-red-500 font-semibold italic text-sm">⚠️ Peringatan: Seluruh data tontonan akan hilang permanen.</p>
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-zinc-300 mb-2">Konfirmasi Password:</label>
                                <input type="password" id="swalPasswordInput" class="w-full bg-black/50 border border-red-500/30 rounded-lg px-4 py-2 text-white" placeholder="Password Anda">
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#3f3f46',
                    confirmButtonText: 'Hapus Sekarang',
                    cancelButtonText: 'Batal',
                    background: '#0d0d0f',
                    color: '#ffffff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const pass = document.getElementById('swalPasswordInput').value;
                        if (!pass) {
                            Swal.fire({ title: 'Error', text: 'Password wajib diisi!', icon: 'error', background: '#0d0d0f', color: '#ffffff' });
                            return;
                        }
                        document.getElementById('passwordInput').value = pass;
                        document.getElementById('deleteAccountForm').submit();
                    }
                });
            }
        </script>
    @endpush
</x-layout>