<?php

namespace App\Http\Controllers\Admin\Good;

use App;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\GoodCateRequest;
use App\Models\CateAttr;
use App\Models\GoodAttr;
use App\Models\GoodCate;
use Illuminate\Http\Request;
Use DB;

class GoodCateController extends BaseController
{
    /**
     * 商品分类列表
     * @return [type] [description]
     */
    public function getIndex()
    {
    	$title = '商品分类管理';
        // 超级管理员可查看所有部门下商品分类
        $all = GoodCate::where('status',1)->orderBy('sort','asc')->get();
        $tree = App::make('com')->toTree($all,'0');
    	$treeHtml = $this->toTreeHtml($tree);
    	return view('admin.goodcate.index',compact('title','treeHtml'));
    }
    // 更新缓存
    public function getCache()
    {
        App::make('com')->updateCache(new GoodCate(),'goodcateCache');
        return redirect('/console/goodcate/index')->with('message', '更新分类缓存成功！');
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
                if ($level >= 2) {
	                $html .= "<tr>
                        <td><input type='checkbox' name='sids[]' class='check_s' value='".$v['id']."'></td>
                        <td><input type='text' min='0' name='sort[".$v['id']."]' value='".$v['sort']."' class='form-control input-sort'></td>
	                    <td>".$v['id']."</td>
	                    <td><span class='level-".$level."'></span>".$v['name']."</td>
                        <td><a href='/console/goodcate/edit/".$v['id']."' class='btn btn-xs btn-info glyphicon glyphicon-edit btn_modal'></a> <a href='/console/goodcate/del/".$v['id']."' class='btn btn-xs btn-danger glyphicon glyphicon-trash confirm'></a></td>
	                    </tr>";
                }
                else
                {
	                $html .= "<tr>
	                    <td><input type='checkbox' name='sids[]' class='check_s' value='".$v['id']."'></td>
                        <td><input type='text' min='0' name='sort[".$v['id']."]' value='".$v['sort']."' class='form-control input-sort'></td>
	                    <td>".$v['id']."</td>
	                    <td><span class='level-".$level."'></span>".$v['name']."<a href='/console/goodcate/add/".$v['id']."' class='glyphicon glyphicon-plus curp add_submenu btn_modal'></a></td>
	                    <td><a href='/console/goodcate/edit/".$v['id']."' class='btn btn-xs btn-info glyphicon glyphicon-edit btn_modal'></a> <a href='/console/goodcate/del/".$v['id']."' class='btn btn-xs btn-danger glyphicon glyphicon-trash confirm'></a></td>
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
    	return view('admin.goodcate.add',compact('title','pid'));
    }
    public function postAdd(GoodCateRequest $res,$pid = '0')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            $resId = GoodCate::create($data);
            // 后台用户组权限
            App::make('com')->updateCache(new GoodCate(),'goodcateCache');
            // 没出错，提交事务
            DB::commit();
            return $this->ajaxReturn(1,'添加成功！',url('/console/goodcate/index'));
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
        $info = GoodCate::findOrFail($id);
        // 超级管理员可查看所有部门下商品分类
        $all = GoodCate::where('status',1)->orderBy('sort','asc')->get();
        $tree = App::make('com')->toTree($all,'0');
        $treeHtml = App::make('com')->toTreeSelect($tree,$info->parentid);
        return view('admin.goodcate.edit',compact('title','info','treeHtml'));
    }
    public function postEdit(GoodCateRequest $res,$id = '')
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            GoodCate::where('id',$id)->update($data);
            // 更新缓存
            App::make('com')->updateCache(new GoodCate(),'goodcateCache');
            // 没出错，提交事务
            DB::commit();
            return redirect('/console/goodcate/index')->with('message', '修改成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return back()->with('message','修改失败，请稍后再试！');
        }
    }
    public function getDel($id)
    {
        // 先找出所有子商品分类
        $allChild = GoodCate::where('id',$id)->value('arrchildid');
        // 所有子商品分类ID转换为集合
        $childs = explode(',',$allChild);
        // 开启事务
        DB::beginTransaction();
        try {
            GoodCate::whereIn('id',$childs)->delete();
            // 没出错，提交事务
            DB::commit();
            return back()->with('message', '删除成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return back()->with('message','删除失败，请稍后再试！');
        }
    }
    // 分类属性关联
    public function getAttr($id)
    {
        $title = "属性关联";
        $all = GoodAttr::where('parentid',0)->where('status',1)->get();
        $hav = CateAttr::where('cate_id',$id)->get();
        $aids = '';
        foreach ($hav as $k) {
            $aids .= $k->attr_id.',';
        }
        $aids = trim($aids,',');
        return view('admin.goodcate.attr',compact('title','all','id','aids'));
    }
    public function postAttr(Request $req,$id)
    {
        $data = [];
        foreach ($req->attr as $k) {
            $data[] = ['cate_id'=>$req->cate_id,'attr_id'=>$k];
        }
        DB::beginTransaction();
        try {
            CateAttr::where('cate_id',$req->cate_id)->delete();
            CateAttr::insert($data);
            // 没出错，提交事务
            DB::commit();
            return redirect('/console/goodcate/index')->with('message', '属性修改成功！');
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
                GoodCate::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择商品！');
        }
    }
}
