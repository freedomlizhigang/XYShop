<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\Good\ManzengRequest;
use App\Models\Manzeng;
use Illuminate\Http\Request;

class ManzengController extends BaseController
{
    /**
     * 满赠管理
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '满赠管理';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Manzeng::where(function($q) use($key){
                if ($key != '') {
                    $q->where('title','like','%'.$key.'%');
                }
            })->where(function($q) use($starttime,$endtime){
                if ($starttime != '' && $endtime != '') {
                    $q->where('starttime','>=',$starttime)->where('starttime','<=',$endtime);
                }
            })->where(function($q) use($status){
                if ($status != '') {
                    $q->where('status',$status);
                }
            })->where('delflag',1)->orderBy('id','desc')->paginate(15);
    	return view('admin.manzeng.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加满赠
    public function getAdd($id = '')
    {
    	$title = '添加满赠';
    	return view('admin.manzeng.add',compact('title','id'));
    }
    public function postAdd(ManzengRequest $req,$id = '')
    {
    	$data = $req->input('data');
        $data['good_id'] = $id;
    	Manzeng::create($data);
        return $this->ajaxReturn(1,'添加成功！',url('/console/manzeng/index'));
    }
    // 修改满赠
    public function getEdit($id = '')
    {
    	$title = '修改满赠';
    	$info = Manzeng::with('good')->findOrFail($id);
    	return view('admin.manzeng.edit',compact('title','info'));
    }
    public function postEdit(ManzengRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Manzeng::where('id',$id)->update($data);
        return $this->ajaxReturn(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
    	Manzeng::where('id',$id)->update(['delflag'=>0]);
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
                Manzeng::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择商品！');
        }
    }
    // 批量删除
    public function postAlldel(Request $req)
    {
        $ids = $req->input('sids');
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            Manzeng::whereIn('id',$ids)->update(['delflag'=>0]);
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}
