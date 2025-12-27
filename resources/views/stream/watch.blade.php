<x-stream-layout title="Watch: {{ $anime['title'] ?? 'Episode' }}">
    <x-slot:player>
        <x-stream.player :streams="$streams" :anime="$anime" :currentEpisode="$currentEpisode" :animeId="$animeId"
            :episodeNumber="$episodeNumber" :language="$language" />
    </x-slot:player>

    <x-slot:details>
        <x-stream.video-details :anime="$anime" :currentEpisode="$currentEpisode" />
    </x-slot:details>

    <x-slot:playlist>
        <x-stream.episode-list :episodes="$episodes" :currentEpisode="$currentEpisode" :animeId="$animeId"
            :language="$language" />
    </x-slot:playlist>

    <x-slot:related>
        <x-stream.related :related="$related" />
    </x-slot:related>

    @push('scripts')
        <script>
            // Store global state
            window.watchState = {
                animeId: '{{ $animeId }}',
                episodeNumber: '{{ $episodeNumber }}',
                language: '{{ $language }}',
                animeTitle: @json($anime['title'] ?? ''),
                animePoster: @json($anime['poster_path'] ?? ''),
                csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            };

            // --- Utils ---
            function safeJsonParse(value) {
                try { return JSON.parse(value); } catch { return null; }
            }
            function nowIso() {
                return new Date().toISOString();
            }

            // --- Progress Logic ---
            const lsKeys = {
                progress: () => `watch:progress:${window.watchState.animeId}:${window.watchState.episodeNumber}:${window.watchState.language}`,
                quality: () => `watch:quality:${window.watchState.animeId}:${window.watchState.language}`,
            };

            // Throttled server sync
            let lastServerSync = 0;
            const SYNC_INTERVAL = 10000;
            let saveTimer = null;

            async function saveProgressToServer(payload) {
                const url = `/watch-progress/${window.watchState.animeId}/${window.watchState.episodeNumber}`;
                try {
                    await fetch(url, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            ...(window.watchState.csrfToken ? { 'X-CSRF-TOKEN': window.watchState.csrfToken } : {}),
                        },
                        body: JSON.stringify({
                            position: payload.position,
                            duration: payload.duration ?? null,
                            resolution: payload.resolution ?? null,
                            language: window.watchState.language,
                            anime_title: window.watchState.animeTitle,
                            anime_poster: window.watchState.animePoster,
                        }),
                        keepalive: true,
                    });
                } catch { }
            }

            async function fetchServerProgress() {
                const url = `/watch-progress/${window.watchState.animeId}/${window.watchState.episodeNumber}`;
                try {
                    const resp = await fetch(url, {
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
                } catch { return null; }
            }

            // --- Player Logic ---
            let player = null;
            let lastId = 0;

            function flushProgress(force = false) {
                if (!player || player.disposed()) return;
                const pos = player.currentTime();
                if (!Number.isFinite(pos)) return;

                const payload = {
                    position: pos,
                    duration: player.duration(),
                    resolution: parseInt(localStorage.getItem(lsKeys.quality())) || null,
                    updated_at: nowIso(),
                };

                // Always save to LS immediately
                localStorage.setItem(lsKeys.progress(), JSON.stringify(payload));

                // Determine if we should sync to server
                const now = Date.now();
                if (force || (now - lastServerSync > SYNC_INTERVAL)) {
                    if (saveTimer) clearTimeout(saveTimer);

                    if (force && navigator.sendBeacon) {
                        const url = `/watch-progress/${window.watchState.animeId}/${window.watchState.episodeNumber}`;
                        const data = new Blob([JSON.stringify({
                            position: payload.position,
                            duration: payload.duration ?? null,
                            resolution: payload.resolution ?? null,
                            language: window.watchState.language,
                            _token: window.watchState.csrfToken
                        })], { type: 'application/json' });
                        navigator.sendBeacon(url, data);
                    } else {
                        saveTimer = setTimeout(() => {
                            saveProgressToServer(payload).then(() => {
                                lastServerSync = Date.now();
                            });
                        }, 500);
                    }
                }
            }

            function setQuality(btn, keepPosition) {
                const url = btn.dataset.proxyUrl || btn.dataset.directUrl;
                const res = parseInt(btn.dataset.resolution);

                document.querySelectorAll('.quality-btn .active-indicator').forEach(el => el.classList.add('opacity-0'));
                const indicator = btn.querySelector('.active-indicator');
                if (indicator) indicator.classList.remove('opacity-0');

                if (!player) return;

                const wasPaused = player.paused();
                const currentTime = player.currentTime();

                player.src({ type: 'video/mp4', src: url });

                if (keepPosition) {
                    player.one('loadedmetadata', () => {
                        player.currentTime(currentTime);
                        if (!wasPaused) player.play();
                    });
                }
                localStorage.setItem(lsKeys.quality(), res);
            }

            async function initPlayer() {
                if (player) {
                    player.dispose();
                    player = null;
                }

                const videoEl = document.getElementById('videoPlayer');
                if (!videoEl) return;

                player = videojs(videoEl, {
                    controls: true,
                    autoplay: false,
                    preload: 'auto',
                    fluid: true,
                    responsive: true,
                });

                // 1. Resume Progress
                const localProgress = safeJsonParse(localStorage.getItem(lsKeys.progress()));
                const serverProgress = await fetchServerProgress();

                const lpTime = localProgress?.updated_at ? Date.parse(localProgress.updated_at) : 0;
                const spTime = serverProgress?.updated_at ? Date.parse(serverProgress.updated_at) : 0;
                const best = (spTime >= lpTime) ? serverProgress : localProgress;

                // 2. Initial Resolution
                const savedQuality = Number(localStorage.getItem(lsKeys.quality()));
                const qualityBtns = document.querySelectorAll('.quality-btn');

                let chosenBtn = null;
                if (qualityBtns.length > 0) {
                    if (Number.isFinite(savedQuality)) {
                        chosenBtn = Array.from(qualityBtns).find(b => parseInt(b.dataset.resolution) === savedQuality);
                    }
                    if (!chosenBtn) chosenBtn = qualityBtns[0];
                }

                if (chosenBtn) {
                    setQuality(chosenBtn, false);

                    const resumeAt = Number(best?.position) || 0;
                    // Always trigger initial sync once metadata is loaded to register "Continue Watching"
                    player.one('loadedmetadata', () => {
                        const duration = player.duration();
                        const safe = (Number.isFinite(duration) && duration > 0 && resumeAt > 0)
                            ? Math.min(resumeAt, Math.max(0, duration - 5))
                            : resumeAt;
                        player.currentTime(safe);

                        // Force initial sync to DB
                        flushProgress(true);
                    });
                }

                // 3. Events
                qualityBtns.forEach(btn => {
                    // Remove old listeners to avoid duplicates if re-init? 
                    // Actually initPlayer might handle fresh DOM if we replace innerHTML. 
                    // But here we are just swapping source. Quality buttons persist?
                    // If switching episode, we replace HTML, so fresh buttons.
                    btn.onclick = (e) => {
                        e.preventDefault();
                        setQuality(btn, true);
                    };
                });

                player.on('timeupdate', () => {
                    const pos = player.currentTime();
                    if (!Number.isFinite(pos)) return;
                    const sec = Math.floor(pos);
                    if (sec !== lastId && sec % 5 === 0) {
                        lastId = sec;
                        flushProgress(false);
                    }
                });

                player.on('pause', () => flushProgress(true));
                player.on('ended', () => flushProgress(true));
            }

            // Global Listeners
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'hidden') flushProgress(true);
            });
            window.addEventListener('pagehide', () => flushProgress(true));

            // AJAX Switching
            window.handleEpisodeClick = async function (e, element) {
                e.preventDefault();
                const url = element.href;
                const epNum = element.dataset.episodeNumber;

                element.classList.add('opacity-50');

                try {
                    const resp = await fetch(url + (url.includes('?') ? '&' : '?') + 'json=1', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!resp.ok) throw new Error('Failed to load');
                    const data = await resp.json();

                    window.watchState.episodeNumber = epNum;
                    window.history.pushState({}, '', url);

                    document.getElementById('player-container').innerHTML = data.html_player;
                    document.getElementById('video-details').innerHTML = data.html_details;

                    if (element.parentElement) {
                        element.parentElement.innerHTML = data.html_playlist;
                    }

                    initPlayer();

                    setTimeout(() => {
                        if (player) player.play();
                    }, 500);

                } catch (err) {
                    console.error(err);
                    window.location.href = url;
                } finally {
                    element.classList.remove('opacity-50');
                }
                return false;
            };

            document.addEventListener('DOMContentLoaded', initPlayer);
        </script>
    @endpush
</x-stream-layout>