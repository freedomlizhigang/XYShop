<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Models\Promotion\DistributionConfig;
use Illuminate\Http\Request;

class DistributionConfigController extends Controller
{
    public function getIndex(Request $req)
    {
        $title = '分销配置';
        $info = DistributionConfig::findOrFail(1);
        return view('admin.distributionconfig.index',compact('title','info'));
    }
    public function postIndex(Request $req)
    {
        $data = $req->input('data');
        DistributionConfig::where('id',1)->update($data);
        return $this->adminJson(1,'更新成功');
    }
}
