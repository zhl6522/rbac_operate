<?php
/**
 * Created by PhpStorm.
 * User: zhl
 * Date: 2017/8/9
 * Time: 上午11:10
 */

namespace App\Repositories;

use App\Models\AdminRole;
use App\Models\Permission;
use App\Models\PermissionRole;
use Auth;

class PermissionRepository extends BaseRepository
{
    /**
     * 每页的数量
     * @var integer
     */
    protected $pageSize = 15;

    /**
     * 初始化
     * __construct
     * zhl
     * 2017-08-09T11:13:29+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function __construct(
        Permission $permissionModel,
        PermissionRole $permissionRoleModel
    ) {
        $this->permission = $permissionModel;
        $this->permissionRole = $permissionRoleModel;
    }

    /**
     * 权限列表
     * getLists
     * zhl
     * 2017-08-16T15:37:01+0800
     * @param   Request $fid        [父ID]
     * @return  [type]              [description]
     */
    public function getLists($fid = 0)
    {
        $permission = Permission::where('fid', $fid)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate($this->pageSize);
        if($fid != 0) {
            $fidArray = array();
            foreach ($permission as $k => $v) {
                $permissionFid = \DB::table('permissions')->where('fid', $v->id)->count();
                $permission[$k]['buttom'] = 0;
                if($permissionFid != 0) {
                    $permission[$k]['buttom'] = 1;
                }
                $fidArray[$k] = $v->id;
            }
        }
        return $permission;
    }

