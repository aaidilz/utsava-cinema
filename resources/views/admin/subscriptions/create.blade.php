<x-admin-layout title="Add Subscription Package">
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subscriptions.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-lg border border-white/10 hover:bg-white/5 text-[#c7c4f3] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold">Add New Package</h1>
                <p class="text-sm text-[#c7c4f3]">Create new subscription package for users</p>
            </div>
        </div>

        <div class="bg-[#352c6a] border border-white/10 rounded-2xl p-6 max-w-2xl">
            <form action="{{ route('admin.subscriptions.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Paket -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium text-[#c7c4f3]">Package Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="w-full bg-[#2b235a] border border-white/10 rounded-xl px-4 py-3 text-white placeholder:text-[#a3a0d9] focus:outline-none focus:border-[#8b7cf6] transition"
                        placeholder="Example: Monthly Premium" required>
                    @error('name')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="space-y-2">
                    <label for="price" class="text-sm font-medium text-[#c7c4f3]">Price (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01"
                        class="w-full bg-[#2b235a] border border-white/10 rounded-xl px-4 py-3 text-white placeholder:text-[#a3a0d9] focus:outline-none focus:border-[#8b7cf6] transition"
                        placeholder="0" required>
                    @error('price')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi -->
                <div class="space-y-2">
                    <label for="duration_days" class="text-sm font-medium text-[#c7c4f3]">Duration (Days)</label>
                    <input type="number" id="duration_days" name="duration_days" value="{{ old('duration_days', 30) }}"
                        min="1"
                        class="w-full bg-[#2b235a] border border-white/10 rounded-xl px-4 py-3 text-white placeholder:text-[#a3a0d9] focus:outline-none focus:border-[#8b7cf6] transition"
                        placeholder="30" required>
                    @error('duration_days')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-white/10 bg-[#2b235a] text-[#8b7cf6] focus:ring-[#8b7cf6] focus:ring-offset-[#352c6a]">
                    <label for="is_active" class="text-sm text-[#c7c4f3]">Active Status (Show on pricing page)</label>
                </div>
                @error('is_active')
                    <p class="text-sm text-red-400">{{ $message }}</p>
                @enderror

                <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                    <a href="{{ route('admin.subscriptions.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-white/10 hover:bg-white/5 text-[#c7c4f3] transition text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white transition text-sm font-medium shadow-lg shadow-[#8b7cf6]/20">
                        Save Package
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>