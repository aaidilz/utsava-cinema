@props(['streams', 'anime', 'currentEpisode', 'animeId', 'episodeNumber', 'language'])

<div class="relative group">
    <div class="aspect-video bg-black relative rounded-lg overflow-hidden">
        <video id="videoPlayer" class="video-js vjs-default-skin vjs-big-play-centered w-full h-full" controls
            preload="auto" poster="{{ $anime['image'] ?? '' }}">
            <p class="vjs-no-js">
                To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5
                video
            </p>
        </video>

        <!-- Quality Overlay (Visible on Hover / Pause) -->
        <div class="absolute top-4 right-4 z-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="bg-black/60 backdrop-blur-md rounded-lg p-1.5 flex flex-col gap-1 ring-1 ring-white/10">
                <span
                    class="text-[10px] text-zinc-400 font-bold px-2 uppercase tracking-wider text-center">Quality</span>
                <div class="flex flex-col gap-1" id="quality-selector-container">
                    @if(count($streams) > 0)
                        @foreach($streams as $stream)
                            <button
                                class="quality-btn px-3 py-1.5 rounded text-xs font-semibold bg-white/10 hover:bg-indigo-600 text-zinc-300 hover:text-white transition-all text-left flex items-center justify-between gap-3 min-w-[80px]"
                                data-resolution="{{ $stream['resolution'] }}"
                                data-proxy-url="{{ route('stream.proxy', [$animeId, $episodeNumber]) }}?resolution={{ $stream['resolution'] }}&language={{ $language }}"
                                data-direct-url="{{ $stream['url'] }}"
                                data-has-referer="{{ !empty($stream['referer']) ? 'true' : 'false' }}">
                                <span>{{ $stream['resolution'] }}p</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-0 active-indicator"></span>
                            </button>
                        @endforeach
                    @else
                        <div class="px-2 py-1 text-xs text-red-400">No Streams</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @php
        $watchContext = [
            'animeId' => $animeId,
            'episodeNumber' => (string) $episodeNumber,
            'language' => $language,
            'progressShowUrl' => route('watch.progress.show', [$animeId, $episodeNumber]) . '?language=' . $language,
            'progressUpdateUrl' => route('watch.progress.update', [$animeId, $episodeNumber]),
        ];
        $watchJsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
    @endphp
    <script type="application/json" id="watch-context-json">{!! json_encode($watchContext, $watchJsonFlags) !!}</script>
    <script type="application/json" id="watch-streams-json">{!! json_encode($streams, $watchJsonFlags) !!}</script>
@endpush