<x-layout title="Watch: {{ $anime['title'] ?? 'Episode' }}">
    <x-navbar />

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
            <aside class="bg-[#352c6a] rounded-lg overflow-hidden">
                <div class="p-4 border-b border-[#4a3f7a]">
                    <h2 class="text-lg font-bold">Playlist</h2>
                </div>
                <div class="max-h-[60vh] overflow-y-auto">
                    @foreach($episodes as $ep)
                        <a href="{{ route('watch.show', [$animeId, $ep['number']]) }}?language={{ $language }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#4a3f7a] {{ (isset($currentEpisode['number']) && $currentEpisode['number'] === $ep['number']) ? 'bg-[#4a3f7a]' : '' }}">
                            <div class="w-16 h-10 bg-gradient-to-br from-[#4a3f7a] to-[#352c6a] rounded flex items-center justify-center">
                                <i class="fas fa-film text-[#6d5bd0]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-[#f2f1ff]">Ep {{ $ep['number'] }}: {{ $ep['title'] }}</p>
                                <p class="text-xs text-[#c7c4f3]">{{ $ep['duration'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </main>

    <x-footer />

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
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
            const streams = @json($streams);
            let currentResolution = streams[0]?.resolution || 1080;

            // Load initial stream (highest quality)
            if (streams.length > 0) {
                const firstBtn = qualityBtns[0];
                const proxyUrl = firstBtn?.dataset.proxyUrl || '';
                const directUrl = firstBtn?.dataset.directUrl || '';
                const hasReferer = firstBtn?.dataset.hasReferer === 'true';
                
                // Use direct if no referer, otherwise use proxy
                const initialUrl = !hasReferer ? directUrl : proxyUrl;
                
                player.src({
                    type: 'video/mp4',
                    src: initialUrl
                });
            }
            
            // Add error event listener
            player.on('error', function(e) {
                const error = player.error();
                console.error('Video player error:', error);
            });

            // Quality switching
            qualityBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const resolution = parseInt(this.dataset.resolution);
                    const proxyUrl = this.dataset.proxyUrl;
                    const directUrl = this.dataset.directUrl;
                    const hasReferer = this.dataset.hasReferer === 'true';
                    
                    // Choose URL: direct if no referer, otherwise proxy
                    const url = !hasReferer ? directUrl : proxyUrl;
                    
                    const currentTime = player.currentTime();
                    const wasPaused = player.paused();

                    // Update active button
                    qualityBtns.forEach(b => {
                        b.classList.remove('bg-[#8b7cf6]', 'text-white');
                        b.classList.add('bg-[#4a3f7a]', 'text-[#c7c4f3]');
                    });
                    this.classList.remove('bg-[#4a3f7a]', 'text-[#c7c4f3]');
                    this.classList.add('bg-[#8b7cf6]', 'text-white');

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
                });
            });

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
