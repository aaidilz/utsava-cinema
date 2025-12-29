@php
  $navAvatar = Auth::user()?->avatar ? asset('storage/' . Auth::user()->avatar) : null;
  $userInitial = Auth::check() ? substr(Auth::user()->name, 0, 1) : '';
@endphp

<!-- Desktop Navbar -->
<header
  class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-[#0d0d0f]/80 backdrop-blur-md border-b border-white/5">
  <div class="container mx-auto max-w-7xl px-4 md:px-6 h-16 flex items-center justify-between">

    <!-- Logo -->
    <a href="{{ route('home') }}"
      class="text-xl font-bold tracking-tighter italic bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
      ANIMETION
    </a>

    <!-- Desktop Menu -->
    <nav class="hidden md:flex items-center gap-8">
      <a href="{{ route('home') }}"
        class="text-sm font-medium transition-colors hover:text-white {{ request()->routeIs('home') ? 'text-white' : 'text-zinc-400' }}">
        Home
      </a>
      <a href="{{ route('anime.index') }}"
        class="text-sm font-medium transition-colors hover:text-white {{ request()->routeIs('anime.*') ? 'text-white' : 'text-zinc-400' }}">
        Browse
      </a>
      <!-- <div class="text-sm font-medium text-zinc-400 cursor-not-allowed opacity-50">
        Trending
      </div> -->
      @auth
        <a href="{{ route('watchlist') }}"
          class="text-sm font-medium transition-colors hover:text-white {{ request()->routeIs('watchlist') ? 'text-white' : 'text-zinc-400' }}">
          My List
        </a>
      @endauth
    </nav>

    <!-- Right Side -->
    <div class="flex items-center gap-4">
      <!-- Search Icon (Mobile/Tablet) -->
      <a href="{{ route('anime.index') }}" class="md:hidden text-zinc-400 hover:text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </a>

      @auth
        <!-- Notification -->
        {{-- <button class="relative text-zinc-400 hover:text-white transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-indigo-500 rounded-full"></span>
        </button> --}}

        <!-- User Dropdown -->
        <div class="relative group">
          <div
            class="w-8 h-8 rounded-full bg-zinc-800 border border-zinc-700 overflow-hidden cursor-pointer hover:border-zinc-500 transition-colors">
            @if($navAvatar)
              <img src="{{ $navAvatar }}" alt="Avatar" class="w-full h-full object-cover">
            @else
              <div
                class="w-full h-full flex items-center justify-center text-xs font-bold text-zinc-400 group-hover:text-white">
                {{ $userInitial }}
              </div>
            @endif
          </div>

          <!-- Dropdown -->
          <div
            class="absolute right-0 mt-2 w-48 bg-[#0d0d0f] border border-zinc-800 rounded-xl shadow-2xl py-1 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 transform origin-top-right z-50">
            <div class="px-4 py-3 border-b border-zinc-900">
              <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
              <p class="text-[10px] text-zinc-500 truncate">{{ Auth::user()->email }}</p>
              @if(Auth::user()->is_premium)
                <div
                  class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/20">
                  <svg class="w-3 h-3 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Premium</span>
                </div>
              @else
                <a href="{{ route('pages.pricing') }}"
                  class="mt-2 block text-center text-[10px] font-bold bg-white text-black px-2 py-1 rounded-full hover:bg-zinc-200 transition-colors">
                  Upgrade to Premium
                </a>
              @endif
            </div>

            @if(Auth::user()->isAdmin())
              <a href="{{ route('dashboard') }}"
                class="block px-4 py-2 text-xs font-medium text-zinc-400 hover:text-white hover:bg-zinc-900">
                Admin Dashboard
              </a>
            @endif

            <a href="{{ route('auth.profile') }}"
              class="block px-4 py-2 text-xs font-medium text-zinc-400 hover:text-white hover:bg-zinc-900">
              Profile & Settings
            </a>

            <a href="{{ route('watchlist') }}"
              class="block px-4 py-2 text-xs font-medium text-zinc-400 hover:text-white hover:bg-zinc-900">
              Watchlist
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
      @else
        <a href="{{ route('login') }}"
          class="text-xs font-bold text-zinc-400 hover:text-white transition-colors uppercase tracking-wider">
          Login
        </a>
        <a href="{{ route('register') }}"
          class="hidden md:block px-5 py-2 rounded-full bg-white text-black text-xs font-bold uppercase tracking-wider hover:bg-zinc-200 transition-colors">
          Get Started
        </a>
      @endauth
    </div>
  </div>
</header>

<!-- Mobile Bottom Navigation -->
<div
  class="fixed bottom-0 left-0 w-full h-16 bg-[#0d0d0f]/90 backdrop-blur-xl border-t border-zinc-800 lg:hidden flex items-center justify-around z-50">
  <a href="{{ route('home') }}"
    class="p-2 flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-indigo-400' : 'text-zinc-600' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span class="text-[10px] font-medium">Home</span>
  </a>
  <a href="{{ route('anime.index') }}"
    class="p-2 flex flex-col items-center gap-1 {{ request()->routeIs('anime.*') ? 'text-indigo-400' : 'text-zinc-600' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
    <span class="text-[10px] font-medium">Browse</span>
  </a>
  @auth
    <a href="{{ route('watchlist') }}"
      class="p-2 flex flex-col items-center gap-1 {{ request()->routeIs('watchlist') ? 'text-indigo-400' : 'text-zinc-600' }}">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
      </svg>
      <span class="text-[10px] font-medium">Saved</span>
    </a>
  @endauth
  <a href="{{ Auth::check() ? route('auth.profile') : route('login') }}"
    class="p-2 flex flex-col items-center gap-1 {{ request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('auth.profile') ? 'text-white' : 'text-zinc-600' }}">
    @auth
      <div class="w-6 h-6 rounded-full bg-zinc-800 overflow-hidden">
        @if($navAvatar)
          <img src="{{ $navAvatar }}" class="w-full h-full object-cover">
        @else
          <div class="flex items-center justify-center w-full h-full text-[10px] text-white">{{ $userInitial }}</div>
        @endif
      </div>
    @else
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
    @endauth
    <span class="text-[10px] font-medium">{{ Auth::check() ? 'Profile' : 'Account' }}</span>
  </a>
</div>