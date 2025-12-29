@props(['episodes', 'currentEpisode', 'animeId', 'language'])

<div class="space-y-1">
    @foreach($episodes as $ep)
        <a href="{{ route('watch.show', [$animeId, $ep['number']]) }}?language={{ $language }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group episode-link
                                              {{ (isset($currentEpisode['number']) && $currentEpisode['number'] === $ep['number'])
            ? 'bg-indigo-600/20 border border-indigo-500/30'
            : 'hover:bg-white/5 border border-transparent' }}" data-episode-number="{{ $ep['number'] }}"
            onclick="return handleEpisodeClick(event, this)">

            <div
                class="relative flex-shrink-0 w-16 h-10 bg-[#2a2a35] rounded-lg overflow-hidden flex items-center justify-center border border-white/5">
                <i
                    class="fas fa-play text-xs play-icon {{ (isset($currentEpisode['number']) && $currentEpisode['number'] === $ep['number']) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-white' }}"></i>

                <div class="loading-indicator hidden absolute inset-0 flex items-center justify-center bg-black/40">
                    <i class="fas fa-circle-notch fa-spin text-xs text-indigo-400"></i>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p
                        class="text-sm font-medium truncate {{ (isset($currentEpisode['number']) && $currentEpisode['number'] === $ep['number']) ? 'text-indigo-300' : 'text-zinc-300 group-hover:text-white' }}">
                        Episode {{ $ep['number'] }}
                    </p>
                </div>
            </div>
        </a>
    @endforeach
</div>