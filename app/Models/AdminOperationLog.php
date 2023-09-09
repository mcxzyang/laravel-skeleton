<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminOperationLog extends Model
{
    protected $fillable = [
        'admin_user_id',
        'method',
        'ip',
        'params',
        'response_params',
        'browser',
        'status_code',
        'url',
    ];
}
