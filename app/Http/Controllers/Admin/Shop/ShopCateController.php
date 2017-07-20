<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Shop\ShopCateRequest;
use App\Models\Shop;
use App\Models\ShopCate;
use DB;
use Illuminate\Http\Request;

class ShopCateController extends BaseController
{
    /**
     * 商品分类列表
     * @return [type] [description]
     */
    public function getIndex()
    {
    	$title = '商品分类管理';
        // 超级管理员可查看所有部门下商品分类
        $all = ShopCate::orderBy('sort','asc')->get();
        $tree = app('com')->toTree($all,'0');
    	$treeHtml = $this->toTreeHtml($tree);
    	return view('admin.shopcate.index',compact('title','treeHtml'));
    }
    // 更新缓存
    public function getCache()
    {
        app('com')->updateCache(new ShopCate(),'shopcateCache');
        return redirect('/console/shopcate/index')->with('message', '更新商铺分类缓存成功！');
    }
    // 树形菜单 html
    private function toTreeHtml($tree)
    {
        $html = '';
        if (is_array($tree)) {
            foreach ($tree as $v) {
                // 用level判断层级，最好不要超过四层，样式中只写了四级
                $cj = count(explode(',', $v['arrparentid']));
                $level = $cj > 4 ? 4 : $cj;
                $isshow = $v['is_show'] ? "<span class='text-success'>显示</span>" : "<span class='text-danger'>隐藏</span>";
                if ($level >= 2) {
	                $html .= "<tr>
                        <td><input type='checkbox' name='sids[]' class='check_s' value='".$v['id']."'></td>
                        <td><input type='text' min='0' name='sort[".$v['id']."]' value='".$v['sort']."' class='form-control input-sort'></td>
	                    <td>".$v['id']."</td>
	                    <td><span class='level-".$level."'></span>".$v['name']."</td>
	                    <td><span class='level-".$level."'></span>".$v['mobile_name']."</td>
	                    <td><span class='level-".$level."'></span>".$isshow."</td>
	                    <td><div data-url='/console/shopcate/edit/".$v['id']."' class='btn btn-xs btn-info glyphicon glyphicon-edit btn_modal' data-title='修改分类' data-toggle='modal' data-target='#myModal'></div> <a href='/console/shopcate/del/".$v['id']."' class='btn btn-xs btn-danger glyphicon glyphicon-trash confirm'></a></td>
	                    </tr>";
                }
                else
                {
	                $html .= "<tr>
	                    <td><input type='checkbox' name='sids[]' class='check_s' value='".$v['id']."'></td>
                        <td><input type='text' min='0' name='sort[".$v['id']."]' value='".$v['sort']."' class='form-control input-sort'></td>
	                    <td>".$v['id']."</td>
	                    <td><span class='level-".$level."'></span>".$v['name']."<div data-url='/console/shopcate/add/".$v['id']."' class='glyphicon glyphicon-plus curp add_submenu btn_modal' data-title='添加分类' data-toggle='modal' data-target='#myModal'></div></td>
	                    <td><span class='level-".$level."'></span>".$v['mobile_name']."</td>
	                    <td><span class='level-".$level."'></span>".$isshow."</td>
	                    <td><div data-url='/console/shopcate/edit/".$v['id']."' class='btn btn-xs btn-info glyphicon glyphicon-edit btn_modal' data-title='修改分类' data-toggle='modal' data-target='#myModal'></div> <a href='/console/shopcate/del/".$v['id']."' class='btn btn-xs btn-danger glyphicon glyphicon-trash confirm'></a></td>
	                    </tr>";
                }
                if ($v['parentid'] != '')
                {
                    $html .= $this->toTreeHtml($v['parentid']);
                }
            }
        }
        return $html;
    }
    /**
     * 添加商品分类
     * @param  integer $pid [父商品分类ID]
     * @return [type]       [description]
     */
    public function getAdd($pid = '0')
    {
    	$title = '添加商品分类';
    	return view('admin.shopcate.add',compact('title','pid'));
    }
    public function postAdd(ShopCateRequest $res,$pid = '0')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            $resId = ShopCate::create($data);
            // 后台用户组权限
            app('com')->updateCache(new ShopCate(),'shopcateCache');
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1,'添加成功',url('/console/shopcate/index'));
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->ajaxReturn(0,'添加失败，请稍后再试！');
        }
    }
    /**
     * 修改商品分类
     * @param  string $id [要修改的商品分类ID]
     * @return [type]     [description]
     */
    public function getEdit($id = '')
    {
        $title = '修改商品分类';
        $info = ShopCate::findOrFail($id);
        // 超级管理员可查看所有部门下商品分类
        $all = ShopCate::orderBy('sort','asc')->get();
        $tree = app('com')->toTree($all,'0');
        $treeHtml = app('com')->toTreeSelect($tree,$info->parentid);
        return view('admin.shopcate.edit',compact('title','info','treeHtml'));
    }
    public function postEdit(ShopCateRequest $res,$id = '')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            ShopCate::where('id',$id)->update($data);
            // 更新缓存
            app('com')->updateCache(new ShopCate(),'shopcateCache');
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1,'修改成功！',url('/console/shopcate/index'));
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->ajaxReturn(0,'修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
        // 先找出所有子商品分类
        $allChild = ShopCate::where('id',$id)->value('arrchildid');
        // 所有子商品分类ID转换为集合
        $childs = explode(',',$allChild);
        // 找分类下有没有商铺
        $ishav = Shop::whereIn('shop_catid',$childs)->count();
        if ($ishav != 0) {
        	return back()->with('message', '分类下有商铺！');
        }
        // 开启事务
        DB::beginTransaction();
        try {
            ShopCate::whereIn('id',$childs)->delete();
            app('com')->updateCache(new ShopCate(),'shopcateCache');
            // 没出错，提交事务
            DB::commit();
            return back()->with('message', '删除成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return back()->with('message','删除失败，请稍后再试！');
        }
    }
    // 排序
    public function postSort(Request $req)
    {
        $ids = $req->input('sids');
        $sort = $req->input('sort');
        if (is_array($ids))
        {
            foreach ($ids as $v) {
                ShopCate::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择商品！');
        }
    }
}
