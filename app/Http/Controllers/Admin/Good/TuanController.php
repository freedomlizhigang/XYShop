<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Good\TuanRequest;
use App\Models\Good\Tuan;
use Illuminate\Http\Request;

class TuanController extends BaseController
{
    /**
     * 团购管理
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '团购管理';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Tuan::where(function($q) use($key){
                if ($key != '') {
                    $q->where('title','like','%'.$key.'%');
                }
            })->where(function($q) use($starttime,$endtime){
                if ($starttime != '' && $endtime != '') {
                    $q->where('starttime','>=',$starttime)->where('starttime','<=',$endtime);
                }
            })->where(function($q) use($status){
                if ($status != '') {
                    $q->where('status',$status);
                }
            })->where('delflag',1)->orderBy('id','desc')->paginate(15);
        // 记录上次请求的url path，返回时用
        session()->put('backurl',$res->fullUrl());
    	return view('admin.tuan.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加团购
    public function getAdd($id = '')
    {
    	$title = '添加团购';
    	return view('admin.tuan.add',compact('title','id'));
    }
    public function postAdd(TuanRequest $req,$id = '')
    {
        try {
    	   $data = $req->input('data');
    	   Tuan::create($data);
           return $this->ajaxReturn(1,'添加成功！');
        } catch (\Exception $e) {
            return $this->ajaxReturn(0,$e->getMessage());
        }
    }
    // 修改团购
    public function getEdit($id = '')
    {
    	$title = '修改团购';
    	$ref = session('backurl');
    	$info = Tuan::with('good')->findOrFail($id);
    	return view('admin.tuan.edit',compact('title','info','ref'));
    }
    public function postEdit(TuanRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Tuan::where('id',$id)->update($data);
    	return $this->ajaxReturn(1,'修改成功！',$req->ref);
        
    }
    // 删除
    public function getDel($id = '')
    {
    	Tuan::where('id',$id)->update(['delflag'=>0]);
    	return back()->with('message','删除成功！');
    }

    // 排序
    public function postSort(Request $req)
    {
        $ids = $req->input('sids');
        $sort = $req->input('sort');
        if (is_array($ids))
        {
            foreach ($ids as $v) {
                Tuan::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择商品！');
        }
    }
    // 批量删除
    public function postAlldel(Request $req)
    {
        $ids = $req->input('sids');
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            Tuan::whereIn('id',$ids)->update(['delflag'=>0]);
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}
