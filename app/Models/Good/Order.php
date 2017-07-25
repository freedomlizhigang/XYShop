<?php

namespace App\Models\Good;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	// 订单表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'orders';

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
    
    // 商品
    public function good()
    {
        return $this->hasMany('\App\Models\Good\OrderGood','order_id','id');
    }
    public function address()
    {
        return $this->belongsTo('\App\Models\User\Address','address_id','id');
    }
    public function extract()
    {
        return $this->belongsTo('\App\Models\Good\Extract','ziti','id');
    }
    public function user()
    {
        return $this->belongsTo('\App\Models\User\User','user_id','id');
    }
    // 退货单
    public function return()
    {
        return $this->hasMany('\App\Models\Good\ReturnGood','order_id','id');
    }
}
