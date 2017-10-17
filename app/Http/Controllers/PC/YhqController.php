<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\YhqUser;
use App\Models\Good\Youhuiquan;
use DB;
use Illuminate\Http\Request;

class YhqController extends BaseController
{
	// 所有优惠券
    public function getIndex()
    {
    	$info = (object) ['title'=>'领取优惠券','keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    	$list = Youhuiquan::where('starttime','<',date('Y-m-d H:i:s'))->where('endtime','>',date('Y-m-d H:i:s'))->where('nums','>',0)->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->get();
        $info->pid = 0;
    	return view($this->theme.'.yhq.list',compact('info','list'));
    }
    // 我的优惠券
    public function getList()
    {
    	$info = (object) ['title'=>'我的优惠券','keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    	$list = YhqUser::with('yhq')->where('user_id',session('member')->id)->where('delflag',1)->orderBy('id','desc')->simplePaginate(10);
        $info->pid = 0;
    	return view($this->theme.'.yhq.myyhq',compact('info','list'));
    }
}
