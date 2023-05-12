<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use FormatDate;

    protected $fillable = [
        'user_id', 'product_id', 'product_sku_id', 'quantity', 'price', 'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
