<x-admin-layout title="Edit User">
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold">Edit User</h1>
            <p class="text-sm text-[#c7c4f3]">Edit detail user {{ $user->name }}</p>
        </div>

        <div class="bg-[#352c6a] border border-white/10 rounded-2xl p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-[#c7c4f3]">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full bg-[#2b235a] border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#8b7cf6]">
                        @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-[#c7c4f3]">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full bg-[#2b235a] border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#8b7cf6]">
                        @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-[#c7c4f3]">Role</label>
                        <select name="role"
                            class="w-full bg-[#2b235a] border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#8b7cf6]">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin
                            </option>
                        </select>
                        @error('role') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-[#c7c4f3]">Password (Optional)</label>
                        <input type="password" name="password"
                            class="w-full bg-[#2b235a] border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#8b7cf6]"
                            placeholder="Leave blank to keep current">
                        @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 text-[#c7c4f3] hover:text-white transition">Cancel</a>
                    <button type="submit"
                        class="bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white px-6 py-2 rounded-lg font-medium transition">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>