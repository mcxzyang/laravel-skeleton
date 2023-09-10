<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminUser extends Authenticatable implements JWTSubject
{
    use FormatDate;
    use Filterable;

    protected $fillable = [
        'username', 'name', 'avatar', 'password', 'is_super_admin', 'status'
    ];

    protected $hidden = [
        'password'
    ];

    protected $appends = ['roles', 'role_names'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function booted()
    {
        static::saving(function (AdminUser $user) {
            if (\Hash::needsRehash($user->password)) {
                $user->password = \bcrypt($user->password);
            }
        });
    }

    public function adminRoles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_user_roles', 'admin_user_id', 'role_id');
    }

    public function getRolesAttribute()
    {
        return $this->adminRoles()->pluck('role_id');
    }

    public function getRoleNamesAttribute()
    {
        return $this->adminRoles()->pluck('name');
    }
}
