<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use Cache;
use Illuminate\Http\Request;

class MenuController extends BaseController
{
    public function __construct()
    {
        $this->menu = new Menu;
    }
    public function getIndex()
    {
        $title = '菜单列表';
        $list = $this->menu->orderBy('sort','asc')->orderBy('id','asc')->get();
        $tree = App::make('com')->toTree($list,'0');
        $treeHtml = $this->toTreeHtml($tree);
        return view('admin.menu.index',compact('treeHtml','title'));
    }
    
    // 树形菜单 html
    private function toTreeHtml($tree)
    {
        $html = '';
        foreach ($tree as $v) {
            // 用level判断层级，最好不要超过四层，样式中只写了四级
            $level = count(explode(',', $v['arrparentid']));
            $disStr = $v['display'] ? "<span class='text-success'>是</span>" : "<span class='text-danger'>否</span>";
            // level < 4 是为了不添加更多的层级关系，其它地方不用判断，只是后台菜单不用那么多级
            if ($level < 4) {
                 $html .= "<tr>
                    <td>".$v['sort']."</td>
                    <td>".$v['id']."</td>
                    <td><span class='level-".$level."'></span>".$v['name']."<div data-url='/console/menu/add/".$v['id']."' class='glyphicon glyphicon-plus curp add_submenu btn_modal' data-title='添加菜单' data-toggle='modal' data-target='#myModal'></div></td>
                    <td>".$v['url']."</td>
                    <td>".$disStr."</td>
                    <td><div data-url='/console/menu/edit/".$v['id']."' class='btn btn-xs btn-info glyphicon glyphicon-edit btn_modal' data-title='修改菜单' data-toggle='modal' data-target='#myModal'></div> <a href='/console/menu/del/".$v['id']."' class='btn btn-xs btn-danger glyphicon glyphicon-trash confirm'></a></td>
                    </tr>";
            }
            else
            {
                 $html .= "<tr>
                    <td>".$v['sort']."</td>
                    <td>".$v['id']."</td>
                    <td><span class='level-".$level."'></span>".$v['name']."</td>
                    <td>".$v['url']."</td>
                    <td>".$disStr."</td>
                    <td><div data-url='/console/menu/edit/".$v['id']."' class='btn btn-xs btn-info glyphicon glyphicon-edit btn_modal' data-title='修改菜单' data-toggle='modal' data-target='#myModal'></div> <a href='/console/menu/del/".$v['id']."' class='btn btn-xs btn-danger glyphicon glyphicon-trash confirm'></a></td>
                    </tr>";
            }
            if ($v['parentid'] != '')
            {
                $html .= $this->toTreeHtml($v['parentid']);
            }
        }
        return $html;
    }

    /**
     * 添加菜单模板
     * @param  Request $request [description]
     * @param  integer $pid     [父栏目id，默认为0，即为一级菜单]
     * @return [type]           [description]
     */
    public function getAdd(Request $request,$pid = 0)
    {
        $title = '添加菜单';
    	return view('admin.menu.add',compact('pid','title'));
    }
    /**
     * 添加菜单提交数据
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postAdd(MenuRequest $request)
    {
    	$data = request('data');
    	$this->menu->create($data);
        App::make('com')->updateCache($this->menu,'menuCache');
        return $this->ajaxReturn(1,'添加菜单成功',url('/console/menu/index'));
    	// return redirect('')->with('message', '');
    }
    /**
     * 修改菜单，当修改父级菜单的时候level要相应的进行修改
     * @param  integer $id [要修改的菜单ID]
     * @return [type]      [description]
     */
    public function getEdit($id = 0)
    {
        $title = '修改菜单';
        $info = $this->menu->findOrFail($id);
        $list = $this->menu->orderBy('sort','asc')->get();
        $tree = App::make('com')->toTree($list,'0');
        $treeSelect = App::make('com')->toTreeSelect($tree,$info->parentid);
        return view('admin.menu.edit',compact('title','info','treeSelect'));
    }
    public function postEdit(MenuRequest $res,$id)
    {
        $data = $res->input('data');
        $this->menu->where('id',$id)->update($data);
        App::make('com')->updateCache($this->menu,'menuCache');
        return $this->ajaxReturn(1,'修改菜单成功',url('/console/menu/index'));
        // return redirect('/console/menu/index')->with('message', '修改菜单成功！');
    }
    /**
     * 删除菜单及下属子菜单，取出当前菜单ID下边所有的子菜单ID（添加修改的时候会进行更新，包含最小是自身），然后转换成数组格式，指进行删除，然后更新菜单
     * @param  [type] $id [要删除的菜单ID]
     * @return [type]     [description]
     */
    public function getDel($id)
    {
        $info = $this->menu->findOrFail($id);
        $arr = explode(',', $info->arrchildid);
        $this->menu->destroy($arr);
        App::make('com')->updateCache($this->menu,'menuCache');
        return redirect('/console/menu/index')->with('message', '删除菜单成功！');
    }
}
