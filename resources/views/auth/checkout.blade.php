<x-layout title="Checkout">
    <main class="flex-1 container mx-auto max-w-7xl p-4 md:p-6 text-white min-h-screen">
        <div class="max-w-6xl mx-auto py-12 px-6">
            <h2 class="text-2xl font-bold mb-8">Checkout</h2>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Form -->
                <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl shadow border border-white/6">
                    <h3 class="font-semibold mb-4">Detail Pembayaran</h3>

                    <form id="paymentForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium">Nama Lengkap</label>
                            <input type="text" value="{{ auth()->user()?->name }}" readonly class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium">Email</label>
                            <input type="email" value="{{ auth()->user()?->email }}" readonly class="w-full border rounded-lg px-3 py-2 mt-1 bg-white/5 text-white">
                        </div>

                        <button id="payButton" type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                            Bayar Sekarang
                        </button>
                    </form>

                    <p id="paymentError" class="hidden mt-4 text-sm text-red-200"></p>
                </div>

                <!-- Summary -->
                <div class="bg-white/3 p-6 rounded-xl shadow border border-white/6">
                    <h3 class="font-semibold mb-4">Ringkasan Pesanan</h3>
                    <p>Paket: <strong class="capitalize text-white">{{ $subscription->name }}</strong></p>
                    <p>Durasi: {{ (int) $subscription->duration_days }} hari</p>
                    <p>Harga: Rp{{ number_format((float) $subscription->price, 0, ',', '.') }}</p>
                    <hr class="my-4 border-white/10">
                    <p class="text-lg font-bold">Total: Rp{{ number_format((float) $subscription->price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </main>

    @php
        $snapUrl = (bool) config('services.midtrans.is_production', false)
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp

    @push('scripts')
        <script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            (function () {
                const form = document.getElementById('paymentForm');
                const payButton = document.getElementById('payButton');
                const errorEl = document.getElementById('paymentError');

                const showError = (message) => {
                    errorEl.textContent = message;
                    errorEl.classList.remove('hidden');
                };

                const clearError = () => {
                    errorEl.textContent = '';
                    errorEl.classList.add('hidden');
                };

                if (!form || !payButton || !errorEl) {
                    return;
                }

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    clearError();

                    if (!"{{ (string) config('services.midtrans.client_key') }}") {
                        showError('MIDTRANS_CLIENT_KEY belum di-set di environment.');
                        return;
                    }

                    if (!window.snap) {
                        showError('Midtrans Snap belum siap (snap.js tidak ter-load).');
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrfToken) {
                        showError('CSRF token tidak ditemukan.');
                        return;
                    }

                    payButton.disabled = true;
                    payButton.classList.add('opacity-80');

                    try {
                        const resp = await fetch("{{ route('payments.initiate') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                subscription_id: "{{ $subscription->id }}",
                            }),
                        });

                        const data = await resp.json().catch(() => null);
                        if (!resp.ok) {
                            const msg = data?.message || 'Gagal memulai pembayaran.';
                            showError(msg);
                            return;
                        }

                        const snapToken = data?.transaction?.snap_token;
                        const transactionId = data?.transaction?.id;
                        if (!snapToken) {
                            showError('Snap token tidak tersedia.');
                            return;
                        }

                        const refreshStatus = async () => {
                            if (!transactionId) {
                                return;
                            }

                            try {
                                const r = await fetch(`/payments/${transactionId}/refresh`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                });

                                const body = await r.json().catch(() => null);
                                return body;
                            } catch (_) {
                                // ignore
                            }
                        };

                        const cancelTransaction = async () => {
                            if (!transactionId) {
                                return;
                            }

                            try {
                                await fetch(`/payments/${transactionId}/cancel`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                });
                            } catch (_) {
                                // ignore
                            }
                        };

                        window.snap.pay(snapToken, {
                            onSuccess: function () {
                                refreshStatus().then((body) => {
                                    const status = body?.transaction?.status;
                                    if (status === 'success') {
                                        window.location.href = "{{ route('home') }}";
                                        return;
                                    }

                                    window.location.reload();
                                }).catch(() => window.location.reload());
                            },
                            onPending: function () {
                                refreshStatus().finally(() => window.location.reload());
                            },
                            onError: function () {
                                cancelTransaction().finally(() => {
                                    showError('Pembayaran gagal. Silakan coba lagi.');
                                });
                            },
                            onClose: function () {
                                cancelTransaction().finally(() => {
                                    showError('Pembayaran dibatalkan karena kamu keluar dari halaman pembayaran.');
                                });
                            },
                        });
                    } catch (err) {
                        showError('Terjadi error jaringan.');
                    } finally {
                        payButton.disabled = false;
                        payButton.classList.remove('opacity-80');
                    }
                });
            })();
        </script>
    @endpush
</x-layout>
