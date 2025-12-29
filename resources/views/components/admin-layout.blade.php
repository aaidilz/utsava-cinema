<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Admin' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>

<body class="bg-black text-white">
  <div class="min-h-screen bg-black text-white">
    <!-- Top Navbar (Admin) -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#0d0d0f]/80 backdrop-blur-md px-6 py-4 flex items-center justify-between border-b border-white/5">
      <div class="flex items-center gap-4">
        <div class="text-lg font-bold tracking-tighter">
          <a href="{{ route('dashboard') }}" class="text-white hover:text-zinc-300 transition-colors">Utsava Cinema</a>
        </div>
<<<<<<< HEAD
        <span class="text-xs text-zinc-500 hidden sm:inline">Admin Dashboard</span>
=======
        <span class="text-xs text-[#c7c4f3] hidden sm:inline">UTSAVA CINEMA</span>
>>>>>>> 20884d2fae145db66916967c367113c9bdb37c2e
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">Kembali ke Home</a>

        @auth
          @php
            $adminAvatar = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
          @endphp
          
          <!-- User Dropdown -->
          <div class="relative group">
            <div class="w-8 h-8 rounded-full bg-zinc-800 border border-zinc-700 overflow-hidden cursor-pointer hover:border-zinc-500 transition-colors flex items-center justify-center">
              @if($adminAvatar)
                <img src="{{ $adminAvatar }}" alt="Avatar" class="w-full h-full object-cover" />
              @else
                <span class="text-white font-semibold text-xs">{{ substr(auth()->user()->name, 0, 1) }}</span>
              @endif
            </div>

            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-2 w-48 bg-[#0d0d0f] border border-zinc-800 rounded-xl shadow-2xl py-1 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 transform origin-top-right">
              <div class="px-4 py-3 border-b border-zinc-900">
                <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-zinc-500 truncate">{{ auth()->user()->email }}</p>
              </div>

              <a href="{{ route('home') }}"
                class="block px-4 py-2 text-xs font-medium text-zinc-400 hover:text-white hover:bg-zinc-900">
                Back to Home
              </a>

              <div class="border-t border-zinc-900 my-1"></div>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                  class="w-full text-left px-4 py-2 text-xs font-medium text-red-500 hover:bg-zinc-900 transition-colors">
                  Sign Out
                </button>
              </form>
            </div>
          </div>
        @endauth
      </div>
    </header>

    <div class="flex flex-col md:flex-row min-h-screen pt-16">
      <!-- Sidebar (Admin) -->
      <aside class="w-full md:w-64 bg-zinc-900 border-b md:border-b-0 md:border-r border-zinc-800 flex flex-col">
        <nav class="flex-1 p-4 space-y-2 text-sm">
          <a href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-zinc-400 hover:text-white hover:bg-white/5' }} transition-colors">
            Dashboard
          </a>

          <a href="{{ route('dashboard') }}#users"
            class="block px-4 py-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/5 transition-colors">
            Users
          </a>

          @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-zinc-400 hover:text-white hover:bg-white/5' }} transition-colors">
              Users
            </a>
          @endif

          <a href="{{ route('admin.subscriptions.index') }}"
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.subscriptions.*') ? 'bg-white/10 text-white' : 'text-zinc-400 hover:text-white hover:bg-white/5' }} transition-colors">
            Pricing
          </a>
        </nav>

        <div class="p-4 border-t border-zinc-800 text-xs text-zinc-500">
          © {{ date('Y') }} Utsava Cinema
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1">
        <div class="p-4 md:p-6">
          {{ $slot }}
        </div>
      </main>
    </div>
  </div>

  @stack('scripts')
</body>

</html>