<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    // 封装返回数据
    public function resJson($code = 0,$msg = '',$result = '')
    {
    	return ['code'=>$code,'msg'=>$msg,'result'=>$result];
    }
}
