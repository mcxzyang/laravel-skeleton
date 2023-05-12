<?php

namespace App\Models;

use App\Traits\FormatDate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Vendor extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use FormatDate;
    use Filterable;
    use HasApiTokens;

    protected $fillable = [
        'name', 'link_name', 'link_phone', 'wechat_no', 'status', 'client_id', 'client_secret'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        //        'password',
        //        'remember_token',
            ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
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
        static::saving(function (Vendor $vendor) {
            if (is_null($vendor->client_id)) {
                $vendor->client_id = \Str::uuid();
            }
            if (is_null($vendor->client_secret)) {
                $vendor->client_secret = \Str::random(36);
            }
        });
    }
}
