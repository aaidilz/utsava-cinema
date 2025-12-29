<x-layout title="Secure Checkout">
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#0d0d0f] text-white">
        <!-- Background Effects -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-violet-600/10 blur-[100px] rounded-full pointer-events-none">
        </div>

        <main class="relative z-10 w-full max-w-5xl mx-auto px-6 py-12">
            <div
                class="grid md:grid-cols-5 gap-8 bg-zinc-900/50 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">

                <!-- Order Summary -->
                <div
                    class="md:col-span-2 bg-gradient-to-br from-indigo-900/20 to-violet-900/20 p-8 border-r border-white/5 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xs font-bold tracking-widest text-indigo-400 uppercase mb-8">Order Summary</h2>
                        <div>
                            <p class="text-sm text-zinc-400 mb-1">Selected Plan</p>
                            <h3 class="text-2xl font-bold capitalize text-white mb-4">{{ $subscription->name }}</h3>

                            <div class="space-y-3 mb-8">
                                <div class="flex justify-between text-sm">
                                    <span class="text-zinc-400">Duration</span>
                                    <span class="font-medium">{{ (int) $subscription->duration_days }} Days</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-zinc-400">Price</span>
                                    <span
                                        class="font-medium">Rp{{ number_format((float) $subscription->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-zinc-400">Tax</span>
                                    <span class="font-medium">Rp0</span>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-white/10">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-sm text-zinc-300">Total Due</span>
                                    <span class="text-3xl font-black text-white">
                                        Rp{{ number_format((float) $subscription->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-xs text-zinc-500">
                        <p>By processing payment, you agree to our Terms of Service.</p>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="md:col-span-3 p-8 md:p-12">
                    <h2 class="text-2xl font-bold mb-6">Complete Payment</h2>

                    <form id="paymentForm" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 uppercase mb-2">Account Name</label>
                            <div class="relative">
                                <input type="text" value="{{ auth()->user()?->name }}" readonly
                                    class="w-full bg-zinc-900/50 border border-zinc-700 rounded-xl px-4 py-3 text-zinc-300 focus:outline-none focus:border-indigo-500 transition-colors cursor-not-allowed">
                                <svg class="w-5 h-5 absolute right-4 top-3.5 text-green-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-400 uppercase mb-2">Email Address</label>
                            <div class="relative">
                                <input type="email" value="{{ auth()->user()?->email }}" readonly
                                    class="w-full bg-zinc-900/50 border border-zinc-700 rounded-xl px-4 py-3 text-zinc-300 focus:outline-none focus:border-indigo-500 transition-colors cursor-not-allowed">
                                <svg class="w-5 h-5 absolute right-4 top-3.5 text-green-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button id="payButton" type="submit"
                                class="w-full relative group overflow-hidden bg-white text-black font-bold py-4 rounded-xl transition-all hover:bg-zinc-200">
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    <span>Proceed to Details</span>
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>

                    <div id="paymentError"
                        class="hidden mt-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-200 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="errorMessage"></span>
                    </div>
                </div>
            </div>
        </main>
    </div>

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
                const errorMessageEl = document.getElementById('errorMessage');

                const showError = (message) => {
                    errorMessageEl.textContent = message;
                    errorEl.classList.remove('hidden');
                };

                const clearError = () => {
                    errorMessageEl.textContent = '';
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
                        showError('Payment gateway is initializing. Please try again in a moment.');
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrfToken) {
                        showError('Security token invalid. Please refresh the page.');
                        return;
                    }

                    const originalBtnContent = payButton.innerHTML;
                    payButton.disabled = true;
                    payButton.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...</span>';
                    payButton.classList.add('opacity-70', 'cursor-not-allowed');

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
                            throw new Error(data?.message || 'Failed to initiate payment.');
                        }

                        const snapToken = data?.transaction?.snap_token;
                        const transactionId = data?.transaction?.id;

                        if (!snapToken) {
                            throw new Error('Invalid payment configuration from server.');
                        }

                        // Helper to check status
                        const refreshStatus = async () => {
                            if (!transactionId) return;
                            try {
                                const r = await fetch(`/payments/${transactionId}/refresh`, {
                                    method: 'POST',
                                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                });
                                return await r.json().catch(() => null);
                            } catch (_) { return null; }
                        };

                        // Helper to cancel
                        const cancelTransaction = async () => {
                            if (!transactionId) return;
                            try {
                                await fetch(`/payments/${transactionId}/cancel`, {
                                    method: 'POST',
                                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                });
                            } catch (_) { /* ignore */ }
                        };

                        window.snap.pay(snapToken, {
                            onSuccess: function (result) {
                                refreshStatus().then((body) => {
                                    if (body?.transaction?.status === 'success') {
                                        window.location.href = "{{ route('home') }}";
                                    } else {
                                        window.location.reload();
                                    }
                                });
                            },
                            onPending: function (result) {
                                refreshStatus().finally(() => window.location.reload());
                            },
                            onError: function (result) {
                                cancelTransaction().finally(() => {
                                    showError('Payment failed. Please try again.');
                                });
                            },
                            onClose: function () {
                                cancelTransaction().finally(() => {
                                    showError('Payment cancelled. You can try again whenever you are ready.');
                                    payButton.disabled = false;
                                    payButton.innerHTML = originalBtnContent;
                                    payButton.classList.remove('opacity-70', 'cursor-not-allowed');
                                });
                            }
                        });
                    } catch (err) {
                        showError(err.message || 'Network error occurred.');
                        payButton.disabled = false;
                        payButton.innerHTML = originalBtnContent;
                        payButton.classList.remove('opacity-70', 'cursor-not-allowed');
                    }
                });
            })();
        </script>
    @endpush
</x-layout>