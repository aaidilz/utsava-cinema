@props(['streams', 'anime', 'currentEpisode', 'animeId', 'episodeNumber', 'language'])

@php
    $streamsData = collect($streams)
        ->unique('resolution')
        ->map(function ($stream) use ($animeId, $episodeNumber, $language) {
            return [
                'resolution' => $stream['resolution'],
                'url' => !empty($stream['referer'])
                    ? route('stream.proxy', [$animeId, $episodeNumber]) . '?resolution=' . $stream['resolution'] . '&language=' . $language
                    : $stream['url'],
                'label' => $stream['resolution'] . 'p'
            ];
        })
        ->values()
        ->toArray();
@endphp

<div class="relative">
    <div class="aspect-video bg-black relative rounded-xl overflow-hidden shadow-2xl ring-1 ring-white/10">
        <video id="videoPlayer" class="video-js vjs-default-skin vjs-big-play-centered w-full h-full" controls
            preload="auto" poster="{{ $anime['image'] ?? '' }}">
            <p class="vjs-no-js">To view this video please enable JavaScript.</p>
        </video>
    </div>

    {{-- Pass streams data to JavaScript --}}
    <script id="streams-data" type="application/json">
        @json($streamsData)
    </script>
</div>