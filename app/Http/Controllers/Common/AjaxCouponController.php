<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\BaseController;
use App\Models\Good\Coupon;
use App\Models\Good\CouponUser;
use Illuminate\Http\Request;

class AjaxCouponController extends BaseController
{
    // 领
    public function postGet(Request $req)
    {
      $id = $req->cid;
      $uid = $req->uid;
      // 先查是不是已经领过
      if (!is_null(CouponUser::where('user_id',$uid)->where('c_id',$id)->first())) {
        $this->ajaxReturn('0','领取过，请不要重复领取！');
      }
      // 查出优惠券到期时间
      $endtime = Coupon::where('id',$id)->value('endtime');
      $data = ['user_id'=>$uid,'c_id'=>$id,'endtime'=>$endtime];
      DB::beginTransaction();
      try {
        // 优惠券数量减1，添加给用户
        CouponUser::create($data);
        CouponUser::where('id',$id)->decrement('nums');
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
        $coupon = CouponUser::findOrFail($id);
        // 优惠券如果没过期并且没使用，数量加1
        if($coupon->endtime > date('Y-m-d H:i:s') && $coupon->status)
        {
          CouponUser::where('id',$coupon->c_id)->increment('nums');
        }
        CouponUser::where('id',$id)->update(['delflag'=>0]);
        // 没出错，提交事务
            DB::commit();
           $this->ajaxReturn('1','删除成功！');
      } catch (\Exception $e) {
        // 出错回滚
            DB::rollBack();
            $this->ajaxReturn('0','领取失败，请稍后再试！');
      }
    }
}
