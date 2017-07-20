<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnGood extends Model
{
    // 退货管理
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'return_good';

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

    // 用户
    public function user()
    {
        return $this->belongsTo('\App\Models\User','user_id','id');
    }
    // 商品
    public function good()
    {
        return $this->belongsTo('\App\Models\Good','good_id','id');
    }
    // 订单
    public function order()
    {
        return $this->belongsTo('\App\Models\Order','order_id','id');
    }
}
