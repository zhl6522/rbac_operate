<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Requests\Rbac\PostRoleMatchNameRequest;
use App\Models\AdminRole;
use App\Models\Role;
use App\Repositories\AdminRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * @var $adminRepository
     */
    protected $adminRepository;

    /**
     * 初始化
     * __construct
     * zhl
     * 2017-08-01T10:52:11+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function __construct(
        AdminRepository $adminRepository,
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->adminRepository = $adminRepository;
    }

    /**
     * 角色管理列表
     * getList
     * zhl
     * 2017-08-09T09:16:40+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getList()
    {
        if (Gate::denies('role-list')) {
            return view('error');
        }
        $data = $this->roleRepository->getLists();

        return view('rbac.role.list', compact('data'), ['t' => '角色列表']);
    }

    /**
     * 权限控制
     * AdvertList
     * zhl
     * 2017-08-26T10:41:42+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function Lists()
    {
        if (Gate::denies('role-list-left')) {
            return view('error');
        }
        $uid = Auth::user()->id;
        $list = $this->permissionRepository->subListTop($uid, 'role-list-left');
        if(isset($list['subPermission'])) {
            if(isset($list['subPermission'][0]['thirdPermission'])) {
                return redirect(route($list['subPermission'][0]['thirdPermission'][0]['name']));
            } else {
                return redirect(route($list['subPermission'][0]['name']));
            }
        } else {
            return view('error');
        }
    }

    /**
     * 角色信息修改
     * getEdit
     * zhl
     * 2017-08-09T14:42:39+0800
     * @param   integer $id         [用户ID]
     * @return  [type]              [description]
     */
    public function getEdit($id)
    {
        if (Gate::denies('role-edit')) {
            return view('error');
        }
        $data = $this->roleRepository->getList($id);

        return view('rbac.role.edit', compact('data'), ['t' => '角色信息修改']);
    }

    /**
     * 角色信息保存
     * postEdit
     * zhl
     * 2017-08-10T08:44:15+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postEdit(PostRoleMatchNameRequest $request)
    {
        if (Gate::denies('role-edit')) {
            return view('error');
        }
//        $match = $this->roleRepository->getRid($request->id, $request->name);
//        if(isset($match->name)) {
//            return redirect()->back()->withErrors('角色名有重复！');
//        }

        $user = Role::find($request->id);
        $user->name = $request->name;
        $user->label = $request->label;
        $user->description = $request->description;
        $user->save();

        return redirect()->back()->with('success', '角色修改成功！');
    }

    /**
     * 角色删除
     * postDel
     * zhl
     * 2017-08-13T17:57:43+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postDel(Request $request)
    {
        if (Gate::denies('role-del')) {
            return view('error');
        }
        $id = $request->id;

        $ids = AdminRole::where('role_id', $request['id'])->get();
        $idarray = $ids->toArray();
        if(count($idarray) > 0) {
            $ids = array();
            foreach ($idarray as $v) {
                $ids[] = $v['admin_id'];
            }
            $this->adminRepository->del($ids);
        }

        $role = Role::findOrFail($id);
        $role->forceDelete();
    }

    /**
     * 角色添加
     * getAdd
     * zhl
     * 2017-08-10T13:32:01+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getAdd()
    {
        if (Gate::denies('role-add')) {
            return view('error');
        }
        return view('rbac.role.add', ['t' => '角色信息添加']);
    }

    /**
     * 角色添加
     * postAdd
     * zhl
     * 2017-08-10T10:00:24+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postAdd(PostRoleMatchNameRequest $request)
    {
        if (Gate::denies('role-add')) {
            return view('error');
        }
        $match = $this->roleRepository->getName($request->name);
        if(isset($match->name)) {
            return redirect()->back()->withErrors('角色有重复！');
        }

        $data = [
            'name'        => $request->name,
            'label'   => $request->label,
            'description'    => $request->description
        ];
        $this->roleRepository->postAdd($data);

        return redirect()->back()->with('success', '角色添加成功！');
    }

    /**
     * 角色权限管理
     * getPermission
     * zhl
     * 2017-08-17T09:53:47+0800
     * @param   Request $id         [角色ID]
     * @return  [type]              [description]
     */
    public function getPermission($id)
    {
        if (Gate::denies('role-permission')) {
            return view('error');
        }
        $role = Role::find($id);
        $permissions = $this->permissionRepository->Permissions(1);
        $rolePermissions = $this->permissionRepository->rolePermissions($id);

        return view('rbac.role.permission', compact('role', 'permissions', 'rolePermissions'), ['t'=>'角色权限添加']);
    }

    /**
     * 角色权限管理修改
     * postEdit
     * zhl
     * 2017-08-18T13:57:04+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postPermission(Request $request)
    {
        if (Gate::denies('role-permission')) {
            return view('error');
        }
        $data = $this->permissionRepository->savePermissions($request->id, $request->permissions);

        return redirect()->back()->with('success', '角色权限更新成功！');
    }

}
