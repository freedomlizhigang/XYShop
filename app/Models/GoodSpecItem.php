<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodSpecItem extends Model
{
    // 商品规格具体值表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'good_spec_item';

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

    // 访问器
    public function getValueAttribute($value)
    {
        $value = json_decode($value,true);
        return implode('，',$value);
    }

    // 关联商品分类表
    public function goodspec()
    {
        return $this->belongsTo('\App\Models\GoodSpec','good_spec_id','id');
    }
}
