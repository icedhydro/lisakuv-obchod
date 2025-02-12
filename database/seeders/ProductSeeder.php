<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Vytvoříme 10 testovacích produktů
        Product::factory()->count(10)->create();
    }
}
