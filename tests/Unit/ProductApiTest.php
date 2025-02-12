<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests store of a new product.
     */
    public function test_create_product()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Jablko',
            'price' => 25.50,
            'stock' => 100,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Produkt byl úspěšně vytvořen',
                'data' => [
                    'name' => 'Jablko',
                    'price' => 25.50,
                    'stock' => 100,
                ]
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Jablko']);
    }

    /**
     * Tests product update and price history tracking.
     */
    public function test_update_product_and_track_price_history()
    {
        $product = Product::factory()->create([
            'name' => 'Banán',
            'price' => 10.00,
            'stock' => 50,
        ]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'price' => 15.00,
            'stock' => 60,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'price' => 15.00,
                'stock' => 60,
            ]);

        $this->assertDatabaseHas('price_history', [
            'product_id' => $product->id,
            'old_price' => 10.00,
            'new_price' => 15.00,
        ]);
    }

    /**
     * Tests product search by name.
     */
    public function test_search_product()
    {
        Product::factory()->create(['name' => 'Hruška']);

        $response = $this->getJson('/api/products/search?name=Hruška');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Hruška']);
    }

    /**
     * Tests product filtering by stock.
     */
    public function test_filter_products_by_stock()
    {
        Product::factory()->create(['name' => 'Meruňka', 'stock' => 20]);
        Product::factory()->create(['name' => 'Broskev', 'stock' => 100]);

        $response = $this->getJson('/api/products/filter?stock_min=10&stock_max=50');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Meruňka'])
            ->assertJsonMissing(['name' => 'Broskev']);
    }

    /**
     * Tests product removal.
     */
    public function test_delete_product()
    {
        $product = Product::factory()->create(['name' => 'Švestka']);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Produkt byl smazán']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
