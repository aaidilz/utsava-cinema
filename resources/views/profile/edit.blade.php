<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-black text-white font-sans antialiased selection:bg-indigo-500 selection:text-white">
    <x-navbar />

    <div class="min-h-screen pt-24 pb-12" x-data="{ activeTab: 'overview' }">
        <!-- Banner & Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="relative group">
                <!-- Banner Image -->
                <div
                    class="w-full h-48 md:h-64 rounded-3xl overflow-hidden relative bg-zinc-900 border border-white/10">
                    @if($user->banner)
                        <img src="{{ Storage::url($user->banner) }}" alt="Banner" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-indigo-900/50 to-purple-900/50"></div>
                    @endif
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>

                    <!-- Edit Banner Button (Visible in Settings or independent?) -> Let's keep it in Settings tab to be cleaner, 
                         or add a quick button here if user wants. For now, let's keep editing in Settings. -->
                </div>

                <!-- Profile Info Overlay -->
                <div class="absolute -bottom-16 left-8 flex items-end gap-6">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full ring-4 ring-black bg-zinc-800 overflow-hidden relative">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-indigo-600 text-3xl font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        @if($user->is_premium)
                            <div class="absolute bottom-1 right-1 bg-indigo-500 text-white p-1.5 rounded-full border-4 border-black"
                                title="Premium Member">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                </svg>
                            </div>
                        @endif
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

        <!-- Navigation Tabs -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="flex border-b border-white/10">
                <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-zinc-400 hover:text-white'"
                    class="px-6 py-4 border-b-2 font-medium transition-colors">
                    Overview
                </button>
                <button @click="activeTab = 'settings'"
                    :class="activeTab === 'settings' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-zinc-400 hover:text-white'"
                    class="px-6 py-4 border-b-2 font-medium transition-colors">
                    Settings
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- OVERVIEW TAB -->
            <div x-show="activeTab === 'overview'" x-transition.opacity>

                <!-- Recent Watchlist -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            </svg>
                            Daftar Tontonan
                        </h2>
                        <button @click="activeTab = 'watchlist'" class="text-sm text-zinc-400 hover:text-white">Lihat
                            Semua</button>
                    </div>

                    @if($watchlist->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($watchlist as $item)
                                <a href="{{ route('anime.show', $item->identifier_id) }}"
                                    class="group relative rounded-lg overflow-hidden aspect-[2/3]">
                                    <img src="{{ $item->poster_path ?? 'https://via.placeholder.com/300x450' }}"
                                        alt="{{ $item->anime_title }}"
                                        class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-300">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <p class="text-xs text-zinc-300">Added {{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-zinc-900/50 rounded-xl border border-dashed border-zinc-800">
                            <p class="text-zinc-500">Daftar tontonan masih kosong.</p>
                        </div>
                    @endif
                </section>
            </div>

            <!-- WATCHLIST TAB -->
            <div x-show="activeTab === 'watchlist'" x-cloak>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @forelse($user->watchlists()->get() as $item)
                        <div class="relative group">
                            <a href="{{ route('anime.show', $item->identifier_id) }}"
                                class="block rounded-xl overflow-hidden aspect-[2/3] mb-3">
                                <img src="{{ $item->poster_path ?? 'https://via.placeholder.com/300x450' }}"
                                    alt="{{ $item->anime_title }}"
                                    class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-300">
                            </a>
                            <h3 class="font-bold text-sm truncate">{{ $item->anime_title }}</h3>
                            <form action="{{ route('watchlist.destroy', $item->identifier_id) }}" method="POST"
                                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-black/60 hover:bg-red-600 text-white p-1.5 rounded-full backdrop-blur-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20">
                            <p class="text-zinc-500 text-lg">Tidak ada item di watchlist.</p>
                            <a href="{{ route('anime.index') }}"
                                class="inline-block mt-4 bg-white/10 hover:bg-white text-white hover:text-black font-bold px-6 py-2 rounded-full transition-colors">Jelajahi
                                Anime</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SETTINGS TAB -->
            <div x-show="activeTab === 'settings'" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Settings Form -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Profile Details -->
                    <div class="bg-zinc-900 border border-white/5 rounded-2xl p-6 md:p-8">
                        <h2 class="text-xl font-bold mb-6">Profile Details</h2>
                        <form action="{{ route('auth.profile.update') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Avatar & Banner Upload -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-2">Profile Photo
                                        (Avatar)</label>
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-full bg-zinc-800 overflow-hidden flex-shrink-0">
                                            @if($user->avatar)
                                                <img src="{{ Storage::url($user->avatar) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-zinc-500">?
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" name="avatar" class="block w-full text-sm text-zinc-400
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-zinc-800 file:text-zinc-300
                                            hover:file:bg-zinc-700
                                        " />
                                    </div>
                                    @if($user->avatar)
                                        <button type="submit" form="deleteAvatarForm"
                                            class="text-xs text-red-400 mt-2 hover:underline">Hapus Avatar</button>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-2">Profile Banner</label>
                                    <input type="file" name="banner" class="block w-full text-sm text-zinc-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-zinc-800 file:text-zinc-300
                                        hover:file:bg-zinc-700
                                    " />
                                    @if($user->banner)
                                        <button type="submit" form="deleteBannerForm"
                                            class="text-xs text-red-400 mt-2 hover:underline">Hapus Banner</button>
                                    @endif
                                </div>
                            </div>

                            <!-- Text Fields -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Display Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="border-t border-white/5 pt-6">
                                <h3 class="text-lg font-bold mb-4">Security</h3>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-400 mb-1">New Password</label>
                                    <input type="password" name="password" placeholder="Leave empty to keep current"
                                        class="w-full bg-black/50 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <p class="text-xs text-zinc-500 mt-1">Min. 8 characters</p>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit"
                                    class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar / Subscription -->
                <div class="space-y-6">
                    <!-- Subscription Card -->
                    <div
                        class="bg-gradient-to-br from-indigo-900/40 to-black border border-indigo-500/20 rounded-2xl p-6 relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold mb-1 text-white">Subscription Status</h3>
                            @if($user->is_premium)
                                <div
                                    class="inline-block px-3 py-1 bg-indigo-500 text-white text-xs font-bold rounded-full mb-4 mt-2">
                                    PREMIUM</div>
                                <p class="text-zinc-300 text-sm mb-6">
                                    Your plan is active until <br>
                                    <span
                                        class="text-white font-bold text-lg">{{ $user->premium_until?->translatedFormat('d F Y') }}</span>
                                </p>
                                <a href="{{ route('pages.pricing') }}"
                                    class="block w-full text-center bg-white text-black font-bold py-2 rounded-lg hover:bg-zinc-200 transition-colors">
                                    Extend Plan
                                </a>
                            @else
                                <div
                                    class="inline-block px-3 py-1 bg-zinc-700 text-zinc-300 text-xs font-bold rounded-full mb-4 mt-2">
                                    FREE PLAN</div>
                                <p class="text-zinc-400 text-sm mb-6">Upgrade to Premium to unlock all anime and remove ads.
                                </p>
                                <a href="{{ route('pages.pricing') }}"
                                    class="block w-full text-center bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                    Upgrade Now
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-500/5 border border-red-500/10 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-red-500 mb-2">Danger Zone</h3>
                        <p class="text-sm text-zinc-500 mb-4">Once you delete your account, there is no going back.
                            Please be certain.</p>
                        <button type="button" onclick="alert('Delete account functionality coming soon')"
                            class="w-full border border-red-500/30 text-red-500 font-medium py-2 rounded-lg hover:bg-red-500/10 transition-colors">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Forms for Deletion -->
        <form id="deleteAvatarForm" action="{{ route('auth.profile.destroy-avatar') }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
        <form id="deleteBannerForm" action="{{ route('auth.profile.destroy-banner') }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    </div>
</body>

</html>