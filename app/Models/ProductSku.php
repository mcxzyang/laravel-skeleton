<?php

namespace App\Models;

use App\Exceptions\InvalidRequestException;
use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    use FormatDate;

    protected $fillable = [
        'product_id', 'sku_number', 'sku_name', 'sale_price', 'original_price', 'unit', 'stock', 'cost_price'
    ];

    public function decreaseStock($stock)
    {
        if ($this->stock < $stock) {
            throw new InvalidRequestException('库存不足');
        }
        $this->where('stock', '>=', $stock)->decrement('stock', $stock);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