    /**
     * 获取权限信息
     * getList
     * zhl
     * 2017-08-16T17:42:56+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getList($id)
    {
        return Permission::where('id', $id)
            ->first();
    }

    /**
     * 顶级权限列表
     * topPermissionSelect
     * zhl
     * 2017-08-16T16:43:01+0800
     * @param   Request $fid        [description]
     * @return  [type]              [description]
     */
    public function topPermissionSelect($fid = 0)
    {
        $tops = $this->Permissions();
        $select = '<select class="form-control input-sm" name="fid">';
        $select .= '<option value="0">--顶部权限--</option>';
        if(count($tops) > 0) {
            foreach ($tops as $top) {
                if($top['id'] == $fid) {
                    $select .= '<option  value="' . $top['id'] . '" selected >' . $top['label'] . '[' . $top['name'] . ']</option>';
                    foreach ($top['subPermission'] as $second) {

                        if(isset($second['SecondPermission']) && count($second['SecondPermission']) > 0) {
                            if($second['id'] == $fid) {
                                $select .= '<option  value="' . $second['id'] . '" selected >--' . $second['label'] . '[' . $second['name'] . ']</option>';
                                if(isset($second['SecondPermission'])) {
                                    foreach ($second['SecondPermission'] as $third) {
                                        if($third['id'] == $fid) {
                                            $select .= '<option  value="' . $third['id'] . '" selected >----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                        } else {
                                            $select .= '<option  value="' . $third['id'] . '">----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                        }
                                    }
                                }
                            } else {
                                $select .= '<option  value="' . $second['id'] . '">--' . $second['label'] . '[' . $second['name'] . ']</option>';
                                if(isset($second['SecondPermission'])) {
                                    foreach ($second['SecondPermission'] as $third) {
                                        if($third['id'] == $fid) {
                                            $select .= '<option  value="' . $third['id'] . '" selected >----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                        } else {
                                            $select .= '<option  value="' . $third['id'] . '">----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                        }
                                    }
                                }
                            }
                        } else {
                            if($second['id'] == $fid) {
                                $select .= '<option  value="' . $second['id'] . '" selected >--' . $second['label'] . '[' . $second['name'] . ']</option>';
                            } else {
                                $select .= '<option  value="' . $second['id'] . '">--' . $second['label'] . '[' . $second['name'] . ']</option>';
                            }
                        }
                    }
                } elseif($top['id'] != $fid) {
                    $select .= '<option  value="' . $top['id'] . '">' . $top['label'] . '[' . $top['name'] . ']</option>';
                    if(isset($top['subPermission'])) {
                        foreach ($top['subPermission'] as $second) {
                            if(isset($second['SecondPermission']) && count($second['SecondPermission']) > 0) {
                                if($second['id'] == $fid) {
                                    $select .= '<option  value="' . $second['id'] . '" selected >--' . $second['label'] . '[' . $second['name'] . ']</option>';
                                    if(isset($second['SecondPermission'])) {
                                        foreach ($second['SecondPermission'] as $third) {
                                            if($third['id'] == $fid) {
                                                $select .= '<option  value="' . $third['id'] . '" selected >----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                            } else {
                                                $select .= '<option  value="' . $third['id'] . '">----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                            }
                                        }
                                    }
                                } else {
                                    $select .= '<option  value="' . $second['id'] . '">--' . $second['label'] . '[' . $second['name'] . ']</option>';
                                    if(isset($second['SecondPermission'])) {
                                        foreach ($second['SecondPermission'] as $third) {
                                            if($third['id'] == $fid) {
                                                $select .= '<option  value="' . $third['id'] . '" selected >----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                            } else {
                                                $select .= '<option  value="' . $third['id'] . '">----' . $third['label'] . '[' . $third['name'] . ']</option>';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if($second['id'] == $fid) {
                                    $select .= '<option  value="' . $second['id'] . '" selected >--' . $second['label'] . '[' . $second['name'] . ']</option>';
                                } else {
                                    $select .= '<option  value="' . $second['id'] . '">--' . $second['label'] . '[' . $second['name'] . ']</option>';
                                }
                            }
                        }
                    }
                }
            }
        }
        $select .= '</select>';
        return $select;
    }

    /**
     * 权限列表
     * topPermissions
     * zhl
     * 2017-08-16T16:43:01+0800
     * @param   Request $fid        [description]
     * @return  [type]              [description]
     */
//    public function topPermissions()
//    {
//        $permissions = $this->permission->where('fid', 0)->orderBy('sort', 'asc')->orderBy('id', 'asc')->get();
//
//        return $permissions;
//    }

    /**
     * 权限列表
     * Permissions
     * zhl
     * 2017-08-16T16:43:01+0800
     * @param   Request $id         [is_menu控制]
     * @return  [type]              [description]
     */
    public function Permissions($id = 0)
    {
        $permissions = $this->permission->where('fid', 0)->orderBy('sort', 'asc')->orderBy('id', 'asc')->get();
        $fidArray = $fidSecondArray = $fidThirdArray = array();
        $permissions = $permissions->toArray();
        foreach ($permissions as $k => $v) {
            $fidArray[$k] = $v['id'];
        }
        $subPermission = $this->getSub($fidArray, $id);
        $subPermission = $subPermission->toArray();

        foreach ($permissions as $k => $v) {
            foreach ($subPermission as $key => $value) {
                if($v['id'] == $value['fid']) {
                    $permissions[$k]['subPermission'][] = $subPermission[$key];
                    $fidSecondArray[$key] = $subPermission[$key]['id'];
                }
            }
        }
        $subSecondPermission = $this->getSub($fidSecondArray, $id);
        $subSecondPermission = $subSecondPermission->toArray();

        foreach ($permissions as $k => $v) {
            if(isset($v['subPermission'])) {
                foreach ($v['subPermission'] as $key => $value) {
                    foreach ($subSecondPermission as $keySub => $valueSub) {
                        if($value['id'] == $valueSub['fid']) {
                            $permissions[$k]['subPermission'][$key]['SecondPermission'][] = $subSecondPermission[$keySub];
                            $fidThirdArray[] = $subSecondPermission[$keySub]['id'];
                        }
                    }
                }
            }
        }

        $subThirdPermission = $this->getSub($fidThirdArray, $id);
        $subThirdPermission = $subThirdPermission->toArray();
        foreach ($permissions as $k => $v) {
            if(isset($v['subPermission'])) {
                foreach ($v['subPermission'] as $key => $value) {
                    if(isset($value['SecondPermission'])) {
                        foreach ($value['SecondPermission'] as $keySecond => $valueSecond) {
                            foreach ($subThirdPermission as $keyThird => $valueThird) {
                                if($valueSecond['id'] == $valueThird['fid']) {
                                    $permissions[$k]['subPermission'][$key]['SecondPermission'][$keySecond]['ThirdPermission'][] = $subThirdPermission[$keyThird];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $permissions;
    }

    /**
     * 查询下一级权限列表
     * getSub
     * zhl
     * 2017-08-18T20:22:22+0800
     * @param   Request $request    [description]
     * @param   Request $id         [is_menu控制]
     * @return  [type]              [description]
     */
    public function getSub($fidArray, $id)
    {
        if($id == 1) {
            return $this->permission->whereIn('fid', array_values($fidArray))->orderBy('sort', 'asc')->orderBy('id', 'asc')->get();
        } else {
            return $this->permission->whereIn('fid', array_values($fidArray))->where('is_menu', 1)->orderBy('sort', 'asc')->orderBy('id', 'asc')->get();
        }
    }

    /**
     * 权限添加
     * create
     * zhl
     * 2017-08-16T17:12:16+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function add($data)
    {
        $this->permission->create($data);
    }

    /**
     * 路由验证
     * getName
     * zhl
     * 2017-08-16T18:14:35+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getName($id, $name)
    {
        return Permission::where('id', '!=', $id)
            ->select('name')
            ->where('name', $name)
            ->first();
    }

    /**
     * 用户角色对应的权限列表
     * subListTop
     * zhl
     * 2017-08-25T17:04:06+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function subListTop($uid, $routeName)
    {

        $role = AdminRole::with('permissionRole', 'permissionRole.permission')
            ->join('permission_role',
                'permission_role.role_id', '=', 'admin_role.role_id')
            ->join('permissions',
                'permissions.id', '=', 'permission_role.permission_id')
            ->select('permissions.*')->where('admin_role.admin_id', $uid)->where('permissions.is_menu', 1)->orderBy('permissions.sort', 'asc')->orderBy('permissions.created_at', 'asc')->get();
        $role = $role->toArray();
        $list = array();
        foreach ($role as $k => $v) {
            if($v['fid'] == 0) {
                $list[$v['name']] = $v;
            } else {
                foreach ($list as $key => $value) {
                    if($v['fid'] == $value['id']) {
                        $list[$key]['subPermission'][] = $v;
                    } else {
                        if(isset($list[$key]['subPermission'])) {
                            foreach ($list[$key]['subPermission'] as $keyThird => $valueThird) {
                                if($v['fid'] == $valueThird['id']) {
                                    $list[$key]['subPermission'][$keyThird]['thirdPermission'][] = $v;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $list[$routeName];
    }

    /**
     * save role permissions
     * @param $id
     * @param array $perms
     * @return bool
     */
    public function savePermissions($id, $perms = [])
    {
        $role = $this->permissionRole->where('role_id', $id)->forceDelete();
        if(!empty($perms)) {
            $perm = array();
            foreach ($perms as $k => $v) {
                $perm[$k]['permission_id'] = $v;
                $perm[$k]['role_id'] = $id;
            }
            $perm = array_values($perm);
            \DB::table('permission_role')->insert($perm);
        }
        return true;
    }

    /**
     * get role's permissions
     * @param $id
     * @return array
     */
    public function rolePermissions($id)
    {
        $perms = [];
        $permissions = $this->permissionRole->with('permission')->where('permission_role.role_id', $id)->get();

        foreach ($permissions as $item) {
            $perms[$item->permission_id] = $item['permission'][0]['name'];
        }

        return $perms;
    }
}