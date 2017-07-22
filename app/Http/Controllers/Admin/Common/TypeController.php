<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Common\TypeRequest;
use App\Models\Common\Type;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class TypeController extends BaseController
{
    /**
     * 分类列表
     * @return [type] [description]
     */
    public function getIndex($pid = 0)
    {
    	$title = '分类管理';
        // 超级管理员可查看所有部门下分类
        $list = Type::where('parentid',$pid)->orderBy('sort','asc')->get();
    	return view('admin.type.index',compact('title','list','pid'));
    }
    /**
     * 添加分类
     * @param  integer $pid [父分类ID]
     * @return [type]       [description]
     */
    public function getAdd($pid = '0')
    {
    	$title = '添加分类';
    	return view('admin.type.add',compact('title','pid'));
    }
    public function postAdd(TypeRequest $res,$pid = '0')
    {
        // 开启事务
        try {
            $data = $res->input('data');
            $resId = Type::create($data);
            // 后台用户组权限
            app('com')->updateCache(new Type,'typeCache');
            return $this->ajaxReturn(1,'添加成功！');
        } catch (Exception $e) {
            return $this->ajaxReturn(0,'添加失败，请稍后再试！');
        }
    }
    /**
     * 修改分类
     * @param  string $id [要修改的分类ID]
     * @return [type]     [description]
     */
    public function getEdit($id = '')
    {
        $title = '修改分类';
        $info = Type::findOrFail($id);
        $all = Type::orderBy('sort','asc')->get();
        $tree = app('com')->toTree($all,'0');
        $treeHtml = app('com')->toTreeSelect($tree,$info->parentid);
        return view('admin.type.edit',compact('title','info','treeHtml'));
    }
    public function postEdit(TypeRequest $res,$id = '')
    {
        try {
            $data = $res->input('data');
            Type::where('id',$id)->update($data);
            // 更新缓存
            app('com')->updateCache(new Type,'typeCache');
            return $this->ajaxReturn(1,'修改成功！');
        } catch (Exception $e) {
            return $this->ajaxReturn(0,'修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
        // 先找出所有子分类，再判断子分类中是否有文章，如果有文章，返回错误
        $allChild = Type::where('id',$id)->value('arrchildid');
        // 所有子分类ID转换为集合，查看是否含有文章或者专题
        $childs = collect(explode(',',$allChild));
        try {
            Type::destroy($childs);
            $message = '删除成功！';
        } catch (Exception $e) {
            return back()->with('message','删除失败，请稍后再试！');
        }
        return back()->with('message', $message);
    }
}
