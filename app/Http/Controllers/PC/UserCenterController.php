<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\Common\BaseController;
use App\Http\Requests\User\AddressRequest;
use App\Http\Requests\User\UserCardRequest;
use App\Models\Common\Type;
use App\Models\Good\Order;
use App\Models\Good\ReturnGood;
use App\Models\Good\YhqUser;
use App\Models\User\Address;
use App\Models\User\Card;
use App\Models\User\Consume;
use App\Models\User\User;
use Illuminate\Http\Request;

class UserCenterController extends BaseController
{
    // 会员中心
    public function getCenter(Request $req)
    {
    	// 取个人信息
        $uid = session('member')->id;
        $seo = ['title'=>'用户中心 - '.cache('config')['title'],'keyword'=>cache('config')['keyword'],'describe'=>cache('config')['describe']];
    	return view($this->theme.'.usercenter.index',compact('seo'));
    }
    // 修改个人信息
    public function getInfo()
    {
        // 取个人信息
        $uid = session('member')->id;
        $info = User::findOrFail($uid);
        $info->pid = 4;
        return view($this->theme.'.usercenter.info',compact('info'));
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
        return view($this->theme.'.usercenter.address',compact('list','info'));
    }
    // 添加地址
    public function getAddressAdd()
    {
        $area = Type::where('parentid',4)->get();
        $info = (object) ['pid'=>4];
        return view($this->theme.'.usercenter.address_add',compact('area','info'));
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
        return view($this->theme.'.usercenter.address_edit',compact('info','area'));
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
        return view($this->theme.'.usercenter.returngood',compact('info','list'));
    }
    // 充值卡充值
    public function getCard()
    {
        $info = (object) ['pid'=>4];
        return view($this->theme.'.usercenter.card',compact('info'));
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
        return view($this->theme.'.usercenter.consume',compact('info','list'));
    }
}
