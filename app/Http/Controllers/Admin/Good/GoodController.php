<?php

namespace App\Http\Controllers\Admin\Good;

use App\Http\Controllers\Controller;
use App\Http\Requests\Good\GoodRequest;
use App\Models\Common\Type;
use App\Models\Good\Cart;
use App\Models\Good\Good;
use App\Models\Good\GoodAttr;
use App\Models\Good\GoodCate;
use App\Models\Good\GoodSpec;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\GoodsAttr;
use App\Models\Good\PromGood;
use App\Models\Good\Tuan;
use DB;
use Illuminate\Http\Request;
use Storage;

class GoodController extends Controller
{
    /**
     * 商品列表
     * @return [type] [description]
     */
    public function getIndex(Request $res)
    {
    	$title = '商品列表';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $starttime = $res->input('starttime');
        $endtime = $res->input('endtime');
        $status = $res->input('status',1);
        $sort = $res->input('sort','id');
        // 销量sales，价格shop_price，库存store
        $sortDesc = $res->input('sc','desc');
        $cate_id_1 = $res->input('cate_id_1');
        $cate_id_2 = $res->input('cate_id_2');
        $cate_id = $res->input('cate_id');
        if ($cate_id == 0) {
            if ($cate_id_2 == 0) {
                $cate_id = $cate_id_1;
            }
            else
            {
                $cate_id = $cate_id_2;
            }
        }
        $list = Good::with('goodspecprice')->where(function($q) use($cate_id){
                if ($cate_id != '') {
                    $ids = GoodCate::where('id',$cate_id)->value('arrchildid');
                    $q->whereIn('cate_id',explode(',',$ids));
                }
            })->where(function($q) use($key){
                if ($key != '') {
                    $q->where('title','like','%'.$key.'%');
                }
            })->where(function($q) use($starttime){
                if ($starttime != '') {
                    $q->where('created_at','>',$starttime);
                }
            })->where(function($q) use($endtime){
                if ($endtime != '') {
                    $q->where('created_at','<',$endtime);
                }
            })->where(function($q) use($status){
                if ($status != '') {
                    $q->where('status',$status);
                }
            })->orderBy($sort,$sortDesc)->orderBy('id','desc')->paginate(15);
        $count = Good::where(function($q) use($cate_id){if ($cate_id != '') {
                    if ($cate_id != '') {
                        $ids = GoodCate::where('id',$cate_id)->value('arrchildid');
                        $q->whereIn('cate_id',explode(',',$ids));
                    }
                }})->where(function($q) use($key){if ($key != '') {
                    $q->where('title','like','%'.$key.'%');
                }})->where(function($q) use($starttime){if ($starttime != '') {
                    $q->where('created_at','>',$starttime);
                }})->where(function($q) use($endtime){if ($endtime != '') {
                    $q->where('created_at','<',$endtime);
                }})->where(function($q) use($status){if ($status != '') {
                    $q->where('status',$status);
                }})->count();
        // 记录上次请求的url path，返回时用
        session()->put('backurl',$res->fullUrl());
        $thisUrlLink = url()->current();
        $thisUrl = parse_url(url()->full());
        if (isset($thisUrl['query'])) {
            $query = explode('&',urldecode($thisUrl['query']));
            $params = [];
            foreach ($query as $k => $v) {
                $tmp_v= explode('=',$v);
                if ('sort' != $tmp_v[0] && $tmp_v[0] != 'sc') {
                    $params[$tmp_v[0]] = $tmp_v[1];
                }
            }
            $thisUrl = $thisUrlLink.'?'.http_build_query($params);
        }
        else
        {
            $thisUrl = $thisUrlLink.'?';
        }
    	return view('admin.good.index',compact('title','list','key','starttime','endtime','status','count','sort','cate_id_1','cate_id_2','cate_id','sortDesc','thisUrl'));
    }

    /**
     * 商品列表
     * @return [type] [description]
     */
    public function getNostore(Request $res)
    {
        $title = '无库存商品列表';
        $list = Good::with('goodspecprice')->where('status',1)->where('store',0)->orderBy('id','desc')->paginate(15);
        $count = $list->count();
        // 记录上次请求的url path，返回时用
        session()->put('backurl',$res->fullUrl());
        return view('admin.good.nostore',compact('title','list','count'));
    }

