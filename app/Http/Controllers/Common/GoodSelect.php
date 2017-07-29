<?php

namespace App\Http\Controllers\Common;

use App\Models\Good\Brand;
use App\Models\Good\Good;
use App\Models\Good\GoodAttr;
use App\Models\Good\GoodSpec;
use App\Models\Good\GoodSpecItem;
use App\Models\Good\GoodSpecPrice;
use App\Models\Good\GoodsAttr;


class GoodSelect
{
	/**
     * @param $goods_id_arr
     * @param $filter_param
     * @param $action
     * @param int $mode 0  返回数组形式  1 直接返回result
     * @return array|mixed 这里状态一般都为1 result 不是返回数据 就是空
     * 获取 商品列表页筛选品牌
     */
    public function get_filter_brand($goods_id_arr, $filter_param, $action, $mode = 0)
    {
        if (!empty($filter_param['brand_id']))
            return array();

        $bids = Good::whereIn('id',$goods_id_arr)->pluck('brand_id')->toArray();
        
        $filterBrands = Brand::whereIn('id',$bids)->select('id','name','icon','describe')->limit(30)->get();
        foreach ($filterBrands as $k => $v) {
            // 筛选参数
            $filter_param['brand_id'] = $v['id'];
            $filterBrands[$k]['href'] = urldecode(url($action).'?'.http_build_query($filter_param));
        }
        if ($mode == 1) return $filterBrands;
        return array('status' => 1, 'msg' => '', 'result' => $filterBrands);
    }
    /**
     * @param $goods_id_arr
     * @param $filter_param
     * @param $action
     * @param int $mode  0  返回数组形式  1 直接返回result
     * @return array 这里状态一般都为1 result 不是返回数据 就是空
     * 获取 商品列表页筛选规格
     */
    public function get_filter_spec($childids,$goods_id_arr,$filter_param, $action, $mode = 0)
    {
        // 已经添加进筛选的id
        $old_spec = [];
        $tmp_spec = '';
        if (isset($filter_param['spec'])) {
            $old_spec = GoodSpecItem::whereIn('id',explode('.',$filter_param['spec']))->pluck('good_spec_id')->toArray();
            $old_spec = array_unique($old_spec);
            $tmp_spec = $filter_param['spec'];
        }
        // 找出来所有此分类下的可筛选规格
        $all_spec = GoodSpecPrice::whereIn('good_id',$goods_id_arr)->pluck('key')->toArray();
        foreach ($all_spec as $k => $v) {
            $all_spec[$k] = trim($v,'_');
        }
        $all_spec = GoodSpecItem::whereIn('id',$all_spec)->pluck('good_spec_id')->unique()->toArray();
        $new_spec_id = array_diff($all_spec,$old_spec);
        $list_spec = GoodSpec::with(['goodspecitem'=>function($q){
                        $q->select('id','good_spec_id','item');
                    }])->whereIn('id',$new_spec_id)->where('search_type',1)->select('id','name')->orderBy('sort','desc')->orderBy('id','desc')->get()->toArray();
        foreach ($list_spec as $k => $v) {
            foreach($v['goodspecitem'] as $kk => $vv)
            {
                // 筛选参数
                if (!empty($tmp_spec))
                    $filter_param['spec'] = $tmp_spec . '.' . $vv['id'];
                else
                    $filter_param['spec'] = $vv['id'];

                $list_spec[$k]['goodspecitem'][$kk]['href'] = urldecode(url($action).'?'.http_build_query($filter_param));
            }
        }
        if ($mode == 1) return $list_spec;
        return array('status' => 1, 'msg' => '', 'result' => $list_spec);
    }

