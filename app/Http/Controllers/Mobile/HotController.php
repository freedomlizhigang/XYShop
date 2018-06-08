<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Good\Coupon;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Promotion;
use Illuminate\Http\Request;

class HotController extends Controller
{
    // 活动列表
    public function getHot()
    {
        try {
            // 排序方式
            $list = Promotion::where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->simplePaginate(20);
            $pos_id = 'hot';
            $title = '促销活动';
            return view(cache('config')['theme'].'.hot',compact('pos_id','title','list'));
        } catch (\Throwable $e) {
            // dd($e);
            return view('errors.404');
        }
    }
    // 活动商品列表
    public function getHotList(Request $req,$id = 0)
    {
        try {
            $promotion = Promotion::where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->findOrFail($id);
            // 排序方式
            $sort = isset($req->sort) ? $req->sort : 'sort';
            $sc = isset($req->sc) ? $req->sc : 'desc';
            $list = Good::where('prom_type',4)->where('prom_id',$id)->where('status',1)->select('id','title','shop_price','thumb','prom_type','is_new','is_pos','is_hot')->orderBy($sort,$sc)->orderBy('id','desc')->simplePaginate(20);
            $pos_id = 'hot';
            $title = $promotion->title;
            return view(cache('config')['theme'].'.list',compact('pos_id','title','list','sort','sc'));
        } catch (\Throwable $e) {
            // dd($e);
            return view('errors.404');
        }
    }
    // 商品详情页面
    public function getGood($id = '')
    {
        try {
            // 分享用的
            $wechat_js = app('wechat.official_account')->jssdk;
            $good = Good::findOrFail($id);
            /*
            * 查出来所有的规格信息
            * 1、找出所有的规格ID
            * 2，查出所有的规格ID对应的名字spec_item及spec内容
            * 3、循环出来所有的规格及规格值
            * */
            $good_spec_ids = GoodSpecPrice::where('good_id',$id)->pluck('item_id')->toArray();
            $good_spec_ids = explode('_',implode('_',$good_spec_ids));
            $good_spec = GoodSpecItem::with(['goodspec'=>function($q){
                            $q->select('id','name');
                        }])->whereIn('id',$good_spec_ids)->get();
            $filter_spec = [];
            foreach ($good_spec as $k => $v) {
                $filter_spec[$v->goodspec->name][] = ['item_id'=>$v->id,'item'=>$v->item];
            }
            // 查出第一个规格信息来，标红用的
            $good_spec_price = GoodSpecPrice::where('good_id',$id)->get()->keyBy('item_id')->toJson();
            // 找出来可以用的优惠券
            $date = date('Y-m-d H:i:s');
            $coupon = Coupon::where('starttime','<=',$date)->where('endtime','>=',$date)->where('delflag',1)->where('status',1)->orderBy('sort','desc')->orderBy('id','desc')->limit(3)->get();
            $title = $good->title;
            $keyword = $good->keyword;
            $describe = $good->describe;
            // 如果是活动里的商品，取出来活动的信息
            $prom_val = $prom_title = '';
            $promotion = Promotion::where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->where('id',$good->prom_id)->first();
            if (!is_null($promotion)) {
                $prom_val = $promotion->type === 1 ? ($promotion->type_val/10)." 折" : "减 $promotion->type_val 元";
                $prom_title = $promotion->title;
            }
            return view(cache('config')['theme'].'.hotgood',compact('title','keyword','describe','good','good_spec_price','filter_spec','coupon','prom_title','prom_val','wechat_js'));
        } catch (\Throwable $e) {
            // dd($e);
            return view('errors.404');
        }
    }
}
