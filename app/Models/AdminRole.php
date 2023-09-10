<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use FormatDate;
    use Filterable;

    protected $fillable = ['name', 'code', 'description', 'status'];

    protected $appends = ['menu_ids'];

    public function menus()
    {
        return $this->belongsToMany(AdminMenu::class, 'admin_role_menus', 'role_id', 'menu_id');
    }

    public function getMenuIdsAttribute()
    {
        return $this->menus()->pluck('menu_id');
    }
}
