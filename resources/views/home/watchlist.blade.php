<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Watchlist - Utsava Cinema</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Instrument Sans', sans-serif; background-color: #111; color: white; }
            .user-layout { display: flex; min-height: 100vh; }
            .sidebar { width: 250px; background-color: #1f2937; padding-top: 2rem; }
            .content-area { flex-grow: 1; padding: 2rem; background-color: #111827; }
        </style>
    @endif
</head>
<body class="bg-gray-900 text-white">

    <header class="h-16 bg-gray-800 shadow-lg flex items-center justify-between px-6 border-b border-red-900">
        <div class="text-xl font-bold text-red-600">Utsava Cinema</div>
        <div class="flex items-center space-x-4">
            <input type="text" placeholder="Search movies..." class="px-3 py-2 text-sm bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:border-red-600">
            <i class="fas fa-bell text-gray-400 hover:text-red-500 cursor-pointer"></i>
            <i class="fas fa-user-circle text-gray-400 hover:text-red-500 cursor-pointer"></i>
        </div>
    </header>

    <div class="user-layout flex min-h-[calc(100vh-4rem)]">

        <div class="sidebar w-64 bg-gray-800 p-4 shadow-xl border-r border-red-900/50">
            <h2 class="text-white text-lg font-bold mb-4">USER MENU</h2>
            <a href="#" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-home"></i> <span>Home</span>
            </a>
            <a href="#" class="nav-item flex items-center space-x-3 p-3 mt-2 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-history"></i> <span>History</span>
            </a>
            <a href="#" class="nav-item flex items-center space-x-3 p-3 mt-2 rounded-lg bg-red-600 text-white font-semibold transition duration-200">
                <i class="fas fa-list-alt"></i> <span>Watchlist</span>
            </a>
        </div>

        <div class="content-area flex-grow p-8 bg-gray-900">
            <h1 class="text-3xl font-bold mb-8 border-b pb-4 border-gray-700 text-red-600">
                <i class="fas fa-list-alt mr-2"></i> MY WATCHLIST (7 Movies)
            </h1>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">

                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden hover:shadow-red-600/30 transition duration-300 group">
                    <img src="placeholder_movie1.jpg" alt="The Scarlet Code" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">The Scarlet Code</h4>
                        <p class="text-xs text-gray-400 mb-2">2024 • Action</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden group">
                    <img src="placeholder_movie2.jpg" alt="Shadow Protocol" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">Shadow Protocol</h4>
                        <p class="text-xs text-gray-400 mb-2">2023 • Sci-Fi</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden group">
                    <img src="placeholder_movie3.jpg" alt="Cosmic Symphony" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">Cosmic Symphony</h4>
                        <p class="text-xs text-gray-400 mb-2">2023 • Adventure</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden group">
                    <img src="placeholder_movie4.jpg" alt="The White" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">The White</h4>
                        <p class="text-xs text-gray-400 mb-2">2024 • Drama</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden group">
                    <img src="placeholder_movie5.jpg" alt="Mowo'l Scade" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">Mowo'l Scade</h4>
                        <p class="text-xs text-gray-400 mb-2">2024 • Mystery</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden group">
                    <img src="placeholder_movie6.jpg" alt="Dovie-cade" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">Dovie-cade</h4>
                        <p class="text-xs text-gray-400 mb-2">2023 • Comedy</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="movie-card bg-gray-800 rounded-lg shadow-xl overflow-hidden group">
                    <img src="placeholder_movie7.jpg" alt="Faormula" class="w-full h-auto object-cover rounded-t-lg">
                    <div class="p-3 text-center">
                        <h4 class="text-sm font-semibold truncate text-white">Faormula</h4>
                        <p class="text-xs text-gray-400 mb-2">2024 • Sci-Fi</p>
                        <div class="flex justify-around items-center space-x-2">
                            <button title="Play Movie" class="text-red-600 hover:text-red-400 transition duration-150">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </button>
                            <button title="Remove from Watchlist" class="text-gray-400 hover:text-red-600 transition duration-150">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>