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
  @stack('scripts')
</body>

</html>