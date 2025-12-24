<x-admin-layout title="Admin Dashboard">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-sm text-[#c7c4f3]">Ringkasan performa platform</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[#352c6a] border border-white/10 rounded-xl p-5">
                <p class="text-sm text-[#c7c4f3]">Total User</p>
                <p class="text-3xl font-bold">{{ number_format((int) $totalUsers) }}</p>
            </div>
            <div class="bg-[#352c6a] border border-white/10 rounded-xl p-5">
                <p class="text-sm text-[#c7c4f3]">Total Transaksi</p>
                <p class="text-3xl font-bold">{{ number_format((int) $totalTransactions) }}</p>
            </div>
            <div class="bg-[#352c6a] border border-white/10 rounded-xl p-5">
                <p class="text-sm text-[#c7c4f3]">Total Pendapatan</p>
                <p class="text-3xl font-bold">Rp {{ number_format((float) $totalRevenue, 0, ',', '.') }}</p>
                <p class="text-xs text-[#c7c4f3] mt-1">Dihitung dari transaksi berstatus success</p>
            </div>
            <div class="bg-[#352c6a] border border-white/10 rounded-xl p-5">
                <p class="text-sm text-[#c7c4f3]">User Aktif (Subscription)</p>
                <p class="text-3xl font-bold">{{ number_format((int) $activeSubscribers) }}</p>
                <p class="text-xs text-[#c7c4f3] mt-1">Premium sampai tanggal yang masih valid</p>
            </div>
        </div>

        <div id="users" class="bg-[#352c6a] border border-white/10 rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-semibold">Daftar User</h2>
                    <p class="text-xs text-[#c7c4f3]">Total: {{ $users->total() }}</p>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row gap-3">
                    <input
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama/email..."
                        class="w-full sm:w-64 border border-white/10 rounded-lg px-3 py-2 bg-[#2b235a] text-white placeholder:text-[#a3a0d9]"
                    />

                    <select
                        name="subscription"
                        class="w-full sm:w-44 border border-white/10 rounded-lg px-3 py-2 bg-[#2b235a] text-white"
                    >
                        <option value="all" {{ $subscriptionStatus === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="premium" {{ $subscriptionStatus === 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="free" {{ $subscriptionStatus === 'free' ? 'selected' : '' }}>Free</option>
                    </select>

                    <button class="bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white px-4 py-2 rounded-lg">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase tracking-wider text-[#c7c4f3] border-b border-white/10">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Subscription</th>
                            <th class="px-4 py-3 text-left">Billing Terakhir</th>
                            <th class="px-4 py-3 text-left">Premium Sampai</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($users as $user)
                            @php
                                $lastTx = $user->latestTransaction;
                                $billing = $lastTx
                                    ? ucfirst((string) $lastTx->status) . ($lastTx->paid_at ? ' • '.$lastTx->paid_at->format('d M Y') : '')
                                    : 'No Transaction';

                                $isPremiumNow = $user->premium_until && $user->premium_until->isFuture();
                            @endphp

                            <tr class="hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-medium">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#8b7cf6] flex items-center justify-center overflow-hidden">
                                            @php
                                                $rowAvatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
                                            @endphp
                                            @if($rowAvatar)
                                                <img src="{{ $rowAvatar }}" alt="Avatar" class="w-full h-full object-cover" />
                                            @else
                                                <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="leading-tight">{{ $user->name }}</div>
                                            <div class="text-xs text-[#c7c4f3] leading-tight">{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-[#c7c4f3]">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @if($isPremiumNow)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-200 border border-green-500/30">Premium</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/10 text-[#c7c4f3] border border-white/10">Free</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-[#c7c4f3]">{{ $billing }}</td>
                                <td class="px-4 py-3 text-[#c7c4f3]">
                                    {{ $user->premium_until?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-[#c7c4f3]">
                                    <a href="{{ route('admin.users.show', $user) }}" class="hover:text-white underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-[#c7c4f3]">Tidak ada user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>