    /**
     * 添加商品
     * @param  string $catid [栏目ID]
     * @return [type]        [description]
     */
    public function getAdd()
    {
    	$title = '添加商品';
    	return view('admin.good.add',compact('title','id'));
    }
    public function postAdd(GoodRequest $res)
    {
        $data = $res->input('data');
        // 开启事务
        DB::beginTransaction();
        try {
            $good = Good::create($data);
            $good_id = $res->good_id;
            $date = date('Y-m-d H:i:s');
            // 规格对应的值
            $spec_item = $res->input('spec_item');
            if (is_array($spec_item)) {
                $store = 0;
                $tmp_spec = [];
                foreach ($spec_item as $sk => $sv) {
                    $tmp_spec[] = ['good_id'=>$good->id,'item_id'=>$sk,'item_name'=>$sv['item_name'],'price'=>$sv['price'],'store'=>$sv['store'],'created_at'=>$date,'updated_at'=>$date];
                    $store += $sv['store'];
                }
                GoodSpecPrice::insert($tmp_spec);
                Good::where('id',$good->id)->update(['store'=>$store,'shop_price'=>$tmp_spec[0]['price']]);
            }
            // 更新商品规格
            GoodSpec::where('good_id',$good_id)->update(['good_id'=>$good->id]);
            GoodSpecItem::where('good_id',$good_id)->update(['good_id'=>$good->id]);
            // 没出错，提交事务
            DB::commit();
            // 跳转回添加的栏目列表
            return $this->adminJson(1,'添加商品成功！',url('/console/good/index'));
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(0,$e->getMessage());
            // return back()->with('message','添加失败，请稍后再试！');
        }
    }

