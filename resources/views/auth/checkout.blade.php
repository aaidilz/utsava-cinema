@extends('layouts.app')

@section( 'content')
<div class="max-w-6xl mx-auto py-12 px-6">
    <h2 class="text-2xl font-bold mb-8">Checkout</h2>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Form -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Detail Pembayaran</h3>

            <form>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Nama Lengkap</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" class="w-full border rounded-lg px-3 py-2 mt-1">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium">Metode Pembayaran</label>
                    <select class="w-full border rounded-lg px-3 py-2 mt-1">
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
        <div class="bg-gray-50 p-6 rounded-xl shadow">
            <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>
            <p>Paket: <strong class="capitalize">{{ $plan }}</strong></p>
            <p>Harga: Rp50.000</p>
            <hr class="my-4">
            <p class="text-lg font-bold">Total: Rp50.000</p>
        </div>
    </div>
</div>
@endsection
