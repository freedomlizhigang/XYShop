<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Good\ZitiRequest;
use App\Models\Type;
use App\Models\Zitidian;
use Illuminate\Http\Request;

class ZitiController extends BaseController
{
    /**
     * 自提管理
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '自提管理';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Zitidian::where(function($q) use($key){
                if ($key != '') {
                    $q->where('address','like','%'.$key.'%');
                }
            })->where(function($q) use($starttime,$endtime){
                if ($starttime != '' && $endtime != '') {
                    $q->where('created_at','>=',$starttime)->where('created_at','<=',$endtime);
                }
            })->where(function($q) use($status){
                if ($status != '') {
                    $q->where('status',$status);
                }
            })->where('delflag',1)->orderBy('id','desc')->paginate(15);
    	return view('admin.ziti.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加自提
    public function getAdd()
    {
    	$title = '添加自提';
        $area = Type::where('parentid',4)->get();
    	return view('admin.ziti.add',compact('title','area'));
    }
    public function postAdd(ZitiRequest $req)
    {
    	$data = $req->input('data');
    	Zitidian::create($data);
        return $this->ajaxReturn(1,'添加成功！',url('/console/ziti/index'));
    }
    // 修改自提
    public function getEdit($id = '')
    {
    	$title = '修改自提';
        $area = Type::where('parentid',4)->get();
    	$info = Zitidian::findOrFail($id);
    	return view('admin.ziti.edit',compact('title','info','area'));
    }
    public function postEdit(ZitiRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Zitidian::where('id',$id)->update($data);
        return $this->ajaxReturn(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
    	Zitidian::where('id',$id)->update(['delflag'=>0]);
    	return back()->with('message','删除成功！');
    }
    // 排序
    public function postSort(Request $req)
    {
        $ids = $req->input('sids');
        $sort = $req->input('sort');
        if (is_array($ids))
        {
            foreach ($ids as $v) {
                Zitidian::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择自提！');
        }
    }
}