    /**
     * @param array $goods_id_arr
     * @param $filter_param
     * @param $action
     * @param int $mode 0  返回数组形式  1 直接返回result
     * @return array
     * 获取商品列表页筛选属性
     */
    public function get_filter_attr($childids,$goods_id_arr,$filter_param, $action, $mode = 0)
    {
        // 找出所有在用的属性
        $goods_attr = GoodsAttr::whereIn('good_id',$goods_id_arr)->get()->toArray();
        // 所有属性
        $goods_attribute = GoodAttr::whereIn('good_cate_id',$childids)->where('search_type',1)->where('input_type',1)->select('id','name')->orderBy('sort','desc')->orderBy('id','desc')->get()->keyBy('id')->toArray();
        if (empty($goods_attr)) {
            if ($mode == 1) return array();
            return array('status' => 1, 'msg' => '', 'result' => array());
        }
        $list_attr = $attr_value_arr = [];
        $old_attr = '';
        if (isset($filter_param['attr'])) {
            $old_attr = $filter_param['attr'];
        }
        // 复合取可用筛选值
        foreach ($goods_attr as $k => $v) {
            // 存在的筛选不再显示
            if (strpos($old_attr, $v['good_attr_id'] . '_') === 0 || strpos($old_attr, '.' . $v['good_attr_id'] . '_'))
                continue;

            // 如果属性值 是数组 说明是多选
            if (is_array($v['good_attr_value'])) {
                foreach ($v['good_attr_value'] as $kk => $vv) {
                    // 如果同一个属性id 的属性值存储过了 就不再存贮
                    if (isset($attr_value_arr[$v['good_attr_id']]) && in_array($v['good_attr_id'] . '_' . $vv,(array) $attr_value_arr[$v['good_attr_id']]))
                    {
                        continue;
                    }

                    $attr_value_arr[$v['good_attr_id']][] = $v['good_attr_id'] . '_' . $vv;

                    $list_attr[$v['good_attr_id']]['id'] = $v['good_attr_id'];
                    $list_attr[$v['good_attr_id']]['name'] = $goods_attribute[$v['good_attr_id']]['name'];

                    // 筛选参数
                    if (!empty($old_attr))
                        $filter_param['attr'] = $old_attr . '.' . $v['good_attr_id'] . '_' . $vv;
                    else
                        $filter_param['attr'] = $v['good_attr_id'] . '_' . $vv;

                    $list_attr[$v['good_attr_id']]['url'][] = array('key' => $v['good_attr_id'], 'val' => $vv, 'href' => urldecode(url($action).'?'.http_build_query($filter_param)));
                }
            }
            else
            {
                // 如果同一个属性id 的属性值存储过了 就不再存贮
                if (isset($attr_value_arr[$v['good_attr_id']]) && in_array($v['good_attr_id'] . '_' . $v['good_attr_value'],(array) $attr_value_arr[$v['good_attr_id']]))
                {
                    continue;
                }

                $attr_value_arr[$v['good_attr_id']][] = $v['good_attr_id'] . '_' . $v['good_attr_value'];

                $list_attr[$v['good_attr_id']]['id'] = $v['good_attr_id'];
                $list_attr[$v['good_attr_id']]['name'] = $goods_attribute[$v['good_attr_id']]['name'];

                // 筛选参数
                if (!empty($old_attr))
                    $filter_param['attr'] = $old_attr . '.' . $v['good_attr_id'] . '_' . $v['good_attr_value'];
                else
                    $filter_param['attr'] = $v['good_attr_id'] . '_' . $v['good_attr_value'];

                $list_attr[$v['good_attr_id']]['url'][] = array('key' => $v['good_attr_id'], 'val' => $v['good_attr_value'], 'href' => urldecode(url($action).'?'.http_build_query($filter_param)));
            }
            //unset($filter_param['attr_id_'.$v['attr_id']]);
        }

        if ($mode == 1) return $list_attr;
        return array('status' => 1, 'msg' => '', 'result' => $list_attr);
    }
    /**
     * 筛选的价格期间
     * @param $goods_id_arr|筛选的分类id
     * @param $filter_param
     * @param $action
     * @param int $c 分几段 默认分5 段
     * @return array
     */
    public function get_filter_price($goods_id_arr, $filter_param, $action, $c = 5)
    {

        if (!empty($filter_param['price']))
            return array();

        $parr = array();
        $goods_id_str = implode(',', $goods_id_arr);
        $goods_id_str = $goods_id_str ? $goods_id_str : '0';
        $priceList = Good::whereIn('id',$goods_id_arr)->pluck('shop_price')->toArray();
        if (count($priceList) == 0) {
            return $parr;
        }
        rsort($priceList);
        $max_price = (int) $priceList[0];

        $psize = ceil($max_price / $c); // 每一段累积的价钱
        for ($i = 0; $i < $c; $i++) {
            $start = $i * $psize;
            $end = $start + $psize;

            // 如果没有这个价格范围的商品则不列出来
            $in = false;
            foreach ($priceList as $k => $v) {
                if ($v > $start && $v < $end)
                    $in = true;
            }
            if ($in == false)
                continue;

            $filter_param['price'] = "{$start}-{$end}";
            if ($i == 0)
                $parr[] = array('value' => "{$end}元以下", 'href' => urldecode(url($action).'?'.http_build_query($filter_param)) );            
            elseif($i == ($c-1) && ($max_price > $end)) 
                $parr[] = array('value' => "{$end}元以上", 'href' => urldecode(url($action).'?'.http_build_query($filter_param)) );
            else
                $parr[] = array('value' => "{$start}-{$end}元", 'href' => urldecode(url($action).'?'.http_build_query($filter_param)) );
        }
        return $parr;
    }
	/**
	 * 根据规格 查找 商品id
	 * @param $spec|规格
	 * @return array|\type
	 */
	public function getGoodsIdBySpec($spec)
	{
	    if (empty($spec))
	        return array();
	    $spec_group = explode('.', $spec);
	    $like = [];
	    foreach ($spec_group as $k => $v) {
            $like[] = "_".$v."_";
	    }
	    $arr = GoodSpecPrice::whereIn('key',$like)->pluck('good_id')->toArray();
	    return array_unique($arr);
	}

	/**
	 * @param $attr|属性
	 * @return array|mixed
	 * 根据属性 查找 商品id
	 * 59_直板_翻盖
	 * 80_BT4.0_BT4.1
	 */
	public function getGoodsIdByAttr($attr)
	{
	    if (empty($attr))
	        return array();
	    $attr_group = explode('.', $attr);
	    $arr = GoodsAttr::whereIn('good_attr_id',$attr_group)->pluck('good_id')->toArray();
	    return array_unique($arr);
	}
	/**
	 * @param  $brand_id|筛选品牌id
	 * @param  $price|筛选价格
	 * @return array|mixed
	 */
	public function getGoodsIdByBrandPrice($brand_id, $price)
	{
	    if (empty($brand_id) && empty($price))
	        return array();

	    $arr = Good::where(function($q) use($brand_id){
	    		if (!empty($brand_id)) {
	    			$q->whereIn('brand_id',explode('_',$brand_id));
    			}	
    		})->where(function($q) use($price){
	    		if (!empty($price)) {
	    			$price = explode('-', $price);
	    			$q->where('shop_price','>=',$price[0])->where('shop_price','<=',$price[1]);
    			}	
    		})->where('status',1)->pluck('id')->toArray();
	    return $arr;
	}
	
}
