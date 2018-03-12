<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    // 生成分销链接，改变分享按钮功能
    public function getShareurl(Request $req)
    {
        $pos_id = 'center';
        $title = '推广链接';
        $uid = session('member')->id;
        $shareurl = '/?shareurl='.base64_encode($uid.'-'.time());
        User::where('id',$uid)->update(['shareurl'=>$shareurl]);
        $url = config('app.url').$shareurl;
        $wechat_js = app('wechat.official_account')->jssdk;
        return view(cache('config')['theme'].'.user.distribution',compact('pos_id','title','url','wechat_js'));
    }
}
