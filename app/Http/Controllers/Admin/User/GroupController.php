<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Models\User;
use Cache;
use DB;
use Illuminate\Http\Request;

class GroupController extends BaseController
{
    public function getIndex(Request $res)
    {
    	$title = '用户组列表';
        $list = Group::where('status',1)->orderBy('id','asc')->paginate(15);
        return view('admin.group.index',compact('list','title'));
    }

    // 添加用户组
    public function getAdd()
    {
        $title = '添加用户组';
        return view('admin.group.add',compact('title'));
    }

    public function postAdd(GroupRequest $request)
    {
        $data = $request->input('data');
        Group::create($data);
        $this->groupCache();
        return $this->ajaxReturn(1,'添加用户组成功！',url('/console/group/index'));
    }
    // 修改用户组
    public function getEdit($id)
    {
        $title = '修改用户组';
        // 拼接返回用的url参数
        $info = Group::findOrFail($id);
        return view('admin.group.edit',compact('title','info'));
    }
    public function postEdit(GroupRequest $request,$id)
    {
        Group::where('id',$id)->update($request->input('data'));
        $this->groupCache();
        return $this->ajaxReturn(1,'修改用户组成功！');
    }
    // 删除用户组
    public function getDel($id)
    {
        // 查询下属用户
        if(is_null(User::where('gid',$id)->first()))
        {
            // 开启事务
            DB::beginTransaction();
            try {
                // 同时删除关联的用户关系
                Group::where('id',$id)->update(['status'=>0]);
                // 没出错，提交事务
                DB::commit();
                $this->groupCache();
                return back()->with('message', '删除用户组成功！');
            } catch (Exception $e) {
                // 出错回滚
                DB::rollBack();
                return back()->with('message','删除失败，请稍后再试！');
            }
        }
        else
        {
            return back()->with('message', '用户组下有用户！');
        }
    }
    // 缓存会员组信息
    public function groupCache()
    {
        $data = Group::where('status',1)->select('id','name','points','discount')->get()->keyBy('id')->toArray();
        // 更新缓存
        Cache::forever('group',$data);
    }
}
