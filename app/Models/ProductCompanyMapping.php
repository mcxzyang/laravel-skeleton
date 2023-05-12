<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCompanyMapping extends Model
{
    use FormatDate;

    protected $fillable = [
        'product_id', 'company_id'
    ];
}
