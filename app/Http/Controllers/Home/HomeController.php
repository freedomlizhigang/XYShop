<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Common\BaseController;
use App\Http\Controllers\Common\GoodSelect;
use App\Models\Common\Article;
use App\Models\Common\Cate;
use App\Models\Good\Brand;
use App\Models\Good\Good;
use App\Models\Good\GoodAttr;
use App\Models\Good\GoodCate;
use App\Models\Good\GoodSpec;
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
        return view($this->theme.'.home',compact('ishome'));
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
        $cinfo = GoodCate::where('id',$cid)->select('id','arrparentid','arrchildid')->first();
        $pids = array_slice(explode(',',$cinfo->arrparentid),1);
        // 找出所有子级
        $childids = explode(',',$cinfo->arrchildid);
        // 找出所有要筛选的商品ID
        $filter_goods_id = Good::whereIn('cate_id',$childids)->where('status',1)->pluck('id')->toArray();
        // 商品筛选功能类
        $goodselect = new GoodSelect();
        // 过滤帅选的结果集里面找商品        
        if($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodselect->getGoodsIdByBrandPrice($brand_id,$price); // 根据 品牌 或者 价格范围 查找所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if($spec)// 规格
        {
            $goods_id_2 = $goodselect->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if($attr)// 属性
        {
            $goods_id_3 = $goodselect->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_3); // 获取多个帅选条件的结果 的交集
        }
        // 价格范围
        $filterPrice = $goodselect->get_filter_price($filter_goods_id,$filter_param,"list/$cid");
        $filterBrand = $goodselect->get_filter_brand($filter_goods_id,$filter_param,"list/$cid",1); // 获取指定分类下的帅选品牌
        $filterSpec  = $goodselect->get_filter_spec($childids,$filter_goods_id,$filter_param,"list/$cid",1); // 获取指定分类下的帅选规格
        $filterAttr  = $goodselect->get_filter_attr($childids,$filter_goods_id,$filter_param,"list/$cid",1); // 获取指定分类下的帅选属性

        
        return view($this->theme.'.list',compact('filter_param','filterPrice','filterBrand','filterSpec','filterAttr'));
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
