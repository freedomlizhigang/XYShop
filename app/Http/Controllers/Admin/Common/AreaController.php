<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\AreaRequest;
use App\Models\Common\Area;
use App\Models\Common\Community;
use DB;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * 地区列表
     * @return [type] [description]
     */
    public function getIndex($pid = 0)
    {
    	$title = '地区管理';
        // 超级管理员可查看所有部门下地区
        $list = Area::where('parentid',$pid)->orderBy('sort','asc')->orderBy('id','asc')->get();
    	return view('admin.area.index',compact('title','list','pid'));
    }
    /**
     * 添加地区
     * @param  integer $pid [父地区ID]
     * @return [type]       [description]
     */
    public function getAdd($pid = '')
    {
    	$title = '添加地区';
    	return view('admin.area.add',compact('title','pid'));
    }
    public function postAdd(AreaRequest $res,$pid = '0')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            $resId = Area::create($data);
            // 没出错，提交事务
            DB::commit();
            return $this->adminJson(1, '添加成功！',url('console/area/index/'.$data['parentid']));
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(0, '添加失败，请稍后再试！');
        }
    }
    /**
     * 修改地区
     * @param  string $id [要修改的地区ID]
     * @return [type]     [description]
     */
    public function getEdit($id = '')
    {
        $title = '修改地区';
        $info = Area::findOrFail($id);
        return view('admin.area.edit',compact('title','info'));
    }
    public function postEdit(AreaRequest $res,$id = '')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            Area::where('id',$id)->update($data);
            // 没出错，提交事务
            DB::commit();
            return $this->adminJson(1, '修改成功！');
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(1, '修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
        $info = Area::findOrFail($id);
        // 先找出来所有子栏目
        if ($info->parentid == 0) {
            $areaid2 = Area::where('parentid',$id)->pluck('id');
            Area::whereIn('id',$areaid2)->delete();
            // 如果不为空，说明还有下一级
            if (!is_null($areaid2)) {
                $areaid3 = [];
                foreach ($areaid2 as $c) {
                    $areaid3 = array_push($areaid3,Area::where('parentid',$c)->pluck('id'));
                }
                Area::whereIn('id',$areaid3)->delete();
                Community::whereIn('areaid3',$areaid3)->delete();
            }
        }
        else
        {
            $areaid3 = Area::where('parentid',$id)->pluck('id');
            Area::whereIn('id',$areaid3)->delete();
            Community::whereIn('areaid3',$areaid3)->delete();
        }
        Area::where('id',$id)->delete();
        Community::where('areaid3',$id)->delete();
        return back()->with('message', '删除完成！');
    }
}