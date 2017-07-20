<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class BaseController extends Controller
{
    // ajax返回格式
    public function ajaxReturn($status = 1,$msg = '',$url = '')
    {
        return ['status'=>$status,'msg'=>$msg,'url'=>$url];
    }
}
