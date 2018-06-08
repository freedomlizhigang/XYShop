<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Http\Requests\Good\TimetobuyRequest;
use App\Models\Good\Good;
use App\Models\Good\Timetobuy;
use Illuminate\Http\Request;

class TimetobuyController extends Controller
{
    /**
     * 抢购管理
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '抢购管理';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Timetobuy::where(function($q) use($key){
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
    	return view('admin.timetobuy.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加抢购
    public function getAdd($id = '')
    {
    	$title = '添加抢购';
    	return view('admin.timetobuy.add',compact('title','id'));
    }
    public function postAdd(TimetobuyRequest $req,$id = '')
    {
        try {
    	   $data = $req->input('data');
    	   $tid = Timetobuy::create($data);
    	   // 设置商品类型及活动ID
    	   Good::where('id',$data['good_id'])->update(['prom_type'=>1,'prom_id'=>$tid->id]);
           return $this->adminJson(1,'添加成功！',url('console/timetobuy/index'));
        } catch (\Throwable $e) {
            return $this->adminJson(0,$e->getMessage());
        }
    }
    // 修改抢购
    public function getEdit($id = '')
    {
    	$title = '修改抢购';
    	$ref = session('backurl');
    	$info = Timetobuy::findOrFail($id);
    	return view('admin.timetobuy.edit',compact('title','info','ref'));
    }
    public function postEdit(TimetobuyRequest $req,$id = '')
    {
    	$data = $req->input('data');
        Good::where('prom_id',$id)->where('prom_type',1)->update(['prom_type'=>0,'prom_id'=>0]);
        if ($data['status']) {
            Good::where('id',$data['good_id'])->update(['prom_type'=>1,'prom_id'=>$id]);
        }
        else
        {
            Good::where('id',$data['good_id'])->update(['prom_type'=>0,'prom_id'=>$id]);
        }
    	Timetobuy::where('id',$id)->update($data);
    	return $this->adminJson(1,'修改成功！',$req->ref);

    }
    // 删除
    public function getDel($id = '')
    {
    	// 商品属性恢复
    	Good::where('prom_id',$id)->where('prom_type',1)->update(['prom_type'=>0,'prom_id'=>0]);
    	Timetobuy::where('id',$id)->update(['delflag'=>0]);
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
                Timetobuy::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
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
	    	// 商品属性恢复
	    	Good::whereIn('prom_id',$ids)->where('prom_type',1)->update(['prom_type'=>0,'prom_id'=>0]);
            Timetobuy::whereIn('id',$ids)->update(['delflag'=>0]);
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}
