<x-layout title="Pricing">
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="max-w-7xl mx-auto py-12 px-6">
            <h2 class="text-3xl font-bold text-center mb-10">Pilih paket berlangganan</h2>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse(($subscriptions ?? collect()) as $subscription)
                    <div class="border rounded-xl shadow hover:shadow-lg transition bg-[#1f1f1f]">
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-semibold capitalize">{{ $subscription->name }}</h3>
                            <p class="text-4xl font-bold my-4">
                                Rp{{ number_format((float) $subscription->price, 0, ',', '.') }}
                            </p>
                            <p class="text-gray-400">{{ (int) $subscription->duration_days }} hari</p>

                            <a href="{{ route('pages.checkout', ['subscription' => $subscription->id]) }}"
                               class="inline-block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                Pilih Paket
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 text-center text-gray-300">
                        Paket belum tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </main>

</x-layout>