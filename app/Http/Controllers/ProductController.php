<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        return Product::create($request->all());
    }

    /**
     * Get the detail of a particular product
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update product (including price change tracking)
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'price' => 'numeric',
            'stock' => 'integer',
        ]);

        // If the price changes, we save it to history
        if ($request->has('price') && $product->price != $request->price) {
            PriceHistory::create([
                'product_id' => $product->id,
                'old_price' => $product->price,
                'new_price' => $request->price,
                'changed_at' => now(),
            ]);
        }

        $product->update($request->all());
        return $product;
    }

    /**
     * Remove product.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    /**
     * Get a product's price history
     */
    public function priceHistory(Product $product)
    {
        return $product->priceHistory()->orderBy('changed_at', 'desc')->get();
    }

    /**
     * Product search by name
     */
    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        return Product::where('name', 'like', "%{$request->name}%")->get();
    }
}
