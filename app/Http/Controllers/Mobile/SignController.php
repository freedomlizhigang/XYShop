<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User\SignConfig;
use App\Models\User\SignLog;
use App\Models\User\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class SignController extends Controller
{
    public function getSignin()
    {
        DB::beginTransaction();
        try {
            $user_id = session('member')->id;
            $config = SignConfig::findOrFail(1);
            $day = date('Y-m-d 00:00:00');
            $yestday = date('Y-m-d 00:00:00',strtotime('-1 day'));
            // 查上次签到时什么时候
            $sign = SignLog::where('user_id',$user_id)->where('type',1)->orderBy('id','desc')->first();
            $addpoint = 0;
            // 为空，签的时间小于昨天(断签)
            if (is_null($sign) || strtotime($sign->signtime) < strtotime($yestday)) {
                SignLog::create(['user_id'=>$user_id,'point'=>$config->onepoint,'days'=>1,'signtime'=>$day]);
                $addpoint = $config->onepoint;
            }
            // 昨天签过了
            elseif($sign->signtime == $yestday)
            {
                // 到奖励日期
                if($sign->days + 1 == $config->days)
                {
                    SignLog::create(['user_id'=>$user_id,'point'=>$config->onepoint + $config->reward,'days'=>$sign->days + 1,'signtime'=>$day]);
                    $addpoint = $config->onepoint + $config->reward;
                }
                // 不到奖励日期
                elseif($sign->days + 1 < $config->days)
                {
                    SignLog::create(['user_id'=>$user_id,'point'=>$config->onepoint,'days'=>$sign->days + 1,'signtime'=>$day]);
                    $addpoint = $config->onepoint;
                }
                // 已经奖励过
                elseif($sign->days == $config->days)
                {
                    SignLog::create(['user_id'=>$user_id,'point'=>$config->onepoint,'days'=>1,'signtime'=>$day]);
                    $addpoint = $config->onepoint;
                }
            }
            // 给用户增加积分
            if ($addpoint) {
                User::where('id',$user_id)->sharedLock()->increment('points',$addpoint);
            }
            DB::commit();
            return back()->with('message','签到成功！');
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('message','签到出了点小问题，一会再试一下吧！');
        }
    }
}
