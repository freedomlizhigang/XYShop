<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Good\YouhuiquanRequest;
use App\Models\Youhuiquan;
use Illuminate\Http\Request;

class YouhuiquanController extends BaseController
{
    /**
     * 优惠券管理
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '优惠券管理';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Youhuiquan::where(function($q) use($key){
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
    	return view('admin.youhuiquan.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加优惠券
    public function getAdd()
    {
    	$title = '添加优惠券';
    	return view('admin.youhuiquan.add',compact('title'));
    }
    public function postAdd(YouhuiquanRequest $req)
    {
    	$data = $req->input('data');
    	Youhuiquan::create($data);
        return $this->ajaxReturn(1,'添加成功！',url('/console/youhuiquan/index'));
    }
    // 修改优惠券
    public function getEdit($id = '')
    {
    	$title = '修改优惠券';
    	$info = Youhuiquan::findOrFail($id);
    	return view('admin.youhuiquan.edit',compact('title','info'));
    }
    public function postEdit(YouhuiquanRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Youhuiquan::where('id',$id)->update($data);
        return $this->ajaxReturn(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
    	Youhuiquan::where('id',$id)->update(['delflag'=>0]);
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
                Youhuiquan::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
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
            Youhuiquan::whereIn('id',$ids)->update(['delflag'=>0]);
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}
