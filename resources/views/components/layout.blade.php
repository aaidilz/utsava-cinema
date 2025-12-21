<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $title ?? 'Animetion' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>

<body class=" bg-linear-to-br from-[#6d5bd0] to-[#8b7cf6]">
  <div class="flex flex-col min-h-screen bg-[#2b235a] text-[#f2f1ff]">

    <x-navbar />

    <div class="flex-1">
      {{ $slot }}
    </div>

    <x-footer />

  </div>
  @stack('scripts')
  </body>
</html>
