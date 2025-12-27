
    <!-- TOP NAVBAR -->
    <header class="h-18 bg-[#1f1a45] px-6 flex items-center justify-between">

      <!-- BRAND -->
      <div class="text-lg font-bold tracking-wide">
        <a href="{{ route('home') }}" class="hover:text-[#f2f1ff] transition-colors">
          Animetion
        </a>
      </div>

      <!-- NAV MENU (ANIME ONLY) -->
      <nav class="flex items-center gap-6 text-sm text-[#c7c4f3]">

        <!-- Home -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-[#352c6a] text-[#f2f1ff]' : 'hover:text-[#f2f1ff]' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 9.75L12 3l9 6.75V21H3V9.75z"/>
          </svg>
          Home
        </a>

        <!-- Trending Anime -->
        <div class="flex items-center gap-2 hover:text-[#f2f1ff] cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 17l6-6 4 4 8-8"/>
          </svg>
          Trending Anime
        </div>

        <!-- Anime List -->
        <a href="{{ route('anime.index') }}" class="flex items-center gap-2 hover:text-[#f2f1ff]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          Anime List
        </a>

        <!-- My List / Favorites (Only for authenticated users) -->
        @auth
        <a href="{{ route('watchlist') }}" class="flex items-center gap-2 hover:text-[#f2f1ff]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 21l-7-7a5 5 0 017-7 5 5 0 017 7l-7 7z"/>
          </svg>
          My List
        </a>
        @endauth

      </nav>

      <!-- RIGHT ICONS -->
      <div class="flex items-center gap-4">

        <!-- SEARCH -->
        <form action="{{ route('anime.search') }}" method="GET" class="flex items-center bg-[#352c6a] px-3 py-2 rounded-lg gap-2 w-55">
          <svg class="w-4 h-4 text-[#c7c4f3]" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
          </svg>
          <input
            type="text"
            name="query"
            value="{{ request('query') }}"
            placeholder="Search anime..."
            class="bg-transparent outline-none text-sm w-full placeholder:text-[#a3a0d9]"
          />
        </form>

        @auth
        <!-- NOTIFICATION (Only for authenticated users) -->
        <div class="relative cursor-pointer">
          <svg class="w-5 h-5 text-[#c7c4f3]" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.857 17.082a23.848 23.848 0 01-5.714 0M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9z"/>
          </svg>
          <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full px-1">3</span>
        </div>

        <!-- PROFILE DROPDOWN -->
        <div class="relative group">
          @php
            $navAvatar = Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : null;
          @endphp
          <div class="w-9 h-9 rounded-full bg-[#8b7cf6] overflow-hidden flex items-center justify-center cursor-pointer">
            @if($navAvatar)
              <img src="{{ $navAvatar }}" alt="Avatar" class="w-full h-full object-cover" />
            @else
              <span class="text-white font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
            @endif
          </div>
          
          <div class="absolute right-0 mt-2 w-48 bg-[#352c6a] rounded-lg shadow-lg py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 z-50">
    
    <!-- USER INFO -->
    <div class="px-4 py-2 border-b border-[#4a3f7a]">
        <p class="text-sm font-semibold text-[#f2f1ff]">{{ Auth::user()->name }}</p>
        <p class="text-xs text-[#c7c4f3]">{{ Auth::user()->email }}</p>
        @if(Auth::user()->isAdmin())
            <span class="inline-block mt-1 px-2 py-0.5 text-[10px] bg-red-600 text-white rounded-full">
                Admin
            </span>
        @endif
    </div>

   
            <a href="{{ route('auth.settings') }}"
              class="block px-4 py-2 text-sm text-[#c7c4f3] hover:bg-[#4a3f7a] hover:text-[#f2f1ff]">
              Pengaturan Akun
            </a>

            @if(Auth::user()->isAdmin())
                <a href="{{ route('dashboard') }}"
                  class="block px-4 py-2 text-sm text-[#c7c4f3] hover:bg-[#4a3f7a] hover:text-[#f2f1ff]">
                  <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
            @endif

            <a href="{{ route('watchlist') }}"
              class="block px-4 py-2 text-sm text-[#c7c4f3] hover:bg-[#4a3f7a] hover:text-[#f2f1ff]">
              <i class="fas fa-heart mr-2"></i>My Watchlist
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 text-sm text-[#c7c4f3] hover:bg-[#4a3f7a] hover:text-[#f2f1ff]">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>

        @else
        <!-- AUTH BUTTONS (Only for guests) -->
        <div class="flex items-center gap-3">
          <a href="{{ route('login') }}" class="px-4 py-2 text-sm text-[#c7c4f3] hover:text-[#f2f1ff] transition-colors">
            Login
          </a>
          <a href="{{ route('register') }}" class="px-4 py-2 text-sm bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white rounded-lg transition-colors">
            Register
          </a>
        </div>
        @endauth
      </div>

    </header>