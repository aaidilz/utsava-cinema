<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            [
                'name' => 'Basic Monthly',
                'price' => 49000.00,
                'duration_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Monthly',
                'price' => 79000.00,
                'duration_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Basic Quarterly',
                'price' => 129000.00,
                'duration_days' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Quarterly',
                'price' => 199000.00,
                'duration_days' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Basic Yearly',
                'price' => 449000.00,
                'duration_days' => 365,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Yearly',
                'price' => 699000.00,
                'duration_days' => 365,
                'is_active' => true,
            ],
            [
                'name' => 'Trial Plan',
                'price' => 0.00,
                'duration_days' => 7,
                'is_active' => false,
            ],
        ];

        foreach ($subscriptions as $subscription) {
            Subscription::create($subscription);
        }
    }
}
