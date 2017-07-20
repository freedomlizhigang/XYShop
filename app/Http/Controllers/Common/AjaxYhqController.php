<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\YhqUser;
use App\Models\Youhuiquan;
use DB;
use Illuminate\Http\Request;

class AjaxYhqController extends BaseController
{
    // 领
    public function postGet(Request $req)
    {
    	$id = $req->yid;
    	$uid = $req->uid;
    	// 先查是不是已经领过
    	if (!is_null(YhqUser::where('user_id',$uid)->where('yhq_id',$id)->first())) {
    		$this->ajaxReturn('0','领取过，请不要重复领取！');
    	}
    	// 查出优惠券到期时间
    	$endtime = Youhuiquan::where('id',$id)->value('endtime');
    	$data = ['user_id'=>$uid,'yhq_id'=>$id,'endtime'=>$endtime];
    	DB::beginTransaction();
    	try {
    		// 优惠券数量减1，添加给用户
    		YhqUser::create($data);
    		Youhuiquan::where('id',$id)->decrement('nums');
    		// 没出错，提交事务
            DB::commit();
        } catch (\Exception $e) {
            // 出错回滚
            DB::rollBack();
            $this->ajaxReturn('0','领取失败，请稍后再试！');
        }
        $this->ajaxReturn('1','领取成功！');
    }
    // 删除优惠券
    public function postDel(Request $req)
    {
    	$id = $req->yid;
    	DB::beginTransaction();
    	try {
    		$yhq = YhqUser::findOrFail($id);
    		// 优惠券如果没过期并且没使用，数量加1
    		if($yhq->endtime > date('Y-m-d H:i:s') && $yhq->status)
    		{
    			Youhuiquan::where('id',$yhq->yhq_id)->increment('nums');
    		}
    		YhqUser::where('id',$id)->update(['del'=>0]);
    		// 没出错，提交事务
            DB::commit();
           $this->ajaxReturn('1','删除成功！');
    	} catch (\Exception $e) {
    		// 出错回滚
            DB::rollBack();
            $this->ajaxReturn('0','领取失败，请稍后再试！');
    	}
    }
    // 比价
    public function postPrice(Request $req)
    {
    	$id = $req->yid;
        // 当前优惠券价
        $price = YhqUser::with('yhq')->where('id',$id)->first();
        // 购物车总价
        $total_prices = trim($req->total_prices,'￥');
        if ($price->yhq->price < $total_prices) {
            $this->ajaxReturn('1');
        }
        else
        {
			$this->ajaxReturn('0');
        }
    }
}
