<x-layout title="{{ $anime['name'] ?? ($anime['title'] ?? 'Anime Detail') }}">

    <!-- Hero Background -->
    <div class="fixed top-0 left-0 w-full h-[80vh] overflow-hidden -z-10">
        <div class="absolute inset-0 bg-cover bg-center blur-3xl opacity-40 scale-110"
            style="background-image: url('{{ $anime['image'] ?? '' }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#0d0d0f]/20 via-[#0d0d0f]/80 to-[#0d0d0f]"></div>
    </div>

    <main class="min-h-screen pt-24 pb-20 px-4 md:px-8 container mx-auto max-w-7xl">

        <!-- CONTENT WRAPPER -->
        <div class="flex flex-col md:flex-row gap-8 lg:gap-12 items-start">

            <!-- LEFT: POSTER & ACTIONS -->
            <div class="w-full md:w-[300px] lg:w-[350px] shrink-0 space-y-6">
                <div
                    class="w-full aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10 relative group">
                    @if(!empty($anime['image']))
                        <img src="{{ $anime['image'] }}" alt="{{ $anime['title'] ?? 'Poster' }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-zinc-800 flex items-center justify-center text-zinc-600">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <!-- Hover Play Overlay -->
                    <a href="#episodes"
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm cursor-pointer">
                        <div
                            class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg transform scale-90 group-hover:scale-100 transition-transform">
                            <svg class="w-8 h-8 text-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                    </a>
                </div>

                <div class="space-y-3">
                    @php
                        $firstEp = $episodes[0]['number'] ?? 1;
                        $id = $anime['id'] ?? $anime['identifier'] ?? '';
                    @endphp
                    @if(count($episodes) > 0)
                        <a href="{{ route('watch.show', [$id, $firstEp]) }}"
                            class="block w-full py-4 rounded-xl bg-white text-black font-black italic text-center text-lg uppercase tracking-wide hover:bg-zinc-200 transition-all shadow-lg shadow-white/10 active:scale-95">
                            Start Watching
                        </a>
                    @else
                        <button disabled
                            class="block w-full py-4 rounded-xl bg-zinc-700 text-zinc-400 font-bold uppercase tracking-wide cursor-not-allowed">
                            Coming Soon
                        </button>
                    @endif

                    @if(auth()->check())
                        <button
                            class="add-to-watchlist w-full py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold uppercase tracking-wide hover:bg-white/10 transition-all flex items-center justify-center gap-2 group"
                            data-id="{{ $id }}" data-title="{{ $anime['title'] ?? '' }}"
                            data-poster="{{ $anime['image'] ?? '' }}">
                            <svg class="w-5 h-5 transition-colors {{ $isInWatchlist ? 'text-red-500 fill-current' : 'group-hover:text-red-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="btn-text">{{ $isInWatchlist ? 'Remove from List' : 'Add to List' }}</span>
                        </button>
                    @endif
                </div>

                <!-- Info Grid -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-6 border border-white/5 space-y-4">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Released</span>
                        <p class="font-medium text-zinc-300">
                            {{ $anime['release_year'] ?? ($anime['year'] ?? 'Unknown') }}
                        </p>
                    </div>

                    @if(!empty($anime['rating_classification']))
                        <div>
                            <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Age Rating</span>
                            <p class="font-medium text-zinc-300">{{ $anime['rating_classification'] }}</p>
                        </div>
                    @endif

                    @if(!empty($anime['genres']))
                        <div>
                            <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Genres</span>
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                @foreach($anime['genres'] as $genre)
                                    <a href="{{ route('anime.index', ['genre' => $genre]) }}"
                                        class="text-xs text-zinc-400 hover:text-indigo-400 transition-colors">{{ $genre }}</a>
                                    @if(!$loop->last) <span class="text-zinc-600">•</span> @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Status</span>
                        <p class="font-medium text-zinc-300">{{ $anime['status'] ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Total
                            Episodes</span>
                        <p class="font-medium text-zinc-300">
                            {{ $anime['total_episode'] ?? ($anime['episodes'] ?? '?') }} eps
                        </p>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Studios</span>
                        <div class="flex flex-wrap gap-2 mt-1">
                            {{-- Placeholder as Studios might not be in generic API response --}}
                            <span class="text-xs text-zinc-400">Official Release</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: DETAILS & EPISODES -->
            <div class="flex-1 space-y-10">

                <!-- Title & Header -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <span
                            class="px-3 py-1 bg-white/10 backdrop-blur text-white text-[10px] font-bold uppercase tracking-widest rounded-full border border-white/10">{{ $anime['type'] ?? 'TV Series' }}</span>
                        @if(($anime['rating_score'] ?? 0) >= 8)
                            <span
                                class="px-3 py-1 bg-green-500/20 text-green-400 text-[10px] font-bold uppercase tracking-widest rounded-full border border-green-500/20">Highly
                                Rated</span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black italic text-white leading-tight">
                        {{ $anime['name'] ?? ($anime['title'] ?? 'Unknown Title') }}
                    </h1>

                    <!-- Rating & Genres -->
                    <div class="flex items-center flex-wrap gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.447a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.447a1 1 0 00-1.176 0l-3.37 2.447c-.784.57-1.839-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" />
                            </svg>
                            <span
                                class="font-bold text-white text-lg">{{ $anime['rating_score'] ?? ($anime['rating'] ?? 'N/A') }}</span>
                            <span class="text-zinc-500">(MAL Score)</span>
                        </div>
                        <div class="w-1 h-1 rounded-full bg-zinc-600"></div>
                        <div class="flex gap-2">
                            @foreach($anime['genres'] ?? [] as $genre)
                                <a href="{{ route('anime.index', ['genre' => $genre]) }}"
                                    class="text-zinc-300 hover:text-indigo-400 transition-colors cursor-pointer">{{ $genre }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Synopsis -->
                    <p class="text-zinc-300 leading-relaxed text-lg max-w-4xl">
                        {{ $anime['synopsis'] ?? 'No synopsis available for this anime.' }}
                    </p>
                </div>

                <!-- EPISODES SECTION -->
                <div id="episodes" class="space-y-6 pt-8 border-t border-white/5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-black italic text-white uppercase tracking-tight">Episodes</h2>

                        <!-- Simple Filter (Optional) -->
                        <div class="flex bg-[#1a1a20] rounded-lg p-1">
                            <button
                                class="px-3 py-1 bg-white/10 text-white text-xs font-bold rounded-md">1-{{ count($episodes) > 100 ? 100 : count($episodes) }}</button>
                            @if(count($episodes) > 100)
                                <button
                                    class="px-3 py-1 text-zinc-500 hover:text-white text-xs font-bold rounded-md transition-colors">101-200</button>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($episodes as $ep)
                            <a href="{{ route('watch.show', [$id, $ep['number']]) }}"
                                class="group bg-[#1a1a20] rounded-xl overflow-hidden hover:ring-2 ring-indigo-500/50 transition-all">
                                <div class="aspect-video relative bg-zinc-800">
                                    {{-- If thumbnail exists, otherwise cover --}}
                                    @php
                                        $thumb = $ep['thumbnail'] ?? null;
                                        // Use main image as fallback if no episode thumb, but maybe darkened
                                        $fallback = $anime['image'] ?? '';
                                    @endphp
                                    <img src="{{ $thumb ? $thumb : $fallback }}"
                                        class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition-colors">
                                    </div>

                                    <!-- Play Icon -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div
                                            class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div
                                        class="absolute bottom-2 right-2 bg-black/80 px-2 py-0.5 rounded text-[10px] font-bold text-white">
                                        {{ $ep['duration'] ?? '24m' }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span
                                                class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Episode
                                                {{ $ep['number'] }}</span>
                                            <h4
                                                class="text-sm font-bold text-white line-clamp-1 mt-0.5 group-hover:text-indigo-400 transition-colors">
                                                {{ $ep['title'] ?? 'Episode ' . $ep['number'] }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-10 text-center text-zinc-500">
                                No episodes available yet.
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>
        </div>

    </main>

    @push('scripts')

    @endpush
</x-layout>