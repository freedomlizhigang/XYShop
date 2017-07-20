<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Cache;
use Illuminate\Http\Request;

class ConfigController extends BaseController
{
    public function index(Request $req)
    {
    	$title = '系统配置';
    	$config = Config::findOrFail(1);
    	return view('admin.config.index',compact('title','config'));
    }
    public function postIndex(Request $req)
    {
    	$data = $req->input('data');
    	Config::where('id',1)->update($data);
    	// 更新缓存
    	Cache::forever('config',$data);
    	return $this->ajaxReturn(1,'更新成功');
    }
}
