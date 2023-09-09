<?php

namespace App\Models;

use App\Traits\FormatDate;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    use FormatDate;

    protected $fillable = [
        'title', 'pid', 'type', 'path', 'name', 'component', 'icon', 'ignoreCache', 'hideInMenu', 'permission', 'sort',
        'status'
    ];

    protected $casts = [
        'ignoreCache' => 'boolean',
        'hideInMenu' => 'boolean'
    ];

    protected $appends = ['key'];

    public function parts()
    {
        return $this->hasMany(get_class($this), 'pid', 'id');
    }

    public function children()
    {
        return $this->parts()->with('children');
    }

    public function getKeyAttribute()
    {
        return $this->id;
    }
}
