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
            let saveTimer = null;
            let lastId = 0; // debounce checks

            async function initPlayer() {
                if (player) {
                    player.dispose();
                    player = null;
                }

                const videoEl = document.getElementById('videoPlayer');
                if (!videoEl) return;

                // Initialize Video.js
                player = videojs(videoEl, {
                    controls: true,
                    autoplay: false, // Don't auto play on load/reload to be polite, or maybe true if switching ep?
                    preload: 'auto',
                    fluid: true,
                    responsive: true,
                });

                // 1. Resume Progress
                const localProgress = safeJsonParse(localStorage.getItem(lsKeys.progress()));
                const serverProgress = await fetchServerProgress();

                // Simple logic: prefer server if newer
                const lpTime = localProgress?.updated_at ? Date.parse(localProgress.updated_at) : 0;
                const spTime = serverProgress?.updated_at ? Date.parse(serverProgress.updated_at) : 0;
                const best = (spTime >= lpTime) ? serverProgress : localProgress;

                // 2. Initial Resolution
                // Try usage preferred resolution from LS
                const savedQuality = Number(localStorage.getItem(lsKeys.quality()));
                const qualityBtns = document.querySelectorAll('.quality-btn');

                let chosenBtn = null;
                if (qualityBtns.length > 0) {
                    if (Number.isFinite(savedQuality)) {
                        chosenBtn = Array.from(qualityBtns).find(b => parseInt(b.dataset.resolution) === savedQuality);
                    }
                    if (!chosenBtn) chosenBtn = qualityBtns[0]; // Default to first (highest usually)
                }

                if (chosenBtn) {
                    setQuality(chosenBtn, false); // false = don't seek/play yet

                    // Seek after load
                    const resumeAt = Number(best?.position) || 0;
                    if (resumeAt > 0) {
                        player.one('loadedmetadata', () => {
                            const duration = player.duration();
                            const safe = (Number.isFinite(duration) && duration > 0)
                                ? Math.min(resumeAt, Math.max(0, duration - 5))
                                : resumeAt;
                            player.currentTime(safe);
                        });
                    }
                }

                // 3. Events
                // Quality Click
                qualityBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        setQuality(btn, true);
                    });
                });

                // Progress Saving
                player.on('timeupdate', () => {
                    const pos = player.currentTime();
                    if (!Number.isFinite(pos)) return;
                    const sec = Math.floor(pos);
                    if (sec !== lastId && sec % 5 === 0) {
                        lastId = sec;
                        flushProgress();
                    }
                });
                player.on('pause', flushProgress);
                player.on('ended', flushProgress);
                window.addEventListener('beforeunload', flushProgress);
            }

            function setQuality(btn, keepPosition) {
                const url = btn.dataset.proxyUrl || btn.dataset.directUrl;
                const res = parseInt(btn.dataset.resolution);

                // Update UI
                document.querySelectorAll('.quality-btn .active-indicator').forEach(el => el.classList.add('opacity-0'));
                // document.querySelectorAll('.quality-btn').forEach(b => b.classList.remove('bg-indigo-600', 'text-white'));

                const indicator = btn.querySelector('.active-indicator');
                if (indicator) indicator.classList.remove('opacity-0');
                // btn.classList.add('bg-indigo-600', 'text-white');

                const wasPaused = player.paused();
                const currentTime = player.currentTime();

                player.src({ type: 'video/mp4', src: url });

                if (keepPosition) {
                    player.one('loadedmetadata', () => {
                        player.currentTime(currentTime);
                        if (!wasPaused) player.play();
                    });
                }

                // Save preference
                localStorage.setItem(lsKeys.quality(), res);
            }

            function flushProgress() {
                if (!player || player.disposed()) return;
                const pos = player.currentTime();
                if (!Number.isFinite(pos)) return;

                const payload = {
                    position: pos,
                    duration: player.duration(),
                    resolution: parseInt(localStorage.getItem(lsKeys.quality())) || null,
                    updated_at: nowIso(),
                };

                localStorage.setItem(lsKeys.progress(), JSON.stringify(payload));

                if (saveTimer) clearTimeout(saveTimer);
                saveTimer = setTimeout(() => {
                    saveProgressToServer(payload);
                }, 1000);
            }

            // --- AJAX Switching ---
            window.handleEpisodeClick = async function (e, element) {
                e.preventDefault();
                const url = element.href;
                const epNum = element.dataset.episodeNumber;

                // Visual feedback
                element.classList.add('opacity-50');

                try {
                    const resp = await fetch(url + (url.includes('?') ? '&' : '?') + 'json=1', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!resp.ok) throw new Error('Failed to load');
                    const data = await resp.json();

                    // 1. Update State
                    window.watchState.episodeNumber = epNum;
                    window.history.pushState({}, '', url);

                    // 2. Update DOM
                    document.getElementById('player-container').innerHTML = data.html_player;
                    document.getElementById('video-details').innerHTML = data.html_details;

                    // Update playlist highlighting (lazy way: replace whole list)
                    // Need to find the container parent of the clicked element or just replace the slot content
                    // Since the slot content wrapper is not identified by ID in layout, we need to be careful.
                    // In stream-layout, the playlist is inside .overflow-y-auto
                    // Let's add ID to the playlist container in layout or find it.
                    // For now, I'll assume the playlist component is wrapped in a div in the layout that I can target if I gave it an ID.
                    // Wait, I didn't give the playlist container an ID in `stream-layout`.
                    // I should assume the `episode-list` component creates a root div, but `episode-list` is a list of `<a>`.
                    // So I need to target the parent. 
                    // Let's go with: find where `element` is, go up to the container.
                    const playlistContainer = element.parentElement;
                    if (playlistContainer) playlistContainer.innerHTML = data.html_playlist;

                    // 3. Re-init Player
                    initPlayer();

                    // Auto play on switch
                    setTimeout(() => {
                        if (player) player.play();
                    }, 500);

                } catch (err) {
                    console.error(err);
                    window.location.href = url; // Fallback
                } finally {
                    element.classList.remove('opacity-50');
                }
                return false;
            };

            // Start
            document.addEventListener('DOMContentLoaded', initPlayer);
        </script>
    @endpush
</x-stream-layout>