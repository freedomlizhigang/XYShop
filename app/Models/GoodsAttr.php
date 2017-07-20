<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{
    // 商品属性对应关系表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'good_attr';

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

    // 查看器
    public function getGoodAttrValueAttribute($v)
    {
        return json_decode($v);
    }

    // 关联商品属性表
    public function goodattr()
    {
        return $this->belongsTo('\App\Models\GoodAttr','good_attr_id','id');
    }

    // 关联商品表
    public function good()
    {
        return $this->belongsTo('\App\Models\Good','good_id','id');
    }
}
