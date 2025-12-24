<x-layout title="Detail User">
    <main class="flex-1 min-h-screen px-6 py-10 text-white">

        <div class="max-w-4xl mx-auto">
            <!-- Card -->
            <div class="bg-[#352c6a] rounded-2xl shadow-xl p-8">

                <!-- Header -->
                <div class="flex items-center gap-6 mb-8">
                    <div class="w-20 h-20 rounded-full bg-[#8b7cf6] flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-[#c7c4f3]">{{ $user->email }}</p>

                        @if($user->isPremium())
                            <span class="inline-block mt-2 px-3 py-1 text-xs bg-green-500/20 text-green-300 rounded-full">
                                Premium User
                            </span>
                        @else
                            <span class="inline-block mt-2 px-3 py-1 text-xs bg-gray-500/20 text-gray-300 rounded-full">
                                Free User
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="bg-[#2b235a] rounded-lg p-4">
                        <p class="text-[#c7c4f3] mb-1">Role</p>
                        <p class="font-semibold capitalize">{{ $user->role }}</p>
                    </div>

                    <div class="bg-[#2b235a] rounded-lg p-4">
                        <p class="text-[#c7c4f3] mb-1">Status Subscription</p>
                        <p class="font-semibold">
                            {{ $user->isPremium() ? 'Active' : 'Inactive' }}
                        </p>
                    </div>

                    <div class="bg-[#2b235a] rounded-lg p-4">
                        <p class="text-[#c7c4f3] mb-1">Member Since</p>
                        <p class="font-semibold">{{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    @if($user->premium_until)
                    <div class="bg-[#2b235a] rounded-lg p-4">
                        <p class="text-[#c7c4f3] mb-1">Premium Until</p>
                        <p class="font-semibold text-green-300">
                            {{ $user->premium_until->format('d M Y') }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Action -->
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('admin.users.index') }}"
                       class="text-[#c7c4f3] hover:text-white transition">
                        ← Kembali ke Dashboard
                    </a>

                    <a href="{{ route('admin.users.edit', $user->id) }}"
                       class="px-5 py-2 bg-[#8b7cf6] hover:bg-[#7a6ae5] rounded-lg text-white font-semibold transition">
                        Edit User
                    </a>
                </div>

            </div>
        </div>

    </main>
</x-layout>
