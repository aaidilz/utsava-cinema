<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Anime Platform Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS v4 Browser -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#1f1f1f] text-white flex flex-col">
        <div class="p-5 text-2xl font-bold border-b border-white/10">
            🎬 Anime Admin
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded bg-[#c7c4f3] text-black font-semibold">
                Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded hover:bg-white/10">
                Users
            </a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-white/10">
                Anime
            </a>
            <a href="#" class="block px-4 py-2 rounded hover:bg-white/10">
                Transaksi
            </a>
        </nav>

        <div class="p-4 border-t border-white/10 text-sm text-gray-400">
            © 2025 Anime Platform
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 space-y-8">

        <!-- HEADER -->
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-gray-500">Ringkasan aktivitas platform anime</p>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total Anime</p>
                <p class="text-3xl font-bold">1,248</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-3xl font-bold">5,342</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">Pendapatan</p>
                <p class="text-3xl font-bold text-green-600">Rp 128.000.000</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-sm text-gray-500">User Premium</p>
                <p class="text-3xl font-bold text-indigo-600">1,024</p>
            </div>
        </div>

        <!-- GRAPH & USER TABLE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

       <!-- USER TABLE -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Daftar User</h2>
        <span class="text-sm text-gray-500">Total: {{ $users->total() ?? 0 }}</span>
    </div>

    <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Subscription</th>
                <th class="px-4 py-3 text-left">Billing</th>
                <th class="px-4 py-3 text-left">Sisa Waktu</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @foreach($users ?? collect() as $user)
                @php
                    $lastTx = $user->transactions->first() ?? null;
                    $billing = $lastTx
                        ? ucfirst($lastTx->status) . ($lastTx->paid_at ? ' • '.$lastTx->paid_at->format('d M Y') : '')
                        : 'No Transaction';

                    $remaining = ($user->premium_until && $user->is_premium)
                        ? \Carbon\Carbon::now()->diffForHumans($user->premium_until, ['short' => true])
                        : '-';

                    $subLbl = $user->is_premium && optional($user->premium_until)->isFuture()
                        ? '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Premium</span>'
                        : '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Free</span>';
                @endphp

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $user->name }}
                    </td>

                    <td class="px-4 py-3 text-gray-500">
                        {{ $user->email }}
                    </td>

                    <td class="px-4 py-3">
                        {!! $subLbl !!}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $billing }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $remaining }}
                    </td>

                    <td class="px-4 py-3">
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="px-3 py-1 text-xs rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100">
                                   Detail
                                </a>

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="px-3 py-1 text-xs rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100">
                                   Edit
                                </a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="px-3 py-1 text-xs rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 italic">
                                Login to manage
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $users->links() ?? '' }}
    </div>
</div>

    </main>

</div>

</body>
</html>