    /**
     * 修改商品
     * @param  string $id [ID]
     * @return [type]        [description]
     */
    public function getEdit($id = '0')
    {
    	$title = '修改商品';
    	$ref = session('backurl');
    	$info = Good::findOrFail($id);
    	return view('admin.good.edit',compact('title','ref','info'));
    }
    public function postEdit(GoodRequest $res,$id)
    {
        // 开启事务
        DB::beginTransaction();
        try {
            $data = $res->input('data');
            Good::where('id',$id)->update($data);
            $date = date('Y-m-d H:i:s');
            // 如果分类变了要删除所有属性重新添加
            // 规格对应的值
            $spec_item = $res->input('spec_item');
            GoodSpecPrice::where('good_id',$id)->delete();
            if (is_array($spec_item)) {
                $store = 0;
                $tmp_spec = [];
                foreach ($spec_item as $sk => $sv) {
                    $tmp_spec[] = ['good_id'=>$id,'item_id'=>$sk,'item_name'=>$sv['item_name'],'price'=>$sv['price'],'store'=>$sv['store'],'created_at'=>$date,'updated_at'=>$date];
                    $store += $sv['store'];
                }
                GoodSpecPrice::insert($tmp_spec);
                Good::where('id',$id)->update(['store'=>$store,'shop_price'=>$tmp_spec[0]['price']]);
            }
            // 同步修改购物车里的价格
            $good_spec_names = GoodSpecPrice::where('good_id',$id)->get()->keyBy('item_name')->toArray();
            $carts = Cart::where('good_id',$id)->get();
            foreach ($carts as $k => $v) {
                if ($v->good_spec_name != '' && isset($good_spec_names[$v->good_spec_name])) {
                    $price = $good_spec_names[$v->good_spec_name]['price'];
                    $total_prices = $v->nums * $price;
                    Cart::where('good_id',$v->good_id)->where('good_spec_name',$v->good_spec_name)->update(['good_title'=>$data['title'],'price'=>$price,'total_prices'=>$total_prices]);
                }
                else
                {
                    $total_prices = $v->nums * $data['shop_price'];
                    Cart::where('good_id',$v->good_id)->update(['good_title'=>$data['title'],'price'=>$data['shop_price'],'total_prices'=>$total_prices]);
                }
            }
            // 没出错，提交事务
            DB::commit();
            // 跳转回添加的栏目列表
            return $this->adminJson(1,'修改商品商品成功！',$res->ref);
        } catch (\Throwable $e) {
            // 出错回滚
            DB::rollBack();
            return $this->adminJson(0,$e->getLine().$e->getMessage());
        }
    }
    // 上下架
    public function getDel(Request $req,$id = '',$status = '')
    {
    	Good::where('id',$id)->update(['status'=>$status]);
        // 下架时删除购物车
        if ($status == 0) {
            Cart::where('good_id',$id)->delete();
        }
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
                Good::where('id',$v)->update(['sort'=>(int) $sort[$v]]);
            }
            return back()->with('message', '排序成功！');
        }
        else
        {
            return back()->with('message', '请先选择商品！');
        }
    }
    // 批量上下架
    public function postAllStatus(Request $req)
    {
        $ids = $req->input('sids');
        $status = $req->status;
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            // 开启事务
            DB::beginTransaction();
            try {
                Good::whereIn('id',$ids)->update(['status'=>$status]);
                // 下架时删除购物车
                if ($status == 0) {
                    Cart::whereIn('good_id',$ids)->delete();
                }
                // 没出错，提交事务
                DB::commit();
                return back()->with('message', '批量操作完成！');
            } catch (\Throwable $e) {
                // 出错回滚
                DB::rollBack();
                return back()->with('message','操作失败，请稍后再试！');
            }
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
    // 数据转移
    public function postAllCate(Request $req)
    {
        $ids = $req->input('sids');
        $cate_id = $req->cate_id;
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            // 开启事务
            DB::beginTransaction();
            try {
                Good::whereIn('id',$ids)->update(['cate_id'=>$cate_id]);
                // 没出错，提交事务
                DB::commit();
                return back()->with('message', '批量修改分类完成！');
            } catch (\Throwable $e) {
                // 出错回滚
                DB::rollBack();
                return back()->with('message','操作失败，请稍后再试！');
            }
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
    // 批量删除
    public function postAlldel(Request $req)
    {
        $ids = $req->input('sids');
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            // 开启事务
            DB::beginTransaction();
            try {
                // Good::whereIn('id',$ids)->update(['status'=>0]);
                Good::whereIn('id',$ids)->delete();
                // 删除对应的规格及价格等信息
                GoodSpec::whereIn('good_id',$ids)->delete();
                GoodSpecPrice::whereIn('good_id',$ids)->delete();
                GoodSpecItem::whereIn('good_id',$ids)->delete();
                // 购物车删除
                Cart::whereIn('good_id',$ids)->delete();
                // 活动
                PromGood::whereIn('good_id',$ids)->delete();
                // 团购
                Tuan::whereIn('good_id',$ids)->delete();
                // 没出错，提交事务
                DB::commit();
                return back()->with('message', '批量删除完成！');
            } catch (\Throwable $e) {
                // 出错回滚
                DB::rollBack();
                return back()->with('message','删除失败，请稍后再试！');
            }
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }

    // 取商品分类下规格
    public function getGoodSpec(Request $req)
    {
        $good_id = $req->good_id;
        // 查出来所有的规格ID
        $items_id = GoodSpecPrice::where('good_id',$good_id)->pluck('item_id');
        $items_ids = [];
        $items_id = $items_id->unique();
        foreach ($items_id as $t) {
            $items_ids = array_merge($items_ids,explode('_',$t));
        }
        $items_ids = array_unique($items_ids);
        if ($good_id == '') {
            $gid = str_replace('.','',microtime(TRUE));
        }
        else
        {
            $gid = $good_id;
        }
        return view('admin.good.goodspec',compact('items_ids','gid'));
    }
    public function getGoodSpecStr(Request $req)
    {
        $good_id = $req->good_id;
        $list = GoodSpec::with('goodspecitem')->where('good_id',$good_id)->orderBy('id','asc')->get();
        // 查出来所有的规格ID
        $items_id = GoodSpecPrice::where('good_id',$good_id)->pluck('item_id');
        $items_ids = [];
        $items_id = $items_id->unique();
        foreach ($items_id as $t) {
            $items_ids = array_merge($items_ids,explode('_',$t));
        }
        $items_ids = array_unique($items_ids);
        $str = '';
        foreach ($list as $l) {
            $str .= '<tr><td class="text-right" width="200"><span data-id="'.$l->id.'" class="btn btn-xs btn-danger glyphicon glyphicon-trash btn-spec-del"></span> <a href="'.url('/console/goodspec/edit',$l->id).'" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-warning">'.$l->name.' <i class="glyphicon glyphicon-edit"></i></a>';
            $str .= '：</td><td>';
            foreach($l->goodspecitem as $gsi){
                $str .= '<span class="btn btn-xs ';
                if (!in_array($gsi->id,$items_ids)) {
                    $str .= 'btn-info';
                }
                else
                {
                    $str .= 'btn-success';
                }
                $str .= '" data-spec_id="'.$l->id.'" data-item_id="'.$gsi->id.'">'.$gsi->item.' <span class="glyphicon glyphicon-plus"></span></span> ';
            }
            $str .= '</td></tr>';
        }
        return $str;
    }
    /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     * 获取 规格的 笛卡尔积
     * @param $goods_id 商品 id
     * @param $spec_arr 笛卡尔积
     * @return string 返回表格字符串
     */
    public function postGoodSpecInput(Request $req){
        try {
            $goods_id = isset(($req->all())['goods_id']) ? ($req->all())['goods_id'] : 0;
            $spec_arr = isset(($req->all())['spec_arr']) ? ($req->all())['spec_arr'] : [[]];

            // 排序
            foreach ($spec_arr as $k => $v)
            {
                $spec_arr_sort[$k] = count($v);
            }
            asort($spec_arr_sort);
            foreach ($spec_arr_sort as $key =>$val)
            {
                $spec_arr2[$key] = $spec_arr[$key];
            }

            $clo_name = array_keys($spec_arr2);
            $spec_arr2 = app('com')->combineDika($spec_arr2); //  获取 规格的 笛卡尔积

            $spec = GoodSpec::select('id','name')->get()->keyBy('id')->toArray(); // 规格表
            $specItem = GoodSpecItem::get()->keyBy('id')->toArray();    //规格项
            $keySpecGoodsPrice = GoodSpecPrice::where('good_id',$goods_id)->select('item_id','item_name','price','store')->get()->keyBy('item_id')->toArray();//规格项

           $str = "<table class='table table-bordered' id='spec_input_tab'>";
           $str .="<tr>";
           // 显示第一行的数据
            foreach ($clo_name as $k => $v)
            {
                if ($v != 0) {
                    $str .=" <td><b>".$spec[$v]['name']."</b></td>";
                }
            }
            $str .="<td><b>价格</b></td>
                   <td><b>库存</b></td>
                 </tr>";
           // 显示第二行开始
           foreach ($spec_arr2 as $k => $v)
           {
                $str .="<tr>";
                $item_key_name = array();
                foreach($v as $k2 => $v2)
                {
                    $str .="<td>".$specItem[$v2]['item']."</td>";
                    // $item_key_name[$v2] = $specItem[$v2]['item'];
                    $item_key_name[$v2] = $spec[$specItem[$v2]['good_spec_id']]['name'].':'.$specItem[$v2]['item'];
                }
                ksort($item_key_name);
                $item_key = '_'.implode('_', array_keys($item_key_name)).'_';
                $item_name = implode(' ', $item_key_name);

                $keySpecGoodsPrice[$item_key]['price'] = isset($keySpecGoodsPrice[$item_key]['price']) ? $keySpecGoodsPrice[$item_key]['price'] : 0; // 价格默认为0
                $keySpecGoodsPrice[$item_key]['store'] = isset($keySpecGoodsPrice[$item_key]['store']) ? $keySpecGoodsPrice[$item_key]['store'] : 0; //库存默认为0
                $str .="<td><input name='spec_item[$item_key][price]' class='form-control input-xs' value='{$keySpecGoodsPrice[$item_key]['price']}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
                $str .="<td><input name='spec_item[$item_key][store]' class='form-control input-xs' value='{$keySpecGoodsPrice[$item_key]['store']}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/>
                    <input type='hidden' name='spec_item[$item_key][item_name]' value='$item_name' /></td>";
                $str .="</tr>";
           }
            $str .= "</table>";
            exit($str);
        } catch (\Throwable $e) {
            exit($e->getLine().' - '.$e->getMessage());
        }
    }

    // 选择商品，活动里用
    public function getSelect(Request $res,$type = '1')
    {
        $title = '商品列表';
        // 搜索关键字
        $key = trim($res->input('q',''));
        $cate_id_1 = $res->input('cate_id_1');
        $cate_id_2 = $res->input('cate_id_2');
        $cate_id = $res->input('cate_id');
        if ($cate_id == 0) {
            if ($cate_id_2 == 0) {
                $cate_id = $cate_id_1;
            }
            else
            {
                $cate_id = $cate_id_2;
            }
        }
        $list = Good::where(function($q) use($key){
                if ($key != '') {
                    $q->where('title','like','%'.$key.'%');
                }
            })->where(function($q) use($cate_id){
                if ($cate_id != '') {
                    $ids = GoodCate::where('id',$cate_id)->value('arrchildid');
                    $q->whereIn('cate_id',explode(',',$ids));
                }
            })->where('status',1)->where('prom_type',0)->where('prom_id',0)->orderBy('sort','desc')->orderBy('id','desc')->paginate(10);
        return view('admin.good.select',compact('title','list','key','type','cate_id_1','cate_id_2','cate_id'));
    }
}
