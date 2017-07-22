<?php

namespace App\Models\Console;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 用户组
     */
    public function role()
    {
        return $this->belongsToMany('\App\Models\Console\Role','role_users','user_id','role_id');
    }

    // 关联
    public function section()
    {
        return $this->belongsTo('\App\Models\Console\Section','section_id','id');
    }
}
