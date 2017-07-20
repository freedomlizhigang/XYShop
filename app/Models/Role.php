<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    /**
     * 用户
     */
    public function Admin()
    {
        return $this->belongsToMany('\App\Models\Admin','role_users','role_id','user_id');
    }

    // 关联privs表
    public function priv()
    {
        return $this->belongsToMany('\App\Models\Priv','role_privs');
    }
}
