<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Plan::insert([
            ['name' => 'Basic Plan', 'price' => 5000, 'duration_days' => 30, 'currency' => 'NGN'],
            ['name' => 'Pro Plan', 'price' => 12000, 'duration_days' => 90, 'currency' => 'NGN'],
            ['name' => 'Yearly Plan', 'price' => 40000, 'duration_days' => 365, 'currency' => 'NGN'],
        ]);
    }
}
