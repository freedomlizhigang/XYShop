<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Good\TuiRequest;
use App\Models\Order;
use App\Models\ReturnGood;
use App\Models\User;
use DB;
use Excel;
use Illuminate\Http\Request;

class RetrunGoodController extends BaseController
{
    // 查列表
    public function getIndex(Request $req)
    {
    	$title = '退货管理';
        // 搜索关键字
        $q = $req->input('q');
        $key = $req->input('key');
        $starttime = $req->input('starttime');
        $endtime = $req->input('endtime');
        $status = $req->input('status');
		$list = ReturnGood::with(['user'=>function($q){
    				$q->select('id','nickname','username');
    			},'order'=>function($q){
                    $q->select('id','order_id');
                }])->where(function($r) use($q){
                    if ($q != '') {
                        // 查出来用户ID
                        $uid = User::where('nickname','like',"%$q%")->orWhere('phone','like',"%$q%")->pluck('id')->toArray();
                        $r->whereIn('user_id',$uid);
                    }
                })->where(function($q) use($starttime,$endtime){
                    if ($starttime != '' && $endtime != '') {
                        $q->where('created_at','>=',$starttime)->where('created_at','<=',$endtime);
                    }
                })->where(function($q) use($key){
                    if ($key != '') {
                        $q->where('good_title','like',"%$key%");
                    }
                })->where(function($q) use($status){
                    if ($status != '') {
                        $q->where('status',$status);
                    }
                })->where('delflag',1)->orderBy('id','desc')->paginate(15);
    	return view('admin.tui.index',compact('title','list','starttime','endtime','status','q','key'));
    }
    // 导出
    public function getExcel(Request $req)
    {
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $status = $req->input('status','');
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('1970-00-00 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $returngoods = ReturnGood::with(['user'=>function($q) {
                    $q->select('id','username','nickname');
                }])->where(function($q)use($status){
                    if ($status != '') {
                        $q->where('status',$status);
                    }
                })->where('created_at','>',$starttime)->where('created_at','<',$endtime)->orderBy('id','desc')->get();
        // 查出所有订单的送货地址
        $orders = Order::with('address')->whereIn('id',$returngoods->pluck('order_id'))->select('id','order_id','address_id')->get()->keyBy('id')->toArray();
        $tmp = [];
        foreach ($returngoods as $v) {
            $username = is_null($v->user) ? '' : $v->user->username.' - '.$v->user->nickname;
            if ($v->status == 0) {
                $status = '未处理';
            }
            elseif($v->status == 1)
            {
                $status = '已退';
            }else
            {
                $status = '驳回';
            }
            $address = '';
            if (isset($orders[$v->order_id]['address'])) {
                $ss = $orders[$v->order_id]['address'];
                $address .= $ss['people'].' : '.$ss['phone'].' — '.$ss['area'].$ss['address'];
            }
            $tmp[] = [$v->id,$orders[$v->order_id]['order_id'],$v->good_title.' - '.$v->good_spec_name,$v->nums,$v->total_prices,$status,$username,$v->mark,$address,$v->created_at,$v->return_time,$v->shopmark];
        }
        $cellData = array_merge(
            [['ID','单号','商品','数量','金额','状态','用户','用户备注','地址','申请时间','处理时间','处理意见']],$tmp
        );
        Excel::create('退货表',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    // 处理
    public function getStatus($id = '')
    {
    	$title = '处理退货请求';
    	return view('admin.tui.status',compact('title','id'));
    }
    public function postStatus(TuiRequest $req,$id = '')
    {
        // 更新为关闭，退款到余额里
        DB::transaction(function() use ($id,$req){
            ReturnGood::where('id',$id)->update($req->input('data'));
            // 如果同意退货
            if ($req->input('data.status') == 1) {
                $order = ReturnGood::findOrFail($id);
                User::where('id',$order->user_id)->increment('user_money',$order->total_prices);
                // 消费记录
                app('com')->consume($order->user_id,$order->order_id,$order->total_prices,'退货返现',1);
            }
        });
        return $this->ajaxReturn(1,'处理成功！');
    }
}
