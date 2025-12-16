<x-layout title="Home">

  <x-navbar />

  <main class="flex-1 p-6">

    <!-- TRENDING VIDEO -->
    <section class="mb-10">
      <h2 class="text-lg font-semibold mb-3">Trending Video</h2>
      <div class="bg-[#352c6a] rounded-xl h-[260px] flex items-center justify-center">
        <span class="text-[#c7c4f3]">Video Preview</span>
      </div>
    </section>

    <!-- GENRE -->
    <section class="space-y-10">
      <div>
          <h3 class="text-lg font-semibold mb-4">Romance</h3>
          <div class="grid grid-cols-5 gap-5">
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-semibold mb-4">Action</h3>
          <div class="grid grid-cols-5 gap-5">
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
            <div class="bg-[#352c6a] h-[220px] rounded-xl"></div>
          </div>
        </div>

    </section>

  </main>

  <x-footer />

</x-layout>


