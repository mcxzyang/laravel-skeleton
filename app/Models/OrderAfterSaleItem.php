<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAfterSaleItem extends Model
{
    use HasFactory;
    use FormatDate;

    protected $fillable = [
        'user_id', 'source', 'images', 'content', 'remark'
    ];

    protected $casts = [
        'images' => 'json'
    ];
}
