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

    @push('styles')
        <style>
            /* 1. Reset Button Container agar Simetris */
            .vjs-quality-menu-button {
                position: relative;
                display: flex !important;
                align-items: center;
                justify-content: center;
                width: 4em !important;
                /* Standar lebar tombol Video.js */
            }

            /* 2. Centering Ikon Settings (Gear) - Pixel Perfect */
            .vjs-quality-menu-button .vjs-icon-placeholder:before {
                content: '\f013';
                /* Gear icon FontAwesome */
                font-family: 'Font Awesome 6 Free', 'FontAwesome', sans-serif;
                font-weight: 900;
                font-size: 1.3em;
                /* Ukuran yang proporsional */

                /* Teknik Centering Absolut */
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -45%) !important;
                /* -45% untuk sedikit kompensasi baseline font */

                /* Reset properti yang mengganggu */
                line-height: 1 !important;
                width: auto !important;
                height: auto !important;
                margin: 0 !important;
                display: flex !important;
                align-items: center;
                justify-content: center;
            }

            /* 3. Normalisasi Menu Popup (Muncul di atas tombol) */
            .vjs-quality-menu-button .vjs-menu {
                bottom: 3em !important;
                /* Tepat di atas control bar */
                left: 50% !important;
                transform: translateX(-50%) !important;
            }

            .vjs-quality-menu-button .vjs-menu-content {
                background: rgba(20, 20, 20, 0.95) !important;
                backdrop-filter: blur(12px);
                border-radius: 10px !important;
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 5px 0;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            }

            /* 4. Styling Item Menu */
            .vjs-quality-menu-button .vjs-menu-item {
                padding: 10px 20px !important;
                font-size: 12px !important;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.2s ease;
            }

            .vjs-quality-menu-button .vjs-menu-item:hover {
                background: rgba(99, 102, 241, 0.2) !important;
                color: #818cf8 !important;
            }

            .vjs-quality-menu-button .vjs-menu-item.vjs-selected {
                background: #6366f1 !important;
                /* Indigo-500 */
                color: white !important;
            }

            /* 5. Label Kualitas (Badge Kecil) */
            .vjs-quality-menu-button .vjs-quality-label {
                font-size: 8px;
                font-weight: 900;
                background: #ef4444;
                /* Merah sebagai indikator resolusi tinggi */
                color: white;
                padding: 1px 3px;
                border-radius: 3px;
                position: absolute;
                top: 8px;
                right: 4px;
                line-height: 1;
                z-index: 2;
                pointer-events: none;
                text-transform: uppercase;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // --- Global State & Config ---
            window.watchState = {
                animeId: '{{ $animeId }}',
                episodeNumber: '{{ $episodeNumber }}',
                language: '{{ $language }}',
                animeTitle: @json($anime['title'] ?? ''),
                animePoster: @json($anime['image'] ?? ''),
                csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            };

            let player = null;
            let currentStreams = [];
            let currentQuality = null;

            // --- Quality Menu Button Component ---
            const createQualityMenuButton = (streams) => {
                const MenuButton = videojs.getComponent('MenuButton');
                const MenuItem = videojs.getComponent('MenuItem');

                // Custom Quality Menu Item
                class QualityMenuItem extends MenuItem {
                    constructor(player, options) {
                        super(player, {
                            label: options.label,
                            selectable: true,
                            selected: options.selected || false
                        });
                        this.resolution = options.resolution;
                        this.streamUrl = options.url;
                    }

                    handleClick(event) {
                        super.handleClick(event);
                        switchQuality(this.resolution, this.streamUrl);
                    }
                }

                // Custom Quality Menu Button
                class QualityMenuButton extends MenuButton {
                    constructor(player, options) {
                        super(player, options);
                        this.addClass('vjs-quality-menu-button');
                        this.controlText('Quality');
                    }

                    createItems() {
                        const items = [];
                        const savedRes = localStorage.getItem(`pref_res_${window.watchState.animeId}`);

                        streams.forEach((stream, index) => {
                            const isSelected = savedRes
                                ? stream.resolution.toString() === savedRes
                                : index === 0;

                            items.push(new QualityMenuItem(this.player_, {
                                label: stream.label,
                                resolution: stream.resolution,
                                url: stream.url,
                                selected: isSelected
                            }));
                        });

                        return items;
                    }

                    buildCSSClass() {
                        return `vjs-quality-menu-button ${super.buildCSSClass()}`;
                    }
                }

                videojs.registerComponent('QualityMenuButton', QualityMenuButton);
                return QualityMenuButton;
            };

            // --- Switch Quality Function ---
            const switchQuality = (resolution, url) => {
                if (!player) return;

                const currentTime = player.currentTime();
                const isPaused = player.paused();
                currentQuality = resolution;

                // Update menu selection
                const qualityButton = player.controlBar.getChild('QualityMenuButton');
                if (qualityButton) {
                    qualityButton.items.forEach(item => {
                        item.selected(item.resolution.toString() === resolution.toString());
                    });
                }

                // Change source
                player.src({ type: 'video/mp4', src: url });

                // Restore position
                player.one('loadedmetadata', () => {
                    player.currentTime(currentTime);
                    if (!isPaused) player.play().catch(() => { });
                });

                // Save preference
                localStorage.setItem(`pref_res_${window.watchState.animeId}`, resolution);
            };

            // --- Initialize Player ---
            async function initPlayer() {
                const videoEl = document.getElementById('videoPlayer');
                const streamsDataEl = document.getElementById('streams-data');

                if (!videoEl) return;

                // Parse streams data
                try {
                    currentStreams = JSON.parse(streamsDataEl?.textContent || '[]');
                } catch (e) {
                    currentStreams = [];
                }

                if (player) player.dispose();

                // Register quality menu component
                if (currentStreams.length > 0) {
                    createQualityMenuButton(currentStreams);
                }

                // Initialize Video.js with custom control bar
                const controlBarChildren = [
                    'playToggle',
                    'volumePanel',
                    'currentTimeDisplay',
                    'timeDivider',
                    'durationDisplay',
                    'progressControl',
                    'remainingTimeDisplay',
                    'customControlSpacer',
                    'playbackRateMenuButton'
                ];

                // Add quality button if streams available
                if (currentStreams.length > 0) {
                    controlBarChildren.push('QualityMenuButton');
                }

                controlBarChildren.push('fullscreenToggle');

                player = videojs(videoEl, {
                    fluid: true,
                    responsive: true,
                    playbackRates: [0.5, 1, 1.25, 1.5, 2],
                    controlBar: { children: controlBarChildren }
                });

                // Set initial quality
                if (currentStreams.length > 0) {
                    const savedRes = localStorage.getItem(`pref_res_${window.watchState.animeId}`);
                    const initialStream = currentStreams.find(s => s.resolution.toString() === savedRes) || currentStreams[0];

                    if (initialStream) {
                        currentQuality = initialStream.resolution;
                        player.src({ type: 'video/mp4', src: initialStream.url });
                    }
                }
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

                    // Sync state before re-init
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
                    window.location.href = url; // Fallback if AJAX fails
                }
                return false;
            };

            document.addEventListener('DOMContentLoaded', initPlayer);
        </script>
    @endpush
</x-stream-layout>