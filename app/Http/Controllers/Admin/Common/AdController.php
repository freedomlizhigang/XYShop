<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Common\AdRequest;
use App\Models\Common\Ad;
use App\Models\Common\Adpos;
use Illuminate\Http\Request;

class AdController extends BaseController
{
    /**
     * 广告管理
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '广告管理';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Ad::where(function($q) use($key){
                if ($key != '') {
                    $q->where('title','like','%'.$key.'%');
                }
            })->where(function($q) use($starttime,$endtime){
                if ($starttime != '' && $endtime != '') {
                    $q->where('created_at','>=',$starttime)->where('created_at','<=',$endtime);
                }
            })->where(function($q) use($status){
                if ($status != '') {
                    $q->where('status',$status);
                }
            })->orderBy('id','desc')->paginate(15);
        // 记录上次请求的url path，返回时用
        session()->put('backurl',$res->fullUrl());
    	return view('admin.ad.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加广告
    public function getAdd($id = '')
    {
    	$title = '添加广告';
    	$pos = Adpos::orderBy('id','asc')->get();
    	return view('admin.ad.add',compact('title','id','pos'));
    }
    public function postAdd(AdRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Ad::create($data);
        return $this->ajaxReturn(1,'添加成功！',url('/console/ad/index'));
    }
    // 修改广告
    public function getEdit($id = '')
    {
    	$title = '修改广告';
    	$pos = Adpos::orderBy('id','asc')->get();
    	$info = Ad::findOrFail($id);
    	return view('admin.ad.edit',compact('title','info','pos'));
    }
    public function postEdit(AdRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Ad::where('id',$id)->update($data);
        return $this->ajaxReturn(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
    	Ad::where('id',$id)->delete();
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
                Ad::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择广告！');
        }
    }
    // 批量删除
    public function postAlldel(Request $req)
    {
        $ids = $req->input('sids');
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            Ad::whereIn('id',$ids)->delete();
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择广告！');
        }
    }
}
