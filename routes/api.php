<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::middleware('api')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::get('products/{product}/price-history', [ProductController::class, 'priceHistory']);
    Route::get('products/search', [ProductController::class, 'search']);
    Route::get('products/filter', [ProductController::class, 'filter']);
});
