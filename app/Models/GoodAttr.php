<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodAttr extends Model
{
    // 商品属性表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'good_attrs';

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
    public function goodcate()
    {
        return $this->belongsTo('\App\Models\GoodCate','good_cate_id','id');
    }

    // 关联商品属性对应表
    public function goodsattr()
    {
        return $this->belongsTo('\App\Models\GoodsAttr','good_attr_id','id');
    }
}
