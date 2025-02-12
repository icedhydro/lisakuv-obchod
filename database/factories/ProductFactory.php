<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Jablko',
                'Hruška',
                'Banán',
                'Pomeranč',
                'Jahoda',
                'Meloun',
                'Švestka',
                'Meruňka',
                'Višeň',
                'Hrozny',
                'Vlašský ořech',
                'Lískový ořech',
                'Mandle',
                'Kešu',
                'Pekanový ořech',
                'Para ořech',
                'Mrkev',
                'Okurka',
                'Rajče',
                'Paprika',
                'Brambory',
                'Cibule',
                'Česnek',
                'Dýně'
            ]),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(10, 200),
        ];
    }
}
