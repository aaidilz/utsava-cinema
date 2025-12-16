
    <!-- TOP NAVBAR -->
    <header class="h-18 bg-[#1f1a45] px-6 flex items-center justify-between">

      <!-- BRAND -->
      <div class="text-lg font-bold tracking-wide">
        Animetion
      </div>

      <!-- NAV MENU (ANIME ONLY) -->
      <nav class="flex items-center gap-6 text-sm text-[#c7c4f3]">

        <!-- Home -->
        <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-[#352c6a] text-[#f2f1ff]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 9.75L12 3l9 6.75V21H3V9.75z"/>
          </svg>
          Home
        </div>

        <!-- Trending Anime -->
        <div class="flex items-center gap-2 hover:text-[#f2f1ff]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 17l6-6 4 4 8-8"/>
          </svg>
          Trending Anime
        </div>

        <!-- Anime List -->
        <div class="flex items-center gap-2 hover:text-[#f2f1ff]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          Anime List
        </div>

        <!-- My List / Favorites -->
        <div class="flex items-center gap-2 hover:text-[#f2f1ff]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 21l-7-7a5 5 0 017-7 5 5 0 017 7l-7 7z"/>
          </svg>
          My List
        </div>

      </nav>

      <!-- RIGHT ICONS -->
      <div class="flex items-center gap-4">

        <!-- SEARCH -->
        <div class="flex items-center bg-[#352c6a] px-3 py-2 rounded-lg gap-2 w-55">
          <svg class="w-4 h-4 text-[#c7c4f3]" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
          </svg>
          <input
            type="text"
            placeholder="Search anime..."
            class="bg-transparent outline-none text-sm w-full placeholder:text-[#a3a0d9]"
          />
        </div>

        <!-- NOTIFICATION -->
        <div class="relative">
          <svg class="w-5 h-5 text-[#c7c4f3]" fill="none" stroke="currentColor" stroke-width="1.5"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.857 17.082a23.848 23.848 0 01-5.714 0M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9z"/>
          </svg>
          <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full px-1">3</span>
        </div>

        <!-- PROFILE -->
        <div class="w-9 h-9 rounded-full bg-[#8b7cf6]"></div>
      </div>

    </header>