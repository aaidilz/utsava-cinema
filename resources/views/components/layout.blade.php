<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <title>{{ $title ?? 'Animetion' }}</title>
  @stack('styles')
</head>

<body class=" bg-linear-to-br from-[#6d5bd0] to-[#8b7cf6]">
  <div class="flex flex-col h-full bg-[#2b235a] text-[#f2f1ff]">

    {{ $slot }}

  </div>
  @stack('scripts')
  </body>
</html>
