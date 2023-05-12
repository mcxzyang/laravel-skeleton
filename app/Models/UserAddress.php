<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use FormatDate;

    protected $fillable = [
        'user_id', 'province', 'city', 'area', 'address', 'contact_name', 'contact_phone', 'is_default'
    ];
}
