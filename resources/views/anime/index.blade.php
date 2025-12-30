<x-layout title="Browse Anime - Utsava Cinema">

    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
    @endpush

    <main class="min-h-screen pt-24 pb-20 px-4 md:px-6 container mx-auto max-w-7xl">

        <!-- Search Header -->
        <div class="mb-10 text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black italic text-white uppercase tracking-tight">Explore Library</h1>
            <p class="text-zinc-400 text-sm max-w-lg mx-auto">Discover thousands of anime series, from classic hits to
                the latest simulcasts.</p>

            <form action="{{ route('anime.index') }}" method="GET" class="relative max-w-3xl mx-auto group">
                <div
                    class="absolute inset-0 bg-indigo-600/20 rounded-full blur-xl group-hover:bg-indigo-600/30 transition-colors">
                </div>
                <div
                    class="relative flex items-center bg-[#1a1a20] border border-white/10 rounded-full overflow-hidden shadow-2xl focus-within:border-indigo-500/50 transition-colors p-2">

                    <!-- Search Icon (Hidden on small screens if needed, or kept) -->
                    <div class="pl-4 text-zinc-500 hidden sm:block">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Genre Select -->
                    <div class="border-r border-white/10 pr-2 mr-2 hidden sm:block">
                        <select name="genre"
                            class="bg-transparent text-zinc-300 text-sm font-medium border-none focus:ring-0 cursor-pointer py-2 pl-2 pr-8">
                            <option value="" class="bg-[#1a1a20]">All Genres</option>
                            @foreach(['Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life', 'Sports', 'Supernatural'] as $g)
                                <option value="{{ $g }}" class="bg-[#1a1a20]" {{ request('query') == $g || request('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input type="text" name="query" value="{{ request('query') }}" placeholder="Search by title..."
                        class="w-full bg-transparent border-none px-4 py-2 text-white placeholder-zinc-500 focus:ring-0 outline-none h-12">

                    @if(request('query') || request('genre'))
                        <a href="{{ route('anime.index') }}" class="pr-4 text-zinc-500 hover:text-white"
                            title="Clear filters">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif

                    <button type="submit"
                        class="hidden sm:block px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-medium transition-colors mr-1">
                        Search
                    </button>
                    <!-- Mobile Search Button -->
                    <button type="submit" class="sm:hidden p-3 bg-indigo-600 text-white rounded-full mr-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Genre Select (Visible only on small screens) -->
                <div class="mt-4 sm:hidden">
                    <select name="genre_mobile"
                        onchange="this.form.querySelector('select[name=genre]').value=this.value; this.form.submit()"
                        class="w-full bg-[#1a1a20] text-zinc-300 border border-white/10 rounded-lg p-3 focus:border-indigo-500 outline-none">
                        <option value="" class="bg-[#1a1a20]">All Genres</option>
                        @foreach(['Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life', 'Sports', 'Supernatural'] as $g)
                            <option value="{{ $g }}" class="bg-[#1a1a20]" {{ request('genre') == $g ? 'selected' : '' }}>
                                {{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Filters (Optional - Placeholder for now) -->
        {{-- <div class="flex items-center justify-center gap-2 mb-8 overflow-x-auto pb-4 hide-scrollbar">
            <button
                class="px-4 py-2 bg-indigo-600 text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-lg shadow-indigo-500/30">All</button>
            <button
                class="px-4 py-2 bg-[#1a1a20] hover:bg-[#25252b] text-zinc-400 hover:text-white border border-white/5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">Trending</button>
            <button
                class="px-4 py-2 bg-[#1a1a20] hover:bg-[#25252b] text-zinc-400 hover:text-white border border-white/5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">New
                Season</button>
            <button
                class="px-4 py-2 bg-[#1a1a20] hover:bg-[#25252b] text-zinc-400 hover:text-white border border-white/5 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">Action</button>
        </div> --}}

        <!-- Grid -->
        <div id="anime-grid"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-10">
            @forelse($animes as $a)
                <x-anime-card :anime="$a" />
            @empty
                <div class="col-span-full py-20 text-center space-y-4">
                    <div class="inline-block p-4 bg-[#1a1a20] rounded-full border border-white/5">
                        <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-zinc-500 font-medium">No results found for your search.</p>
                </div>
            @endforelse
        </div>

        <!-- Infinite Scroll Sentinel & Loader -->
        <div id="infinite-scroll-sentinel" class="mt-12 h-20 flex justify-center items-center">
            <div id="loading-spinner" class="hidden">
                <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>
            <p id="no-more-results" class="hidden text-zinc-600 text-sm">No more anime to load.</p>
        </div>

        <!-- Pagination (if available) -->
        <div class="mt-12 flex justify-center">
            {{-- Laravel Pagination links usually go here --}}
            {{-- {{ $animes->links() }} --}}
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let page = 1;
                let hasNext = true;
                let isLoading = false;
                const grid = document.getElementById('anime-grid');
                const sentinel = document.getElementById('infinite-scroll-sentinel');
                const spinner = document.getElementById('loading-spinner');
                const noMoreInfo = document.getElementById('no-more-results');

                // Get current query params
                const urlParams = new URLSearchParams(window.location.search);
                const query = urlParams.get('query') || '';
                const genre = urlParams.get('genre') || '';

                const observer = new IntersectionObserver(async (entries) => {
                    if (entries[0].isIntersecting && hasNext && !isLoading) {
                        isLoading = true;
                        spinner.classList.remove('hidden');

                        try {
                            page++;
                            const response = await fetch(`{{ route('anime.index') }}?page=${page}&query=${encodeURIComponent(query)}&genre=${encodeURIComponent(genre)}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                if (data.html) {
                                    // Create a temporary container
                                    const temp = document.createElement('div');
                                    temp.innerHTML = data.html;

                                    // Append children to grid
                                    while (temp.firstChild) {
                                        grid.appendChild(temp.firstChild);
                                    }
                                }

                                hasNext = data.has_next;
                                if (!hasNext) {
                                    noMoreInfo.classList.remove('hidden');
                                    observer.disconnect();
                                }
                            }
                        } catch (error) {
                            console.error('Error loading more anime:', error);
                        } finally {
                            isLoading = false;
                            spinner.classList.add('hidden');
                        }
                    }
                }, {
                    rootMargin: '200px', // Trigger before reaching bottom
                    threshold: 0.1
                });

                if (sentinel) {
                    observer.observe(sentinel);
                }
            });

            // ...existing code...

    @push('scripts')
        <script>
            // ...existing infinite scroll code...

            // Toast Notification Function
            function showToast(message, type = 'success') {
                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: type === 'success' 
                        ? "linear-gradient(to right, #10b981, #059669)" 
                        : "linear-gradient(to right, #ef4444, #dc2626)",
                    stopOnFocus: true,
                    className: "text-white font-medium",
                    onClick: function() {}
                }).showToast();
            }

            // Handle Add/Remove from Watchlist
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.add-to-watchlist');
                if (!btn) return;

                e.preventDefault();
                e.stopPropagation();

                const icon = btn.querySelector('svg');
                const id = btn.dataset.id;
                const title = btn.dataset.title;
                const poster = btn.dataset.poster;

                const data = {
                    identifier_id: id,
                    anime_title: title,
                    poster_path: poster,
                };

                const isCurrentlyAdded = icon.classList.contains('text-red-500');

                // Optimistic UI update
                if (!isCurrentlyAdded) {
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
                        if (data.status === 'added') {
                            icon.classList.remove('text-zinc-400');
                            icon.classList.add('text-red-500', 'fill-current');
                            showToast(`✓ ${title} berhasil ditambahkan ke watchlist!`, 'success');
                        } else if (data.status === 'removed') {
                            icon.classList.add('text-zinc-400');
                            icon.classList.remove('text-red-500', 'fill-current');
                            showToast(`✓ ${title} dihapus dari watchlist!`, 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert UI on error
                        if (!isCurrentlyAdded) {
                            icon.classList.add('text-zinc-400');
                            icon.classList.remove('text-red-500', 'fill-current');
                        } else {
                            icon.classList.remove('text-zinc-400');
                            icon.classList.add('text-red-500', 'fill-current');
                        }
                        showToast('✗ Gagal memproses permintaan. Silakan coba lagi!', 'error');
                    });
            });
        </script>
    @endpush

        </script>
    @endpush

</x-layout>