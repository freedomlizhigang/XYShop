<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\Area;
use App\Models\Common\Community;
use App\Models\Good\Brand;
use App\Models\Good\GoodCate;
use Illuminate\Http\Request;

class AjaxCommonController extends Controller
{
	// 取栏目下子栏目
	public function postGoodCate(Request $req)
	{
		try {
			$res = GoodCate::where('parentid',$req->pid)->select('id','name')->orderBy('sort','asc')->orderBy('id','asc')->get();
			$this->ajaxReturn('1',$res);
		} catch (\Throwable $e) {
			$this->ajaxReturn('0',$e->getMessage());
		}
	}

	// 取栏目下品牌
	public function postBrand(Request $req)
	{
		try {
			$res = Brand::where('goodcate_parentid',$req->pid)->where('goodcate_id',$req->cid)->select('id','name')->orderBy('id','asc')->get();
			$this->ajaxReturn('1',$res);
		} catch (\Throwable $e) {
			$this->ajaxReturn('0',$e->getMessage());
		}
	}

    // 取下级地区后台
    public function postArea(Request $req)
    {
        try {
            $res = Area::where('parentid',$req->pid)->where('is_show',1)->select('id','areaname')->orderBy('sort','asc')->orderBy('id','asc')->get();
            $this->ajaxReturn('1',$res);
        } catch (\Throwable $e) {
            $this->ajaxReturn('0',$e->getMessage());
        }
    }

    // 取社区后台
    public function postCommunity(Request $req)
    {
        try {
            $res = Community::where('areaid3',$req->areaid3)->where('is_show',1)->select('id','name')->orderBy('sort','asc')->orderBy('id','asc')->get();
            $this->ajaxReturn('1',$res);
        } catch (\Throwable $e) {
            $this->ajaxReturn('0',$e->getMessage());
        }
    }

	// 取下级地区
    public function postArea2(Request $req)
    {
    	try {
            $pid = $req->pid == '0' ? 0 : Area::where('areaname',$req->pid)->value('id');
			$res = Area::where('parentid',$pid)->where('is_show',1)->select('id','areaname')->orderBy('sort','asc')->orderBy('id','asc')->get();
			$this->ajaxReturn('1',$res);
		} catch (\Throwable $e) {
			$this->ajaxReturn('0',$e->getMessage());
		}
    }

    // 取社区
    public function postCommunity2(Request $req)
    {
    	try {
            $pid = Area::where('areaname',$req->areaid3)->value('id');
			$res = Community::where('areaid3',$pid)->where('is_show',1)->select('id','name')->orderBy('sort','asc')->orderBy('id','asc')->get();
			$this->ajaxReturn('1',$res);
		} catch (\Throwable $e) {
			$this->ajaxReturn('0',$e->getMessage());
		}
    }
}
