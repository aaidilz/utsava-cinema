<x-layout title="My Watchlist">
    @push('styles')
    @endpush

    <x-navbar />

    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2">
                <i class="fas fa-heart text-red-500 mr-3"></i>My Watchlist
            </h1>
            <p class="text-[#c7c4f3]">Anime series and movies you want to watch</p>
        </div>

        <!-- Success/Error Messages -->
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

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg flex items-center justify-between">
            <p class="text-red-500 text-sm">{{ session('error') }}</p>
            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif

        <!-- Filter Tabs -->
        <div class="flex gap-4 mb-6 border-b border-[#4a3f7a]">
            <button class="px-4 py-2 text-sm font-semibold text-[#f2f1ff] border-b-2 border-[#8b7cf6]">
                All
            </button>
            <button class="px-4 py-2 text-sm text-[#c7c4f3] hover:text-[#f2f1ff] transition-colors">
                Watching
            </button>
            <button class="px-4 py-2 text-sm text-[#c7c4f3] hover:text-[#f2f1ff] transition-colors">
                Completed
            </button>
            <button class="px-4 py-2 text-sm text-[#c7c4f3] hover:text-[#f2f1ff] transition-colors">
                Plan to Watch
            </button>
        </div>

        <!-- Watchlist Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            
            @forelse(range(1, 12) as $index)
            <!-- Anime Card -->
            <div class="group relative bg-[#352c6a] rounded-lg overflow-hidden hover:shadow-lg hover:shadow-[#8b7cf6]/20 transition-all duration-300">
                <!-- Poster -->
                <div class="aspect-[2/3] bg-gradient-to-br from-[#4a3f7a] to-[#352c6a] relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-[#6d5bd0] opacity-50"></i>
                    </div>
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <div class="text-center space-y-3">
                            <button class="px-4 py-2 bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white rounded-lg text-sm transition-colors">
                                <i class="fas fa-play mr-2"></i>Watch
                            </button>
                            <button class="block w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm transition-colors">
                                <i class="fas fa-trash mr-2"></i>Remove
                            </button>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-2 left-2 px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                        Watching
                    </div>
                </div>

                <!-- Info -->
                <div class="p-3">
                    <h3 class="font-semibold text-sm text-[#f2f1ff] line-clamp-2 mb-1">
                        Anime Title {{ $index }}
                    </h3>
                    <div class="flex items-center justify-between text-xs text-[#c7c4f3]">
                        <span class="flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            8.5
                        </span>
                        <span>24 eps</span>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-20">
                <i class="fas fa-heart-broken text-6xl text-[#6d5bd0] mb-4"></i>
                <h3 class="text-2xl font-bold text-[#f2f1ff] mb-2">Your watchlist is empty</h3>
                <p class="text-[#c7c4f3] mb-6">Start adding your favorite anime to your watchlist!</p>
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Browse Anime
                </a>
            </div>
            @endforelse

        </div>

        <!-- Pagination (if needed) -->
        <div class="mt-8 flex justify-center">
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-4 py-2 bg-[#8b7cf6] text-white rounded-lg">1</button>
                <button class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">2</button>
                <button class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">3</button>
                <button class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

    </main>

    <x-footer />

</x-layout>
