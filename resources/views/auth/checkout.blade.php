<x-layout title="Checkout">
    <main class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 text-white">
        <div class="container mx-auto max-w-7xl px-4 py-12">

            <!-- Title -->
            <h2 class="text-3xl font-bold mb-10">Checkout</h2>

            <div class="grid gap-8 md:grid-cols-2">

                <!-- FORM PEMBAYARAN -->
                <div class="rounded-2xl bg-white/10 backdrop-blur-md p-6 shadow-lg border border-white/10">
                    <h3 class="text-lg font-semibold mb-6">Detail Pembayaran</h3>

                    <form class="space-y-5">
                        <!-- Nama -->
                        <div>
                            <label class="block text-sm mb-1 text-white/80">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                class="w-full rounded-lg bg-white/5 border border-white/10 px-4 py-2 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Masukkan nama lengkap"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm mb-1 text-white/80">
                                Email
                            </label>
                            <input
                                type="email"
                                class="w-full rounded-lg bg-white/5 border border-white/10 px-4 py-2 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="email@example.com"
                            >
                        </div>

                        <!-- Metode -->
                        <div>
                            <label class="block text-sm mb-1 text-white/80">
                                Metode Pembayaran
                            </label>
                            <select
                                class="w-full rounded-lg bg-white/5 border border-white/10 px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                <option class="bg-slate-800">Transfer Bank</option>
                                <option class="bg-slate-800">E-Wallet</option>
                                <option class="bg-slate-800">Kartu Kredit</option>
                            </select>
                        </div>

                        <!-- Button -->
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-green-600 py-3 font-semibold hover:bg-green-700 transition"
                        >
                            Bayar Sekarang
                        </button>
                    </form>
                </div>

                <!-- RINGKASAN -->
                <div class="rounded-2xl bg-white/5 p-6 shadow-lg border border-white/10">
                    <h3 class="text-lg font-semibold mb-6">Ringkasan Pesanan</h3>

                    <div class="space-y-3 text-white/90">
                        <div class="flex justify-between">
                            <span>Paket</span>
                            <span class="capitalize font-medium">
                                {{ $plan }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Harga</span>
                            <span>Rp50.000</span>
                        </div>
                    </div>

                    <hr class="my-5 border-white/10">

                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>Rp50.000</span>
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-layout>
