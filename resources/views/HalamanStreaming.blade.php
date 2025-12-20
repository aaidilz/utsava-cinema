<x-layout title="Home">
    <x-navbar />
  <!-- MAIN CONTENT -->
  <main class="flex-1 p-6 overflow-y-auto">

    <!-- VIDEO PLAYER -->
    <section class="mb-6">
      <div class="bg-[#352c6a] rounded-xl h-[380px] flex items-center justify-center">
        <span class="text-[#c7c4f3] text-lg">🎥 Video Player</span>
      </div>
    </section>

    <!-- INFO ANIME -->
    <section class="mb-10">
      <h1 class="text-2xl font-bold mb-2">Your Name</h1>

      <div class="flex gap-2 text-sm text-[#c7c4f3] mb-4">
        <span class="bg-[#352c6a] px-3 py-1 rounded-full">Romance</span>
        <span class="bg-[#352c6a] px-3 py-1 rounded-full">Drama</span>
        <span class="bg-[#352c6a] px-3 py-1 rounded-full">Fantasy</span>
      </div>

      <p class="text-sm text-[#c7c4f3] max-w-3xl">
        Mitsuha dan Taki mengalami fenomena misterius di mana mereka saling bertukar tubuh.
        Dari sinilah kisah romantis dan emosional mereka dimulai.
      </p>
    </section>

    <!-- EPISODE LIST -->
    <section class="mb-12">
      <h2 class="text-lg font-semibold mb-4">
        Episodes (12)
      </h2>

      <div class="grid grid-cols-4 gap-4">
        <div class="bg-[#352c6a] px-4 py-3 rounded-lg text-sm hover:bg-[#453c85] cursor-pointer">
          Episode 1
        </div>
        <div class="bg-[#352c6a] px-4 py-3 rounded-lg text-sm hover:bg-[#453c85] cursor-pointer">
          Episode 2
        </div>
        <div class="bg-[#352c6a] px-4 py-3 rounded-lg text-sm hover:bg-[#453c85] cursor-pointer">
          Episode 3
        </div>
        <div class="bg-[#352c6a] px-4 py-3 rounded-lg text-sm hover:bg-[#453c85] cursor-pointer">
          Episode 4
        </div>
      </div>
    </section>

    <!-- RECOMMENDED ANIME -->
    <section>
      <h2 class="text-lg font-semibold mb-4">Recommended Anime</h2>

      <div class="grid grid-cols-5 gap-5">
        <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
        <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
        <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
        <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
        <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
      </div>
    </section>

  </main>

</x-layout>