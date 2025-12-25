<x-layout title="Watch: {{ $anime['title'] ?? 'Episode' }}">
    
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Video Player -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-[#352c6a] rounded-lg overflow-hidden">
                    <div class="aspect-video bg-black">
                        <video id="videoPlayer" class="video-js vjs-default-skin vjs-big-play-centered w-full h-full" controls preload="auto">
                            <p class="vjs-no-js">
                                To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
                            </p>
                        </video>
                    </div>
                    <div class="p-4">
                        <h1 class="text-xl font-bold">{{ $anime['title'] ?? 'Unknown' }}</h1>
                        <p class="text-[#c7c4f3]">Episode {{ $currentEpisode['number'] ?? '—' }}: {{ $currentEpisode['title'] ?? '' }}</p>
                    </div>
                </div>

                <!-- Quality Selector -->
                <div class="bg-[#352c6a] rounded-lg p-4">
                    <h3 class="font-semibold mb-3 text-[#f2f1ff]">Quality</h3>
                    @if(count($streams) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($streams as $stream)
                                <button 
                                    class="quality-btn px-4 py-2 rounded-lg transition-colors {{ $loop->first ? 'bg-[#8b7cf6] text-white' : 'bg-[#4a3f7a] text-[#c7c4f3] hover:bg-[#6d5bd0]' }}"
                                    data-resolution="{{ $stream['resolution'] }}"
                                    data-proxy-url="{{ route('stream.proxy', [$animeId, $episodeNumber]) }}?resolution={{ $stream['resolution'] }}&language={{ $language }}"
                                    data-direct-url="{{ $stream['url'] }}"
                                    data-has-referer="{{ !empty($stream['referer']) ? 'true' : 'false' }}"
                                >
                                    {{ $stream['resolution'] }}p
                                </button>
                            @endforeach
                        </div>
                    @else
                        <p class="text-red-500">⚠️ No streams available for this episode</p>
                    @endif
                </div>
            </div>

            <!-- Playlist -->
            <aside class="bg-[#352c6a] rounded-lg flex flex-col">
    <div class="p-4 border-b border-[#4a3f7a]">
        <h2 class="text-lg font-bold">Playlist</h2>
    </div>

<div class="max-h-[80vh] overflow-y-auto
            [&::-webkit-scrollbar]:hidden
            [-ms-overflow-style:none]
            [scrollbar-width:none]">    
            @foreach($episodes as $ep)
        <a href="{{ route('watch.show', [$animeId, $ep['number']]) }}?language={{ $language }}"
           class="flex items-center gap-3 px-4 py-3 hover:bg-[#4a3f7a]
           {{ (isset($currentEpisode['number']) && $currentEpisode['number'] === $ep['number']) ? 'bg-[#4a3f7a]' : '' }}">
            
            <div class="w-16 h-10 bg-gradient-to-br from-[#4a3f7a] to-[#352c6a] rounded flex items-center justify-center">
                <i class="fas fa-film text-[#6d5bd0]"></i>
            </div>

            <div class="flex-1">
                <p class="text-sm text-[#f2f1ff]">
                    Ep {{ $ep['number'] }}: {{ $ep['title'] }}
                </p>
                <p class="text-xs text-[#c7c4f3]">{{ $ep['duration'] }}</p>
            </div>
        </a>
    @endforeach
</div>

</aside>

        </div>
    </main>

    

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const ctxEl = document.getElementById('watch-context-json');
            const streamsEl = document.getElementById('watch-streams-json');
            const ctx = ctxEl ? JSON.parse(ctxEl.textContent || '{}') : {};
            const streams = streamsEl ? JSON.parse(streamsEl.textContent || '[]') : [];

            const lsKeys = {
                progress: `watch:progress:${ctx.animeId}:${ctx.episodeNumber}:${ctx.language}`,
                quality: `watch:quality:${ctx.animeId}:${ctx.language}`,
            };

            function safeJsonParse(value) {
                try {
                    return JSON.parse(value);
                } catch {
                    return null;
                }
            }

            function nowIso() {
                return new Date().toISOString();
            }

            function pickStream(streamButtons, preferredResolution) {
                if (!streamButtons.length) return null;
                if (!preferredResolution) return streamButtons[0];

                const exact = streamButtons.find(b => parseInt(b.dataset.resolution) === preferredResolution);
                if (exact) return exact;

                // Prefer next-lower resolution if exact not available
                const sorted = [...streamButtons].sort((a, b) => parseInt(b.dataset.resolution) - parseInt(a.dataset.resolution));
                const lowerOrEqual = sorted.find(b => parseInt(b.dataset.resolution) <= preferredResolution);
                return lowerOrEqual || sorted[0];
            }

            function setActiveQualityButton(activeBtn) {
                const qualityBtns = document.querySelectorAll('.quality-btn');
                qualityBtns.forEach(b => {
                    b.classList.remove('bg-[#8b7cf6]', 'text-white');
                    b.classList.add('bg-[#4a3f7a]', 'text-[#c7c4f3]');
                });
                if (activeBtn) {
                    activeBtn.classList.remove('bg-[#4a3f7a]', 'text-[#c7c4f3]');
                    activeBtn.classList.add('bg-[#8b7cf6]', 'text-white');
                }
            }

            function urlForButton(btn) {
                const proxyUrl = btn?.dataset.proxyUrl || '';
                const directUrl = btn?.dataset.directUrl || '';
                // Prefer same-origin proxy for consistent seek/buffering.
                return proxyUrl || directUrl;
            }

            function readLocalProgress() {
                const raw = localStorage.getItem(lsKeys.progress);
                const parsed = safeJsonParse(raw);
                if (!parsed || typeof parsed !== 'object') return null;

                const position = Number(parsed.position);
                if (!Number.isFinite(position) || position < 0) return null;

                return {
                    position,
                    duration: Number.isFinite(Number(parsed.duration)) ? Number(parsed.duration) : null,
                    resolution: Number.isFinite(Number(parsed.resolution)) ? Number(parsed.resolution) : null,
                    updated_at: typeof parsed.updated_at === 'string' ? parsed.updated_at : null,
                };
            }

            function writeLocalProgress(progress) {
                localStorage.setItem(lsKeys.progress, JSON.stringify({
                    position: progress.position,
                    duration: progress.duration ?? null,
                    resolution: progress.resolution ?? null,
                    updated_at: progress.updated_at ?? nowIso(),
                }));
            }

            function readLocalQuality() {
                const raw = localStorage.getItem(lsKeys.quality);
                const res = Number(raw);
                return Number.isFinite(res) ? res : null;
            }

            function writeLocalQuality(resolution) {
                if (!Number.isFinite(resolution)) return;
                localStorage.setItem(lsKeys.quality, String(resolution));
            }

            let lastSavedSecond = -1;
            let saveTimer = null;
            async function saveProgressToServer(payload) {
                try {
                    await fetch(ctx.progressUpdateUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                        },
                        body: JSON.stringify({
                            position: payload.position,
                            duration: payload.duration ?? null,
                            resolution: payload.resolution ?? null,
                            language: ctx.language,
                        }),
                        keepalive: true,
                    });
                } catch {
                    // ignore network errors; localStorage still works
                }
            }

            function scheduleSave(progress) {
                // debounce writes (like YouTube): write at most every ~3s
                if (saveTimer) return;
                saveTimer = setTimeout(async () => {
                    saveTimer = null;
                    await saveProgressToServer(progress);
                }, 3000);
            }

            async function fetchServerProgress() {
                try {
                    const resp = await fetch(ctx.progressShowUrl, {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin',
                    });
                    if (!resp.ok) return null;
                    const data = await resp.json();
                    return {
                        position: Number(data.position) || 0,
                        duration: data.duration != null ? Number(data.duration) : null,
                        resolution: data.resolution != null ? Number(data.resolution) : null,
                        updated_at: typeof data.updated_at === 'string' ? data.updated_at : null,
                    };
                } catch {
                    return null;
                }
            }

            function newerProgress(a, b) {
                const at = a?.updated_at ? Date.parse(a.updated_at) : 0;
                const bt = b?.updated_at ? Date.parse(b.updated_at) : 0;
                return at >= bt ? a : b;
            }
            
            // Initialize Video.js player
            const player = videojs('videoPlayer', {
                controls: true,
                autoplay: false,
                preload: 'auto',
                fluid: true,
                responsive: true,
            });

            // Get quality buttons and streams
            const qualityBtns = document.querySelectorAll('.quality-btn');
            let currentResolution = streams[0]?.resolution || 1080;

            // Load initial stream (remembered quality if available)
            (async function init() {
                const serverProgress = await fetchServerProgress();
                const localProgress = readLocalProgress();
                const bestProgress = newerProgress(localProgress, serverProgress) || localProgress || serverProgress;

                // Determine preferred resolution priority: query/server/local quality
                const localQuality = readLocalQuality();
                const preferredResolution = Number.isFinite(localQuality)
                    ? localQuality
                    : (Number.isFinite(bestProgress?.resolution) ? bestProgress.resolution : null);

                const btns = Array.from(qualityBtns);
                const chosenBtn = pickStream(btns, preferredResolution);

                if (chosenBtn) {
                    const chosenResolution = parseInt(chosenBtn.dataset.resolution);
                    currentResolution = Number.isFinite(chosenResolution) ? chosenResolution : currentResolution;
                    setActiveQualityButton(chosenBtn);
                    writeLocalQuality(currentResolution);

                    player.src({
                        type: 'video/mp4',
                        src: urlForButton(chosenBtn),
                    });

                    // Resume from last saved position
                    const resumeAt = Number(bestProgress?.position) || 0;
                    if (resumeAt > 0) {
                        player.one('loadedmetadata', function() {
                            const duration = player.duration();
                            const safeResumeAt = (Number.isFinite(duration) && duration > 0)
                                ? Math.min(resumeAt, Math.max(0, duration - 5))
                                : resumeAt;
                            player.currentTime(safeResumeAt);
                        });
                    }
                }
            })();
            
            // Add error event listener
            player.on('error', function(e) {
                const error = player.error();
                console.error('Video player error:', error);
            });

            // Quality switching
            qualityBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const resolution = parseInt(this.dataset.resolution);
                    const url = urlForButton(this);
                    
                    const currentTime = player.currentTime();
                    const wasPaused = player.paused();

                    // Update active button
                    setActiveQualityButton(this);

                    // Change source
                    player.src({
                        type: 'video/mp4',
                        src: url
                    });

                    // Resume from same position
                    player.one('loadedmetadata', function() {
                        player.currentTime(currentTime);
                        if (!wasPaused) {
                            player.play();
                        }
                    });

                    currentResolution = resolution;

                    // Persist preferred quality
                    writeLocalQuality(currentResolution);
                    writeLocalProgress({
                        position: Number.isFinite(currentTime) ? currentTime : 0,
                        duration: Number.isFinite(player.duration()) ? player.duration() : null,
                        resolution: currentResolution,
                        updated_at: nowIso(),
                    });
                    scheduleSave({
                        position: Number.isFinite(currentTime) ? currentTime : 0,
                        duration: Number.isFinite(player.duration()) ? player.duration() : null,
                        resolution: currentResolution,
                    });
                });
            });

            // Progress saving (local + server cache) like YouTube
            function flushProgress() {
                const position = player.currentTime();
                const duration = player.duration();
                if (!Number.isFinite(position) || position < 0) return;

                const payload = {
                    position,
                    duration: Number.isFinite(duration) ? duration : null,
                    resolution: Number.isFinite(currentResolution) ? currentResolution : null,
                    updated_at: nowIso(),
                };

                writeLocalProgress(payload);
                scheduleSave(payload);
            }

            player.on('timeupdate', function() {
                const position = player.currentTime();
                if (!Number.isFinite(position)) return;

                const second = Math.floor(position);
                if (second === lastSavedSecond) return;

                // Save roughly every 5 seconds
                if (second % 5 === 0) {
                    lastSavedSecond = second;
                    flushProgress();
                }
            });

            player.on('pause', flushProgress);
            player.on('ended', flushProgress);
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    flushProgress();
                }
            });
            window.addEventListener('pagehide', flushProgress);

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                switch(e.key.toLowerCase()) {
                    case ' ':
                    case 'k':
                        e.preventDefault();
                        if (player.paused()) {
                            player.play();
                        } else {
                            player.pause();
                        }
                        break;
                    case 'f':
                        e.preventDefault();
                        if (player.isFullscreen()) {
                            player.exitFullscreen();
                        } else {
                            player.requestFullscreen();
                        }
                        break;
                    case 'm':
                        e.preventDefault();
                        player.muted(!player.muted());
                        break;
                    case 'arrowleft':
                        e.preventDefault();
                        player.currentTime(player.currentTime() - 5);
                        break;
                    case 'arrowright':
                        e.preventDefault();
                        player.currentTime(player.currentTime() + 5);
                        break;
                    case 'arrowup':
                        e.preventDefault();
                        player.volume(Math.min(1, player.volume() + 0.1));
                        break;
                    case 'arrowdown':
                        e.preventDefault();
                        player.volume(Math.max(0, player.volume() - 0.1));
                        break;
                }
            });
        });
    </script>
    @endpush
</x-layout>