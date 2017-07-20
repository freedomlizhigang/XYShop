<?php

namespace App\Models;

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
        return $this->belongsToMany('\App\Models\Role','role_users','user_id','role_id');
    }

    // 关联
    public function section()
    {
        return $this->belongsTo('\App\Models\Section','section_id','id');
    }

    // 授权判断
    public function hasRole($role)
    {
        /*
         *  超级管理员(用户ID == 租户ID)跳过权限检查
         *   这里应该再判断一下前后台用户，前台后台区别开，$user->
         *   admin是判断后台用户的标准，或者前台不使用$user->role->id，而使用其它方法
         *   成功后进行下一次判断
        */
        // if (session('user')->id == 1)
        // {
        //     return true;
        // }
        // else
        // {
        //     $res = false;
        //     // 因为是一对多关系，得到的role是个Model类，这么坑，所以只能循环一下看看有没有相同的角色ID，同时角色没有被禁用
        //     foreach ($role as $v) {
        //         if($v->id == session('user')->role_id && $v->status == 1)
        //         {
        //             $res = true;
        //         }
        //     }
        //     return $res;
        // }
        
    }
}
