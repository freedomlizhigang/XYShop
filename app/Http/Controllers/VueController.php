<?php

namespace App\Http\Controllers;

use App\Ecs\Card as c;
use App\Ecs\Category;
use App\Ecs\Good as G;
use App\Ecs\User as U;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Card;
use App\Models\Community;
use App\Models\Good;
use App\Models\GoodCate;
use App\Models\User;
use Excel;
use Illuminate\Http\Request;
use Storage;

class VueController extends Controller
{
    public function index()
    {

    	/*$url = "https://api.weixin.qq.com/sns/jscode2session?appid=wx1d99bcc1f07ebd6b&secret=e1998edcc82fd9ea370ce1dcb75510da&js_code=013Pxpsa0e8LXs1gLMua01Yxsa0Pxpsi&grant_type=authorization_code";
    	$res = app('com')->postCurl($url,[],'GET');
    	dd($res);*/

    	return view('vue.vue');
    }
    // 数据插入
    public function area()
    {
        dd('success');
        // 给所有乡找到市省
        $all_com = Community::groupBy('areaid3')->select('id','areaid3')->get();
        foreach ($all_com as $ac) {
            // 开始找
            $areaid2 = Area::where('id',$ac->areaid3)->value('parentid');
            $areaid1 = Area::where('id',$areaid2)->value('parentid');
            Community::where('areaid3',$ac->areaid3)->update(['areaid2'=>$areaid2,'areaid1'=>$areaid1]);
            dump($ac->id);
        }
    }
    public function database()
    {
        
        // 插入用户
        /*$old_user = U::get();
        User::where('id','>',0)->delete();
        $new = [];
        foreach ($old_user as $k => $v) {
            $new[] = ['username'=>$v->user_name,'nickname'=>$v->user_name,'openid'=>trim($v->aite_id,'weixin_'),'sex'=>$v->sex,'birthday'=>$v->birthday,'email'=>$v->email,'user_money'=>$v->user_money,'points'=>$v->points,'phone'=>$v->mobile_phone,'thumb'=>$v->heading,'created_at'=>date('Y-m-d H:i:s',$v->reg_time),'updated_at'=>date('Y-m-d H:i:s')];
        }*/
        User::insert($new);
        
        dump('user');
       
        // 栏目
        /*$old_cate = Category::get();
        $new_cate = [];
        GoodCate::where('id','>',0)->delete();
        foreach ($old_cate as $k => $v) {
           $new_cate[] = ['id'=>$v->cat_id,'parentid'=>$v->parent_id,'name'=>$v->cat_name,'sort'=>$v->sort_order,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
        }
        GoodCate::insert($new_cate);
*/
        dump('goodcate');

       /*
       * 商品
       */
        /*$old_goods = G::get();
        $new_goods = [];
        Good::where('id','>',0)->delete();
        foreach ($old_goods as $k => $v) {
           $new_goods[] = ['id'=>$v->goods_id,'cate_id'=>$v->cat_id,'title'=>$v->goods_name,'pronums'=>$v->goods_sn,'store'=>$v->goods_number,'price'=>$v->shop_price,'sort'=>$v->sort_order,'content'=>$v->goods_desc,'notice'=>'','pack'=>'','thumb'=>$v->original_img,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')];
        }
        Good::insert($new_goods);*/
       dump('good');

        // 导卡
        /*$data = c::where('user_id',0)->select('vc_type_id','vc_sn','vc_pwd')->get();
        $tmp = [];
        $time = date('Y-m-d H:i:s');
        foreach ($data as $v) {
            switch ($v->vc_type_id) {
                case 9:
                    $price = 200;
                    break;
                case 8:
                    $price = 500;
                    break;
                case 7:
                    $price = 500;
                    break;
                case 5:
                    $price = 2000;
                    break;
                case 3:
                    $price = 1000;
                    break;
                case 2:
                    $price = 500;
                    break;
                
                default:
                    $price = 200;
                    break;
            }
            $tmp[] = ['card_id'=>$v->vc_sn,'card_pwd'=>$v->vc_pwd,'price'=>$price,'created_at'=>$time,'updated_at'=>$time];
        }
        Card::insert($tmp);*/
        dump('card');

        /*
        DB::statement("UPDATE li_goods SET thumb=REPLACE(thumb,'images/','/upload/'),content=REPLACE(content,'http://www.hsjixianfeng.com/includes/ueditor/php/../../../bdimages/upload1/','/upload/')");
         */
        dd('success');
    }
}
