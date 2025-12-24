<x-layout title="Anime List">
    
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Browse Anime</h1>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @forelse($animes as $a)
                <a href="{{ route('anime.show', $a['id']) }}" class="group relative bg-[#352c6a] rounded-lg overflow-hidden hover:shadow-lg hover:shadow-[#8b7cf6]/20 transition-all duration-300">
                    <div class="aspect-[2/3] bg-gradient-to-br from-[#4a3f7a] to-[#352c6a]">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-[#6d5bd0] opacity-50"></i>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm text-[#f2f1ff] line-clamp-2 mb-1">{{ $a['title'] }}</h3>
                        <div class="flex items-center justify-between text-xs text-[#c7c4f3]">
                            <span class="flex items-center">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                {{ $a['rating'] ?? '—' }}
                            </span>
                            <span>{{ $a['episodes'] ?? '—' }} eps</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-[#c7c4f3]">No anime found.</p>
            @endforelse
        </div>
    </main>

    
</x-layout>
