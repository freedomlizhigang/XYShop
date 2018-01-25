<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class BargainOrder extends Model
{
    // 砍价订单
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'bargain_orders';

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
        return $this->belongsTo('\App\Models\User\User','user_id','id');
    }
    // 砍价活动
    public function bargain()
    {
        return $this->belongsTo('\App\Models\Promotion\Bargain','bargain_id','id');
    }
}
