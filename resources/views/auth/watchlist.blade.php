<x-layout title="My Watchlist - Utsava Cinema">
    <main class="min-h-screen pt-24 pb-20 px-4 md:px-6 container mx-auto max-w-7xl">

        <div class="flex items-center gap-4 mb-8 border-b border-white/10 pb-6">
            <h1 class="text-3xl font-black italic text-white uppercase tracking-tight">My Watchlist</h1>
            <span class="px-3 py-1 bg-[#1a1a20] text-zinc-400 text-xs font-bold rounded-full border border-white/5">
                {{ count($watchlists) }} Items
            </span>
        </div>

        @if($watchlists->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-10">
                @foreach($watchlists as $item)
                    {{-- Adapt UserWatchlist model to anime card props --}}
                    @php
                        $animeData = [
                            'id' => $item->identifier_id,
                            'title' => $item->anime_title,
                            'poster_path' => $item->poster_path,
                            // Rating/Year might not be stored in watchlist, so we skip or placeholder
                            'rating' => '?',
                            'episodes' => '?'
                        ];
                    @endphp
                    <x-anime-card :anime="$animeData" />
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-24 text-center space-y-6">
                <div
                    class="w-24 h-24 bg-[#1a1a20] rounded-full flex items-center justify-center border border-white/5 shadow-2xl">
                    <svg class="w-10 h-10 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl font-bold text-white">Your list is empty</h3>
                    <p class="text-zinc-500 max-w-xs mx-auto">Start building your collection by adding anime from the browse
                        page.</p>
                </div>
                <a href="{{ route('anime.index') }}"
                    class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-full transition-colors shadow-lg shadow-indigo-600/20">
                    Discover Anime
                </a>
            </div>
        @endif

    </main>
</x-layout>