<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends BaseController
{
    /**
     * 社区列表
     * @return [type] [description]
     */
    public function getIndex(Request $req)
    {
    	$title = '社区管理';
        $q = $req->input('q','');
        // 超级管理员可查看所有部门下社区
        $list = Community::where(function($r)use($q){
            if ($q != '') {
                $r->where('name','like','%$q%');
            }
        })->orderBy('id','desc')->paginate(15);
    	return view('admin.community.index',compact('title','list','q'));
    }
    /**
     * 添加社区
     * @param  integer $pid [父社区ID]
     * @return [type]       [description]
     */
    public function getAdd()
    {
    	$title = '添加社区';
    	return view('admin.community.add',compact('title'));
    }
    public function postAdd(AreaRequest $res)
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            $resId = Community::create($data);
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1, '添加成功！',url('console/community/index'));
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->ajaxReturn(0, '添加失败，请稍后再试！');
        }
    }
    /**
     * 修改社区
     * @param  string $id [要修改的社区ID]
     * @return [type]     [description]
     */
    public function getEdit($id = '')
    {
        $title = '修改社区';
        $info = Community::findOrFail($id);
        return view('admin.community.edit',compact('title','info'));
    }
    public function postEdit(AreaRequest $res,$id = '')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            Community::where('id',$id)->update($data);
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1, '修改成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->ajaxReturn(1, '修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
        Community::where('id',$id)->delete();
        return back()->with('message', '删除完成！');
    }
}
