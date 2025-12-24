<x-layout title="{{ $anime['title'] ?? 'Anime Detail' }}">
    
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Poster & Basic Info -->
            <div class="bg-[#352c6a] rounded-lg p-4">
                @if(!empty($anime['image']))
                    <img src="{{ $anime['image'] }}" alt="{{ $anime['title'] }}" class="aspect-[2/3] w-full object-cover rounded-md mb-4" />
                @else
                    <div class="aspect-[2/3] bg-gradient-to-br from-[#4a3f7a] to-[#352c6a] rounded-md mb-4 flex items-center justify-center">
                        <i class="fas fa-image text-5xl text-[#6d5bd0] opacity-50"></i>
                    </div>
                @endif
                <h1 class="text-2xl font-bold mb-2">{{ $anime['title'] ?? 'Unknown Title' }}</h1>
                <div class="text-[#c7c4f3] space-y-1 text-sm">
                    <p><span class="font-semibold">Year:</span> {{ $anime['release_year'] ?? ($anime['year'] ?? '—') }}</p>
                    <p><span class="font-semibold">Rating:</span> {{ $anime['rating_score'] ?? ($anime['rating'] ?? '—') }}</p>
                    <p><span class="font-semibold">Genres:</span> {{ implode(', ', $anime['genres'] ?? []) }}</p>
                    <p><span class="font-semibold">Episodes:</span> {{ $anime['total_episode'] ?? ($anime['episodes'] ?? '—') }}</p>
                    @php($alts = $anime['alternative_names'] ?? ($anime['aliases'] ?? []))
                    @if(!empty($alts))
                        <p><span class="font-semibold">Also known as:</span> {{ implode(', ', $alts) }}</p>
                    @endif
                </div>
            </div>

            <!-- Synopsis -->
            <div class="md:col-span-2 bg-[#352c6a] rounded-lg p-4">
                <h2 class="text-xl font-bold mb-3">Synopsis</h2>
                <p class="text-[#c7c4f3] leading-relaxed">{{ $anime['synopsis'] ?? 'No synopsis available.' }}</p>
            </div>
        </div>

        <!-- Episodes List -->
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Episodes</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($episodes as $ep)
                    <a href="{{ route('watch.show', [$anime['id'], $ep['number']]) }}" class="group bg-[#352c6a] rounded-lg overflow-hidden">
                        <div class="aspect-video relative overflow-hidden bg-gradient-to-br from-[#4a3f7a] to-[#352c6a]">
                            @php($thumb = $ep['thumbnail'] ?? $anime['image'] ?? null)
                            @if(!empty($thumb))
                                <img src="{{ $thumb }}" alt="Episode {{ $ep['number'] }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover" />
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <i class="fas fa-film text-3xl text-[#6d5bd0] opacity-60"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-semibold text-[#f2f1ff]">Episode {{ $ep['number'] }}: {{ $ep['title'] }}</p>
                            <p class="text-xs text-[#c7c4f3]">{{ $ep['duration'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </main>

    
</x-layout>
