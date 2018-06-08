<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common\Area;
use App\Models\Common\Cate;
use App\Models\Common\Type;
use App\Models\Console\Config;
use App\Models\Console\Menu;
use App\Models\Console\Priv;
use App\Models\Good\Good;
use App\Models\Good\GoodCate;
use App\Models\Good\Order;
use App\Models\Good\OrderGood;
use App\Models\User\Consume;
use App\Models\User\Group;
use App\Models\User\User;
use Cache;
use Excel;
use DB;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 主页里关闭调试
        \Debugbar::disable();
        $this->menu = new Menu;
    }

    /**
     * 后台首页
     */
    public function getIndex()
    {
        $allUrl = $this->allPriv();
        $main = $this->menu->where('parentid','=','0')->where('display','=','1')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
        if (in_array(1, session('console')->allRole))
        {
            $mainmenu = $main;
        }
        else
        {
            $mainmenu = array();
            foreach ($main as $k => $v) {
                foreach ($allUrl as $url) {
                    if ($v['url'] == $url) {
                        $mainmenu[$k] = $v;
                    }
                }
            }
        }
        return view('admin.index',compact('mainmenu'));
    }
    // 今日消费情况
    public function getConsume(Request $req)
    {
        $title = '消费情况';
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $consume = Consume::with(['user'=>function($q){
                    $q->select('id','username','nickname','openid','phone');
                }])->where('created_at','>',$starttime)->where('created_at','<',$endtime)->orderBy('id','desc')->get();
        // 今日总入账、出账、结余
        $today_inc = Consume::where('created_at','>',$starttime)->where('created_at','<',$endtime)->where('type',0)->orderBy('user_id','asc')->sum('price');
        $today_dec = Consume::where('created_at','>',$starttime)->where('created_at','<',$endtime)->where('type',1)->orderBy('user_id','asc')->sum('price');
        $today_over = $today_inc - $today_dec;
        return view('admin.index.consume',compact('starttime','endtime','title','consume','today_dec','today_inc','today_over'));
    }
    public function getExcelConsume(Request $req)
    {
        $title = '消费情况';
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $consume = Consume::with(['user'=>function($q){
                    $q->select('id','username','nickname','openid','phone');
                }])->where('created_at','>',$starttime)->where('created_at','<',$endtime)->orderBy('id','desc')->get();
        $tmp = [];
        foreach ($consume as $v) {
            $username = is_null($v->user) ? '' : $v->user->nickname;
            $typename = $v->type ? '充值' : '消费';
            $tmp[] = [$username,$v->mark,$v->price,$typename,$v->created_at];
        }

        $cellData = array_merge(
            [['用户','备注','金额','类型','时间']],$tmp
        );
        Excel::create('消费情况统计表',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    /**
     * 主要信息展示
     * 统计：总订单-销售额-已收款-未收款-代发货
     *       今日销量最高商品10件，今日新用户，七日销量统计折线图，七日新用户折线图
     *
     */
    public function getMain(Request $req)
    {
        $title = '系统信息';
        $data = [];
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        // $starttime = date('2017-05-01 00:00:00');
        // $endtime = date('2017-06-12 24:00:00');
        // 今日总订单量
        $data['today_ordernum'] = Order::where('orderstatus','>',0)->where('created_at','>',$starttime)->count();
        // 今日销售额
        $data['today_prices'] = Order::where('orderstatus','>',0)->where('created_at','>',$starttime)->sum('total_prices');
        // 今日已收款数
        $data['today_prices_real'] = Consume::where('created_at','>',$starttime)->where('created_at','<',$endtime)->where('type',0)->orderBy('user_id','asc')->sum('price');
        // 今日待发货
        $data['today_ship'] = Order::where('orderstatus',1)->where('shipstatus',0)->where('ziti',0)->where('created_at','>',$starttime)->where('paystatus',1)->count();
        // 查这个时间段发货了的订单
        $oids = Order::where('created_at','>',$starttime)->where('created_at','<',$endtime)->where('shipstatus',1)->pluck('id');
        // 今日销量最高商品10件
        $top_goods = OrderGood::whereIn('order_id',$oids)->select(DB::raw("*,count(good_id) as total_nums"))->groupBy('good_id')->orderBy('total_nums','desc')->limit(10)->get();
        // 今日新用户
        $new_user = User::where('created_at','>',$starttime)->orderBy('id','desc')->limit(20)->get();
        // 七天销量统计折线图
        $timediff = strtotime($endtime) - strtotime($starttime);
        $sort =  $timediff > 604800 ? '%Y-%m' : '%Y-%m-%d';
        $takes = $timediff > 604800 ? 12 : 7;
        $order_chart = Order::where('shipstatus',1)->select(DB::raw("DATE_FORMAT(created_at,'$sort') as datetime,sum(total_prices) as tprices"))->groupBy(DB::raw("DATE_FORMAT(created_at,'$sort')"))->orderBy("created_at","desc")->take($takes)->get();
        // 七日新用户折线图
        $new_user_chart = User::select(DB::raw("DATE_FORMAT(created_at,'$sort') as datetime,count(id) as news"))->groupBy(DB::raw("DATE_FORMAT(created_at,'$sort')"))->orderBy("created_at","desc")->take($takes)->get();
        return view('admin.index.main',compact('title','data','top_goods','new_user','order_chart','new_user_chart','starttime','endtime'));
    }
    public function getExcelGoods(Request $req)
    {
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $order_ids = Order::where('orderstatus',1)->where('paystatus',1)->where('created_at','>',$starttime)->where('created_at','<',$endtime)->pluck('id');
        $goods = OrderGood::whereIn('order_id',$order_ids)->get();

        // 循环出来每一个产品的重量值，并去重累加
        $good_ship = [];
        foreach ($goods as $k => $v) {
            if (isset($good_ship[$v->good_id.'_'.$v->good_spec_key])) {
                $good_ship[$v->good_id.'_'.$v->good_spec_key]['nums'] += $v->nums;
            }
            else
            {
                $good_ship[$v->good_id.'_'.$v->good_spec_key] = ['good_id'=>$v->good_id,'nums'=>$v->nums,'spec_key'=>$v->good_spec_key,'good_spec_name'=>$v->good_spec_name,'price'=>$v->price];
            }
        }
        // 查每个的重量并累加，先查出所有商品属性及商品规格，省去重复查询
        $goods_weigth = Good::whereIn('id',$goods->pluck('good_id'))->select('id','title','pronums','weight')->get();
        foreach ($good_ship as $k => $v) {
            $tmp_arr = [];
            $tmp_good = $goods_weigth->where('id',$v['good_id'])->first()->toArray();
            $tmp_arr['id'] = $v['good_id'];
            $tmp_arr['title'] = $tmp_good['title'];
            $tmp_arr['good_spec_name'] = $v['good_spec_name'];
            $tmp_arr['pronums'] = $tmp_good['pronums'];
            $tmp_arr['price'] = $v['price'];
            $tmp_arr['weight'] = $tmp_good['weight'];
            $tmp_arr['nums'] = $v['nums'];
            $tmp_arr['total_weight'] = $v['nums'] * $tmp_good['weight'];
            $tmp_arr['total_prices'] = $v['nums'] * $v['price'];
            $good_ship[$k] = $tmp_arr;
        }
        $cellData = array_merge(
            [['ID','标题','规格','货号','单价','单件重量','数量','总重量','总价']],$good_ship
        );
        Excel::create('今日销售统计表',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    // 库房表
    public function getExcelStore(Request $req)
    {
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $orders = Order::with(['address'=>function($q){
                        $q->select('id','area','address','phone','people');
                    },'extract'=>function($q){
                        $q->select('id','area','address','phone');
                    },'user'=>function($q){
                        $q->select('id','nickname','address','phone','user_money');
                    }])->where('orderstatus',1)->where('paystatus',1)->where('paytime','>',$starttime)->where('paytime','<',$endtime)->get();
        // ->where('orderstatus',1)->where('paystatus',1)
        $goods = OrderGood::whereIn('order_id',$orders->pluck('id'))->get();
        $pronums = Good::whereIn('id',$goods->pluck('good_id'))->select('id','pronums')->get()->keyBy('id')->toArray();
        // 循环每个订单的订单信息
        $excel = [];
        foreach ($orders as $k => $v) {
            // 查有几个商品
            $tmp_good = $goods->where('order_id',$v->id)->all();
            try {
                $first_gid = $goods->where('order_id',$v->id)->first()->good_id;
                foreach ($tmp_good as $kg => $g) {
                    $tmp = [];
                    $title = $g['good_title'];
                    // 判断是否有规格
                    if ($g['good_spec_key'] != '') {
                        $title .= ' - '.$g['good_spec_name'];
                    }
                    // 第一个订单产品写出详细信息来
                    $tmp_pronums = isset($pronums[$g['good_id']]) ? $pronums[$g['good_id']]['pronums'] : '';
                    if ($g->good_id == $first_gid) {
                        // 判断是自提还是配送
                        $name = is_null($v->user) ? '' : $v->user->nickname;
                        $user_money = is_null($v->user) ? '' : $v->user->user_money;
                        $people = is_null($v->address) ? '' : $v->address->people;
                        $tmp = [$v->order_id,$v->created_at,$name,$user_money,$title,$tmp_pronums,$g['good_spec_name'],$g['old_price'],$g['price'],$g['nums'],$g['old_price'] * $g['nums'],$g['price'] * $g['nums'],$v->total_prices,$people];
                        $excel[] = $tmp;
                    }
                    else
                    {
                        $tmp = ['','','','',$title,$tmp_pronums,$g['good_spec_name'],$g['old_price'],$g['price'],$g['nums'],$g['old_price'] * $g['nums'],$g['price'] * $g['nums'],'',''];
                        $excel[] = $tmp;
                    }
                }
                $tmp_good = null;
            } catch (\Throwable $e) {
                dd($e->getMessage());
                continue;
            }
        }
        $cellData = array_merge([['订单编号','下单时间','会员','会员余额','订单商品名称','商家编码','规格','单价','优惠后','数量','总价','优惠后','实收款','收货人姓名']],$excel);
        Excel::create('库房专用表',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    // 订单表
    public function getExcelOrders(Request $req)
    {
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $paystatus = isset($req->paystatus) ? $req->paystatus : 1;
        $shipstatus = isset($req->shipstatus) ? $req->shipstatus : 1;
        $orderstatus = isset($req->orderstatus) ? $req->orderstatus : '';
        $orders = Order::with(['address'=>function($q){
                        $q->select('id','area','address','phone','people');
                    },'extract'=>function($q){
                        $q->select('id','area','address','phone');
                    },'user'=>function($q){
                        $q->select('id','nickname','address','phone');
                    }])->where(function($q) use($orderstatus){
                        if ($orderstatus != '') {
                            $q->where('orderstatus',$orderstatus);
                        }
                        else
                        {
                            $q->where('orderstatus','!=',0);
                        }
                    })->where('paystatus',$paystatus)->where('shipstatus',$shipstatus)->where('paytime','>',$starttime)->where('paytime','<',$endtime)->get();
        $goods = OrderGood::whereIn('order_id',$orders->pluck('id'))->get();
        // 循环每个订单的订单信息
        $excel = [];
        foreach ($orders as $k => $v) {
            // 查有几个商品
            $tmp_good = $goods->where('order_id',$v->id)->all();
            try {
                $first_gid = $goods->where('order_id',$v->id)->first()->good_id;
            } catch (\Throwable $e) {
                continue;
            }
            foreach ($tmp_good as $kg => $g) {
                $tmp = [];
                $title = $g['good_title'];
                // 判断是否有规格
                if ($g['good_spec_key'] != '') {
                    $title .= ' - '.$g['good_spec_name'];
                }
                // 第一个订单产品写出详细信息来
                if ($g->good_id == $first_gid) {
                    // 判断是自提还是配送
                    if (!is_null($v->address)) {
                        $phone = $v->address->phone == '' ? '13333332222' : $v->address->phone;
                        $tmp = [$v->order_id,'',$v->address->people,$phone,'','河北','衡水',$v->address->area,$v->address->address,'','',$v->total_prices,$v->mark,$v->shopmark,'',$title,$g['nums'],$g['price'],'',$g->good_spec_name];
                    }
                    else
                    {
                        $phone = $v->user->phone == '' ? '13333332222' : $v->user->phone;
                        $tmp = [$v->order_id,'',$v->user->nickname.'（自提）',$phone,'','河北','衡水',$v->extract->area,$v->extract->address,'','',$v->total_prices,$v->mark,$v->shopmark,'',$title,$g['nums'],$g['price'],'',$g->good_spec_name];
                    }
                    $excel[] = $tmp;
                }
                else
                {
                    $tmp = ['','','','','','','','','','','','','','','',$title,$g['nums'],$g['price'],'',$g->good_spec_name];
                    $excel[] = $tmp;
                }
            }
            $tmp_good = null;
        }
        $cellData = array_merge(
            [['订单号','购买人昵称','收件人姓名*','手机*','电话','省*','市*','区*','地址*','邮编','运费','订单金额','买家留言','卖家备忘','代收货款金额','物品名称','数量','单价','商品编码','销售属性']]
            ,$excel
        );
        Excel::create('发货易订单打印表',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                // $sheet->mergeCells('A1:M1');
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    public function getLeft($pid)
    {
        // 权限url
        $allUrl = $this->allPriv();
        // 二级菜单
        $left = $this->menu->where('parentid','=',$pid)->where('display','=','1')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
        $leftmenu = array();
        // 判断权限
        if (!in_array(1, session('console')->allRole))
        {
            foreach ($left as $k => $v) {
                foreach ($allUrl as $url) {
                    if ($v['url'] == $url) {
                        $leftmenu[$k] = $v;
                    }
                }
            }
        }
        else
        {
            $leftmenu = $left;
        }
        // 三级菜单
        foreach ($leftmenu as $k => $v) {
            // 取所有下级菜单
            $res = $this->menu->where('parentid','=',$v['id'])->where('display','=','1')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
            // 进行权限判断
            if (!in_array(1, session('console')->allRole))
            {
                foreach ($res as $s => $v) {
                    foreach ($allUrl as $url) {
                        if ($v['url'] == $url) {
                            $leftmenu[$k]['submenu'][$s] = $v;
                        }
                    }
                }
            }
            else
            {
                $leftmenu[$k]['submenu'] = $res;
            }
        }
        // dd($leftm enu);
        return view('admin.left',compact('leftmenu'));
    }
    // 查出所有有权限的url
    private function allPriv()
    {
        $rid = session('console')->allRole;
        // 查url
        $priv = Priv::whereIn('role_id',$rid)->pluck('url')->toArray();
        return $priv;
    }

    // 更新缓存
    public function getCache()
    {
        $config = Config::select('sitename','title','keyword','describe','theme','person','phone','email','address','content')->findOrFail(1)->toArray();
        Cache::forever('config',$config);
        echo "<p><small>更新系统缓存成功...</small></p>";
        app('com')->updateCache(new Cate,'cateCache');
        echo "<p><small>更新栏目缓存成功...</small></p>";
        app('com')->updateCache(new Menu,'menuCache');
        echo "<p><small>更新后台菜单缓存成功...</small></p>";
        app('com')->updateCache(new GoodCate,'goodcateCache');
        echo "<p><small>更新商品分类缓存成功...</small></p>";
        app('com')->updateCache(new Type,'typeCache');
        echo "<p><small>更新分类缓存成功...</small></p>";
        $data = Group::where('status',1)->select('id','name','points','discount')->get()->keyBy('id')->toArray();
        Cache::forever('group',$data);
        echo "<p><small>更新会员组缓存成功...</small></p>";
        $area = Area::select('id','areaname')->get()->keyBy('id')->toArray();
        Cache::forever('area',$area);
        echo "<p><small>更新地区缓存成功...</small></p>";
        echo "<p><small>更新缓存完成...</small></p>";
    }
}