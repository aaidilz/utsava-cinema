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

            <!-- GRAPH -->
            <div class="lg:col-span-1 bg-white p-5 rounded-xl shadow">
                <h2 class="font-semibold mb-4">Pertumbuhan User</h2>

                <!-- Simple bar graph -->
                <div class="flex items-end gap-3 h-40">
                    <div class="w-8 bg-indigo-400 h-[40%] rounded"></div>
                    <div class="w-8 bg-indigo-400 h-[55%] rounded"></div>
                    <div class="w-8 bg-indigo-400 h-[65%] rounded"></div>
                    <div class="w-8 bg-indigo-400 h-[80%] rounded"></div>
                    <div class="w-8 bg-indigo-400 h-[60%] rounded"></div>
                </div>

                <p class="text-xs text-gray-500 mt-2">Data user per bulan</p>
            </div>

            <!-- USER TABLE -->
            <div class="lg:col-span-2 bg-white p-5 rounded-xl shadow overflow-x-auto">
                <h2 class="font-semibold mb-4">Daftar User</h2>

                <table class="w-full text-sm">
                    <thead class="text-left text-gray-500 border-b">
                        <tr>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Status Subscription</th>
                            <th class="py-2">Status Billing</th>
                            <th class="py-2">Sisa Waktu</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($users ?? collect() as $user)
                            @php
                                $lastTx = $user->transactions->first() ?? null;
                                $billing = $lastTx ? (ucfirst($lastTx->status) . ($lastTx->paid_at ? ' • '.$lastTx->paid_at->format('Y-m-d') : '')) : 'No Tx';
                                $remaining = ($user->premium_until && $user->is_premium) ? \Carbon\Carbon::now()->diffForHumans($user->premium_until, ['parts' => 3, 'short' => true]) : '-';
                                $subLbl = $user->is_premium && optional($user->premium_until)->isFuture() ? '<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Premium</span>' : '<span class="px-2 py-1 text-xs bg-gray-200 rounded">Free</span>';
                            @endphp
                            <tr>
                                <td class="py-3">{{ $user->name }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="py-3">{!! $subLbl !!}</td>
                                <td class="py-3">{{ $billing }}</td>
                                <td class="py-3">{{ $remaining }}</td>
                                <td class="py-3">
                                    @if(auth()->check() && auth()->user()->isAdmin())
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-indigo-600 hover:underline mr-2">Detail</a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 mr-2">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600">Hapus</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="text-sm text-gray-500 italic">Login to manage</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() ?? '' }}
                </div>
            </div>
        </div>

    </main>

</div>

</body>
</html>