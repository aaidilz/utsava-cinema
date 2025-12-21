@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">
    <h2 class="text-3xl font-bold text-center mb-10">Pilih paket berlangganan</h2>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Basic -->
        <div class="border rounded-xl shadow hover:shadow-lg transition">
            <div class="p-6 text-center">
                <h3 class="text-xl font-semibold">basic</h3>
                <p class="text-4xl font-bold my-4">Rp50K</p>
                <p class="text-gray-500">per bulan</p>

                <ul class="my-6 space-y-2 text-gray-600">
                    <li>✔ 5 anime </li>
                    <li>✔ 10 GB Storage</li>
                    <li>✖ Support Premium</li>
                </ul>

                <a href="{{ route('checkout','basic') }}"
                   class="inline-block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Pilih Paket
                </a>
            </div>
        </div>

        <!-- Pro -->
        <div class="border-2 border-blue-600 rounded-xl shadow-lg">
            <div class="p-6 text-center">
                <h3 class="text-xl font-semibold">Pro</h3>
                <p class="text-4xl font-bold my-4">Rp100K</p>
                <p class="text-gray-500">per bulan</p>

                <ul class="my-6 space-y-2 text-gray-600">
                    <li>✔ Unlimited Project</li>
                    <li>✔ 50 GB Storage</li>
                    <li>✔ Support Premium</li>
                </ul>

                <a href="{{ route('checkout','pro') }}"
                   class="inline-block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Pilih Paket
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
