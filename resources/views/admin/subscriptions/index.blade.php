<x-admin-layout title="Manage Subscription Packages">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Subscription Packages</h1>
                <p class="text-sm text-[#c7c4f3]">Manage subscription pricing and duration</p>
            </div>
            <a href="{{ route('admin.subscriptions.create') }}"
                class="bg-[#8b7cf6] hover:bg-[#7a6ae5] text-white px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Package
            </a>
        </div>

        @if (session('success'))
            <div
                class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-[#352c6a] border border-white/10 rounded-2xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-xs uppercase tracking-wider text-[#c7c4f3] border-b border-white/10">
                        <tr>
                            <th class="px-4 py-3 text-left">Package Name</th>
                            <th class="px-4 py-3 text-left">Price</th>
                            <th class="px-4 py-3 text-left">Duration (Days)</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($subscriptions as $sub)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-medium">{{ $sub->name }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($sub->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $sub->duration_days }} Days</td>
                                <td class="px-4 py-3">
                                    @if($sub->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.subscriptions.edit', $sub) }}"
                                            class="text-[#8b7cf6] hover:text-[#a78bfa] transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.subscriptions.destroy', $sub) }}" method="POST"
                                            onsubmit="return confirm('Do you really want to delete this package?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-[#c7c4f3]">
                                    No subscription packages available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>