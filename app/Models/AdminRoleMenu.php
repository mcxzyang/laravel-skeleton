<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class AdminRoleMenu extends Model
{
    use FormatDate;

    protected $fillable = ['role_id', 'menu_id'];
}
