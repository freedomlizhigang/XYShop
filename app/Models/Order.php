<?php

namespace App\Models;

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
        return $this->hasMany('\App\Models\OrderGood','order_id','id');
    }
    public function address()
    {
        return $this->belongsTo('\App\Models\Address','address_id','id');
    }
    public function zitidian()
    {
        return $this->belongsTo('\App\Models\Zitidian','ziti','id');
    }
    public function user()
    {
        return $this->belongsTo('\App\Models\User','user_id','id');
    }
    // 退货单
    public function return()
    {
        return $this->hasMany('\App\Models\ReturnGood','order_id','id');
    }
}
