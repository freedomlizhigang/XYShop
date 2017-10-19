<?php

namespace App\Models\Good;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
	// 商品表
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'goods';

    // 不可以批量赋值的字段，为空则表示都可以
    protected $guarded = [];

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $hidden = [];
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    // 计算会员价
/*    public function getUserPriceAttribute()
    {
        if (!session()->has('member')) {
            return $this->price;
        }
       $prices = ($this->price * session('member')->discount) / 100;
        return $prices;
    }*/

    /**
     * 追加到模型数组表单的访问器
     *
     * @var array
     */

    // 活动
    public function getPromTagAttribute()
    {
        // 0普通商品，1限时，2团购，3满赠，4活动
        switch ($this->attributes['prom_type']) {
            case '4':
                $str = '活动';
                break;

            case '3':
                $str = '满赠';
                break;

            case '2':
                $str = '团购';
                break;

            case '1':
                $str = '限时';
                break;
            
            default:
                $str = '';
                break;
        }
        return  $str;
    }

    // 新品
    public function getNewTagAttribute()
    {
        return $this->attributes['is_new'] ? '新品' : '';
    }

    // 推荐
    public function getPosTagAttribute()
    {
        return $this->attributes['is_pos'] ? '推荐' : '';
    }

    // 热卖
    public function getHotTagAttribute()
    {
        return $this->attributes['is_hot'] ? '热卖' : '';
    }

    // 二级分类
    public function getUrlAttribute()
    {
        $url = url('good',['id'=>$this->attributes['id']]);
        return $url;
    }

    // 二级分类
    public function getGoodcateTwoIdAttribute()
    {
        $two_id = GoodCate::where('id',$this->attributes['cate_id'])->value('parentid');
        return $two_id;
    }

    // 一级分类
    public function getGoodcateOneIdAttribute()
    {
        $one_id = GoodCate::where('id',$this->attributes['cate_id'])->value('arrparentid');
        $one_id = explode(',', $one_id)[1];
        return $one_id;
    }


    // 购物车
    public function cart()
    {
        return $this->hasMany('\App\Models\Good\Cart','good_id','id');
    }

    // 商品规格价格库存表
    public function goodspecprice()
    {
        return $this->hasMany('\App\Models\Good\GoodSpecPrice','good_id','id');
    }
    // 商品对应属性表
    public function goodattr()
    {
        return $this->hasMany('\App\Models\Good\GoodsAttr','good_id','id');
    }
    // 关联商品分类
    public function goodcate()
    {
        return $this->belongsTo('\App\Models\Good\GoodCate','cate_id','id');
    }
    // 关联订单
    public function order_good()
    {
        return $this->hasMany('\App\Models\Good\OrderGood','good_id','id');
    }

    // 满赠
    public function fullgift()
    {
        return $this->hasMany('\App\Models\Good\Fullgift','good_id','id');
    }

    // 限时
    public function timetobuy()
    {
        return $this->hasMany('\App\Models\Good\Timetobuy','good_id','id');
    }

    // 团购
    public function tuan()
    {
        return $this->hasMany('\App\Models\Good\Tuan','good_id','id');
    }
    // 退货
    public function return_good()
    {
        return $this->hasMany('\App\Models\Good\ReturnGood','good_id','id');
    }
}
