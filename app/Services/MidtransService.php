<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

final class MidtransService
{
    public function __construct()
    {
        $this->configure();
    }

    private function configure(): void
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('services.midtrans.is_3ds', true);
    }

    public function createSnapToken(Transaction $transaction): string
    {
        $transaction->loadMissing(['user', 'subscription']);

        $orderId = (string) $transaction->id;

        // Midtrans expects numeric gross_amount (commonly int). Use rounded amount.
        $grossAmount = (int) round((float) $transaction->amount);
        if ($grossAmount < 1) {
            $grossAmount = 1;
        }

        $itemName = (string) ($transaction->subscription?->name ?? 'Subscription');

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => [
                [
                    'id' => (string) $transaction->subscription_id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => $itemName,
                ],
            ],
            'customer_details' => [
                'first_name' => (string) ($transaction->user?->name ?? 'User'),
                'email' => (string) ($transaction->user?->email ?? 'user@example.com'),
            ],
        ];

        return (string) Snap::getSnapToken($params);
    }

    /**
     * Fetch transaction status directly from Midtrans.
     *
     * @return array<string, mixed>
     */
    public function fetchStatus(string $orderId): array
    {
        /** @var object|array $result */
        $result = MidtransTransaction::status($orderId);

        if (is_array($result)) {
            return $result;
        }

        /** @var array<string, mixed> $asArray */
        $asArray = json_decode(json_encode($result), true) ?? [];

        return $asArray;
    }

    /**
     * Apply Midtrans payload/status response to local Transaction and related User.
     *
     * Expected keys (webhook and status API are compatible):
     * - transaction_status, fraud_status, payment_type, transaction_id
     */
    public function applyMidtransStatus(Transaction $transaction, array $payload): void
    {
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $paymentType = (string) ($payload['payment_type'] ?? '');
        $midtransId = (string) ($payload['transaction_id'] ?? '');

        $newStatus = match ($transactionStatus) {
            'settlement' => 'success',
            'capture' => ($fraudStatus === 'challenge') ? 'pending' : 'success',
            'pending' => 'pending',
            'deny', 'cancel', 'expire', 'failure' => 'failed',
            default => (string) $transaction->status,
        };

        $transaction->status = $newStatus;

        if ($paymentType !== '') {
            $transaction->payment_type = $paymentType;
        }

        if ($midtransId !== '') {
            $transaction->midtrans_id = $midtransId;
        }

        if ($newStatus === 'success' && $transaction->paid_at === null) {
            $transaction->paid_at = now();
        }

        $transaction->save();

        if ($newStatus !== 'success') {
            return;
        }

        $transaction->loadMissing(['user', 'subscription']);

        $user = $transaction->user;
        $subscription = $transaction->subscription;

        if (!$user || !$subscription) {
            return;
        }

        $user->active_subscription_id = (string) $subscription->id;
        $user->is_premium = true;

        $days = (int) $subscription->duration_days;
        $user->premium_until = now()->addDays($days > 0 ? $days : 30);

        $user->save();
    }

    /**
     * Verify Midtrans notification signature_key (sha512).
     *
     * Midtrans formula:
     * signature_key = sha512(order_id + status_code + gross_amount + server_key)
     */
    public function verifyNotification(array $payload): bool
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '') {
            return false;
        }

        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return false;
        }

        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($expected, $signatureKey);
    }
}
