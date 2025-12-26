<x-layout title="Home - Utsava Cinema">
    @push('styles')
        <style>
            /* .swiper {
                                    overflow: visible !important;
                                } */

            .swiper-pagination-bullet {
                background: #4b5563;
                opacity: 0.5;
                width: 6px;
                height: 6px;
                transition: all 0.3s;
            }

            .swiper-pagination-bullet-active {
                background: #818cf8;
                opacity: 1;
                width: 20px;
                border-radius: 4px;
            }

            /* Hide scrollbar for swiper wrapper but allow horizontal overflow */
            .swiper-wrapper {
                padding-bottom: 40px;
            }

            /* Custom Hero Gradient */
            .hero-gradient {
                background: linear-gradient(to top, #0d0d0f 10%, transparent 100%),
                    linear-gradient(to right, #0d0d0f 0%, transparent 50%, #0d0d0f 100%);
            }
        </style>
    @endpush

    <main class="min-h-screen pb-20">

        <!-- HERO SECTION -->
        <section class="relative h-[70vh] w-full overflow-hidden">
            @if(isset($hero) && $hero)
                <!-- Background Image -->
                <div class="absolute inset-0 bg-cover bg-center transition-all duration-1000"
                    style="background-image: url('{{ $hero['image'] }}');">
                </div>

                <!-- Overlay & Gradient -->
                <div class="absolute inset-0 bg-black/40 hero-gradient"></div>

                <div class="relative container mx-auto max-w-7xl px-4 md:px-6 h-full flex items-end pb-20">
                    <div class="max-w-2xl space-y-6 animate-fade-in-up">
                        <span
                            class="inline-block px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg shadow-indigo-500/30">
                            Top Trending
                        </span>
                        <h1
                            class="text-5xl md:text-7xl font-black italic text-white leading-[0.9] drop-shadow-2xl line-clamp-2">
                            {{ $hero['title'] }}
                        </h1>

                        <div class="flex items-center gap-4 text-sm font-medium text-zinc-300">
                            @if($hero['rating'])
                                <span class="text-green-400 font-bold"><i
                                        class="fas fa-star mr-1"></i>{{ $hero['rating'] }}</span>
                            @endif
                            @if(!empty($hero['year']))
                                <span>{{ $hero['year'] }}</span>
                            @endif
                            @if(!empty($hero['classification']))
                                <span
                                    class="px-2 py-0.5 border border-zinc-600 rounded text-xs">{{ $hero['classification'] }}</span>
                            @endif
                            @if(!empty($hero['episodes']))
                                <span>{{ $hero['episodes'] }} Episodes</span>
                            @endif
                        </div>

                        <p class="text-zinc-300 line-clamp-3 md:line-clamp-2 text-sm md:text-base max-w-xl drop-shadow-md">
                            {{ $hero['synopsis'] }}
                        </p>

                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="{{ route('anime.show', $hero['id']) }}"
                                class="flex items-center gap-2 px-8 py-3 bg-white text-black font-black italic text-lg rounded-full hover:bg-zinc-200 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:scale-105 active:scale-95">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                                WATCH NOW
                            </a>
                            <button
                                class="add-to-watchlist px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full hover:bg-white/20 transition-all active:scale-95"
                                data-id="{{ $hero['id'] }}" data-title="{{ $hero['title'] }}"
                                data-poster="{{ $hero['image'] }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Fallback if no hero data -->
                <div class="absolute inset-0 bg-zinc-900 flex items-center justify-center">
                    <p class="text-white">Loading Highlights...</p>
                </div>
            @endif
        </section>

        <!-- CONTENT CONTAINER -->
        <div class="container mx-auto max-w-7xl px-4 md:px-6 -mt-10 relative z-10 space-y-16">

            <!-- TRENDING SWIPER -->
            @if(!empty($popular))
                <div class="swiper swiper-trending !overflow-visible">
                    <div class="swiper-wrapper">
                        @foreach($popular as $index => $trend)
                            <div class="swiper-slide w-full">
                                <div
                                    class="bg-gradient-to-r from-[#1a1a20] to-[#131316] rounded-3xl p-1 border border-white/5 shadow-2xl relative overflow-hidden group h-full">
                                    <a href="{{ url('/anime/' . $trend['id']) }}"
                                        class="block p-6 md:p-8 flex flex-col md:flex-row items-center gap-8 md:gap-12 h-full">
                                        <div
                                            class="relative w-full md:w-1/3 aspect-video md:aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl skew-x-[-3deg] border-4 border-[#0d0d0f] group-hover:skew-x-0 transition-all duration-500 shrink-0">
                                            <img src="{{ $trend['image'] }}" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition">
                                            </div>
                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                <div
                                                    class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 space-y-4 text-center md:text-left z-10 w-full">
                                            <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                                                <span
                                                    class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Trending
                                                    #{{ $index + 1 }}</span>
                                            </div>
                                            <h2
                                                class="text-2xl md:text-4xl font-black italic text-white uppercase line-clamp-2 leading-tight group-hover:text-indigo-400 transition-colors">
                                                {{ $trend['title'] }}
                                            </h2>
                                            <div class="grid grid-cols-3 gap-4 border-t border-white/5 pt-4 w-full">
                                                <div>
                                                    <p class="text-2xl font-bold text-indigo-400">#{{ $index + 1 }}</p>
                                                    <p class="text-[10px] text-zinc-500 uppercase font-bold">Rank</p>
                                                </div>
                                                <div>
                                                    <p class="text-2xl font-bold text-white">{{ $trend['rating'] ?? 'N/A' }}</p>
                                                    <p class="text-[10px] text-zinc-500 uppercase font-bold">Rating</p>
                                                </div>
                                                <div>
                                                    <p class="text-2xl font-bold text-white">{{ $trend['views'] }}</p>
                                                    <p class="text-[10px] text-zinc-500 uppercase font-bold">Views</p>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Decorative --}}
                                        <div
                                            class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl -z-10 group-hover:bg-indigo-600/20 transition-all duration-700 pointer-events-none">
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- <div class="swiper-pagination"></div> --}}
                </div>
            @endif

            <!-- GENRE SECTIONS -->
            <div class="space-y-12">
                @forelse($genres as $genreName => $items)
                    @php $slug = \Illuminate\Support\Str::slug($genreName); @endphp

                    <section>
                        <div class="flex justify-between items-end mb-6 px-1">
                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-2xl font-black italic text-white uppercase tracking-tight">{{ $genreName }}
                                </h3>
                                <p class="text-xs text-zinc-500 font-medium uppercase tracking-widest mt-1">Recommended for
                                    you</p>
                            </div>
                            <a href="{{ url('/search') }}?genre={{ urlencode($genreName) }}"
                                class="text-xs font-bold text-indigo-400 hover:text-white transition-colors flex items-center gap-1 group">
                                VIEW ALL <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        </div>

                        @if(!empty($items))
                            <div class="swiper swiper-container-{{ $slug }} !p-4" data-slug="{{ $slug }}">
                                <div class="swiper-wrapper">
                                    @foreach($items as $item)
                                        <div class="swiper-slide">
                                            <x-anime-card :anime="$item" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="py-10 text-center border border-dashed border-zinc-800 rounded-2xl">
                                <p class="text-zinc-600 text-sm">No anime found in this category.</p>
                            </div>
                        @endif
                    </section>
                @empty
                    <div class="text-center py-20">
                        <p class="text-zinc-500">No content available.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.swiper').forEach(container => {
                    const slug = container.dataset.slug || null;
                    if (!container.querySelector('.swiper-wrapper')) return;

                    new Swiper(container, {
                        slidesPerView: 2,
                        spaceBetween: 16,
                        lazy: true,
                        breakpoints: {
                            640: { slidesPerView: 3, spaceBetween: 16 },
                            768: { slidesPerView: 4, spaceBetween: 20 },
                            1024: { slidesPerView: 5, spaceBetween: 20 },
                            1280: { slidesPerView: 6, spaceBetween: 24 },
                            1536: { slidesPerView: 7, spaceBetween: 24 },
                        }
                    });
                });
                new Swiper('.swiper-trending', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    effect: 'fade', // or 'slide'
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    fadeEffect: { crossFade: true },
                    speed: 1000,
                });
            });
        </script>
    @endpush
</x-layout>