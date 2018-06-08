<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\Address;
use App\Models\User\Consume;
use App\Models\User\Group;
use App\Models\User\User;
use Excel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getIndex(Request $res)
    {
    	$title = '会员列表';
    	$q = trim($res->input('q',''));
        $list = User::with('group')->where(function($r)use($q){
        	if ($q != '') {
        		$r->where('username',$q)->orWhere('email',$q)->orWhere('phone',$q)->orWhere('nickname',$q);
        	}
        })->orderBy('id','desc')->paginate(15);
        return view('admin.member.index',compact('list','title'));
    }
    // 导出用户信息
    public function getExcel(Request $req)
    {
        $user = User::with('group')->where('status',1)->get();
        $tmp = [];
        foreach ($user as $v) {
            $sex = $v->sex == 2 ? '女' : '男';
            $tmp[] = [$v->id,$v->group->name,$v->username,$v->nickname,$v->user_money,$sex,$v->phone,$v->email,$v->address];
        }
        $cellData = array_merge(
            [['ID','会员级别','账号','昵称','余额','性别','电话','邮箱','地址']],$tmp
        );
        Excel::create('用户信息',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    // 消费排名
    public function getConsumeRanking(Request $req)
    {
        $title = '消费排名';
        $starttime = isset($req->starttime) ? $req->starttime : date('Y-m-d 00:00:00');
        $endtime = isset($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $list = Consume::where('created_at','>',$starttime)->where('created_at','<',$endtime)->where('type',0)->get();
        $user = Consume::with(['user'=>function($q){
                    $q->select('id','nickname','phone','points');
                }])->where('created_at','>',$starttime)->where('created_at','<',$endtime)->where('type',0)->groupBy('user_id')->get()->unique()->toArray();
        foreach ($user as $k => $v) {
            $user[$k]['total'] = $list->where('user_id',$v['user_id'])->sum('price');
        }
        $user = collect($user)->sortByDesc('total');
        return view('admin.member.ranking',compact('user','title'));
    }

    // 审核会员
    public function getStatus($id,$status)
    {
        User::where('id',$id)->update(['status'=>$status]);
        return back()->with('message', '修改会员状态成功！');
    }

    // 修改会员组
    public function getEditGroup($id)
    {
        $title = '修改会员组';
        $info = User::findOrFail($id);
        $group = Group::where('status',1)->get();
        return view('admin.member.group',compact('title','info','group'));
    }
    public function postEditGroup(Request $req,$id)
    {
        User::where('id',$id)->update(['gid'=>$req->gid]);
        return $this->adminJson(1,'改会员组成功！');
    }
    // 修改会员
    public function getEdit($id)
    {
        $title = '修改会员';
        $info = User::findOrFail($id);
        return view('admin.member.edit',compact('title','info'));
    }
    public function postEdit(Request $req,$id)
    {
        $pwd = $req->input('data.password');
        $rpwd = $req->input('data.repassword');
        if(strlen($pwd) < 6)
        {
            return $this->adminJson(0,'密码长度小于6位');
        }
        if ($pwd == $rpwd) {
            User::where('id',$id)->update(['password'=>encrypt($rpwd)]);
            return $this->adminJson(1,'改密码成功！');
        }
        else
        {
            return $this->adminJson(0,'两次密码不相同，请重新输入');
        }
    }
    // 会员消费
    public function getConsumed($id = '')
    {
        $title = '会员消费';
        return view('admin.member.consumed',compact('title','id'));
    }
    public function postConsumed(Request $req,$id = '')
    {
        $pwd = $req->pwd;
        if (app('com')->makepwd($pwd,session('console')->crypt) != session('console')->password) {
            return $this->adminJson(0,'密码错误！');
        }
        $money = $req->input('data.user_money');
        User::where('id',$id)->decrement('user_money',$money);
        // 消费记录
        app('com')->consume($id,'0',$money,'后台消费',0);
        return $this->adminJson(1,'会员消费成功！');
    }
    // 会员充值
    public function getChong($id = '')
    {
        $title = '会员充值';
        return view('admin.member.chong',compact('title','id'));
    }
    public function postChong(Request $req,$id = '')
    {
        $pwd = $req->pwd;
        if (app('com')->makepwd($pwd,session('console')->crypt) != session('console')->password) {
            return $this->adminJson(0,'密码错误！');
        }
        $money = $req->input('data.user_money');
        User::where('id',$id)->increment('user_money',$money);
        // 消费记录
        app('com')->consume($id,0,$money,'后台充值',1);
        return $this->adminJson(1,'会员充值成功！');
    }
    // 消费记录
    public function getConsume($id = '')
    {
        $title = '消费记录';
        $list = Consume::where('user_id',$id)->orderBy('id','desc')->paginate(15);
        return view('admin.member.consume',compact('list','title'));
    }
    // 收货地址
    public function getAddress($id = '')
    {
        $title = '收货地址';
        $list = Address::where('user_id',$id)->where('delflag',1)->orderBy('id','desc')->paginate(15);
        return view('admin.member.address',compact('list','title'));
    }
    // 修改收货地址
    public function getAddressEdit($id)
    {
        $title = '修改收货地址';
        $info = Address::findOrFail($id);
        return view('admin.member.addressedit',compact('title','info'));
    }
    public function postAddressEdit(Request $req,$id)
    {
        Address::where('id',$id)->update($req->input('data'));
        return $this->adminJson(1,'改密码成功！');
    }
}
