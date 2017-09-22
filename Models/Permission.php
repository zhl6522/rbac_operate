<?php

namespace App\Models;

//use Zizaco\Entrust\EntrustPermission;

//class Permission extends EntrustPermission
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'label',
        'description',
        'fid',
        'is_menu',
        'sort',
        'yield'
    ];

    /**
     * 角色权限
     * permissionRole
     * zhl
     * 2017-08-17T16:59:40+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function permissionRole()
    {
        return $this->hasMany('App\Models\PermissionRole', 'permission_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
