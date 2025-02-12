<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $fruits = ['Jablko', 'Hruška', 'Banán', 'Pomeranč', 'Jahoda', 'Meloun', 'Švestka', 'Meruňka', 'Višeň', 'Hrozny'];
        $nuts = ['Vlašský ořech', 'Lískový ořech', 'Mandle', 'Kešu', 'Pekanový ořech', 'Para ořech'];
        $vegetables = ['Mrkev', 'Okurka', 'Rajče', 'Paprika', 'Brambory', 'Cibule', 'Česnek', 'Dýně'];

        $products = array_merge($fruits, $nuts, $vegetables);

        return [
            'name' => $this->faker->randomElement($products),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(10, 200),
        ];
    }
}
