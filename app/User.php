<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','active', 'activation_code',
    ];

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_OWNER = 'ROLE_OWNER';
    const ROLE_CLIENT = 'ROLE_CLIENT';

    private const ROLES_HIERARCHY = [
        self::ROLE_ADMIN => [self::ROLE_OWNER],
        self::ROLE_OWNER => [self::ROLE_CLIENT],
        self::ROLE_CLIENT => []
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function product()
    {
        return $this->hasMany('App\Product');
    }

    public function request()
    {
        return $this->hasMany('App\Request');
    }

    public function isGranted($role){
        if ($role === $this->role) {
            return true;
        }
        return self::isRoleInHierarchy($role, self::ROLES_HIERARCHY[$this->role]);
    }
    private static function isRoleInHierarchy($role, $role_hierarchy){
        if (in_array($role, $role_hierarchy)) {
            return true;
        }
        foreach ($role_hierarchy as $role_included) {
            if(self::isRoleInHierarchy($role,self::ROLES_HIERARCHY[$role_included])){
                return true;
            }
        }
        return false;
    }

    public function userable()
    {
        return $this->morphTo();
    }
}
