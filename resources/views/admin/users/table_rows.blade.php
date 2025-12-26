@forelse($users as $user)
    @php
        $lastTx = $user->latestTransaction;
        $billing = $lastTx
            ? ucfirst((string) $lastTx->status) . ($lastTx->paid_at ? ' • ' . $lastTx->paid_at->format('d M Y') : '')
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
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-200 border border-green-500/30">Premium</span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/10 text-[#c7c4f3] border border-white/10">Free</span>
            @endif
        </td>
        <td class="px-4 py-3 text-[#c7c4f3]">{{ $billing }}</td>
        <td class="px-4 py-3 text-[#c7c4f3]">
            {{ $user->premium_until?->format('d M Y') ?? '-' }}
        </td>
        <td class="px-4 py-3 text-xs text-[#c7c4f3]">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-400 hover:text-blue-300">Detail</a>
                <span class="text-white/20">|</span>
                <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-400 hover:text-yellow-300">Edit</a>
                <span class="text-white/20">|</span>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                    onsubmit="return confirm('Are you sure?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-4 py-6 text-center text-[#c7c4f3]">Tidak ada user.</td>
    </tr>
@endforelse