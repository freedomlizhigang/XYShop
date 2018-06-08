<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\Timetobuy;
use Illuminate\Http\Request;

class TimetobuyController extends Controller
{
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
            $title = $good->title;
            $keyword = $good->keyword;
            $describe = $good->describe;
            $timetobuy = Timetobuy::where('id',$good->prom_id)->where('status',1)->where('delflag',1)->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->first();
            if (is_null($timetobuy)) {
                return back()->with('message','抢购活动已经结束！');
            }
            return view(cache('config')['theme'].'.timetobuy',compact('title','good','good_spec_price','filter_spec','timetobuy','wechat_js'));
        } catch (\Throwable $e) {
            // dd($e);
            return view('errors.404');
        }
    }
}
