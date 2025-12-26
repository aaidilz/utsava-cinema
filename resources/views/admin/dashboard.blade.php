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

        <div class="flex justify-end">
            <a href="{{ route('admin.reports.revenue') }}"
                class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Laporan Pendapatan
            </a>
        </div>

        <div id="users" class="bg-[#352c6a] border border-white/10 rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                <div>
                    <h2 class="text-lg font-semibold">Daftar User</h2>
                    <p class="text-xs text-[#c7c4f3]">Total: {{ $users->total() }}</p>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row gap-3">
                    <input name="q" value="{{ $search }}" placeholder="Cari nama/email..."
                        class="w-full sm:w-64 border border-white/10 rounded-lg px-3 py-2 bg-[#2b235a] text-white placeholder:text-[#a3a0d9]" />

                    <select name="subscription"
                        class="w-full sm:w-44 border border-white/10 rounded-lg px-3 py-2 bg-[#2b235a] text-white">
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

                    <tbody class="divide-y divide-white/10" id="usersTableBody">
                        @include('admin.users.table_rows', ['users' => $users])
                    </tbody>
                </table>
            </div>

            <div class="mt-6" id="paginationLinks">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.querySelector('input[name="q"]');
                const subSelect = document.querySelector('select[name="subscription"]');
                const tableBody = document.getElementById('usersTableBody');
                const paginationLinks = document.getElementById('paginationLinks');

                let timeoutId;

                function fetchUsers() {
                    const q = searchInput.value;
                    const sub = subSelect.value;
                    const url = `{{ route('dashboard') }}?q=${q}&subscription=${sub}`;

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.text())
                        .then(html => {
                            tableBody.innerHTML = html;
                            // Note: Updating pagination links via AJAX is complex if not returning full partial. 
                            // For now, let's keep it simple. Usually you'd return JSON with html and pagination html.
                            // But for "Live Search" on the current page, this is often sufficient or we reload pagination too.
                            // Since we return only table rows, pagination might get out of sync if number of pages changes.
                            // To do this properly without full reload, we should probably just reload if pagination interaction is needed
                            // OR return a JSON struct with { table: '...', pagination: '...' }.
                            // BUT, sticking to the plan: "fetch updated table rows via AJAX".
                        });
                }

                searchInput.addEventListener('input', function () {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(fetchUsers, 300);
                });

                subSelect.addEventListener('change', fetchUsers);
            });
        </script>
    @endpush
</x-admin-layout>