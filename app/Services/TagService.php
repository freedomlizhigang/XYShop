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

    // 取商品分类三级菜单
    public function getMenu()
    {
        $menus = GoodCate::where('ismenu',1)->select('id','parentid','arrparentid','name')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
        $tree = $arr = $result = [];
        if (is_array($menus)) {
            foreach ($menus as $v) {
                $level = count(explode(',', $v['arrparentid']));
                if($level == 3){
                    $crr[$v['parentid']][] = $v;
                }
                if($level == 2){
                    $arr[$v['parentid']][] = $v;
                }
                if($level == 1){
                    $tree[$v['id']] = $v;
                }
            }
            foreach ($arr as $k=>$v){
                foreach ($v as $kk=>$vv){
                    $arr[$k][$kk]['sub_menu'] = empty($crr[$vv['id']]) ? array() : $crr[$vv['id']];
                }
            }
            foreach ($tree as $val){
                $val['child_menu'] = empty($arr[$val['id']]) ? array() : $arr[$val['id']];
                $result[$val['id']] = $val;
            }
        }
        return $result;
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