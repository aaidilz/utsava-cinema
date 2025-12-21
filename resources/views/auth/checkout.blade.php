<x-layout title="Checkout">
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="max-w-6xl mx-auto py-12 px-6">
            <h2 class="text-2xl font-bold mb-8">Checkout</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Form -->
                <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl shadow border border-white/6">
                    <h3 class="font-semibold mb-4">Detail Pembayaran</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Nama Lengkap</label>
                            <input type="text" class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium">Email</label>
                            <input type="email" class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium">Metode Pembayaran</label>
                            <select class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white">
                                <option>Transfer Bank</option>
                                <option>E-Wallet</option>
                                <option>Kartu Kredit</option>
                            </select>
                        </div>

                        <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>

                <!-- Summary -->
                <div class="bg-white/3 p-6 rounded-xl shadow border border-white/6">
                    <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>
                    <p>Paket: <strong class="capitalize text-white">{{ $plan }}</strong></p>
                    <p>Harga: Rp50.000</p>
                    <hr class="my-4 border-white/10">
                    <p class="text-lg font-bold">Total: Rp50.000</p>
                </div>
            </div>
        </div>
    </main>

    </main>

</x-layout>
