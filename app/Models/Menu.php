<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	// 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // 关联role表
    public function role()
    {
        return $this->belongsToMany('\App\Models\Role','role_privs');
    }
}
