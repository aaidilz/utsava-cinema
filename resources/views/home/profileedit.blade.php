
<x-layout title="Edit Profile">
    <main class="flex-1 container mx-auto max-w-2xl p-4 md:p-6 text-white min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Edit Profile</h1>
            <p class="text-[#c7c4f3]">Update your personal information and profile picture</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg flex items-center justify-between">
            <p class="text-green-500 text-sm">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif

        <div class="bg-[#352c6a] rounded-lg p-6 md:p-8">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar Section -->
                <div>
                    <label class="block text-sm font-medium mb-4">Profile Picture</label>
                    
                    <div class="flex items-center gap-6">
                        <!-- Current Avatar -->
                        <div class="relative">
                            <img src="{{ $user->getAvatarUrl() }}" 
                                 alt="{{ $user->name }}"
                                 class="w-24 h-24 rounded-full object-cover border-2 border-[#8b7cf6]">
                        </div>

                        <!-- Upload Input -->
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="avatar"
                                id="avatar"
                                accept="image/*"
                                class="hidden"
                            >
                            <label for="avatar" class="inline-block px-4 py-2 bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white rounded-lg cursor-pointer transition-colors">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>Upload New Picture
                            </label>
                            <p class="text-xs text-[#c7c4f3] mt-2">JPG, PNG or GIF (max 2MB)</p>

                            <!-- Preview -->
                            <div id="preview-container" class="mt-4"></div>
                        </div>
                    </div>

                    @error('avatar')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Divider -->
                <div class="border-t border-[#4a3f7a]"></div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 bg-[#2b235a] border border-[#4a3f7a] rounded-lg focus:outline-none focus:border-[#8b7cf6] focus:ring-2 focus:ring-[#8b7cf6]/30 text-white"
                        required
                    >
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 bg-[#2b235a] border border-[#4a3f7a] rounded-lg focus:outline-none focus:border-[#8b7cf6] focus:ring-2 focus:ring-[#8b7cf6]/30 text-white"
                        required
                    >
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Info Display -->
                <div class="bg-[#2b235a] rounded-lg p-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-[#c7c4f3]">Member Since:</span>
                        <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#c7c4f3]">Role:</span>
                        <span class="text-white capitalize">{{ $user->role }}</span>
                    </div>
                    @if($user->is_premium)
                    <div class="flex justify-between">
                        <span class="text-[#c7c4f3]">Premium Until:</span>
                        <span class="text-green-400">{{ $user->premium_until->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="submit"
                        class="flex-1 py-3 px-4 bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white font-semibold rounded-lg transition-colors"
                    >
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <a 
                        href="{{ route('home') }}"
                        class="flex-1 py-3 px-4 bg-[#4a3f7a] hover:bg-[#5a4f8a] text-white font-semibold rounded-lg transition-colors text-center"
                    >
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
    <script>
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-container');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.innerHTML = `
                        <div class="relative mt-4">
                            <img src="${event.target.result}" 
                                 alt="Preview" 
                                 class="w-24 h-24 rounded-full object-cover border-2 border-green-500">
                            <span class="absolute -top-2 -right-2 bg-green-500 text-white text-xs rounded-full px-2 py-1">New</span>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-layout>