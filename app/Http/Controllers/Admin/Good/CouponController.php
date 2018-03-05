<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Http\Requests\Good\CouponRequest;
use App\Models\Good\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
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
		$list = Coupon::where(function($q) use($key){
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
    	return view('admin.coupon.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加优惠券
    public function getAdd()
    {
    	$title = '添加优惠券';
    	return view('admin.coupon.add',compact('title'));
    }
    public function postAdd(CouponRequest $req)
    {
    	$data = $req->input('data');
    	Coupon::create($data);
        return $this->adminJson(1,'添加成功！',url('/console/coupon/index'));
    }
    // 修改优惠券
    public function getEdit($id = '')
    {
    	$title = '修改优惠券';
    	$info = Coupon::findOrFail($id);
    	return view('admin.coupon.edit',compact('title','info'));
    }
    public function postEdit(CouponRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Coupon::where('id',$id)->update($data);
        return $this->adminJson(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
    	Coupon::where('id',$id)->update(['delflag'=>0]);
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
                Coupon::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
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
            Coupon::whereIn('id',$ids)->update(['delflag'=>0]);
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}
