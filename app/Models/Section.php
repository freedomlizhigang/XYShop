<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    // 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    // 关联Admins表
    public function admin()
    {
    	return $this->hasMany('\App\Models\Admin','section_id','id');
    }
}
