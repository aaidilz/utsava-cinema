<x-layout title="Premium Access">
    <div class="relative min-h-screen flex flex-col justify-center overflow-hidden bg-[#0d0d0f] text-white">
        <!-- Background Effects -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] bg-indigo-600/20 blur-[120px] rounded-full pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-violet-600/10 blur-[100px] rounded-full pointer-events-none">
        </div>

        <main class="relative z-10 w-full max-w-7xl mx-auto px-6 py-20">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-xs font-bold tracking-[0.2em] text-indigo-400 uppercase mb-4">Upgrade Experience</h2>
                <h1
                    class="text-4xl md:text-6xl font-black tracking-tight bg-gradient-to-br from-white via-white to-white/50 bg-clip-text text-transparent mb-6">
                    Unlock the Full Power of Anime
                </h1>
                <p class="text-lg text-zinc-400 leading-relaxed">
                    Nikmati streaming tanpa batas, kualitas 4K, dan akses eksklusif ke konten premium. Batalkan kapan
                    saja.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 items-start">
                @forelse(($subscriptions ?? collect()) as $subscription)
                    <div
                        class="group relative bg-zinc-900/50 backdrop-blur-xl border border-white/10 rounded-3xl p-8 hover:border-indigo-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/20 hover:-translate-y-2">

                        @if($subscription->is_featured ?? false)
                            <div
                                class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-indigo-500 to-violet-500 text-white text-[10px] font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow-lg">
                                Most Popular
                            </div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-white mb-2 capitalize">{{ $subscription->name }}</h3>
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm text-zinc-400">Rp</span>
                                <span class="text-4xl font-black tracking-tight text-white">
                                    {{ number_format((float) $subscription->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="text-sm text-zinc-500 mt-2">Per {{ (int) $subscription->duration_days }} hari</p>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-sm text-zinc-300">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Akses seluruh anime premium
                            </li>
                            <li class="flex items-center gap-3 text-sm text-zinc-300">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Kualitas streaming hingga 4K
                            </li>
                            <li class="flex items-center gap-3 text-sm text-zinc-300">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Bebas iklan selamanya
                            </li>
                        </ul>

                        <a href="{{ route('pages.checkout', ['subscription' => $subscription->id]) }}"
                            class="block w-full py-4 rounded-xl bg-white text-black font-bold text-center hover:bg-zinc-200 transition-colors">
                            Pilih Paket
                        </a>
                    </div>
                @empty
                    <div class="md:col-span-3 text-center py-20">
                        <p class="text-zinc-500">Belum ada paket tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- FAQ or Trust Badges could go here -->
            <div class="mt-20 text-center border-t border-white/5 pt-12">
                <p class="text-xs text-zinc-600 uppercase tracking-widest font-semibold mb-6">Trusted by Payment
                    Partners</p>
                <div class="flex justify-center gap-8 grayscale opacity-50">
                    <!-- Placeholder Icons for payment methods -->
                    <div class="h-8 w-20 bg-zinc-800 rounded"></div>
                    <div class="h-8 w-20 bg-zinc-800 rounded"></div>
                    <div class="h-8 w-20 bg-zinc-800 rounded"></div>
                </div>
            </div>
        </main>
    </div>
</x-layout>