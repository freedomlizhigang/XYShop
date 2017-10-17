<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Common\Ad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdController extends BaseController
{
  public function postIndex(Request $req)
  {
    try {
      $day = Carbon::now();
      $data = Ad::where('pos_id',$req->pos_id)->where('starttime','<=',$day)->where('endtime','>=',$day)->where('status',1)->orderBy('sort','desc')->orderBy('id','desc')->limit($req->nums)->get();
      return $this->resJson(1,'查询成功！',$data);
    } catch (\Exception $e) {
      return $this->resJson(0,$e->getMessage());
    }
  }
}
