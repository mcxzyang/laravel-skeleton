<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'url', 'method', 'ip', 'user_id', 'params', 'response_params', 'duration'
    ];
}
