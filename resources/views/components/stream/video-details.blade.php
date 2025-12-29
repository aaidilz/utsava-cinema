@props(['anime', 'currentEpisode'])

<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white leading-tight">{{ $anime['title'] ?? 'Unknown Anime' }}</h1>
            <h2 class="text-lg text-indigo-400 font-medium">
                Episode {{ $currentEpisode['number'] ?? '—' }}
            </h2>
        </div>

        <div class="flex gap-2">
            {{-- Action buttons like Share, Report could go here --}}
        </div>
    </div>

    <!-- Genres / Meta -->
    @if(!empty($anime['genres']))
        <div class="flex flex-wrap gap-2">
            @foreach($anime['genres'] as $genre)
                <a href="{{ route('anime.index', ['genre' => $genre]) }}"
                    class="px-3 py-1 text-xs font-semibold bg-white/5 hover:bg-white/10 border border-white/5 rounded-full text-zinc-400 hover:text-white transition-colors">
                    {{ $genre }}
                </a>
            @endforeach
        </div>
    @endif

    @if(!empty($anime['synopsis']))
        <div class="mt-4 text-zinc-400 text-sm leading-relaxed max-w-4xl">
            <p class="line-clamp-3 hover:line-clamp-none transition-all cursor-pointer">{{ $anime['synopsis'] }}</p>
        </div>
    @endif
</div>