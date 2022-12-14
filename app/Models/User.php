<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    public static $ROLE_SA = 'sa';
    public static $ROLE_ADMIN = 'admin';
    public static $ROLE_MERCHANT = 'merchant';
    public static $ROLE_USER = 'user';

    public static $GUARDS = [
        'sa' => ['sa'],
        'admin' => ['sa', 'admin'],
        'merchant' => ['sa', 'admin', 'merchant'],
        'user' => ['sa', 'admin', 'merchant', 'user'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'avatar'
    ];

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }

    public function email()
    {
        return $this->hasOne(UserEmail::class)->where('primary', 1)
            ->where('active', 1);
    }

    public function emails()
    {
        return $this->hasMany(UserEmail::class);
    }

    public function password()
    {
        return $this->hasOne(UserPassword::class)->where('active', 1);
    }
}
