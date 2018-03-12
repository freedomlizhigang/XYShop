<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class DistributionLog extends Model
{
    // 分成记录
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'distribution_logs';

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
}
