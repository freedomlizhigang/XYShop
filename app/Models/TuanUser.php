<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TuanUser extends Model
{
    // 参团用户
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'tuan_user';

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
