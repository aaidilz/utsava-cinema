@props(['related'])

<div class="swiper related-swiper w-full">
    <div class="swiper-wrapper py-4">
        @foreach($related as $anime)
            <div class="swiper-slide !w-[160px] sm:!w-[180px] md:!w-[200px]">
                <x-anime-card :anime="$anime" />
            </div>
        @endforeach
    </div>
    <!-- Add Pagination -->
    {{-- <div class="swiper-pagination"></div> --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.related-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            freeMode: true,
            grabCursor: true,
            mousewheel: {
                forceToAxis: true,
            },
        });
    });
</script>