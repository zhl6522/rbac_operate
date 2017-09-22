<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cellphone', 'password',
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
     * 用户角色表
     * roleUser
     * zhl
     * 2017-08-09T11:33:36+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function roleUser()
    {
        $this->hasOne('App\Models\RoleUser', 'user_id', 'id');
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // 判断用户是否具有某个角色
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)->count();
    }

    // 判断用户是否具有某权限
    public function hasPermission($permission)
    {
        return $this->hasRole($permission->roles);
    }

    // 给用户分配角色
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * 查询用户权限
     * role
     * zhl
     * 2017-08-17T17:52:21+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function role()
    {
        return $this->hasMany('App\Models\PermissionRole', 'role_id', 'id');
    }
}
