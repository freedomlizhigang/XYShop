<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priv extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'role_privs';
    
	// 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];
}
