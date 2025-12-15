<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Utsava Cinema</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Instrument Sans', sans-serif; background-color: #111; color: white; }
            .admin-layout { display: flex; min-height: 100vh; }
            .sidebar { width: 250px; background-color: #1f2937; padding-top: 2rem; }
            .content-area { flex-grow: 1; padding: 2rem; background-color: #111827; }
        </style>
    @endif
</head>
<body class="bg-gray-900 text-white">

    <header class="h-16 bg-gray-800 shadow-lg flex items-center justify-between px-6 border-b border-red-900">
        <div class="text-xl font-bold text-red-600">Admin Panel</div>
        <div class="flex items-center space-x-4">
            <input type="text" placeholder="Search..." class="px-3 py-2 text-sm bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:border-red-600">
            <i class="fas fa-bell text-gray-400 hover:text-red-500 cursor-pointer"></i>
            <i class="fas fa-user-circle text-gray-400 hover:text-red-500 cursor-pointer"></i>
        </div>
    </header>

    <div class="admin-layout flex min-h-[calc(100vh-4rem)]">

        <div class="sidebar w-64 bg-gray-800 p-4 shadow-xl border-r border-red-900/50">
            <a href="#" class="nav-item flex items-center space-x-3 p-3 rounded-lg bg-red-800 text-white font-semibold transition duration-200">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item flex items-center space-x-3 p-3 mt-2 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-film"></i> <span>Movie</span>
            </a>
            <a href="#" class="nav-item flex items-center space-x-3 p-3 mt-2 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-users"></i> <span>User</span>
            </a>
            <a href="#" class="nav-item flex items-center space-x-3 p-3 mt-2 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-tags"></i> <span>Genre</span>
            </a>
        </div>

        <div class="content-area flex-grow p-8 bg-gray-900">
            <h1 class="text-3xl font-bold mb-8 border-b pb-4 border-gray-700 text-red-600">
                <i class="fas fa-tachometer-alt mr-2"></i> DASHBOARD
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                <div class="info-card bg-teal-600 p-6 rounded-xl shadow-lg relative overflow-hidden">
                    <h3 class="text-xl font-bold mb-1">Total Movies</h3>
                    <p class="text-4xl font-extrabold">256</p>
                    <a href="#" class="mt-4 inline-block text-sm font-semibold hover:underline">View Details &rarr;</a>
                    <i class="fas fa-film absolute right-[-10px] bottom-[-20px] text-5xl opacity-20 transform -rotate-12"></i>
                </div>

                <div class="info-card bg-green-600 p-6 rounded-xl shadow-lg relative overflow-hidden">
                    <h3 class="text-xl font-bold mb-1">Registered Users</h3>
                    <p class="text-4xl font-extrabold">1.5K</p>
                    <a href="#" class="mt-4 inline-block text-sm font-semibold hover:underline">View Details &rarr;</a>
                    <i class="fas fa-users absolute right-[-10px] bottom-[-20px] text-5xl opacity-20 transform -rotate-12"></i>
                </div>

                <div class="info-card bg-red-600 p-6 rounded-xl shadow-lg relative overflow-hidden">
                    <h3 class="text-xl font-bold mb-1">Available Genres</h3>
                    <p class="text-4xl font-extrabold">42</p>
                    <a href="#" class="mt-4 inline-block text-sm font-semibold hover:underline">View Details &rarr;</a>
                    <i class="fas fa-tags absolute right-[-10px] bottom-[-20px] text-5xl opacity-20 transform -rotate-12"></i>
                </div>
                
                <div class="info-card bg-indigo-600 p-6 rounded-xl shadow-lg relative overflow-hidden">
                    <h3 class="text-xl font-bold mb-1">Today's Revenue</h3>
                    <p class="text-4xl font-extrabold">$1,234</p>
                    <a href="#" class="mt-4 inline-block text-sm font-semibold hover:underline">View Report &rarr;</a>
                    <i class="fas fa-dollar-sign absolute right-[-10px] bottom-[-20px] text-5xl opacity-20 transform -rotate-12"></i>
                </div>

            </div>
            
            </div>
    </div>

</body>
</html>