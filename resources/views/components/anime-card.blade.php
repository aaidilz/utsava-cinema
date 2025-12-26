@props(['anime'])

@php
    $isInWatchlist = false;
    if (auth()->check()) {
        $isInWatchlist = auth()->user()->watchlists->contains('identifier_id', $anime['id'] ?? $anime['identifier']);
    }
    // Handle differences in API response keys vs DB/view keys if any
    $id = $anime['id'] ?? $anime['identifier'] ?? '';
    $title = $anime['title'] ?? $anime['name'] ?? 'Unknown Title';
    $image = $anime['image'] ?? $anime['poster_path'] ?? '';
    $rating = $anime['rating'] ?? $anime['rating_score'] ?? '—';
    $episodes = $anime['episodes'] ?? $anime['total_episode'] ?? '—';
@endphp

<div
    class="group relative bg-[#352c6a] rounded-lg overflow-hidden hover:shadow-lg hover:shadow-[#8b7cf6]/20 transition-all duration-300">
    @if(auth()->check())
        <button
            class="absolute top-2 left-2 z-20 p-2 bg-black/50 hover:bg-black/70 text-white rounded-full opacity-100 transition-all duration-300 add-to-watchlist group-hover:scale-110"
            data-id="{{ $id }}" data-title="{{ $title }}" data-poster="{{ $image }}">
            <i class="fas fa-heart text-sm {{ $isInWatchlist ? 'text-red-500' : 'text-gray-400' }}"></i>
        </button>
    @endif

    <a href="{{ route('anime.show', $id) }}" class="block">
        <div class="aspect-[2/3] bg-gradient-to-br from-[#4a3f7a] to-[#352c6a] overflow-hidden relative">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="fas fa-image text-4xl text-[#6d5bd0] opacity-50"></i>
                </div>
            @endif

            <div
                class="absolute inset-0 bg-gradient-to-t from-[#1a1631] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>

            <div
                class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 flex items-center justify-center">
                <span class="px-3 py-1 bg-[#8b7cf6] text-white text-xs rounded-full font-medium">
                    <i class="fas fa-play mr-1"></i> Watch Now
                </span>
            </div>
        </div>
        <div class="p-3">
            <h3
                class="font-semibold text-sm text-[#f2f1ff] line-clamp-2 mb-1 group-hover:text-[#8b7cf6] transition-colors">
                {{ $title }}</h3>
            <div class="flex items-center justify-between text-xs text-[#c7c4f3]">
                <span class="flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-1"></i>
                    {{ $rating }}
                </span>
                <span>{{ $episodes }} eps</span>
            </div>
        </div>
    </a>
</div>