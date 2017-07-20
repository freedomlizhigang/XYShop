<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodCate extends Model
{
    // 商品分类表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'good_cates';

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

    // 属性值
    public function good()
    {
        return $this->hasMany('\App\Models\Good','cate_id','id');
    }

    // 关联属性表
    public function goodattr()
    {
        return $this->hasMany('\App\Models\GoodAttr','good_cate_id','id');
    }

    // 关联规格表
    public function goodspec()
    {
        return $this->hasMany('\App\Models\GoodSpec','good_cate_id','id');
    }
}
