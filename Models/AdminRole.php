<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $table = 'admin_role';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    /**
     * 角色
     * role
     * zhl
     * 2017-08-08T14:33:41+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

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
        return $this->hasMany('App\Models\PermissionRole', 'role_id', 'role_id');
    }

    /**
     * 用户
     * user
     * zhl
     * 2017-08-15T10:58:22+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function user()
    {
        return $this->hasOne('App\Models\Admin', 'id', 'user_id');
    }
}
