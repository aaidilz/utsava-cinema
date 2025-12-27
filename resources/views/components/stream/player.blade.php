@props(['streams', 'anime', 'currentEpisode', 'animeId', 'episodeNumber', 'language'])

<div class="relative group">
    <div class="aspect-video bg-black relative rounded-xl overflow-hidden shadow-2xl ring-1 ring-white/10">
        <video id="videoPlayer" class="video-js vjs-default-skin vjs-big-play-centered w-full h-full" controls
            preload="auto" poster="{{ $anime['poster_path'] ?? '' }}">
            <p class="vjs-no-js">To view this video please enable JavaScript.</p>
        </video>

        <div class="absolute top-4 right-4 z-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div
                class="bg-black/80 backdrop-blur-md rounded-xl p-2 flex flex-col gap-1.5 ring-1 ring-white/20 shadow-2xl">
                <div class="flex items-center gap-2 px-2 pb-1 border-b border-white/10">
                    <i class="fas fa-sliders-h text-[10px] text-indigo-400"></i>
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Quality</span>
                </div>
                <div class="flex flex-col gap-1" id="quality-selector-container">
                    @forelse($streams as $stream)
                                    <button
                                        class="quality-btn px-3 py-2 rounded-lg text-xs font-bold transition-all text-left flex items-center justify-between gap-6 min-w-[110px] group/btn"
                                        data-resolution="{{ $stream['resolution'] }}" data-url="{{ !empty($stream['referer'])
                        ? route('stream.proxy', [$animeId, $episodeNumber]) . '?resolution=' . $stream['resolution'] . '&language=' . $language
                        : $stream['url'] }}">
                                        <span
                                            class="group-hover/btn:translate-x-1 transition-transform">{{ $stream['resolution'] }}p</span>
                                        <div
                                            class="w-2 h-2 rounded-full bg-indigo-500 opacity-0 active-indicator shadow-[0_0_8px_rgba(99,102,241,0.6)]">
                                        </div>
                                    </button>
                    @empty
                        <div class="px-3 py-2 text-[10px] text-zinc-500 italic">No sources found</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>