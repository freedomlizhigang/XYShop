<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Common\BaseController;
use App\Http\Requests\Good\GoodCommentRequest;
use App\Models\Common\Ad;
use App\Models\Good\Cart;
use App\Models\Good\Good;
use App\Models\Good\GoodCate;
use App\Models\Good\GoodComment;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Manzeng;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\Good\ReturnGood;
use App\Models\Good\YhqUser;
use App\Models\Good\Youhuiquan;
use App\Models\Good\Zitidian;
use App\Models\User\Address;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ShopController extends BaseController
{
    /*
     * 分类页面
     * 添加筛选功能 
     */
    public function getGoodcate(Request $req,$id = 0)
    {   
        // 如果没有标明分类，取第一个
        if ($id == 0) {
            $info = GoodCate::where('parentid',0)->where('status',1)->orderBy('sort','asc')->orderBy('id','asc')->first();
        }
        else
        {
            $info = GoodCate::findOrFail($id);
        }
        // 如果当前分类下没有子分类，直接跳转到产品上
        if ($id == $info->arrchildid) {
            return redirect("/shop/goodlist/$id");
        }
        // 找出所有的一级分类来
        $allcate = GoodCate::where('parentid',0)->where('status',1)->orderBy('sort','asc')->orderBy('id','asc')->get();
        // 找当前分类的所有子分类
        $childid = explode(',',$info->arrchildid);
        unset($childid[0]);
        $subcate = GoodCate::whereIn('id',$childid)->where('status',1)->orderBy('sort','asc')->orderBy('id','asc')->get();
        // 找出来广告们
        $info->pid = 2;
        $ad = Ad::where('pos_id',2)->where('status',1)->get()->random();
        return view($this->theme.'.goodcate',compact('info','allcate','subcate','ad'));
        
    }
    // 一级分类直接显示商品页面
    public function getGoodlist(Request $req,$id = 0)
    {   
        // 如果没有标明分类，取第一个
        $info = GoodCate::findOrFail($id);
        $info->pid = 2;
        $sort = isset($req->sort) ? $req->sort : 'sort';
        $sc = isset($req->sc) ? $req->sc : 'desc';
        $list = Good::whereIn('cate_id',explode(',',$info->arrchildid))->where('status',1)->orderBy($sort,$sc)->orderBy('id','desc')->simplePaginate(20);
        switch ($sort) {
            case 'sales':
                $active = 2;
                break;

            case 'id':
                $active = 3;
                break;

            case 'price':
                $active = 4;
                break;
            
            default:
                $active = 1;
                break;
        }
        return view($this->theme.'.goodlist',compact('info','list','active','sort','sc'));
    }
    /*
     * 当传了属性时，按属性值计算，没传时按第一个计算
     */
    public function getGood($id = '')
    {
        // 查出来商品信息，关联查询出对应属性及属性名称
        $info = Good::with(['goodattr'=>function($q){
                    $q->with('goodattr');
                }])->findOrFail($id);
        /*
        * 查出来所有的规格信息
        * 1、找出所有的规格ID 
        * 2，查出所有的规格ID对应的名字spec_item及spec内容
        * 3、循环出来所有的规格及规格值
        * */
        $good_spec_ids = GoodSpecPrice::where('good_id',$id)->pluck('key')->toArray();
        $good_spec_ids = explode('_',implode('_',$good_spec_ids));
        $good_spec = GoodSpecItem::with(['goodspec'=>function($q){
                        $q->select('id','name');
                    }])->whereIn('id',$good_spec_ids)->get();
        $filter_spec = [];
        foreach ($good_spec as $k => $v) {
            $filter_spec[$v->goodspec->name][] = ['item_id'=>$v->id,'item'=>$v->item];
        }
        // 查出第一个规格信息来，标红用的
        $good_spec_price = GoodSpecPrice::where('good_id',$id)->get()->keyBy('key')->toJson();

        // 取评价，20条
        $goodcomment = GoodComment::with(['user'=>function($q){
                $q->select('id','nickname','thumb','username');
            }])->where('good_id',$id)->where('delflag',1)->orderBy('id','desc')->limit(20)->get();
        $havyhq = Youhuiquan::where('starttime','<',date('Y-m-d H:i:s'))->where('endtime','>',date('Y-m-d H:i:s'))->where('nums','>',0)->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->limit(2)->get();
        
        $info->pid = 0;
        return view($this->theme.'.good',compact('info','goodcomment','havyhq','good_spec_price','filter_spec'));
    }
    // 直接购买
    public function getFirstOrder(Request $req)
    {
        try {
            return redirect('/shop/cart');
        } catch (\Exception $e) {
            return back()->with('message','购买失败，请稍后再试！');
        }
    }
    // 购物车
    public function getCart()
    {
        $info = (object) ['pid'=>3];
        // 找出购物车
        $goods = Cart::with(['good'=>function($q){
                    $q->select('id','thumb');
                }])->where(function($q){
                if (!is_null(session('member'))) {
                    $q->where('user_id',session('member')->id);
                }
                else
                {
                    $q->where('session_id',session()->getId());
                }
            })->orderBy('updated_at','desc')->get();
        // 如果购物车为空，显示空模板
        if ($goods->count() == 0) {
            return view($this->theme.'.cart_empty',compact('info'));
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
        // 查此用户的所有可用优惠券
        $yhq = YhqUser::with('yhq')->where('user_id',session('member')->id)->where('endtime','>',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->get();
        // 送货地址
        $address = Address::where('user_id',session('member')->id)->where('delflag',1)->get();
        // 满赠列表
        $mz = Manzeng::with(['good'=>function($q){
                $q->select('id','title');
            }])->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('price','asc')->get();
        // 自提点
        $ziti = Zitidian::where('status',1)->where('delflag',1)->orderBy('sort','desc')->get();
        return view($this->theme.'.cart',compact('goods','goodlists','info','total_prices','yhq','address','mz','ziti'));
    }
    // 提交订单
    public function getAddorder(Request $req,$oid = '')
    {
        try {
            $order = Order::findOrFail($oid);
            $info = (object)['pid'=>3];
            $paylist = Pay::where('status',1)->where('paystatus',1)->orderBy('id','asc')->get();
            return view($this->theme.'.addorder',compact('info','order','paylist'));
        } catch (\Exception $e) {
            return back()->with('message','添加失败，请稍后再试！');
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
