<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('is_premium', true)->get();
        $subscriptions = Subscription::where('is_active', true)->get();

        if ($users->isEmpty() || $subscriptions->isEmpty()) {
            return;
        }

        $paymentTypes = ['credit_card', 'bank_transfer', 'e_wallet', 'gopay', 'qris'];
        $statuses = ['success', 'pending', 'failed'];

        // Create successful transactions for premium users
        foreach ($users->take(3) as $user) {
            $subscription = $subscriptions->random();
            
            Transaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'midtrans_id' => 'MT-' . strtoupper(Str::random(16)),
                'snap_token' => Str::random(64),
                'status' => 'success',
                'amount' => $subscription->price,
                'payment_type' => $paymentTypes[array_rand($paymentTypes)],
                'paid_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create pending transactions
        $regularUsers = User::where('is_premium', false)->limit(2)->get();
        foreach ($regularUsers as $user) {
            $subscription = $subscriptions->random();
            
            Transaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'midtrans_id' => 'MT-' . strtoupper(Str::random(16)),
                'snap_token' => Str::random(64),
                'status' => 'pending',
                'amount' => $subscription->price,
                'payment_type' => null,
                'paid_at' => null,
            ]);
        }

        // Create failed transactions
        $failedUsers = User::limit(2)->get();
        foreach ($failedUsers as $user) {
            $subscription = $subscriptions->random();
            
            Transaction::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'midtrans_id' => 'MT-' . strtoupper(Str::random(16)),
                'snap_token' => Str::random(64),
                'status' => 'failed',
                'amount' => $subscription->price,
                'payment_type' => $paymentTypes[array_rand($paymentTypes)],
                'paid_at' => null,
            ]);
        }

        // Create multiple transactions for some users (history)
        $activeUser = $users->first();
        if ($activeUser) {
            for ($i = 0; $i < 3; $i++) {
                $subscription = $subscriptions->random();
                
                Transaction::create([
                    'user_id' => $activeUser->id,
                    'subscription_id' => $subscription->id,
                    'midtrans_id' => 'MT-' . strtoupper(Str::random(16)),
                    'snap_token' => Str::random(64),
                    'status' => 'success',
                    'amount' => $subscription->price,
                    'payment_type' => $paymentTypes[array_rand($paymentTypes)],
                    'paid_at' => now()->subMonths($i + 1),
                ]);
            }
        }
    }
}
