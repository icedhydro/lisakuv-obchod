<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Lišákův obchod API",
 *      description="Dokumentace k API pro evidenci produktů",
 *      @OA\Contact(
 *          email="support@lisakuvobchod.com"
 *      ),
 * )
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api",
 *      description="Lokální server"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Získání seznamu produktů",
     *     tags={"Produkty"},
     *     @OA\Response(
     *         response=200,
     *         description="Seznam všech produktů",
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Vytvoření nového produktu",
     *     tags={"Produkty"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","stock"},
     *             @OA\Property(property="name", type="string", example="Jablko"),
     *             @OA\Property(property="price", type="number", format="float", example=25.50),
     *             @OA\Property(property="stock", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produkt byl úspěšně vytvořen",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Získání detailu produktu",
     *     tags={"Produkty"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail produktu"
     *     )
     * )
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Aktualizace produktu",
     *     tags={"Produkty"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="price", type="number", format="float", example=30.00),
     *             @OA\Property(property="stock", type="integer", example=90)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produkt byl aktualizován"
     *     )
     * )
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
        ]);

        if (isset($validated['price']) && $product->price != $validated['price']) {
            PriceHistory::create([
                'product_id' => $product->id,
                'old_price' => $product->price,
                'new_price' => $validated['price'],
                'changed_at' => now(),
            ]);
        }

        $product->update($validated);
        return response()->json($product, 200);
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Smazání produktu",
     *     tags={"Produkty"},
     *     @OA\Response(
     *         response=200,
     *         description="Produkt byl smazán"
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Produkt byl smazán'], 200);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}/price-history",
     *     summary="Získání historie cen produktu",
     *     tags={"Produkty"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Historie cen produktu"
     *     )
     * )
     */
    public function priceHistory(Product $product)
    {
        return response()->json($product->priceHistory()->orderBy('changed_at', 'desc')->get(), 200);
    }


    /**
     * @OA\Get(
     *     path="/products/search",
     *     summary="Vyhledání produktu podle názvu",
     *     tags={"Produkty"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Seznam nalezených produktů"
     *     )
     * )
     */
    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        return response()->json(Product::where('name', 'like', "%{$request->name}%")->get(), 200);
    }

    /**
     * @OA\Get(
     *     path="/products/filter",
     *     summary="Filtrování produktů podle počtu kusů na skladě",
     *     tags={"Produkty"},
     *     @OA\Parameter(
     *         name="stock_min",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="stock_max",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Seznam produktů odpovídající filtraci"
     *     )
     * )
     */
    public function filter(Request $request)
    {
        $request->validate([
            'stock_min' => 'nullable|integer',
            'stock_max' => 'nullable|integer',
        ]);

        $query = Product::query();

        if ($request->has('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->has('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }

        return response()->json($query->get(), 200);
    }
}
