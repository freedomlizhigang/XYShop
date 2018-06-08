<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Http\Requests\Good\PromotionRequest;
use App\Models\Good\Good;
use App\Models\Good\PromGood;
use App\Models\Good\Promotion;
use DB;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * 活动列表
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '活动列表';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status');
		$list = Promotion::where(function($q) use($key){
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
        // 记录上次请求的url path，返回时用
        session()->put('backurl',$res->fullUrl());
    	return view('admin.promotion.index',compact('title','list','key','starttime','endtime','status'));
    }
    // 添加活动
    public function getAdd()
    {
    	$title = '添加活动';
    	return view('admin.promotion.add',compact('title'));
    }
    public function postAdd(PromotionRequest $req)
    {
    	DB::beginTransaction();
        try {
            $data = $req->input('data');
        	$pid = Promotion::create($data);
            // 取出来所有的商品ID
            $gids = array_unique($req->input('good_id'));
            $insert = [];
            foreach ($gids as $v) {
                $insert[] = ['good_id'=>$v,'prom_id'=>$pid->id];
            }
            PromGood::insert($insert);
            Good::whereIn('id',$gids)->update(['prom_type'=>4,'prom_id'=>$pid->id]);
            // 没出错，提交事务
            DB::commit();
            return $this->adminJson(1,'添加成功！',url('/console/promotion/index'));
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(0,$e->getMessage());
        }
    }
    // 修改活动
    public function getEdit($id = '')
    {
    	$title = '修改活动';
        $ref = session('backurl');
    	$info = Promotion::findOrFail($id);
        $gids = PromGood::where('prom_id',$id)->pluck('good_id');
        $goodlist = Good::whereIn('id',$gids)->select('id','title','shop_price','store')->get();
    	return view('admin.promotion.edit',compact('title','info','goodlist','ref'));
    }
    public function postEdit(PromotionRequest $req,$id = '')
    {
        DB::beginTransaction();
        try {
            $data = $req->input('data');
        	Promotion::where('id',$id)->update($data);
            // 取出来所有的商品ID
            $gids = array_unique($req->input('good_id'));
            $insert = [];
            foreach ($gids as $v) {
                $insert[] = ['good_id'=>$v,'prom_id'=>$id];
            }
            // 删除旧的
            Good::where('prom_id',$id)->where('prom_type',4)->update(['prom_type'=>0,'prom_id'=>0]);
            PromGood::where('prom_id',$id)->delete();
            // 加入新的
            PromGood::insert($insert);
            // 商品状态更新
            if ($data['status']) {
                Good::whereIn('id',$gids)->update(['prom_type'=>4,'prom_id'=>$id]);
            }
            else
            {
                Good::whereIn('id',$gids)->update(['prom_type'=>0,'prom_id'=>$id]);
            }
            // 没出错，提交事务
            DB::commit();
            return $this->adminJson(1,'添加成功！',$req->ref);
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(0,$e->getMessage());
        }

    	return $this->adminJson(1,'修改成功！');
    }
    // 删除
    public function getDel($id = '')
    {
        // 删除旧的
        Good::where('prom_id',$id)->where('prom_type',4)->update(['prom_type'=>0,'prom_id'=>0]);
    	Promotion::where('id',$id)->update(['delflag'=>0]);
        PromGood::where('prom_id',$id)->delete();
    	return back()->with('message','删除成功！');
    }
    // 下属商品
    public function getGoodlist($id = '')
    {
    	$title = '商品列表';
        // 搜索关键字
		$ids = PromGood::where('prom_id',$id)->pluck('good_id');
		$list = Good::whereIn('id',$ids)->where('status',1)->orderBy('id','desc')->get();
    	return view('admin.promotion.goodlist',compact('title','list','id'));
    }

    // 排序
    public function postSort(Request $req)
    {
        $ids = $req->input('sids');
        $sort = $req->input('sort');
        if (is_array($ids))
        {
            foreach ($ids as $v) {
                Promotion::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
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
            // 删除旧的
            Good::whereIn('prom_id',$ids)->where('prom_type',4)->update(['prom_type'=>0,'prom_id'=>0]);
            Promotion::whereIn('id',$ids)->update(['delflag'=>0]);
            PromGood::whereIn('prom_id',$ids)->delete();
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}
