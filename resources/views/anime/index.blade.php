<x-layout title="Anime List">

    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">Browse Anime</h1>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @forelse($animes as $a)
                <x-anime-card :anime="$a" />
            @empty
                <p class="text-[#c7c4f3] col-span-full text-center">No anime found.</p>
            @endforelse

        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Handle add to watchlist
                document.addEventListener('click', function (e) {
                    const btn = e.target.closest('.add-to-watchlist');
                    if (btn) {
                        e.preventDefault();
                        const icon = btn.querySelector('i');

                        // Optimistic UI update
                        // const wasActive = icon.classList.contains('text-red-500');
                        // icon.classList.toggle('text-red-500');
                        // icon.classList.toggle('text-gray-400');

                        const data = {
                            identifier_id: btn.dataset.id,
                            anime_title: btn.dataset.title,
                            poster_path: btn.dataset.poster,
                        };

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
                                    icon.classList.remove('text-gray-400');
                                    icon.classList.add('text-red-500');
                                    // alert('Added to watchlist!');
                                } else if (data.status === 'removed') {
                                    icon.classList.remove('text-red-500');
                                    icon.classList.add('text-gray-400');
                                    // alert('Removed from watchlist!');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // Revert on error if optimistic update was used
                            });
                    }
                });
            });
        </script>
    @endpush

</x-layout>