<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'name', 'link_name', 'link_phone', 'key', 'status', 'client_id', 'client_secret'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Company $company) {
            if (is_null($company->key)) {
                $company->key = \Str::uuid();
            }
        });
    }
}
