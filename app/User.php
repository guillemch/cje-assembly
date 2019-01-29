<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the group that the user belongs to
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    /**
     * Get the votes cast by a user
     */
    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function authorizeRoles($roles)
    {
        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
     * Check role
     * @param mixed $role
     */
    public function hasRole($role)
    {
        if ($this->role === 'admin') return true;

        if (is_array($role)) {
            return in_array($this->role, $role);
        }

        return $this->role === $role;
    }
}
