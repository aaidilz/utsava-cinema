<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Animetion' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>

<body class="bg-[#0d0d0f] font-sans text-white antialiased selection:bg-indigo-500 selection:text-white">
  <div class="flex flex-col min-h-screen">

    <x-navbar />

    <div class="flex-1">
      {{ $slot }}
    </div>

    {{-- <x-footer /> --}}

    {{-- Mobile Bottom Nav Spacer --}}
    <div class="h-20 lg:hidden"></div>

  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Handle add to watchlist
      document.addEventListener('click', function (e) {
        const btn = e.target.closest('.add-to-watchlist');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        const icon = btn.querySelector('svg');

        const data = {
          identifier_id: btn.dataset.id,
          anime_title: btn.dataset.title,
          poster_path: btn.dataset.poster,
        };

        // Optimistic UI used to be here, but let's do it smarter
        const isCurrentlyAdded = icon.classList.contains('text-red-500');

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
            } else if (data.status === 'removed') {
              // Check if we are on the watchlist page
              if (window.location.pathname.includes('/watchlist')) {
                const card = btn.closest('.group');
                if (card) {
                  card.style.transition = 'all 0.3s ease-out';
                  card.style.opacity = '0';
                  card.style.transform = 'scale(0.9)';
                  setTimeout(() => {
                    card.remove();
                    // Try to update counter
                    const counters = document.querySelectorAll('h1 + span');
                    counters.forEach(c => {
                      const match = c.innerText.match(/(\d+)/);
                      if (match) {
                        let count = parseInt(match[1]);
                        if (count > 0) c.innerText = (count - 1) + ' Items';
                      }
                    });
                  }, 300);
                }
              } else {
                icon.classList.add('text-zinc-400');
                icon.classList.remove('text-red-500', 'fill-current');
              }
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
          });
      });
    });
  </script>
  @stack('scripts')
</body>

</html>