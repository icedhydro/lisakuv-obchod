<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $table = 'price_history';

    protected $fillable = ['product_id', 'old_price', 'new_price', 'changed_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
