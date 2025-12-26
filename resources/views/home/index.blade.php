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
            <!-- Background Image -->
            <div
                class="absolute inset-0 bg-[url('https://images5.alphacoders.com/131/1317606.jpeg')] bg-cover bg-center">
            </div>

            <!-- Overlay & Gradient -->
            <div class="absolute inset-0 bg-black/40 hero-gradient"></div>

            <div class="relative container mx-auto max-w-7xl px-4 md:px-6 h-full flex items-end pb-20">
                <div class="max-w-2xl space-y-6 animate-fade-in-up">
                    <span
                        class="inline-block px-3 py-1 bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg shadow-indigo-500/30">
                        Top Trending
                    </span>
                    <h1 class="text-5xl md:text-7xl font-black italic text-white leading-[0.9] drop-shadow-2xl">
                        JUJUTSU<br>KAISEN
                    </h1>

                    <div class="flex items-center gap-4 text-sm font-medium text-zinc-300">
                        <span class="text-green-400 font-bold">98% Match</span>
                        <span>2023</span>
                        <span class="px-2 py-0.5 border border-zinc-600 rounded text-xs">TV-MA</span>
                        <span>24 Episodes</span>
                    </div>

                    <p class="text-zinc-300 line-clamp-3 md:line-clamp-2 text-sm md:text-base max-w-xl drop-shadow-md">
                        A boy swallows a cursed talisman - the finger of a demon - and becomes cursed himself. He enters
                        a shaman's school to be able to locate the demon's other body parts and thus exorcise himself.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ url('/anime/jujutsu-kaisen-s2') }}"
                            class="flex items-center gap-2 px-8 py-3 bg-white text-black font-black italic text-lg rounded-full hover:bg-zinc-200 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:scale-105 active:scale-95">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                            WATCH NOW
                        </a>
                        <button
                            class="px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full hover:bg-white/20 transition-all active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
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
                                            <a href="{{ url('/anime', $item['id'] ?? '#') }}" class="group block relative">
                                                <!-- Poster Card -->
                                                <div
                                                    class="aspect-[2/3] w-full rounded-2xl overflow-hidden bg-[#1a1a20] relative shadow-lg group-hover:-translate-y-2 group-hover:shadow-indigo-500/20 transition-all duration-300">
                                                    <img src="{{ $item['image'] ?? ($item['cover'] ?? 'https://via.placeholder.com/200x300?text=No+Image') }}"
                                                        alt="{{ $item['title'] ?? 'Anime' }}" loading="lazy"
                                                        class="w-full h-full object-cover">

                                                    <!-- Overlay Gradient -->
                                                    <div
                                                        class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-60 group-hover:opacity-80 transition-opacity">
                                                    </div>

                                                    <!-- Rating Badge -->
                                                    <div
                                                        class="absolute top-3 right-3 bg-black/60 backdrop-blur-md border border-white/10 text-white text-[10px] font-bold px-2 py-1 rounded-md flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.447a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.447a1 1 0 00-1.176 0l-3.37 2.447c-.784.57-1.839-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z" />
                                                        </svg>
                                                        {{ $item['rating_score'] ?? ($item['rating'] ?? '?') }}
                                                    </div>
                                                </div>

                                                <!-- Metadata -->
                                                <div class="mt-3 px-1">
                                                    <h4 class="text-base font-bold text-white line-clamp-1 group-hover:text-indigo-400 transition-colors"
                                                        title="{{ $item['title'] ?? '' }}">
                                                        {{ $item['title'] ?? 'Unknown' }}
                                                    </h4>
                                                    <div class="flex items-center gap-2 mt-1 text-xs text-zinc-500 font-medium">
                                                        <span>{{ $item['release_year'] ?? ($item['year'] ?? 'N/A') }}</span>
                                                        <span class="w-1 h-1 rounded-full bg-zinc-600"></span>
                                                        <span>{{ $item['total_episode'] ?? ($item['episodes'] ?? '?') }} Eps</span>
                                                    </div>
                                                </div>
                                            </a>
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