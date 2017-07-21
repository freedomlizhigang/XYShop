<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Section;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    /**
     * 构造函数
     */
    public function __construct()
    {
    	$this->admin = new Admin;
        $this->role = new Role;
    }
    public function getIndex(Request $res)
    {
    	$title = '用户列表';
        $key = isset($res->q) ? $res->q : 0;
        $list = $this->admin->with(['section','role'])->where(function($d) use($key){
            if($key)
            {
                $d->where('name','like','%'.$key.'%')->orWhere('realname','like','%'.$key.'%');
            }
        })->paginate(15);
        return view('admin.user.index',compact('list','title','key'));
    }
    // 添加用户
    public function getAdd(Request $res)
    {
        $title = '添加用户';
        $section = Section::where('status',1)->get();
        $rolelist = $this->role->where('status',1)->get();
        return view('admin.user.add',compact('title','rolelist','section'));
    }
    public function postAdd(AdminRequest $request)
    {
        $data = $request->input('data');
        unset($data['password_confirmation']);
        $data['password'] = bcrypt($request->input('data.password'));
        $data['lastip'] = $request->ip();
        $data['lasttime'] = Carbon::now();
        // 添加，事务
        DB::beginTransaction();
        try {
            $admin = $this->admin->create($data);
            $rids = $request->role_id;
            if (is_array($rids)) {
                $rdata = [];
                foreach ($rids as $k) {
                    $rdata[] = ['role_id'=>$k,'user_id'=>$admin->id];
                }
            }
            RoleUser::insert($rdata);
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1,'添加用户成功！',url('/console/admin/index'));
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->ajaxReturn(0,'添加失败，请稍后再试！');
        }
    }
    // 修改资料
    public function getEdit($uid)
    {
        $title = '修改资料';
        $rolelist = $this->role->where('status',1)->get();
        $section = Section::where('status',1)->get();
        $info = $this->admin->with('role')->findOrFail($uid);
        $rids = '';
        foreach ($info->role as $r) {
            $rids .= "'".$r->id."',";
        }
        return view('admin.user.edit',compact('title','info','rolelist','section','rids'));
    }
    public function postEdit(AdminRequest $request,$uid)
    {
        $data = $request->input('data');
        // 添加，事务
        DB::beginTransaction();
        try {
            $this->admin->where('id',$uid)->update($data);
            $rids = $request->role_id;
            // 先删除再添加
            RoleUser::where('user_id',$uid)->delete();
            if (is_array($rids)) {
                $rdata = [];
                foreach ($rids as $k) {
                    $rdata[] = ['role_id'=>$k,'user_id'=>$uid];
                }
            }
            RoleUser::insert($rdata);
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1,'修改用户成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->ajaxReturn(0,'修改失败，请稍后再试！');
        }
    }
    // 修改密码
    public function getPwd($uid)
    {
        $title = '修改密码';
        // 拼接返回用的url参数
        $info = $this->admin->findOrFail($uid);
        return view('admin.user.pwd',compact('title','info'));
    }
    public function postPwd(AdminRequest $req,$uid)
    {
        $crypt = str_random(10);
        $pwd = app('com')->makepwd($req->input('data.password'),$crypt);
        $this->admin->where('id',$uid)->update(['password'=>$pwd,'crypt'=>$crypt]);
        return $this->ajaxReturn(1,'修改密码成功！');
    }
    // 删除用户
    public function getDel($uid)
    {
        if($uid != 1)
        {
            $this->admin->destroy($uid);
            RoleUser::where('user_id',$uid)->delete();
            return back()->with('message', '删除用户成功！');
        }
        else
        {
            return back()->with('message', '超级管理员不能被删除！');
        }
    }

    // 个人修改资料
    public function getMyedit()
    {
        $title = '修改个人资料';
        $info = $this->admin->with('role')->findOrFail(session('console')->id);
        return view('admin.user.myedit',compact('title','info'));
    }
    public function postMyedit(AdminRequest $request)
    {
        $data = $request->input('datas');
        $this->admin->where('id',session('console')->id)->update($data);
        return $this->ajaxReturn(1,'修改个人资料成功！');
    }
    // 修改密码
    public function getMypwd()
    {
        $title = '修改密码';
        $info = $this->admin->findOrFail(session('console')->id);
        return view('admin.user.mypwd',compact('title','info'));
    }
    public function postMypwd(AdminRequest $req)
    {
        $crypt = str_random(10);
        $pwd = app('com')->makepwd($req->input('data.password'),$crypt);
        $res = $this->admin->where('id',session('console')->id)->update(['password'=>$pwd,'crypt'=>$crypt]);
        if ($res) {
            \Session::put('console',null);
            return $this->ajaxReturn(1,'修改密码成功，请登陆登录！',url('/console/login'));
        }
        else
        {
            return $this->ajaxReturn(0,'修改密码失败！');
        }
    }
}
