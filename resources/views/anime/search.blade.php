<x-layout title="Search Anime">
    

    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-3">Search</h1>
            <form action="{{ route('anime.search') }}" method="GET" class="flex gap-2">
                <input type="text" name="query" value="{{ $query }}" placeholder="Search anime..." class="flex-1 px-4 py-3 bg-[#352c6a] border border-[#4a3f7a] rounded-lg outline-none text-white placeholder:text-[#a3a0d9]">
                <select name="limit" class="px-3 py-3 bg-[#352c6a] border border-[#4a3f7a] rounded-lg text-white">
                    @foreach([10, 20, 24, 30, 50] as $opt)
                        <option value="{{ $opt }}" {{ (int)$limit === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-3 bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white rounded-lg">Search</button>
            </form>
        </div>

        @if(strlen($query) <= 1)
            <p class="text-[#c7c4f3]">Type at least 2 characters to search.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @forelse($results as $r)
                    <a href="{{ route('anime.show', $r['id']) }}" class="group relative bg-[#352c6a] rounded-lg overflow-hidden hover:shadow-lg hover:shadow-[#8b7cf6]/20 transition-all duration-300">
                        <div class="aspect-[2/3] bg-gradient-to-br from-[#4a3f7a] to-[#352c6a]">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-image text-4xl text-[#6d5bd0] opacity-50"></i>
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm text-[#f2f1ff] line-clamp-2 mb-1">{{ $r['title'] }}</h3>
                            <div class="flex items-center gap-1 text-xs text-[#c7c4f3]">
                                @foreach($r['languages'] as $lang)
                                    <span class="px-2 py-0.5 bg-[#4a3f7a] text-[#f2f1ff] rounded">{{ strtoupper($lang) }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-[#c7c4f3]">No results for "{{ $query }}"</p>
                @endforelse
            </div>
        @endif
    </main>

    
</x-layout>
