<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Constants\DiscountTypeEnum;
use App\Constants\DiscountScopeEnum;

class DiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $discounts = [
            [
                'name' => 'Product Discount 10%',
                'type' => DiscountTypeEnum::FIXED(), // 'product' or 'batch'
                'scope' => DiscountScopeEnum::PRODUCT(), // 'product' or 'batch'
                'value' => 10, // Percentage or fixed amount
                'summary' => '10% off on selected products',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Batch Discount 5%',
                'type' => DiscountTypeEnum::PERCENTAGE(), // 'product' or 'batch'
                'scope' => DiscountScopeEnum::BATCH(), // 'product' or 'batch'
                'value' => 5, // Percentage or fixed amount
                'summary' => '5% off on the entire cart',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Product Discount 15%',
                'type' => DiscountTypeEnum::PERCENTAGE(), // 'product' or 'batch'
                'scope' => DiscountScopeEnum::PRODUCT(), // 'product' or 'batch'
                'value' => 15, // Percentage or fixed amount
                'summary' => '15% off on specific products in category A',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Batch Discount 20%',
                'type' => DiscountTypeEnum::FIXED(), // 'product' or 'batch'
                'scope' => DiscountScopeEnum::BATCH(), // 'product' or 'batch'
                'value' => 20, // Percentage or fixed amount
                'summary' => '20% off on total cart if total exceeds $500',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into discounts table
        DB::table('discounts')->insert($discounts);
    }
}
