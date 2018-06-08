<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Models\Common\Pay;
use App\Models\Good\Cart;
use App\Models\Good\Coupon;
use App\Models\Good\Order;
use App\Models\User\Address;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ShopController extends BaseController
{
    // 购物车
    public function getCart()
    {
        $seo = ['title'=>'购物车 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        // 找出购物车
        $goods = Cart::with(['good'=>function($q){
                    $q->select('id','thumb');
                }])->where('user_id',session('member')->id)->orderBy('updated_at','desc')->get();
        // 如果购物车为空，显示空模板
        if ($goods->count() == 0) {
            return view($this->theme.'.cart_empty',compact('seo'));
        }
        $goodlists = [];
        $total_prices = 0;
        // 如果有购物车
        // 循环查商品，方便带出属性来
        foreach ($goods as $k => $v) {
            $goodlists[$k] = $v;
            $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
            $goodlists[$k]['total_prices'] = $tmp_total_price;
            $total_prices += $tmp_total_price;
        }
        // 找出所有商品来
        $total_prices = number_format($total_prices,2,'.','');
        return view($this->theme.'.cart',compact('goodlists','seo','total_prices'));
    }
    // 提交订单
    public function getOrderinfo(Request $req)
    {
        $seo = ['title'=>'订单确认页 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        // 找出购物车
        $cid = explode('.', trim(session('order_info_'.$req->rid),'.'));
        $goods = Cart::with(['good'=>function($q){
                    $q->select('id','thumb');
                }])->whereIn('id',$cid)->where('user_id',session('member')->id)->orderBy('updated_at','desc')->get();
        $goodlists = [];
        $total_prices = 0;
        // 如果有购物车
        // 循环查商品，方便带出属性来
        foreach ($goods as $k => $v) {
            $goodlists[$k] = $v;
            $tmp_total_price = number_format($v->nums * $v->price,2,'.','');
            $goodlists[$k]['total_prices'] = $tmp_total_price;
            $total_prices += $tmp_total_price;
        }
        // 找出所有商品来
        $total_prices = number_format($total_prices,2,'.','');
        // 送货地址
        $address = Address::where('user_id',session('member')->id)->orderBy('id','desc')->where('delflag',1)->get();
        // 找出来可以的优惠券
        $coupon = Coupon::where('price','<=',$total_prices)->where('starttime','=<',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->orderBy('sort','desc')->orderBy('id','desc')->where('delflag',1)->get();
        return view($this->theme.'.orderinfo',compact('goodlists','seo','total_prices','address','coupon'));
    }
    // 提交结算页面
    public function postOrderinfo(Request $req)
    {
        try {
            $cid = $req->cid;
            $time = time();
            $sname = 'order_info_'.$time;
            session()->put($sname,$cid);
            echo json_encode(['code'=>1,'msg'=>$time]);
            return;
        } catch (\Throwable $e) {
            exit(json_encode(['code'=>0,'msg'=>$e->getMessage()]));
            return;
        }
    }
    // 提交订单完成，付款页面
    public function getOrderpay(Request $req,$oid ='')
    {
        try {
            $seo = ['title'=>'订单结算页 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
            $order = Order::findOrFail($oid);
            $info = (object)['pid'=>3];
            $paylist = Pay::where('status',1)->where('paystatus',1)->orderBy('id','asc')->get();
            return view($this->theme.'.pay',compact('info','order','paylist','seo'));
        } catch (\Throwable $e) {
            dd($e);
        }
    }
    // 订单列表
    public function getOrder(Request $req,$status = 1)
    {
        $info = (object) ['pid'=>4];
        $orders = Order::with(['good'=>function($q){
                    $q->with('good');
                }])->where('status',1)->where('user_id',session('member')->id)->where(function($q) use($status){
                    // 找出订单
                    switch ($status) {
                        // 待评价
                        case '4':
                            $q->whereIn('orderstatus',[2,0]);
                            break;
                        // 待收货
                        case '3':
                            $q->where('paystatus',1)->where('orderstatus',1)->where('shipstatus',1);
                            break;
                        // 待发货
                        case '2':
                            $q->where(['paystatus'=>1,'shipstatus'=>0,'orderstatus'=>1]);
                            break;
                        // 待付款
                        default:
                            $q->where(['paystatus'=>0,'orderstatus'=>1]);
                            break;
                    }
                })->orderBy('id','desc')->simplePaginate(10);
                // ->simplePaginate(10)
        return view($this->theme.'.order',compact('info','orders','status'));
    }
    // 退货申请
    public function getTui($id = '',$gid = '')
    {
        $info = (object) ['pid'=>4];
        return view($this->theme.'.tui',compact('info'));
    }
    public function postTui(Request $req,$id = '',$gid = '')
    {
        // Order::where('id',$id)->update(['orderstatus'=>3]);
        // 先查出来具体的订单商品信息
        $og = OrderGood::where('order_id',$id)->where('good_id',$gid)->first();
        $data = ['user_id'=>$og->user_id,'order_id'=>$og->order_id,'good_id'=>$og->good_id,'good_title'=>$og->good_title,'good_spec_key'=>$og->good_spec_key,'good_spec_name'=>$og->good_spec_name,'nums'=>$og->nums,'price'=>$og->price,'total_prices'=>$og->total_prices,'mark'=>$req->mark];
        OrderGood::where('order_id',$id)->where('good_id',$gid)->update(['status'=>0]);
        ReturnGood::create($data);
        return back()->with('message','退货申请已提交');
    }
    // 订单评价
    public function getComment($oid = '',$gid = '')
    {
        $info = (object) ['pid'=>4];
        // 记录上次请求的url path，返回时用
        $ref = url()->previous();
        return view($this->theme.'.good_comment',compact('info','gid','oid','ref'));
    }
    public function postComment(GoodCommentRequest $req,$oid = '',$gid = '')
    {
        GoodComment::create(['good_id'=>$gid,'user_id'=>session('member')->id,'title'=>$req->input('data.title'),'content'=>$req->input('data.content'),'score'=>$req->input('data.score')]);
        OrderGood::where('good_id',$gid)->where('order_id',$oid)->update(['commentstatus'=>1]);
        // 评价数+1
        Good::where('id',$gid)->increment('commentnums');
        return redirect($req->ref)->with('message','评价成功！');
    }
    // 确认收货
    public function getShip($oid = '')
    {
        $info = (object) ['pid'=>4];
        Order::where('id',$oid)->update(['orderstatus'=>2,'confirm_at'=>date('Y-m-d H:i:s')]);
        return redirect('/user/order/4')->with('message','收货成功！');
    }
}
