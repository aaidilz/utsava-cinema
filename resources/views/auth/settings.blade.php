<x-layout title="Pengaturan Akun">

<div class="max-w-4xl mx-auto pt-20 pb-12 px-6">
    <h2 class="text-3xl font-bold mb-8 text-center text-white">
        Pengaturan Akun
    </h2>

    <div class=" bg-linear-to-br from-[#6d5bd0] to-[#8b7cf6] backdrop-blur p-8 rounded-2xl shadow-xl">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('auth.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-3">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <!-- Current Avatar Preview -->
                    <div class="w-20 h-20 rounded-full bg-[#8b7cf6] overflow-hidden flex items-center justify-center flex-shrink-0">
                        @php
                            $avatarUrl = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
                        @endphp
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" />
                        @else
                            <span class="text-white font-semibold text-2xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    
                    <!-- Upload Input -->
                    <div class="flex-1">
                        <input 
                            type="file" 
                            name="avatar" 
                            id="avatar"
                            accept="image/jpeg,image/png,image/jpg"
                            class="w-full border border-white/30 rounded-lg px-3 py-2 bg-[#2b235a] text-white file:bg-[#8b7cf6] file:border-0 file:px-3 file:py-1 file:rounded file:text-white file:cursor-pointer hover:border-white/50"
                        >
                        <p class="text-xs text-[#c7c4f3] mt-1">Maksimal 2MB (JPG, PNG)</p>
                        @error('avatar')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Nama</label>
                <input type="text"
                       name="name"
                       class="w-full border border-white/30 rounded-lg px-3 py-2 mt-1 bg-[#2b235a] text-white placeholder:text-[#a3a0d9]"
                       value="{{ old('name', auth()->user()->name) }}"
                       required>
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Email</label>
                <input type="email"
                       name="email"
                       class="w-full border border-white/30 rounded-lg px-3 py-2 mt-1 bg-[#2b235a] text-white placeholder:text-[#a3a0d9]"
                       value="{{ old('email', auth()->user()->email) }}"
                       required>
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Baru -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Password Baru</label>
                <input type="password"
                       name="password"
                       class="w-full border border-white/30 rounded-lg px-3 py-2 mt-1 bg-[#2b235a] text-white placeholder:text-[#a3a0d9]"
                       placeholder="Kosongkan jika tidak diganti">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>

</x-layout>