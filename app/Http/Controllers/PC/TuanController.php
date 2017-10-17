<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Good;
use App\Models\Good\GoodComment;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\Tuan;
use App\Models\Good\Zitidian;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class TuanController extends BaseController
{
    /*
     * 当传了属性时，按属性值计算，没传时按第一个计算
     */
    public function getGood($tid = '',$gid = '')
    {
    	$tuan = Tuan::findOrFail($tid);
        // 查出来商品信息，关联查询出对应属性及属性名称
        $info = Good::with(['goodattr'=>function($q){
                    $q->with('goodattr');
                }])->findOrFail($gid);
        /*
        * 查出来所有的规格信息
        * 1、找出所有的规格ID 
        * 2，查出所有的规格ID对应的名字spec_item及spec内容
        * 3、循环出来所有的规格及规格值
        * */
        $good_spec_ids = GoodSpecPrice::where('good_id',$gid)->pluck('key')->toArray();
        $good_spec_ids = explode('_',implode('_',$good_spec_ids));
        $good_spec = GoodSpecItem::with(['goodspec'=>function($q){
                        $q->select('id','name');
                    }])->whereIn('id',$good_spec_ids)->get();
        $filter_spec = [];
        foreach ($good_spec as $k => $v) {
            $filter_spec[$v->goodspec->name][] = ['item_id'=>$v->id,'item'=>$v->item];
        }
        // 查出第一个规格信息来，标红用的
        $good_spec_price = GoodSpecPrice::where('good_id',$gid)->get()->keyBy('key')->toJson();

        // 取评价，20条
        $goodcomment = GoodComment::with(['user'=>function($q){
                $q->select('id','nickname','thumb','username');
            }])->where('good_id',$gid)->where('delflag',1)->orderBy('id','desc')->limit(20)->get();
        // 送货地址
        if (session()->has('member')) {
            $address = Address::where('user_id',session('member')->id)->where('delflag',1)->get();
        }
        else
        {
            $address = [];
        }
        // 自提点
        $ziti = Zitidian::where('status',1)->where('delflag',1)->orderBy('sort','desc')->get();
        $info->pid = 0;
        return view($this->theme.'.tuan.good',compact('info','goodcomment','tuan','ziti','address','good_spec_price','filter_spec'));
    }
}
