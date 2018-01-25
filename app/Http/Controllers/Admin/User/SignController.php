<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\BaseController;
use App\Models\User\SignConfig;
use Illuminate\Http\Request;

class SignController extends BaseController
{
    public function getConfig(Request $req)
    {
        $title = '签到配置';
        $config = SignConfig::findOrFail(1);
        return view('admin.sign.index',compact('title','config'));
    }
    public function postConfig(Request $req)
    {
        $data = $req->input('data');
        SignConfig::where('id',1)->update($data);
        return $this->ajaxReturn(1,'更新成功');
    }
}
