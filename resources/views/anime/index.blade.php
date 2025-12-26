<x-layout title="Browse Anime - Utsava Cinema">

    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <main class="min-h-screen pt-24 pb-20 px-4 md:px-6 container mx-auto max-w-7xl">
        
        <!-- Search Header -->
        <div class="mb-10 text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black italic text-white uppercase tracking-tight">Explore Library</h1>
            <p class="text-zinc-400 text-sm max-w-lg mx-auto">Discover thousands of anime series, from classic hits to the latest simulcasts.</p>
            
            <form action="{{ route('anime.search') }}" method="GET" class="relative max-w-2xl mx-auto group">
                <div class="absolute inset-0 bg-indigo-600/20 rounded-full blur-xl group-hover:bg-indigo-600/30 transition-colors"></div>
                <div class="relative flex items-center bg-[#1a1a20] border border-white/10 rounded-full overflow-hidden shadow-2xl focus-within:border-indigo-500/50 transition-colors">
                    <div class="pl-6 text-zinc-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        name="query" 
                        value="{{ request('query') }}"
                        placeholder="Search for anime..." 
                        class="w-full bg-transparent border-none px-4 py-4 text-white placeholder-zinc-500 focus:ring-0 outline-none"
                    >
                    @if(request('query'))
                        <a href="{{ route('anime.index') }}" class="pr-6 text-zinc-500 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Filters (Optional - Placeholder for now) -->
        {{-- <div class="flex items-center justify-center gap-2 mb-8 overflow-x-auto pb-4 hide-scrollbar">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-lg shadow-indigo-500/30">All</button>
            <button class="px-4 py-2 bg-[#1a1a20] hover:bg-[#25252b] text-zinc-400 hover:text-white border border-white/5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">Trending</button>
            <button class="px-4 py-2 bg-[#1a1a20] hover:bg-[#25252b] text-zinc-400 hover:text-white border border-white/5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">New Season</button>
            <button class="px-4 py-2 bg-[#1a1a20] hover:bg-[#25252b] text-zinc-400 hover:text-white border border-white/5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">Action</button>
        </div> --}}

        <!-- Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-10">
            @forelse($animes as $a)
                <x-anime-card :anime="$a" />
            @empty
                <div class="col-span-full py-20 text-center space-y-4">
                    <div class="inline-block p-4 bg-[#1a1a20] rounded-full border border-white/5">
                        <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-zinc-500 font-medium">No results found for your search.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination (if available) -->
        <div class="mt-12 flex justify-center">
            {{-- Laravel Pagination links usually go here --}}
            {{-- {{ $animes->links() }} --}}
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Handle add to watchlist
                document.addEventListener('click', function (e) {
                    const btn = e.target.closest('.add-to-watchlist');
                    if (!btn) return;

                    e.preventDefault();
                    e.stopPropagation();

                    const icon = btn.querySelector('svg');
                    const originalClass = icon.getAttribute('class');

                    const data = {
                        identifier_id: btn.dataset.id,
                        anime_title: btn.dataset.title,
                        poster_path: btn.dataset.poster,
                    };

                    // Optimistic UI
                    if(icon.classList.contains('text-zinc-400')) {
                       icon.classList.remove('text-zinc-400');
                       icon.classList.add('text-red-500', 'fill-current');
                    } else {
                       icon.classList.add('text-zinc-400');
                       icon.classList.remove('text-red-500', 'fill-current');
                    }

                    fetch('/watchlist', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Confirm state
                        if (data.status === 'added') {
                            icon.classList.remove('text-zinc-400');
                            icon.classList.add('text-red-500', 'fill-current');
                        } else if (data.status === 'removed') {
                             icon.classList.add('text-zinc-400');
                             icon.classList.remove('text-red-500', 'fill-current');
                        }
                    })
                    .catch(console.error);
                });
            });
        </script>
    @endpush

</x-layout>