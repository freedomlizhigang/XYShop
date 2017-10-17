<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Http\Controllers\Common\GoodSelect;
use App\Models\Common\Article;
use App\Models\Common\Cate;
use App\Models\Good\Brand;
use App\Models\Good\Good;
use App\Models\Good\GoodAttr;
use App\Models\Good\GoodCate;
use App\Models\Good\GoodComment;
use App\Models\Good\GoodSpec;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $ishome = 1;
        $seo = ['title'=>cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        return view($this->theme.'.home',compact('ishome','seo'));
    }
    /*
    * 商品详情页面
    */
    public function getGood($id = '')
    {
        $good = Good::with(['goodattr'=>function($q){
                    $q->with('goodattr');
                }])->findOrFail($id);
        /*
        * 查出来所有的规格信息
        * 1、找出所有的规格ID 
        * 2，查出所有的规格ID对应的名字spec_item及spec内容
        * 3、循环出来所有的规格及规格值
        * */
        $good_spec_ids = GoodSpecPrice::where('good_id',$id)->pluck('item_id')->toArray();
        $good_spec_ids = explode('_',implode('_',$good_spec_ids));
        $good_spec = GoodSpecItem::with(['goodspec'=>function($q){
                        $q->select('id','name');
                    }])->whereIn('id',$good_spec_ids)->get();
        $filter_spec = [];
        foreach ($good_spec as $k => $v) {
            $filter_spec[$v->goodspec->name][] = ['item_id'=>$v->id,'item'=>$v->item];
        }
        // 查出第一个规格信息来，标红用的
        $good_spec_price = GoodSpecPrice::where('good_id',$id)->get()->keyBy('item_id')->toJson();

        // 取评价，20条
        $goodcomment = GoodComment::with(['user'=>function($q){
                $q->select('id','nickname','thumb','username');
            }])->where('good_id',$id)->where('delflag',1)->orderBy('id','desc')->limit(20)->get();

        return view($this->theme.'.good',compact('good','filter_spec','good_spec_price','goodcomment'));
    }
    /*
    * 分类筛选页面
    *
     */
    public function getList(Request $req)
    {
        // 分类id
        $cid = $req->id;
        // 筛选字段
        $filter_param = [];
        $brand_id = $req->input('brand_id','');;
        $spec = $req->input('spec',''); // 规格 
        $attr = $req->input('attr','');; // 属性        
        $sort = $req->input('sort','sort');; // 排序
        $sort_asc = $req->input('sort_asc','desc');; // 排序
        $price = $req->input('price','');; // 价钱
        $start_price = $req->input('start_price',0); // 输入框价钱
        $end_price = $req->input('end_price',0);; // 输入框价钱        
        if($start_price && $end_price) $price = $start_price.'-'.$end_price; // 如果输入框有价钱 则使用输入框的价钱
     
        $brand_id  && ($filter_param['brand_id'] = $brand_id); //加入筛选条件中
        $spec  && ($filter_param['spec'] = $spec); //加入筛选条件中
        $attr  && ($filter_param['attr'] = $attr); //加入筛选条件中
        $price  && ($filter_param['price'] = $price); //加入筛选条件中
        // 找出分类所有父级
        $cinfo = GoodCate::where('id',$cid)->select('id','name','arrparentid','arrchildid')->first();
        // 所有父级id数组
        $pids = array_slice(explode(',',$cinfo->arrparentid),1);
        $level = count($pids) + 1;
        // 如果是三级则找出来一级跟二级下的所有栏目
        $cate_3 = $cate_2 = [];
        $cate_2_info = $cate_3_info = '';
        if ($level == 3) {
            $cate_3_info = GoodCate::where('id',$cid)->select('id','name')->orderBy('sort','asc')->first();
            $cate_3 = GoodCate::where('parentid',$pids[1])->select('id','name')->orderBy('sort','asc')->get();
        }
        if ($level >= 2) {
            $tmp_pid = $level == 2 ? $cid : $pids[1];
            $cate_2_info = GoodCate::where('id',$tmp_pid)->select('id','name')->orderBy('sort','asc')->first();
            $cate_2 = GoodCate::where('parentid',$pids[0])->select('id','name')->orderBy('sort','asc')->get();
        }
        // 找出所有子级
        $childids = explode(',',$cinfo->arrchildid);
        // 找出所有要筛选的商品ID
        $filter_goods_id = Good::whereIn('cate_id',$childids)->where('status',1)->pluck('id')->toArray();
        // 商品筛选功能类
        $goodselect = new GoodSelect();
        // 过滤帅选的结果集里面找商品        
        if($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodselect->getGoodsIdByBrandPrice($brand_id,$price,$filter_goods_id); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if($spec)// 规格
        {
            $goods_id_2 = $goodselect->getGoodsIdBySpec($spec,$filter_goods_id); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if($attr)// 属性
        {
            $goods_id_3 = $goodselect->getGoodsIdByAttr($attr,$filter_goods_id); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_3); // 获取多个帅选条件的结果 的交集
        }
        // 价格范围
        $filter_menu  = $goodselect->get_filter_menu($filter_param,"list/$cid"); // 获取显示的帅选菜单
        $filterPrice = $goodselect->get_filter_price($filter_goods_id,$filter_param,"list/$cid");
        $filterBrand = $goodselect->get_filter_brand($filter_goods_id,$filter_param,"list/$cid",1); // 获取指定分类下的帅选品牌
        $filterSpec  = $goodselect->get_filter_spec($childids,$filter_goods_id,$filter_param,"list/$cid",1); // 获取指定分类下的帅选规格
        $filterAttr  = $goodselect->get_filter_attr($childids,$filter_goods_id,$filter_param,"list/$cid",1); // 获取指定分类下的帅选属性

        // 真实的排序字段
        switch ($sort) {
            case 'price':
                $sort_real = 'shop_price';
                break;
            case 'sale':
                $sort_real = 'sales';
                break;
            case 'comment':
                $sort_real = 'commentnums';
                break;
            case 'times':
                $sort_real = 'created_at';
                break;
            default:
                $sort_real = 'sort';
                break;
        }
        // 真正的商品查询
        $list = Good::select('id','title','thumb','shop_price')->whereIn('id',$filter_goods_id)->orderBy($sort_real,$sort_asc)->orderBy('id','desc')->paginate(40);
        $count = count($filter_goods_id);
        $catename = GoodCate::where('id',$cid)->value('name');
        $seo = ['title'=>$catename,'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
        return view($this->theme.'.list',compact('cinfo','cate_2','cate_3','cate_3_info','cate_2_info','cid','sort_asc','sort','filter_param','filterPrice','filterBrand','filterSpec','filterAttr','filter_menu','list','count','seo'));
    }

    // 栏目
    public function getCate($url = '')
    {
        // 找栏目
        $info = Cate::where('url',$url)->first();
        $info->pid = 0;
        // 如果存在栏目，接着找
        if (is_null($info)) {
            return back()->with('message','没有找到对应栏目！');
        }
        $aside_name = $info->name;
        $tpl = $info->theme;
        if ($info->type == 0) {
            $list = Article::whereIn('catid',explode(',', $info->arrchildid))->orderBy('id','desc')->simplePaginate(20);
            return view($this->theme.'.'.$tpl,compact('info','list','aside_name'));
        }
        else
        {
            return view($this->theme.'.'.$tpl,compact('info','aside_name'));
        }
    }
    // 文章
    public function getPost($url = '')
    {
        // 找栏目
        $info = Article::with(['cate'=>function($q){
                $q->select('id','parentid','name');
            }])->where('url',$url)->first();
        $info->pid = 0;
        $aside_name = $info->cate->name;
        // 如果存在栏目，接着找
        if (is_null($info)) {
            return back()->with('message','没有找到对应栏目！');
        }
        return view($this->theme.'.post',compact('info','aside_name'));
    }
    // 搜索
    public function getSearch(Request $req)
    {
        if ($req->q == '') {
            return back()->with('message','请输入要搜索的关键词');
        }
        $q = $req->q;
        $info = (object) ['pid'=>0];
        $sort = isset($req->sort) ? $req->sort : 'sort';
        $sc = isset($req->sc) ? $req->sc : 'desc';
        $list = Good::where('title','like',"%$q%")->where('status',1)->orderBy($sort,$sc)->orderBy('id','desc')->simplePaginate(20);
        switch ($sort) {
            case 'sales':
                $active = 2;
                break;

            case 'id':
                $active = 3;
                break;

            case 'price':
                $active = 4;
                break;
            
            default:
                $active = 1;
                break;
        }
        return view($this->theme.'.search',compact('info','list','active','sort','sc','q'));
    }
}
