<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Billing\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class MidtransCallbackController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans): JsonResponse
    {
        $payload = $request->all();

        if (!$midtrans->verifyNotification($payload)) {
            return response()->json(['ok' => false, 'message' => 'Invalid signature'], 401);
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        if ($orderId === '') {
            return response()->json(['ok' => false, 'message' => 'Missing order_id'], 422);
        }

        /** @var Transaction|null $transaction */
        $transaction = Transaction::query()->whereKey($orderId)->first();
        if (!$transaction) {
            return response()->json(['ok' => false, 'message' => 'Transaction not found'], 404);
        }

        DB::transaction(function () use ($transaction, $payload, $midtrans): void {
            $midtrans->applyMidtransStatus($transaction, $payload);
        });

        return response()->json(['ok' => true]);
    }
}
