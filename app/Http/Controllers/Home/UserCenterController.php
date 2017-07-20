<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Common\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Good\UserCardRequest;
use App\Http\Requests\User\AddressRequest;
use App\Models\Address;
use App\Models\Card;
use App\Models\Consume;
use App\Models\Order;
use App\Models\ReturnGood;
use App\Models\Type;
use App\Models\User;
use App\Models\YhqUser;
use Illuminate\Http\Request;

class UserCenterController extends BaseController
{
    // 会员中心
    public function getCenter(Request $req)
    {
    	// 取个人信息
        $uid = session('member')->id;
    	$info = User::findOrFail($uid);
        $info->pid = 4;
        $yhq_nums = YhqUser::where('user_id',$uid)->count();
        // 数据
        $order_1 = Order::where('user_id',$uid)->where('paystatus',0)->where('orderstatus',1)->where('status',1)->count();
        $order_2 = Order::where('user_id',$uid)->where('paystatus',1)->where('shipstatus',0)->where('orderstatus',1)->where('status',1)->count();
        $order_3 = Order::where('user_id',$uid)->where('status',1)->where('paystatus',1)->where('orderstatus',1)->where('shipstatus',1)->count();
        $order_4 = 0;
        $order_5 = ReturnGood::where('user_id',$uid)->where('status',0)->where('delflag',1)->count();
    	return view('home.user.usercenter',compact('info','yhq_nums','order_1','order_2','order_3','order_4','order_5'));
    }
    // 修改个人信息
    public function getInfo()
    {
        // 取个人信息
        $uid = session('member')->id;
        $info = User::findOrFail($uid);
        $info->pid = 4;
        return view('home.user.info',compact('info'));
    }
    public function postInfo(Request $req)
    {
        $data = $req->input('data');
        User::where('id',session('member')->id)->update($data);
        return redirect('user/center')->with('message','修改成功');
    }

    // 收货地址
    public function getAddress()
    {
        $list = Address::where('user_id',session('member')->id)->where('delflag',1)->get();
        $info = (object) ['pid'=>4];
        return view('home.user.address',compact('list','info'));
    }
    // 添加地址
    public function getAddressAdd()
    {
        $area = Type::where('parentid',4)->get();
        $info = (object) ['pid'=>4];
        return view('home.user.address_add',compact('area','info'));
    }
    public function postAddressAdd(AddressRequest $req)
    {
        $data = $req->input('data');
        $data['user_id'] = session('member')->id;
        Address::create($data);
        return redirect('user/address')->with('message','添加成功');
    }
    // 修改地址
    public function getAddressEdit($id = '')
    {
        $info = Address::findOrFail($id);
        $area = Type::where('parentid',4)->get();
        $info->pid = 4;
        return view('home.user.address_edit',compact('info','area'));
    }
    public function postAddressEdit(AddressRequest $req,$id = '')
    {
        $data = $req->input('data');
        Address::where('id',$id)->update($data);
        return redirect('user/address')->with('message','修改成功');
    }
    // 修改地址
    public function getAddressDel($id = '')
    {
        Address::where('id',$id)->update(['delflag'=>0]);
        return back()->with('message','删除成功');
    }
    // 查看所有在退货中的商品
    public function getReturngood()
    {
        $info = (object) ['pid'=>4];
        $list = ReturnGood::with(['good'=>function($q){
                $q->select('id','title','thumb');
            }])->where('user_id',session('member')->id)->where('delflag',1)->orderBy('id','desc')->simplePaginate(10);
        return view('home.user.returngood',compact('info','list'));
    }
    // 充值卡充值
    public function getCard()
    {
        $info = (object) ['pid'=>4];
        return view('home.user.card',compact('info'));
    }
    public function postCard(UserCardRequest $req)
    {
        $card_id = $req->input('data.card_id');
        $card_pwd = $req->input('data.card_pwd');
        $card = Card::where('status',0)->where('card_id',$card_id)->where('card_pwd',$card_pwd)->orderBy('id','desc')->first();
        if (is_null($card)) {
            return back()->with('message','没有找到此卡，请确认输入的正确！');
        }
        else
        {
            // 找出来卡，给用记充上钱，并标记为已用
            Card::where('id',$card->id)->update(['status'=>1,'user_id'=>session('member')->id]);
            User::where('id',session('member')->id)->increment('user_money',$card->price);
            // 消费记录
            app('com')->consume(session('member')->id,0,$card->price,'充值卡充值',1);
            return redirect('user/center')->with('message','充值成功');
        }
    }
    // 消费记录
    public function getConsume()
    {
        $info = (object) ['pid'=>4];
        $list = Consume::where('user_id',session('member')->id)->orderBy('id','desc')->simplePaginate(10);
        return view('home.user.consume',compact('info','list'));
    }
}
