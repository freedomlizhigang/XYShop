<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Shop;
use App\Models\ShopCate;
use Illuminate\Http\Request;

class ShopController extends BaseController
{
    public function getIndex(Request $res)
    {
    	$title = '商铺列表';
    	$key = $res->input('q','');
    	$cate_id = $res->input('catid','');
        $list = Shop::paginate(15);
    	$all = ShopCate::orderBy('sort','asc')->get();
        $tree = app('com')->toTree($all,'0');
        $shopcate = app('com')->toTreeSelect($tree,'0');
        // 保存一次性数据，url参数，供编辑完成后跳转用
        session()->put('backurl',$res->fullUrl());
        return view('admin.shop.index',compact('list','title','shopcate','key','cate_id'));
    }

    // 添加商铺
    public function getAdd()
    {
        $title = '添加商铺';
        return view('admin.shop.add',compact('title'));
    }

    public function postAdd(RoleRequest $request)
    {
        $data = $request->input('data');
        Shop::create($data);
        return $this->ajaxReturn(1,'添加商铺成功！',url('/console/role/index'));
    }
    // 修改商铺
    public function getEdit($rid)
    {
        $title = '修改商铺';
        // 拼接返回用的url参数
        $info = Shop::findOrFail($rid);
        return view('admin.shop.edit',compact('title','info'));
    }
    public function postEdit(RoleRequest $request,$rid)
    {
        Shop::where('id',$rid)->update($request->input('data'));
        return $this->ajaxReturn(1,'修改商铺成功！');
    }
    // 删除商铺
    public function getDel($rid)
    {
        
    }
}
