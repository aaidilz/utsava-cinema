<x-stream-layout title="Watch: {{ $anime['title'] ?? 'Episode' }}">
    <x-slot:player>
        <div id="player-container">
            <x-stream.player :streams="$streams" :anime="$anime" :currentEpisode="$currentEpisode" :animeId="$animeId"
                :episodeNumber="$episodeNumber" :language="$language" />
        </div>
    </x-slot:player>

    <x-slot:details>
        <div id="video-details">
            <x-stream.video-details :anime="$anime" :currentEpisode="$currentEpisode" />
        </div>
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
            // --- Global State & Config ---
            window.watchState = {
                animeId: '{{ $animeId }}',
                episodeNumber: '{{ $episodeNumber }}',
                language: '{{ $language }}',
                animeTitle: @json($anime['title'] ?? ''),
                animePoster: @json($anime['poster_path'] ?? ''),
                csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            };

            let player = null;

            // --- UI Helpers ---
            const updateUIActiveQuality = (res) => {
                document.querySelectorAll('.quality-btn').forEach(b => {
                    const indicator = b.querySelector('.active-indicator');
                    const isActive = b.dataset.resolution === res.toString();
                    if (indicator) indicator.classList.toggle('opacity-0', !isActive);
                    b.classList.toggle('bg-indigo-600', isActive);
                    b.classList.toggle('text-white', isActive);
                });
            };

            // --- Core Functions ---

            function setQuality(btn, keepPosition = true) {
                if (!player) return;

                const newUrl = btn.dataset.url;
                const res = btn.dataset.resolution;
                const currentTime = player.currentTime();
                const isPaused = player.paused();

                updateUIActiveQuality(res);
                player.src({ type: 'video/mp4', src: newUrl });

                if (keepPosition) {
                    player.one('loadedmetadata', () => {
                        player.currentTime(currentTime);
                        if (!isPaused) player.play().catch(() => { });
                    });
                }
                localStorage.setItem(`pref_res_${window.watchState.animeId}`, res);
            }

            async function initPlayer() {
                const videoEl = document.getElementById('videoPlayer');
                if (!videoEl) return;

                if (player) player.dispose();

                player = videojs(videoEl, {
                    fluid: true,
                    responsive: true,
                    playbackRates: [0.5, 1, 1.25, 1.5, 2],
                    controlBar: { children: ['playToggle', 'volumePanel', 'currentTimeDisplay', 'timeDivider', 'durationDisplay', 'progressControl', 'liveDisplay', 'remainingTimeDisplay', 'customControlSpacer', 'playbackRateMenuButton', 'chaptersButton', 'descriptionsButton', 'subsCapsButton', 'audioTrackButton', 'fullscreenToggle'] }
                });

                // Inisialisasi Kualitas
                const qualityBtns = document.querySelectorAll('.quality-btn');
                const savedRes = localStorage.getItem(`pref_res_${window.watchState.animeId}`);
                let initialBtn = Array.from(qualityBtns).find(b => b.dataset.resolution === savedRes) || qualityBtns[0];

                if (initialBtn) setQuality(initialBtn, false);

                // Event Listeners
                qualityBtns.forEach(btn => btn.onclick = (e) => (e.preventDefault(), setQuality(btn, true)));


            }

            // --- AJAX Episode Switcher ---
            window.handleEpisodeClick = async function (e, element) {
                e.preventDefault();
                const url = element.href;

                // Visual Loading
                element.classList.add('opacity-50', 'pointer-events-none');
                const loading = element.querySelector('.loading-indicator');
                if (loading) loading.classList.remove('hidden');

                try {
                    const resp = await fetch(url + (url.includes('?') ? '&' : '?') + 'json=1');
                    if (!resp.ok) throw new Error();
                    const data = await resp.json();

                    // Sinkronisasi State sebelum re-init
                    window.watchState.episodeNumber = element.dataset.episodeNumber;
                    window.history.pushState({}, '', url);

                    // Update DOM
                    document.getElementById('player-container').innerHTML = data.html_player;
                    document.getElementById('video-details').innerHTML = data.html_details;

                    const playlistContainer = element.closest('.space-y-1');
                    if (playlistContainer) playlistContainer.innerHTML = data.html_playlist;

                    await initPlayer();
                    setTimeout(() => player.play(), 300);
                } catch (err) {
                    window.location.href = url; // Fallback jika AJAX gagal
                }
                return false;
            };

            document.addEventListener('DOMContentLoaded', initPlayer);

        </script>
    @endpush
</x-stream-layout>