<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Requests\Rbac\PostMatchPermissionEditRequest;
use App\Http\Requests\Rbac\PostMatchPermissionRequest;
use App\Models\Permission;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
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
        PermissionRepository $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * 权限管理列表
     * getList
     * zhl
     * 2017-08-09T09:16:40+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getList()
    {
        if (Gate::denies('permission-list')) {
            return view('error');
        }
        $data = $this->permissionRepository->getLists();
        return view('rbac.permission.list', compact('data'), ['t' => '权限管理列表']);
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
        if (Gate::denies('permission-list-left')) {
            return view('error');
        }
        $uid = Auth::user()->id;
        $list = $this->permissionRepository->subListTop($uid, 'permission-list-left');
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
     * 查看下一级级导航权限列表
     * getListSecond
     * zhl
     * 2017-08-16T16:05:05+0800
     * @param   Request $request    [description]
     * @return  [type]              [description]
     */
    public function getListSub(Request $request)
    {
        $data = $this->permissionRepository->getLists($request->id);

        return view('rbac.permission.list', compact('data'), ['t' => '下一级导航权限管理列表']);
    }

    /**
     * 权限增加
     * getAdd
     * zhl
     * 2017-08-16T16:12:38+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getAdd()
    {
        if (Gate::denies('permission-add')) {
            return view('error');
        }
        $permission = $this->permissionRepository->getLists();
        return view('rbac.permission.add', compact('permission'), ['t' => '权限添加']);
    }

    /**
     * 权限添加
     * postAdd
     * zhl
     * 2017-08-16T17:04:20+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postAdd(PostMatchPermissionRequest $request)
    {
        if (Gate::denies('permission-add')) {
            return view('error');
        }
        $data = [
            'name'          => $request->name,
            'label'         => $request->label,
            'yield'         => $request->yield,
            'description'   => $request->description,
            'is_menu'       => $request->is_menu,
            'fid'           => $request->fid,
            'sort'          => !empty($request->sort)?$request->sort:0
        ];

        $this->permissionRepository->add($data);

        return redirect()->back()->with('success', '新权限添加成功!');
    }

    /**
     * 权限管理修改
     * getEdit
     * zhl
     * 2017-08-16T17:41:05+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getEdit($id)
    {
        if (Gate::denies('permission-edit')) {
            return view('error');
        }
        $data = $this->permissionRepository->getList($id);

        return view('rbac.permission.edit', compact('data'), ['t' => '角色信息修改']);
    }

    /**
     * 权限信息修改
     * postEdit
     * zhl
     * 2017-08-16T17:50:55+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postEdit(PostMatchPermissionEditRequest $request)
    {
        if (Gate::denies('permission-edit')) {
            return view('error');
        }
//        $match = $this->permissionRepository->getName($request->id, $request->name);
//        if(isset($match->name)) {
//            return redirect()->back()->withErrors('权限路由有重复！');
//        }

        $permission = Permission::find($request->id);
        $permission->name       = $request->name;
        $permission->label      = $request->label;
        $permission->yield      = $request->yield;
        $permission->description= $request->description;
        $permission->is_menu    = $request->is_menu;
        $permission->fid        = $request->fid;
        $permission->sort       = !empty($request->sort)?$request->sort:0;
        $permission->save();

        return redirect()->back()->with('success', '权限信息修改成功！');
    }

    /**
     * 权限信息删除
     * postDel
     * zhl
     * 2017-08-13T17:57:43+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postDel(Request $request)
    {
        if (Gate::denies('permission-del')) {
            return view('error');
        }
        $id = $request->id;
        $data = Permission::where('fid', $id)->select('name')->first();
        if(isset($data->name)) {
            return $data = [
                'status' => 1,
                'msg'    => '需要先删除子集数据'
            ];
        }
        $role = Permission::findOrFail($id);
        $role->forceDelete();
        return $data = [
            'status' => 0,
            'msg'    => 'success'
        ];
    }

}
