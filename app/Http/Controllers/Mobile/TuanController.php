<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Good\Good;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Order;
use App\Models\Good\Tuan;
use App\Models\User\User;
use Illuminate\Http\Request;

class TuanController extends Controller
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
            // 团,查参加过没有
            $tuan = Tuan::where('status',1)->where('delflag',1)->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->findOrFail($good->prom_id);
            // 找出来已经参团的人
            $old_t_orderid = Order::where('prom_type',2)->where('tuan_id',$tuan->id)->where('paystatus',1)->where('orderstatus',1)->where('status',1)->orderBy('id','desc')->value('t_orderid');
            $nums = $old_t_orderid == '' || is_null($old_t_orderid) ? 0 : Order::where('tuan_id',$tuan->id)->where('t_orderid',$old_t_orderid)->where('paystatus',1)->where('orderstatus',1)->where('prom_type',2)->count();
            // 参团
            if ($nums+1 < $tuan->tuan_num) {
                $userid = Order::where('tuan_id',$tuan->id)->where('t_orderid',$old_t_orderid)->where('paystatus',1)->where('orderstatus',1)->where('prom_type',2)->pluck('user_id');
                $user = User::whereIn('id',$userid)->select('id','thumb','nickname')->get();
            }
            else
            {
                // 开团
                $user = (object)[];
            }
            return view(cache('config')['theme'].'.tuan',compact('title','good','good_spec_price','filter_spec','tuan','wechat_js','user'));
        } catch (\Throwable $e) {
            // dd($e);
            return view('errors.404');
        }
    }
}
