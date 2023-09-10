<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class AdminUserRole extends Model
{
    use FormatDate;

    protected $fillable = ['admin_user_id', 'role_id'];
}
