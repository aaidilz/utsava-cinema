<x-admin-layout title="Detail User">
    <main class="flex-1 min-h-screen text-white">
        <div class="max-w-4xl mx-auto">
            <div class="bg-[#352c6a] rounded-2xl border border-white/10 p-6 md:p-8">
                <div class="flex items-center gap-6 mb-8">
                    <div
                        class="w-20 h-20 rounded-full bg-[#8b7cf6] overflow-hidden flex items-center justify-center text-3xl font-bold">
                        @php
                            $detailAvatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
                        @endphp
                        @if($detailAvatar)
                            <img src="{{ $detailAvatar }}" alt="Avatar" class="w-full h-full object-cover" />
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-[#c7c4f3]">{{ $user->email }}</p>

                        @if($user->isPremium())
                            <span
                                class="inline-block mt-2 px-3 py-1 text-xs bg-green-500/20 text-green-200 rounded-full border border-green-500/30">
                                Premium User
                            </span>
                        @else
                            <span
                                class="inline-block mt-2 px-3 py-1 text-xs bg-white/10 text-[#c7c4f3] rounded-full border border-white/10">
                                Free User
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="bg-[#2b235a] rounded-lg p-4 border border-white/10">
                        <p class="text-[#c7c4f3] mb-1">Role</p>
                        <p class="font-semibold capitalize">{{ $user->role }}</p>
                    </div>

                    <div class="bg-[#2b235a] rounded-lg p-4 border border-white/10">
                        <p class="text-[#c7c4f3] mb-1">Status Subscription</p>
                        <p class="font-semibold">
                            {{ $user->isPremium() ? 'Active' : 'Inactive' }}
                        </p>
                    </div>

                    <div class="bg-[#2b235a] rounded-lg p-4 border border-white/10">
                        <p class="text-[#c7c4f3] mb-1">Member Since</p>
                        <p class="font-semibold">{{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    <div class="bg-[#2b235a] rounded-lg p-4 border border-white/10">
                        <p class="text-[#c7c4f3] mb-1">Premium Until</p>
                        <p
                            class="font-semibold {{ $user->premium_until?->isFuture() ? 'text-green-200' : 'text-[#c7c4f3]' }}">
                            {{ $user->premium_until?->format('d M Y') ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('dashboard') }}#users" class="text-[#c7c4f3] hover:text-white transition">
                        ← Back to Dashboard
                    </a>

                    <div class="text-xs text-[#c7c4f3]">
                        Total transactions: {{ number_format((int) $user->transactions->count()) }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-admin-layout>