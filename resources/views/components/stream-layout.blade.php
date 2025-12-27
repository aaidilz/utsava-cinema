<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Watch' }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-[#0d0d0f] text-white selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <x-navbar />

        <!-- Main Content (Full width/height for immersive feel) -->
        <main class="flex-1 w-full max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left/Main Column: Player, Title, Related -->
                <div class="lg:col-span-8 xl:col-span-9 flex flex-col gap-6">
                    <!-- Player Section -->
                    <div id="player-container"
                        class="w-full bg-[#1a1a20] rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/5">
                        {{ $player }}
                    </div>

                    <!-- Video Details -->
                    <div id="video-details" class="bg-[#1a1a20] rounded-2xl p-6 ring-1 ring-white/5">
                        {{ $details }}
                    </div>

                    <!-- Related Anime -->
                    <div class="mt-4">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                            You Might Also Like
                        </h3>
                        {{ $related ?? '' }}
                    </div>
                </div>

                <!-- Right Column: Playlist/Chat -->
                <div class="lg:col-span-4 xl:col-span-3 flex flex-col gap-6">
                    <div
                        class="bg-[#1a1a20] rounded-2xl overflow-hidden ring-1 ring-white/5 h-[80vh] sticky top-24 flex flex-col">
                        <div class="p-4 border-b border-white/5 bg-[#23232a]">
                            <h3 class="font-bold text-lg">Episodes</h3>
                        </div>
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
                            {{ $playlist }}
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Mobile Bottom Nav Spacer -->
        <div class="h-20 lg:hidden"></div>
    </div>

    @stack('scripts')
</body>

</html>