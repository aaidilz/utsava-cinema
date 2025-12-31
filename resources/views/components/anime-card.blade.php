@props(['anime'])

@php
    $isInWatchlist = false;
    if (auth()->check()) {
        $isInWatchlist = auth()->user()->watchlists->contains('identifier_id', $anime['id'] ?? $anime['identifier']);
    }
    // Handle differences in API response keys vs DB/view keys if any
    $id = $anime['id'] ?? $anime['identifier'] ?? '';
    $title = $anime['title'] ?? $anime['name'] ?? 'Unknown Title';
    $image = $anime['image'] ?? $anime['poster_path'] ?? $anime['cover'] ?? '';
    // $rating = $anime['rating'] ?? $anime['rating_score'] ?? '?';
    $rating = isset($anime['rating_score']) ? $anime['rating_score'] : ($anime['rating'] ?? '?');
    $episodes = $anime['episodes'] ?? $anime['total_episode'] ?? '?';
    $year = $anime['year'] ?? $anime['release_year'] ?? '';
@endphp

<div class="group relative">
    <a href="{{ route('anime.show', $id) }}" class="block">
        <!-- Card Image -->
        <div
            class="aspect-[2/3] w-full rounded-2xl overflow-hidden bg-[#1a1a20] relative shadow-lg group-hover:-translate-y-2 group-hover:shadow-indigo-500/20 transition-all duration-300 ring-1 ring-white/5">
            @if($image)
                <img src="{{ $image }}" alt="{{ $title }}" loading="lazy"
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-in-out">
            @else
                <div class="w-full h-full flex items-center justify-center bg-zinc-800 text-zinc-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif

            <!-- Gradient Overlay -->
            <div
                class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-60 group-hover:opacity-80 transition-opacity">
            </div>

            <!-- Tags/Badges -->
            <div class="absolute top-3 left-3 flex flex-col gap-2">
                @if($year == date('Y'))
                    <span class="bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-lg">NEW</span>
                @endif
            </div>

            <!-- Heart Button (Watchlist) -->
            @if(auth()->check())
                <button
                    class="add-to-watchlist absolute top-3 right-3 p-2 rounded-full bg-black/40 backdrop-blur-md border border-white/10 hover:bg-white text-zinc-400 hover:text-red-500 transition-all group/btn z-20"
                    data-id="{{ $id }}" data-title="{{ $title }}" data-poster="{{ $image }}">
                    <svg class="w-4 h-4 {{ $isInWatchlist ? 'text-red-500 fill-current' : '' }}" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            @endif
        </div>

        <!-- Metadata -->
        <div class="mt-3 px-1 space-y-1">
            <h3 class="text-base font-bold text-white line-clamp-1 group-hover:text-indigo-400 transition-colors"
                title="{{ $title }}">
                {{ $title }}
            </h3>
        </div>
    </a>
</div>