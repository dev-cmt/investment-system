<?php

namespace Database\Seeders;

use App\Models\InvestmentPost;
use Illuminate\Database\Seeder;

class InvestmentPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Wireless Earbuds',
                'description' => 'High demand TWS Wireless Earbuds imported directly from top Shenzhen electronics suppliers.',
                'image' => 'images/earbuds.jpg',
                'gallery_images' => json_encode(['images/earbuds1.jpg', 'images/earbuds2.jpg', 'images/earbuds3.jpg']),
                'total_quantity' => 1000,
                'unit_cost' => 500.00,
                'profit_per_unit' => 50.00,
                'expected_import_days' => 25,
                'target_amount' => 500000.00,
                'current_invested_amount' => 320000.00,
                'min_investment_amount' => 500.00,
                'status' => 'active',
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(20),
            ],
            [
                'title' => 'Smart Watch Series 8',
                'description' => 'Feature-rich HD display smartwatches with health monitoring, sports tracking, and Bluetooth calling.',
                'image' => 'images/smartwatch.jpg',
                'gallery_images' => json_encode(['images/smartwatch1.jpg', 'images/smartwatch2.jpg', 'images/smartwatch3.jpg']),
                'total_quantity' => 500,
                'unit_cost' => 1200.00,
                'profit_per_unit' => 120.00,
                'expected_import_days' => 28,
                'target_amount' => 600000.00,
                'current_invested_amount' => 270000.00,
                'min_investment_amount' => 1200.00,
                'status' => 'active',
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addDays(21),
            ],
            [
                'title' => 'Portable Blender',
                'description' => 'Rechargeable 6-blade personal size travel juicer and smoothie mixer for active lifestyle.',
                'image' => 'images/blender.jpg',
                'gallery_images' => json_encode(['images/blender1.jpg', 'images/blender2.jpg', 'images/blender3.jpg']),
                'total_quantity' => 800,
                'unit_cost' => 650.00,
                'profit_per_unit' => 65.00,
                'expected_import_days' => 25,
                'target_amount' => 520000.00,
                'current_invested_amount' => 156000.00,
                'min_investment_amount' => 650.00,
                'status' => 'active',
                'starts_at' => now()->subDays(9),
                'ends_at' => now()->addDays(16),
            ],
        ];

        foreach ($posts as $postData) {
            InvestmentPost::updateOrCreate(
                ['title' => $postData['title']],
                $postData
            );
        }
    }
}
