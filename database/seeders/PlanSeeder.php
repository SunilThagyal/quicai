<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'price' => 10.00,
                'credits' => 1000,
                'credit_per_call' => 4,
                'description' => 'Perfect for small projects and testing',
                'features' => json_encode([
                    '1000 API Credits',
                    '4 Credits per API call',
                    'Basic Support',
                    '30-day validity'
                ])
            ],
            [
                'name' => 'Pro Plan',
                'slug' => 'pro',
                'price' => 15.00,
                'credits' => 1600,
                'credit_per_call' => 3,
                'description' => 'Great for growing businesses',
                'features' => json_encode([
                    '1600 API Credits',
                    '3 Credits per API call',
                    'Priority Support',
                    '60-day validity'
                ])
            ],
            [
                'name' => 'Plus Plan',
                'slug' => 'plus',
                'price' => 20.00,
                'credits' => 2200,
                'credit_per_call' => 2,
                'description' => 'Best value for high-volume usage',
                'features' => json_encode([
                    '2200 API Credits',
                    '2 Credits per API call',
                    '24/7 Premium Support',
                    '90-day validity'
                ])
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
