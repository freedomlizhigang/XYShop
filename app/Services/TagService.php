<?php
namespace App\Services;

use App\Models\Common\Ad;
use App\Models\Common\Article;
use App\Models\Common\Cate;
use App\Models\Good\Good;
use App\Models\Good\GoodCate;
use App\Models\Good\Huodong;
use App\Models\Good\Tuan;

class TagService
{

    // 活动
    public function hd($num = 5)
    {
        $hd = Huodong::where('starttime','<',date('Y-m-d H:i:s'))->where('endtime','>',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->limit($num)->get();
        return $hd;
    }

    // 团购
    public function tuan($num = 10)
    {
        $tuan = Tuan::with(['good'=>function($q){
                $q->where('status',1)->select('id','title','thumb','price','isxs','isxl','tags');
            }])->where('store','>',0)->where('starttime','<',date('Y-m-d H:i:s'))->where('endtime','>',date('Y-m-d H:i:s'))->where('status',1)->where('delflag',1)->orderBy('sort','desc')->orderBy('id','desc')->limit($num)->get();
        return $tuan;
    }


    // 广告
    public function ad($pos_id = 0,$num = 10)
    {
        $ad = Ad::where('pos_id',$pos_id)->where('starttime','<=',date('Y-m-d H:i:s'))->where('endtime','>=',date('Y-m-d H:i:s'))->where('status',1)->limit($num)->orderBy('sort','desc')->orderBy('id','desc')->get();
        return $ad;
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

    // 取商品列表
    public function good($cid = '',$num = 10)
    {
        $good = Good::where(function($q)use($cid){
                if($cid != '')
                {
                    $cid = GoodCate::where('id',$cid)->where('status',1)->value('arrchildid');
                    $q->whereIn('cate_id',explode(',',$cid));
                }
            })->where('status',1)->select('id','title','thumb','price','isxs','isxl','tags')->limit($num)->orderBy('sort','desc')->orderBy('id','desc')->get();
        return $good;
    }

    // 取商品分类列表
    public function goodcate($pid = 0,$num = 10)
    {
        $goodcate = GoodCate::where('parentid',$pid)->select('id','name','sort','thumb')->where('status',1)->limit($num)->orderBy('sort','desc')->orderBy('id','asc')->get();
        return $goodcate;
    }
    
    /*
    * 取栏目
     */
    public function cate($pid = 0,$nums = 10)
    {
        $cate = Cate::where('parentid',$pid)->limit(4)->orderBy('sort','desc')->get();
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

}