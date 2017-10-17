<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Good;
use App\Models\Good\HdGood;
use App\Models\Good\Huodong;
use Illuminate\Http\Request;

class HuodongController extends BaseController
{
    // 所有优惠券
    public function getIndex()
    {
    	$info = (object) ['title'=>'最新活动','keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    	$list = Huodong::where('starttime','<',date('Y-m-d H:i:s'))->where('endtime','>',date('Y-m-d H:i:s'))->where('status',1)->where('del',1)->orderBy('sort','desc')->orderBy('id','desc')->simplePaginate(20);
        $info->pid = 0;
    	return view($this->theme.'.hd',compact('info','list'));
    }
    // 列表
    public function getList(Request $req,$id = 0)
    {   
        $info = (object) ['seotitle'=>Huodong::where('id',$id)->value('title'),'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe'],'pid'=>0];
        $sort = isset($req->sort) ? $req->sort : 'sort';
        $sc = isset($req->sc) ? $req->sc : 'desc';
        // 先找出来活动下所有商品ID
        $ids = HdGood::where('hd_id',$id)->pluck('good_id');
        $list = Good::whereIn('id',$ids)->orderBy($sort,$sc)->orderBy('id','desc')->simplePaginate(5);
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
}
