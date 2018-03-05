<?php

namespace App\Models\Good;

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

    // url
    public function getUrlAttribute()
    {
        return url('list',['id'=>$this->attributes['id']]);
    }

    // 属性值
    public function good()
    {
        return $this->hasMany('\App\Models\Good\Good','cate_id','id');
    }
}
