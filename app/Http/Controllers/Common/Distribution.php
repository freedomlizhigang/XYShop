<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Promotion\Distribution as Dis;
use App\Models\Promotion\DistributionLog;
use App\Models\User\User;
use Illuminate\Http\Request;

class Distribution
{
    // 分销分成结算
    public static function clearing($userid = 0,$orderid = 0,$order_prices)
    {
        try {
            // 找出来这个用户的分销关系
            $distribution = Dis::where('user_id',$userid)->orderBy('id','desc')->first();
            // 给直接上级反
            if (!is_null($distribution) && $distribution->parent_id != 0) {
                $money = number_format(($order_prices * $config->son_proportion)/100,2);
                $insert = ['user_id'=>$distribution->parent_id,'son_id'=>$userid,'sun_id'=>0,'order_id'=>$orderid,'money'=>$money];
                // 做记录，用户增加余额
                User::where('id',$distribution->parent_id)->increment('user_money',$money);
                // 记入消费记录
                app('com')->consume($userid,$orderid,$money,'下级分成结算',1);
                DistributionLog::create($insert);
            }
            // 给上上级反
            if (!is_null($distribution) && $distribution->father_id != 0) {
                $money_father = number_format(($order_prices * $config->sun_proportion)/100,2);
                $insert_father = ['user_id'=>$distribution->father_id,'son_id'=>$distribution->parent_id,'sun_id'=>$userid,'order_id'=>$orderid,'money'=>$money_father];
                // 做记录，用户增加余额
                User::where('id',$distribution->father_id)->increment('user_money',$money_father);
                // 记入消费记录
                app('com')->consume($userid,$orderid,$money_father,'二级分成结算',1);
                DistributionLog::create($insert_father);
            }
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
