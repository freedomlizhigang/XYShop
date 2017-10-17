<?php
namespace App\Services;

use App\Models\Common\Ad;
use App\Models\Good\Good;
use App\Models\Good\GoodCate;
use App\Models\Good\Tuan;

class TagService
{

    // 活动
    public function hd($num = 5)
    {
        $hd = Huodong::where('starttime','<',date('Y-m-d H:i:s'))->where('endtime','>',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->limit($num)->get();
        return $hd;
    }

    


    

    // 面包屑导航
    public function goodcatpos($cid)
    {
        try {
            $cate = GoodCate::where('id',$cid)->first();
            if ($cate->parentid == 0) {
                echo "<li class='active'><a href='/shop/goodcate/".$cate->id."'>".$cate->name."</a></li>";
            }
            else
            {
                $parentcate = GoodCate::where('id',$cate->parentid)->first();
                echo "<li><a href='/shop/goodcate/".$parentcate->id."'>".$parentcate->name."</a></li><li class='active'><a href='/shop/goodcate/".$cate->id."'>".$cate->name."</a></li>";
            }
        } catch (\Exception $e) {
            echo '';
        }
    }

    

    
    
    /*
    * 取栏目
     */
    public function cate($pid = 0,$nums = 10)
    {
        $cate = Cate::where('parentid',$pid)->limit(4)->get();
        return $cate;
    }

    /*
    * 取文章，不带分页
     */
    public function arts($cid = 0,$num = 5)
    {
        $cid = explode(',', $cid);
        $art = Article::whereIn('catid',$cid)->limit($num)->orderBy('id','desc')->get();
        return $art;
    }

    // 面包屑导航
    public function catpos($cid)
    {
        try {
            $cate = Cate::where('id',$cid)->first();
            if ($cate->parentid == 0) {
                echo "<li class='active'><a href='/cate/".$cate->url."'>".$cate->name."</a></li>";
            }
            else
            {
                $parentcate = Cate::where('id',$cate->parentid)->first();
                echo "<li><a href='/cate/".$parentcate->url."'>".$parentcate->name."</a></li><li class='active'><a href='/cate/".$cate->url."'>".$cate->name."</a></li>";
            }
        } catch (\Exception $e) {
            echo '';
        }
    }

    // 团购
    public function tuan($num = 10)
    {
        $tuan = Tuan::with(['good'=>function($q){
                $q->where('status',1)->select('id','title','thumb','shop_price');
            }])->where('store','>',0)->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->limit($num)->get();
        return $tuan;
    }

    // 取商品列表
    public function good($cid = '',$num = 10)
    {
        $good = Good::where(function($q)use($cid){
                if($cid != '')
                {
                    $cid = GoodCate::where('id',$cid)->value('arrchildid');
                    $q->whereIn('cate_id',explode(',',$cid));
                }
            })->where('status',1)->select('id','title','thumb','shop_price','is_pos','is_hot','is_new')->limit($num)->orderBy('sort','desc')->orderBy('id','desc')->get();
        return $good;
    }

    // 取商品分类列表
    public function catelist($pid = 0,$num = 10,$ishome = 0,$ismenu = 0)
    {
        $goodcate = GoodCate::where('parentid',$pid)->where(function($q)use($ishome){
                        if ($ishome) {
                            $q->where('ishome',1);
                        }
                    })->where(function($q)use($ismenu){
                        if ($ismenu) {
                            $q->where('ismenu',1);
                        }
                    })->select('id','name','mobilename','sort','thumb')->limit($num)->orderBy('sort','desc')->orderBy('id','desc')->get(); 
        return $goodcate;
    }

    // 广告
    public function ad($pos_id = 0,$num = 5,$isrand = 0)
    {
        if ($isrand) {
            $ad = Ad::where('pos_id',$pos_id)->where('status',1)->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->limit($num)->orderByRaw('RAND()')->orderBy('sort','desc')->orderBy('id','desc')->get();
        }
        else
        {
            $ad = Ad::where('pos_id',$pos_id)->where('status',1)->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->limit($num)->orderBy('sort','desc')->orderBy('id','desc')->get();
        }
        return $ad;
    }

}
    