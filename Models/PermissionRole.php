<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    protected $table = 'permission_role';

    public $timestamps = true;

    protected $fillable = [
        'permission_id',
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
        return $this->hasMany('App\Models\Role', 'id', 'role_id');
    }

    /**
     * 用户
     * user
     * zhl
     * 2017-08-15T10:58:22+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function permission()
    {
        return $this->hasMany('App\Models\Permission', 'id', 'permission_id');
    }
}
