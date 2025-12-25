<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\InitiatePaymentRequest;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\Billing\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class PaymentController extends Controller
{
    public function initiate(InitiatePaymentRequest $request, MidtransService $midtrans): JsonResponse
    {
        $user = $request->user();
        $subscriptionId = (string) $request->validated('subscription_id');

        $subscription = Subscription::query()
            ->whereKey($subscriptionId)
            ->where('is_active', true)
            ->firstOrFail();

        $transaction = DB::transaction(function () use ($user, $subscription, $midtrans): Transaction {
            $trx = new Transaction();
            $trx->user_id = (string) $user->id;
            $trx->subscription_id = (string) $subscription->id;
            $trx->amount = $subscription->price;
            $trx->status = 'pending';
            $trx->save();

            $trx->snap_token = $midtrans->createSnapToken($trx);
            $trx->save();

            return $trx;
        });

        return response()->json([
            'ok' => true,
            'transaction' => [
                'id' => (string) $transaction->id,
                'status' => (string) $transaction->status,
                'amount' => (string) $transaction->amount,
                'snap_token' => (string) $transaction->snap_token,
            ],
            'midtrans' => [
                'client_key' => (string) config('services.midtrans.client_key'),
                'is_production' => (bool) config('services.midtrans.is_production', false),
            ],
        ]);
    }

    /**
     * Manually refresh transaction status from Midtrans (useful in local/sandbox).
     */
    public function refresh(Request $request, Transaction $transaction, MidtransService $midtrans): JsonResponse
    {
        $user = $request->user();
        if (!$user || (string) $transaction->user_id !== (string) $user->id) {
            abort(403);
        }

        try {
            $status = $midtrans->fetchStatus((string) $transaction->id);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Gagal mengambil status dari Midtrans.',
            ], 502);
        }

        DB::transaction(function () use ($transaction, $status, $midtrans): void {
            $midtrans->applyMidtransStatus($transaction, $status);
        });

        $transaction->refresh();

        return response()->json([
            'ok' => true,
            'transaction' => [
                'id' => (string) $transaction->id,
                'status' => (string) $transaction->status,
                'paid_at' => $transaction->paid_at?->toISOString(),
                'payment_type' => (string) ($transaction->payment_type ?? ''),
                'midtrans_id' => (string) ($transaction->midtrans_id ?? ''),
            ],
            'midtrans' => [
                'transaction_status' => (string) ($status['transaction_status'] ?? ''),
                'status_code' => (string) ($status['status_code'] ?? ''),
                'status_message' => (string) ($status['status_message'] ?? ''),
            ],
        ]);
    }

    /**
     * Mark a transaction as failed when user closes Snap without completing payment.
     */
    public function cancel(Request $request, Transaction $transaction): JsonResponse
    {
        $user = $request->user();
        if (!$user || (string) $transaction->user_id !== (string) $user->id) {
            abort(403);
        }

        if ((string) $transaction->status === 'success') {
            return response()->json([
                'ok' => false,
                'message' => 'Transaksi sudah berhasil.',
            ], 409);
        }

        $transaction->status = 'failed';
        $transaction->save();

        return response()->json([
            'ok' => true,
            'transaction' => [
                'id' => (string) $transaction->id,
                'status' => (string) $transaction->status,
            ],
        ]);
    }
}
