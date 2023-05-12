<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use FormatDate;
    use Filterable;


    protected $fillable = ['pid', 'name', 'status', 'remark', 'image'];

    public function parts()
    {
        return $this->hasMany(get_class($this), 'pid', 'id');
    }

    public function children()
    {
        return $this->parts()->with('children');
    }
}
