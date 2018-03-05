<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CardRequest;
use App\Models\User\Card;
use Excel;
use Illuminate\Http\Request;

class CardController extends Controller
{
    // 查列表
    public function getIndex(Request $req)
    {
    	$title = '会员卡管理';
        // 搜索关键字
        $starttime = $req->input('starttime');
        $endtime = $req->input('endtime');
        $status = $req->input('status');
        $q = $req->input('q');
		$list = Card::with(['user'=>function($q){
				$q->select('id','nickname','username');
			}])->where(function($q) use($starttime,$endtime){
                if ($starttime != '' && $endtime != '') {
                    $q->where('init_time','>=',$starttime)->where('init_time','<=',$endtime);
                }
            })->where(function($q) use($status){
                if ($status != '') {
                    $q->where('status',$status);
                }
            })->where(function($r) use($q){
                if ($q != '') {
                    // 查出来用户ID
                    $uid = User::where('nickname','like',"%$q%")->orWhere('phone','like',"%$q%")->pluck('id')->toArray();
                    $r->whereIn('user_id',$uid);
                }
            })->orderBy('id','desc')->paginate(15);
    	return view('admin.card.index',compact('title','list','starttime','endtime','status','q'));
    }
    // 导出卡
    public function getCardExcel(Request $req)
    {
        // 今日销售统计表，先查出今天的已付款订单，再按订单查出所有产品及属性
        $status = $req->input('status','');
        $starttime = isset($req->starttime) && !is_null($req->starttime) ? $req->starttime : date('1970-00-00 00:00:00');
        $endtime = isset($req->endtime) && !is_null($req->endtime) ? $req->endtime : date('Y-m-d 24:00:00');
        $cards = Card::with(['user'=>function($q){
                    $q->select('id','username','nickname','openid','phone');
                }])->where(function($q)use($status){
                    if ($status != '') {
                        $q->where('status',$status);
                    }
                })->where('created_at','>',$starttime)->where('created_at','<',$endtime)->orderBy('id','desc')->get();
        $tmp = [];
        foreach ($cards as $v) {
            $username = is_null($v->user) ? '' : $v->user->username.' - '.$v->user->nickname;
            $status = $v->status ? '已开' : '未开';
            $tmp[] = [$v->card_id,$v->card_pwd,$v->price,$status,$username,$v->init_time,$v->created_at];
        }

        $cellData = array_merge(
            [['卡号','密码','金额','状态','用户','开卡时间','建卡时间']],$tmp
        );
        Excel::create('会员卡表',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
    // 添加新卡
    public function getAdd()
    {
    	return view('admin.card.add');
    }
    public function postAdd(CardRequest $req,$id = '')
    {
    	$nums = $req->input('data.nums');
    	$prices = $req->input('data.prices');
    	$tmp = [];
    	$date = date('Y-m-d H:i:s');
    	for ($i=0; $i < $nums; $i++) { 
    		$tmp[] = ['card_id'=>app('com')->random(8),'card_pwd'=>app('com')->random(6),'price'=>$prices,'created_at'=>$date,'updated_at'=>$date];
    	}
    	Card::insert($tmp);
    	return $this->adminJson(1,'添加成功！');
    }
    // 修改卡金额
    public function getEdit($id = 0)
    {
        $info = Card::findOrFail($id);
        return view('admin.card.edit',compact('info'));
    }
    public function postEdit(Request $req,$id = 0)
    {
        Card::where('id',$id)->update(['price'=>$req->price]);
        return $this->adminJson(1,'修改成功！');
    }
    // 批量删除
    public function postAlldel(Request $req)
    {
        $ids = $req->input('sids');
        // 是数组更新数据，不是返回
        if(is_array($ids))
        {
            Card::whereIn('id',$ids)->delete();
            return back()->with('message', '批量删除完成！');
        }
        else
        {
            return back()->with('message','请选择商品！');
        }
    }
}