<x-layout title="Home">
    @push('styles')
        <style>
            .swiper-wrapper { padding-bottom: 30px; /* Space for pagination */ } 
            
            /* Custom styling untuk pagination dots agar terlihat modern */
            .swiper-pagination-bullet { 
                background: #4b5563; 
                opacity: 0.5;
                width: 8px;
                height: 8px;
                transition: all 0.3s;
            }
            .swiper-pagination-bullet-active { 
                background: #c7c4f3; 
                opacity: 1; 
                width: 20px; 
                border-radius: 4px; 
            }
        </style>
    @endpush

    <x-navbar />

    {{-- 
      FIX 1: Tambahkan 'container mx-auto max-w-7xl' 
      Ini membatasi lebar konten agar tidak melar sampai ujung monitor ultrawide.
    --}}
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        
        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500 rounded-lg flex items-center justify-between">
            <p class="text-green-500 text-sm">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif
        
        {{-- TRENDING SECTION --}}
        <section class="mb-10">
            <h2 class="text-xl font-bold mb-4 border-l-4 border-[#c7c4f3] pl-3">Trending Video</h2>
            {{-- FIX 2: Ubah tinggi fixed h-64 menjadi responsive aspect ratio agar tidak gepeng di mobile --}}
            <div class="w-full aspect-video md:aspect-21/9 bg-[#352c6a] rounded-xl flex items-center justify-center shadow-lg relative overflow-hidden group">
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition"></div>
                <span class="text-[#c7c4f3] z-10 font-medium text-lg">Trending Preview Placeholder</span>
            </div>
        </section>

        {{-- GENRE LOOPS --}}
        <section class="space-y-12">
            @forelse($genres as $genreName => $items)
                @php 
                    $slug = \Illuminate\Support\Str::slug($genreName); 
                @endphp
                
                <div class="genre-section">
                    {{-- Header --}}
                    <div class="flex justify-between items-end mb-4 px-1 border-b border-white/10 pb-2">
                        <div>
                            <h3 class="text-xl font-bold capitalize text-white">{{ $genreName }}</h3>
                        </div>
                        <a href="{{ url('/search') }}?genre={{ urlencode($genreName) }}" 
                           class="text-xs font-semibold text-[#c7c4f3] hover:text-white transition flex items-center gap-1">
                           Lihat Semua <span class="text-lg leading-none">&rsaquo;</span>
                        </a>
                    </div>

                    {{-- Swiper Container --}}
                    @if(!empty($items))
                        <div class="swiper swiper-container-{{ $slug }} w-full" data-slug="{{ $slug }}">
                            <div class="swiper-wrapper flex flex-nowrap">
                                @foreach($items as $item)
                                    <div class="swiper-slide flex-shrink-0 h-auto">
                                        {{-- Anime Card --}}
                                        <div class="group relative bg-[#1f1f1f] rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full hover:-translate-y-1">
                                            <a href="{{ url('/anime', $item['id'] ?? '#') }}" class="absolute inset-0 z-10"></a>
                                            
                                            {{-- FIX 3: Gunakan aspect-[2/3] (Syntax JIT yang benar) --}}
                                            <div class="aspect-2/3 w-full overflow-hidden relative">
                                                <img src="{{ $item['cover'] ?? 'https://via.placeholder.com/200x300?text=No+Image' }}" 
                                                     alt="{{ $item['title'] ?? 'Anime' }}" 
                                                     loading="lazy"
                                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 ease-in-out">
                                                
                                                {{-- Rating Badge Overlay --}}
                                                <div class="absolute top-2 right-2 bg-black/70 text-sm text-white px-2 py-0.5 rounded flex items-center gap-2 backdrop-blur-sm">
                                                    @php
                                                        $langs = is_array($item['languages'] ?? null) ? $item['languages'] : [];
                                                        $langsLabel = count($langs) ? implode(', ', array_slice($langs, 0, 2)) : '-';
                                                    @endphp
                                                    <span class="font-medium">{{ $langsLabel }}</span>
                                                </div>
                                            </div>

                                            {{-- Content --}}
                                            <div class="p-3">
                                                <h4 class="font-bold text-sm text-white line-clamp-1 group-hover:text-[#c7c4f3] transition-colors" title="{{ $item['title'] ?? '' }}">
                                                    {{ $item['title'] ?? 'Unknown Title' }}
                                                </h4>
                                                <div class="flex justify-between items-center mt-1 text-xs text-gray-400">
                                                    <span>{{ $item['type'] ?? 'TV' }}</span>
                                                    <span>{{ $item['year'] ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    @else
                        <div class="text-gray-500 text-sm italic">Data tidak tersedia saat ini.</div>
                    @endif
                </div>
            @empty
                <div class="text-center py-10 text-gray-400">
                    <p>Belum ada kategori yang ditampilkan.</p>
                </div>
            @endforelse
        </section>

    </main>

    <x-footer />

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.swiper').forEach(container => {
                    const slug = container.dataset.slug || null;
                    if (!slug || !container.querySelector('.swiper-wrapper')) return;

                    const paginationEl = container.querySelector('.swiper-pagination');

                    new Swiper(container, {
                        // FIX 4: Konfigurasi Breakpoints yang lebih padat (kecil)
                        // Agar di layar besar kita menampilkan lebih banyak item (6 atau 7)
                        // sehingga kartu tidak menjadi raksasa.
                        slidesPerView: 2,
                        spaceBetween: 15,
                        lazy: true,
                        loop: false,
                        pagination: paginationEl ? {
                            el: paginationEl,
                            clickable: true,
                            dynamicBullets: true,
                        } : undefined,
                        breakpoints: {
                            480: { slidesPerView: 3, spaceBetween: 15 }, // HP Landscape
                            640: { slidesPerView: 3, spaceBetween: 20 }, // Tablet Kecil
                            768: { slidesPerView: 4, spaceBetween: 20 }, // Tablet
                            1024: { slidesPerView: 5, spaceBetween: 24 }, // Laptop
                            1280: { slidesPerView: 6, spaceBetween: 24 }, // Desktop Large
                            1536: { slidesPerView: 7, spaceBetween: 24 }, // Desktop XL
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layout>