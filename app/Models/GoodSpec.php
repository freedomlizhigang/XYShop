<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodSpec extends Model
{
    // 商品规格表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'good_spec';

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

    // 关联商品分类表
    public function goodcate()
    {
        return $this->belongsTo('\App\Models\GoodCate','good_cate_id','id');
    }

    // 关联商品规格值表
    public function goodspecitem()
    {
        return $this->hasMany('\App\Models\GoodSpecItem','good_spec_id','id');
    }
}
