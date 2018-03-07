<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // ajax返回数据
    public function resJson($code = 0,$msg = '',$result = '')
    {
        return ['code'=>$code,'msg'=>$msg,'result'=>$result];
    }
    // 后台ajax返回格式
    public function adminJson($status = 1,$msg = '',$url = '')
    {
        return ['status'=>$status,'msg'=>$msg,'url'=>$url];
    }
    // 公用控制器ajax返回
    public function ajaxReturn($code = '1',$msg = '')
    {
        exit(json_encode(['code'=>$code,'msg'=>$msg]));
        return;
    }
}
