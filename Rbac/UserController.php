<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Requests\Rbac\PostMatchNameRequest;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Repositories\AdminRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @var $adminRepository
     */
    protected $adminRepository;

    protected $permissionRepository;

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
        PermissionRepository $permissionRepository
    ) {
        $this->adminRepository = $adminRepository;
        $this->permissionRepository = $permissionRepository;
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
        if (Gate::denies('rbac-list-top')) {
            return view('error');
        }
        $uid = Auth::user()->id;
        $list = $this->permissionRepository->subListTop($uid, 'rbac-list-top');
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
     * 用户管理列表
     * getList
     * zhl
     * 2017-08-09T09:16:40+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getList()
    {
        if (Gate::denies('rbac-list')) {
            return view('error');
        }
        $data = $this->adminRepository->getLists();

        return view('rbac.user.list', $data, ['t' => '用户信息列表']);
    }

    /**
     * 用户信息修改
     * getEdit
     * zhl
     * 2017-08-09T14:42:39+0800
     * @param   integer $id         [用户ID]
     * @return  [type]              [description]
     */
    public function getEdit($id)
    {
        if (Gate::denies('user-edit')) {
            return view('error');
        }
        $data = $this->adminRepository->getList($id);

        return view('rbac.user.edit', $data, ['t' => '用户信息修改']);
    }

    /**
     * 用户信息保存
     * postEdit
     * zhl
     * 2017-08-10T08:44:15+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postEdit(PostMatchNameRequest $request)
    {
        if (Gate::denies('user-edit')) {
            return view('error');
        }

        $match = $this->adminRepository->getUid($request->id, $request->name);
        if(isset($match->name)) {
            return redirect()->back()->withErrors('用户名有重复！');
        }

        $user = Admin::find($request->id);
        $user->name = $request->name;
        $user->cellphone = $request->cellphone;
        $user->password = Hash::make($request->password);
        $user->save();
        $role = AdminRole::where('admin_id', $request->id)->update(['role_id' => $request->rid]);

        return redirect()->back()->with('success', '用户信息修改成功！');
    }

    /**
     * 用户删除
     * postDel
     * zhl
     * 2017-08-13T17:57:43+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postDel(Request $request)
    {
        if (Gate::denies('user-del')) {
            return view('error');
        }
        $id = $request->id;
        $this->adminRepository->del($id);
    }

    /**
     * 用户添加
     * getAdd
     * zhl
     * 2017-08-10T13:32:01+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function getAdd()
    {
        if (Gate::denies('user-add')) {
            return view('error');
        }

        $data = $this->adminRepository->AllRole();

        return view('rbac.user.add', compact('data'), ['t' => '用户信息添加']);
    }

    /**
     * 用户添加
     * postAdd
     * zhl
     * 2017-08-10T10:00:24+0800
     * @param   Request $request [description]
     * @return  [type]              [description]
     */
    public function postAdd(PostMatchNameRequest $request)
    {
        if (Gate::denies('user-add')) {
            return view('error');
        }
        $match = $this->adminRepository->getUid('0', $request->name);
        if(isset($match->name)) {
            return redirect()->back()->withErrors('用户名有重复！');
        }
        $cellphone = $this->adminRepository->getCellphone($request->cellphone);
        if(isset($cellphone->name)) {
            return redirect()->back()->withErrors('登录账号有重复！');
        }

        $data = [
            'name'        => $request->name,
            'cellphone'   => $request->cellphone,
            'password'    => Hash::make($request->password)
        ];
        $this->adminRepository->postAdd($data);

        $id = $this->adminRepository->getName($request->name);
        DB::table('admin_role')->insert(array(
            array('admin_id' => $id->id, 'role_id' => $request['rid'])
        ));
//        $roleUser = new UserController;
//        $roleUser->user_id = $id->id;
//        $roleUser->role_id = $request->rid;
//        $dataRoleUser = [
//            'user_id'     => $id->id,
//            'role_id'     => $request->rid
//        ];
//        $this->adminRepository->create($roleUser);

        return redirect()->back()->with('success', '用户添加成功！');
    }

}
