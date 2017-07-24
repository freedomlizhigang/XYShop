<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Good\GoodAttrRequest;
use App\Models\Good\GoodAttr;
use App\Models\Good\GoodCate;
use Illuminate\Http\Request;

class GoodAttrController extends BaseController
{
    public function getIndex(Request $res)
    {
    	$title = '商品属性列表';
        $list = GoodAttr::with(['goodcate'=>function($q){
                    $q->select('id','name');
                }])->orderBy('id','desc')->paginate(15);
        return view('admin.goodattr.index',compact('list','title'));
    }

    // 添加商品属性
    public function getAdd()
    {
        $title = '添加商品属性';
        return view('admin.goodattr.add',compact('title'));
    }

    public function postAdd(GoodAttrRequest $req)
    {
        $data = $req->input('data');
        $data['value'] = app('com')->trim_value($data['value']);
        GoodAttr::create($data);
        return $this->ajaxReturn(1,'添加商品属性成功！',url('/console/goodattr/index'));
    }
    // 修改商品属性
    public function getEdit(Request $req,$id)
    {
        $title = '修改商品属性';
        $info = GoodAttr::findOrFail($id);
        return view('admin.goodattr.edit',compact('title','info','id'));
    }
    public function postEdit(GoodAttrRequest $req,$id)
    {
        $data = $req->input('data');
        $data['value'] = app('com')->trim_value($data['value']);
        GoodAttr::where('id',$id)->update($data);
        return $this->ajaxReturn(1,'修改商品属性成功！');
    }
    // 删除商品属性
    public function getDel($id)
    {
    	GoodAttr::destroy($id);
        return back()->with('message', '商品属性删除成功！');
    }
}
