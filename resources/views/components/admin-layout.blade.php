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

<body class="bg-linear-to-br from-[#6d5bd0] to-[#8b7cf6]">
  <div class="min-h-screen bg-[#2b235a] text-[#f2f1ff]">
    <header class="h-18 bg-[#1f1a45] px-6 flex items-center justify-between border-b border-white/10">
      <div class="flex items-center gap-4">
        <div class="text-lg font-bold tracking-wide">
          <a href="{{ route('dashboard') }}" class="hover:text-[#f2f1ff] transition-colors">Admin</a>
        </div>
        <span class="text-xs text-[#c7c4f3] hidden sm:inline">UTSAVA CINEMA</span>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="text-sm text-[#c7c4f3] hover:text-[#f2f1ff]">Kembali ke Home</a>

        @auth
          @php
            $adminAvatar = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : null;
          @endphp
          <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-full bg-[#8b7cf6] overflow-hidden flex items-center justify-center">
              @if($adminAvatar)
                <img src="{{ $adminAvatar }}" alt="Avatar" class="w-full h-full object-cover" />
              @else
                <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
              @endif
            </div>
            <div class="hidden md:block">
              <p class="text-sm font-semibold leading-tight">{{ auth()->user()->name }}</p>
              <p class="text-xs text-[#c7c4f3] leading-tight">{{ auth()->user()->email }}</p>
            </div>
          </div>

          <form id="admin-logout-form" method="POST" action="{{ route('logout') }}" class="hidden sm:block">
            @csrf
            <button type="button" onclick="confirmLogout('admin-logout-form')"
              class="text-sm px-3 py-2 rounded-lg bg-[#352c6a] hover:bg-white/5 text-[#f2f1ff]">Logout</button>
          </form>
        @endauth
      </div>
    </header>

    <div class="flex flex-col md:flex-row min-h-screen">
      <aside class="w-full md:w-64 bg-[#1f1a45] border-b md:border-b-0 md:border-r border-white/10 flex flex-col">
        <nav class="flex-1 p-4 space-y-2 text-sm">
          <a href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-[#352c6a] text-[#f2f1ff]' : 'text-[#c7c4f3] hover:text-[#f2f1ff] hover:bg-white/5' }}">
            Dashboard
          </a>

          <a href="{{ route('dashboard') }}#users"
            class="block px-4 py-2 rounded-lg text-[#c7c4f3] hover:text-[#f2f1ff] hover:bg-white/5">
            Users
          </a>

          @if(\Illuminate\Support\Facades\Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-[#352c6a] text-[#f2f1ff]' : 'text-[#c7c4f3] hover:text-[#f2f1ff] hover:bg-white/5' }}">
              Users
            </a>
          @endif

          <a href="{{ route('admin.subscriptions.index') }}"
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('admin.subscriptions.*') ? 'bg-[#352c6a] text-[#f2f1ff]' : 'text-[#c7c4f3] hover:text-[#f2f1ff] hover:bg-white/5' }}">
            Pricing
          </a>
        </nav>

        <div class="p-4 border-t border-white/10 text-xs text-[#c7c4f3]">
          © {{ date('Y') }} Animetion
        </div>
      </aside>

      <main class="flex-1">
        <div class="p-4 md:p-6">
          {{ $slot }}
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function confirmLogout(formId) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Sesi Admin akan berakhir.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8b7cf6', 
            cancelButtonColor: '#ef4444', 
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal',
            background: '#1f1a45', 
            color: '#f2f1ff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        })
    }
  </script>

  @stack('scripts')
</body>
</html>