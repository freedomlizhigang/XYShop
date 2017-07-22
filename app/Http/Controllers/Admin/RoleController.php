<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\RoleRequest;
use App\Models\Console\Menu;
use App\Models\Console\Priv;
use App\Models\Console\Role;
use App\Models\Console\RoleUser;
use DB;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    /**
     * 构造函数
     */
    public function __construct()
    {
    	$this->role = new Role;
        $this->priv = new Priv;
        $this->menu = new Menu;
    }

    public function getIndex(Request $res)
    {
    	$title = '角色列表';
        $list = $this->role->paginate(15);
        // 保存一次性数据，url参数，供编辑完成后跳转用
        $res->flash();
        return view('admin.role.index',compact('list','title'));
    }

    // 添加角色
    public function getAdd()
    {
        $title = '添加角色';
        return view('admin.role.add',compact('title'));
    }

    public function postAdd(RoleRequest $request)
    {
        $data = $request->input('data');
        $this->role->create($data);
        return $this->ajaxReturn(1,'添加角色成功！',url('/console/role/index'));
    }
    // 修改角色
    public function getEdit($rid)
    {
        $title = '修改角色';
        // 拼接返回用的url参数
        $info = $this->role->findOrFail($rid);
        return view('admin.role.edit',compact('title','info'));
    }
    public function postEdit(RoleRequest $request,$rid)
    {
        $this->role->where('id',$rid)->update($request->input('data'));
        return $this->ajaxReturn(1,'修改角色成功！');
    }
    // 删除角色
    public function getDel($rid)
    {
        if ($rid === 1) {
            return back()->with('message','超级管理员组不能删除！');
        }
        // 查询下属用户
        if(is_null(RoleUser::where('role_id',$rid)->first()))
        {
            // 开启事务
            DB::beginTransaction();
            try {
                // 同时删除关联的用户关系
                $this->role->destroy($rid);
                Priv::where('role_id',$rid)->delete();
                // 没出错，提交事务
                DB::commit();
                return back()->with('message', '删除角色成功！');
            } catch (Exception $e) {
                // 出错回滚
                DB::rollBack();
                return back()->with('message','删除失败，请稍后再试！');
            }
        }
        else
        {
            return back()->with('message', '角色下有用户！');
        }
        
    }
    // 更新角色权限，主要是为了显示后台菜单
    public function getPriv($rid)
    {
        $title = '更新权限';
        $ishav = $this->priv->where('role_id',$rid)->get();
        $rids = '';
        if($ishav->isEmpty() == false)
        {
            $hav = $ishav->toArray();
            foreach ($hav as $v) {
                $rids .= "'".$v['menu_id']."',";
            }
        }
        // dd($rids);
        $all = $this->menu->get();
        $tree = app('com')->toTree($all,'0');
        $treePriv = $this->treePriv($tree);
        return view('admin.role.priv',compact('title','rids','treePriv'));
    }
    public function postPriv(Request $res,$rid)
    {
        if ($rid === 1) {
            return back()->with('message','超级管理员有所有权限，不用修改！');
        }
        // 开启事务
        DB::beginTransaction();
        try {
            $this->priv->where('role_id',$rid)->delete();
            // 所有选中的菜单url，以此查找出所有url label
            $ids = $res->input('ids');
            $all = $this->menu->whereIn('id',$ids)->get()->toArray();
            // 将查出来的数据组成数组插入到role_privs表里
            foreach ($all as $v) {
                $insertArr = array('menu_id'=>$v['id'],'role_id'=>$rid,'url'=>$v['url'],'label'=>$v['label']);
                $this->priv->create($insertArr);
            }
            // 没出错，提交事务
            DB::commit();
            return back()->with('message', '更新权限菜单成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return back()->with('message','更新权限菜单失败，请稍后再试！');
        }
    }
    // 权限菜单 html
    private function treePriv($tree)
    {
        $html = '';
        if (is_array($tree)) {
            foreach ($tree as $v) {
                // 计算level
                $level = count(explode(',',$v['arrparentid']));
                if ($v['parentid'] == '')
                {
                    $html .= "<li><label class='checkbox-inline'><input type='checkbox' name='ids[]' class='check-mr' value='".$v['id']."'>".$v['name']."</label></li>";
                }
                else
                {
                    $html .= "<li><label class='checkbox-inline'><input type='checkbox' name='ids[]' class='check-mr' value='".$v['id']."'>".$v['name']."</label>";
                    $html .= $this->treePriv($v['parentid']);
                    $html .= "</li>";
                }
            }
        }
        return $html ? "<ul class='clearfix priv-level-".$level."'>".$html."</ul>" : $html;
    }
}
