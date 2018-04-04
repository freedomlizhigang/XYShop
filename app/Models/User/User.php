<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // 用户表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'users';

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

    // 关联用户组
    public function group()
    {
        return $this->belongsTo('\App\Models\User\Group','gid','id');
    }

    // 关联商品评价
    public function good_comment()
    {
        return $this->hasMany('\App\Models\Good\GoodComment','user_id','id');
    }

    // 属性值
    public function return_good()
    {
        return $this->hasMany('\App\Models\Good\ReturnGood','user_id','id');
    }

    // 属性值
    public function card()
    {
        return $this->hasMany('\App\Models\User\Card','user_id','id');
    }
    // 订单
    public function order()
    {
        return $this->hasMany('\App\Models\Good\Order','user_id','id');
    }

    // 关联消费记录
    public function consume()
    {
        return $this->hasMany('\App\Models\User\Consume','user_id','id');
    }
    // 充值记录
    public function recharge()
    {
        return $this->hasMany('\App\Models\User\Recharge','user_id','id');
    }
    // 砍价活动
    public function bargain()
    {
        return $this->hasMany('\App\Models\Promotion\BargainOrder','user_id','id');
    }

    // 被分销人
    public function distribution_user()
    {
        return $this->hasMany('\App\Models\Promotion\DistributionLog','user_id','id');
    }

    // 一级分销人
    public function distribution_son()
    {
        return $this->hasMany('\App\Models\Promotion\DistributionLog','son_id','id');
    }

    // 二级分销人
    public function distribution_sun()
    {
        return $this->hasMany('\App\Models\Promotion\DistributionLog','sun_id','id');
    }
}
