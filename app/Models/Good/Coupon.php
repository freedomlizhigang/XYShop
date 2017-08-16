<?php

namespace App\Models\Good;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    // 优惠券
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'coupon';

    // 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $hidden = [];
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    // 关联商品评价
    public function yhquser()
    {
        return $this->hasOne('\App\Models\Good\CouponUser','c_id','id');
    }
}
