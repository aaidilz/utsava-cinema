<x-layout title="My Watchlist">
    @push('styles')
    @endpush


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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 rounded-lg flex items-center justify-between">
                <p class="text-red-500 text-sm">{{ session('error') }}</p>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
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

            @forelse($watchlists as $item)
                @php
                    // Transform model to array expected by component
                    $anime = [
                        'id' => $item->identifier_id,
                        'title' => $item->anime_title,
                        'image' => $item->poster_path,
                        'rating' => '?', // Not stored in DB
                        'episodes' => '?', // Not stored in DB
                    ];
                @endphp
                <x-anime-card :anime="$anime" />
            @empty
                <!-- Empty State -->
                <div class="col-span-full text-center py-20">
                    <i class="fas fa-heart-broken text-6xl text-[#6d5bd0] mb-4"></i>
                    <h3 class="text-2xl font-bold text-[#f2f1ff] mb-2">Your watchlist is empty</h3>
                    <p class="text-[#c7c4f3] mb-6">Start adding your favorite anime to your watchlist!</p>
                    <a href="{{ route('anime.index') }}"
                        class="inline-block px-6 py-3 bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white rounded-lg transition-colors">
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
                <button
                    class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">2</button>
                <button
                    class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">3</button>
                <button class="px-4 py-2 bg-[#352c6a] text-[#c7c4f3] rounded-lg hover:bg-[#4a3f7a] transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

    </main>

</x-layout>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Handle add to watchlist
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.add-to-watchlist');
                if (btn) {
                    e.preventDefault();
                    const icon = btn.querySelector('i');

                    const data = {
                        identifier_id: btn.dataset.id,
                        anime_title: btn.dataset.title,
                        poster_path: btn.dataset.poster,
                    };

                    fetch('/watchlist', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'added') {
                                icon.classList.remove('text-gray-400');
                                icon.classList.add('text-red-500');
                                // alert('Added to watchlist!');
                            } else if (data.status === 'removed') {
                                icon.classList.remove('text-red-500');
                                icon.classList.add('text-gray-400');
                                // alert('Removed from watchlist!');

                                // If in watchlist page, maybe remove the card or reload
                                if (window.location.pathname.includes('/watchlist')) {
                                    const card = btn.closest('.group');
                                    if (card) {
                                        card.remove();
                                        // Check if empty
                                        const grid = document.querySelector('.grid');
                                        if (grid && grid.children.length === 0) {
                                            location.reload();
                                        }
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>
@endpush