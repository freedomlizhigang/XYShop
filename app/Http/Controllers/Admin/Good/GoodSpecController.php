<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Http\Requests\Good\GoodSpecRequest;
use App\Models\Good\Good;
use App\Models\Good\GoodSpec;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use DB;
use Illuminate\Http\Request;

class GoodSpecController extends Controller
{
    public function getIndex(Request $res)
    {
    	$title = '商品规格列表';
        $list = GoodSpec::with('goodspecitem')->orderBy('id','desc')->paginate(15);
        return view('admin.goodspec.index',compact('list','title'));
    }

    public function postAdd(Request $req)
    {
        $data['name'] = $req->goodspec;
        $data['good_id'] = $req->good_id;
        // 开启事务
        DB::beginTransaction();
        try {
        	$goodspec = GoodSpec::create($data);
            DB::commit();
            return $this->resJson(1,'添加商品规格成功！',$goodspec->id);
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->resJson(0,$e->getMessage());
        }
    }
    // 修改商品规格
    public function getEdit(Request $req,$id)
    {
        $title = '修改商品规格';
        $info = GoodSpec::with('goodspecitem')->findOrFail($id);
        return view('admin.goodspec.edit',compact('title','info','id'));
    }
    public function postEdit(GoodSpecRequest $req,$id)
    {
        $data = $req->input('data');
        $items = app('com')->trim_value($req->input('items'));
        // 开启事务
        DB::beginTransaction();
        try {
            GoodSpec::where('id',$id)->update($data);
            $items = json_decode($items);
            $goodspecitem = [];
            $date = date('Y-m-d H:i:s');
            // 找出来老的，合并新的
            $old = GoodSpecItem::where('good_spec_id',$id)->select('id','item')->get();
            /* 提交过来的 跟数据库中比较 不存在 插入*/
            $dataList = [];
            $old_item = $old->pluck('item')->toArray();
            foreach($items as $key => $val)
            {
                if (array_search(trim($val),$old_item) === false) {
                    $dataList[] = array('good_spec_id'=>$id,'item'=>trim($val),'created_at'=>$date,'updated_at'=>$date);
                }
            }
            // 添加新的
            GoodSpecItem::insert($dataList);
            /* 数据库中的 跟提交过来的比较 不存在删除*/
            foreach($old as $key => $val)
            {
                if(!in_array(trim($val->item), $items))
                {
                    // 找出来正使用的规格的商品ID
                    $goods = GoodSpecPrice::where('item_id','like','%_'.$val->id.'_%')->get();
                    // 减库存
                    foreach ($goods as $g) {
                        Good::where('id',$g->good_id)->decrement('store',$g->store);
                    }
                    // 删除正在使用的规格
                    GoodSpecPrice::whereIn('item_id',$goods->pluck('item_id'))->delete();
                    GoodSpecItem::where('id',$val->id)->delete();

                }
            }
            // 没出错，提交事务
            DB::commit();
            return $this->adminJson(1,'修改商品规格成功！');
        } catch (Exception $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(0,$e->getMessage());
        }
    }
    // 删除商品规格
    public function getDel($id)
    {
    	// 先查有没有过着的，有，不让删除
        $keys = GoodSpecItem::where('good_spec_id',$id)->pluck('id');
        foreach ($keys as $k) {
            if (!is_null(GoodSpecPrice::where('item_id','like','%_'.$k.'_%')->first())) {
                return back()->with('message', '商品规格正在使用中，不可删除！');
            }
        }
        GoodSpec::destroy($id);
        // 同时删除相关数据
        GoodSpecItem::where('good_spec_id',$id)->delete();
        return back()->with('message', '商品规格删除成功！');
    }
}