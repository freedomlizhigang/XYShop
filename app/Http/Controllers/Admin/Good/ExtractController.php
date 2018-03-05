<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Http\Requests\Good\ExtractRequest;
use App\Models\Common\Type;
use App\Models\Good\Extract;
use Illuminate\Http\Request;

class ExtractController extends Controller
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
		$list = Extract::where(function($q) use($key){
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
    	return view('admin.extract.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加自提
    public function getAdd()
    {
    	$title = '添加自提';
        $area = Type::where('parentid',4)->get();
    	return view('admin.extract.add',compact('title','area'));
    }
    public function postAdd(ExtractRequest $req)
    {
    	$data = $req->input('data');
    	Extract::create($data);
        return $this->adminJson(1,'添加成功！',url('/console/extract/index'));
    }
    // 修改自提
    public function getEdit($id = '')
    {
    	$title = '修改自提';
        $area = Type::where('parentid',4)->get();
    	$info = Extract::findOrFail($id);
    	return view('admin.extract.edit',compact('title','info','area'));
    }
    public function postEdit(ExtractRequest $req,$id = '')
    {
    	$data = $req->input('data');
    	Extract::where('id',$id)->update($data);
        return $this->adminJson(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
    	Extract::where('id',$id)->update(['delflag'=>0]);
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
                Extract::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择自提！');
        }
    }
}
