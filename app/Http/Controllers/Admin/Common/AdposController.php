<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\AdposRequest;
use App\Models\Adpos;
use Illuminate\Http\Request;

class AdposController extends BaseController
{
    /**
     * 广告位列表
     * @return [type] [description]
     */
    public function getIndex(Request $req)
    {
    	$title = '广告位管理';
        $q = $req->input('q','');
        $list = Adpos::where(function($r)use($q){
            if ($q != '') {
                $r->where('name','like','%$q%');
            }
        })->orderBy('id','desc')->paginate(15);
    	return view('admin.adpos.index',compact('title','list','q'));
    }
    /**
     * 添加广告位
     */
    public function getAdd()
    {
    	$title = '添加广告位';
    	return view('admin.adpos.add',compact('title'));
    }
    public function postAdd(AdposRequest $res)
    {
        try {
            $data = $res->input('data');
            $resId = Adpos::create($data);
            return $this->ajaxReturn(1, '添加成功！',url('console/adpos/index'));
        } catch (Exception $e) {
            return $this->ajaxReturn(0, '添加失败，请稍后再试！');
        }
    }
    /**
     * 修改广告位
     */
    public function getEdit($id = '')
    {
        $title = '修改广告位';
        $info = Adpos::findOrFail($id);
        return view('admin.adpos.edit',compact('title','info'));
    }
    public function postEdit(AdposRequest $res,$id = '')
    {
        try {
            $data = $res->input('data');
            Adpos::where('id',$id)->update($data);
            return $this->ajaxReturn(1, '修改成功！');
        } catch (Exception $e) {
            return $this->ajaxReturn(1, '修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
    	// 先查广告位下有没有商品，没有直接删除
        Adpos::where('id',$id)->delete();
        return back()->with('message', '删除完成！');
    }
}
