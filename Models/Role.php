<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    public $timestamps = true;

    protected $fillable = [
                    'id',
                    'name',
                    'label',
                    'description'
                ];

    /**
     * 权限
     * permission
     * zhl
     * 2017-08-17T17:12:36+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function permissionRole()
    {
        return $this->hasMany('App\Models\PermissionRole', 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    //给角色添加权限
    public function givePermissionTo($permission)
    {
        return $this->permissions()->save($permission);
    }
}
