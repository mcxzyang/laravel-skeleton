<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemRate extends Model
{
    use HasFactory;
    use FormatDate;

    protected $fillable = [
        'order_id', 'order_item_id', 'product_id', 'score', 'content', 'status', 'user_id'
    ];
}
