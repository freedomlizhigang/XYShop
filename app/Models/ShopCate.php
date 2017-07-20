<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCate extends Model
{
    // 商铺分类表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'shop_cate';

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
    // 商铺
    public function shop()
    {
        return $this->hasMany('\App\Models\Shop','shop_catid','id');
    }
}
