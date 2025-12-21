<x-layout title="Watch: {{ $anime['title'] ?? 'Episode' }}">
    <x-navbar />

    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Video Player -->
            <div class="lg:col-span-2 bg-[#352c6a] rounded-lg overflow-hidden">
                <div class="aspect-video bg-black">
                    <!-- Placeholder HTML5 video -->
                    <video id="player" class="w-full h-full" controls preload="metadata">
                        <source src="{{ $streamUrl }}" type="video/mp4" />
                        Your browser does not support HTML5 video.
                    </video>
                </div>
                <div class="p-4">
                    <h1 class="text-xl font-bold">{{ $anime['title'] ?? 'Unknown' }}</h1>
                    <p class="text-[#c7c4f3]">Episode {{ $currentEpisode['number'] ?? '—' }}: {{ $currentEpisode['title'] ?? '' }}</p>
                </div>
            </div>

            <!-- Playlist -->
            <aside class="bg-[#352c6a] rounded-lg overflow-hidden">
                <div class="p-4 border-b border-[#4a3f7a]">
                    <h2 class="text-lg font-bold">Playlist</h2>
                </div>
                <div class="max-h-[60vh] overflow-y-auto">
                    @foreach($episodes as $ep)
                        <a href="{{ route('watch.show', [$anime['id'], $ep['number']]) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#4a3f7a] {{ (isset($currentEpisode['number']) && $currentEpisode['number'] === $ep['number']) ? 'bg-[#4a3f7a]' : '' }}">
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
</x-layout>
