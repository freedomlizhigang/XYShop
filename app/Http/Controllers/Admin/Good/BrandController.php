<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends BaseController
{
    /**
     * 品牌列表
     * @return [type] [description]
     */
    public function getIndex(Request $req)
    {
    	$title = '品牌管理';
        $q = $req->input('q','');
        $list = Brand::where(function($r)use($q){
            if ($q != '') {
                $r->where('name','like','%$q%');
            }
        })->orderBy('id','desc')->paginate(15);
    	return view('admin.brand.index',compact('title','list','q'));
    }
    /**
     * 添加品牌
     * @param  integer $pid [父品牌ID]
     * @return [type]       [description]
     */
    public function getAdd()
    {
    	$title = '添加品牌';
    	return view('admin.brand.add',compact('title'));
    }
    public function postAdd(BrandRequest $res)
    {
        try {
            $data = $res->input('data');
            $resId = Brand::create($data);
            return $this->ajaxReturn(1, '添加成功！',url('console/brand/index'));
        } catch (Exception $e) {
            return $this->ajaxReturn(0, '添加失败，请稍后再试！');
        }
    }
    /**
     * 修改品牌
     * @param  string $id [要修改的品牌ID]
     * @return [type]     [description]
     */
    public function getEdit($id = '')
    {
        $title = '修改品牌';
        $info = Brand::findOrFail($id);
        return view('admin.brand.edit',compact('title','info'));
    }
    public function postEdit(BrandRequest $res,$id = '')
    {
        try {
            $data = $res->input('data');
            Brand::where('id',$id)->update($data);
            return $this->ajaxReturn(1, '修改成功！');
        } catch (Exception $e) {
            return $this->ajaxReturn(1, '修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
    	// 先查品牌下有没有商品，没有直接删除
        Brand::where('id',$id)->delete();
        return back()->with('message', '删除完成！');
    }
}
