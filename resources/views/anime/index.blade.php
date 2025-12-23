<x-layout title="Anime List">
    
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Browse Anime</h1>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @forelse($animes as $a)
                <a href="{{ route('anime.show', $a['id']) }}" class="group relative bg-[#352c6a] rounded-lg overflow-hidden hover:shadow-lg hover:shadow-[#8b7cf6]/20 transition-all duration-300">
                    @if(auth()->check())
                        <button class="absolute top-2 left-2 z-20 p-2 bg-red-500/80 hover:bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity add-to-watchlist"
                                data-id="{{ $a['id'] }}"
                                data-title="{{ $a['title'] }}"
                                data-poster="{{ $a['cover'] ?? $a['poster'] }}">
                            <i class="fas fa-heart text-sm"></i>
                        </button>
                    @endif
                    <div class="aspect-[2/3] bg-gradient-to-br from-[#4a3f7a] to-[#352c6a]">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-[#6d5bd0] opacity-50"></i>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm text-[#f2f1ff] line-clamp-2 mb-1">{{ $a['title'] }}</h3>
                        <div class="flex items-center justify-between text-xs text-[#c7c4f3]">
                            <span class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                {{ $a['rating'] ?? '—' }}
                            </span>
                            <span>{{ $a['episodes'] ?? '—' }} eps</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-[#c7c4f3]">No anime found.</p>
            @endforelse
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Handle add to watchlist
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.add-to-watchlist')) {
                        e.preventDefault();
                        const btn = e.target.closest('.add-to-watchlist');
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
                            if (data.message === 'Added to watchlist') {
                                alert('Added to watchlist!');
                            } else if (data.message === 'Already in watchlist') {
                                alert('Already in your watchlist!');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });
        </script>
    @endpush
    
</x-layout>