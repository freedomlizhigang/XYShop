<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User\Address;
use App\Models\User\User;
use Illuminate\Http\Request;
use Validator;

class AjaxUserController extends Controller
{
    // 补充信息
    public function postPerfect(Request $req)
    {
        try {
            $validator = Validator::make($req->input(), [
                'uid' => 'required|integer',
                'phone' => 'required|digits:11',
                'passwd' => 'required|min:6|max:15',
            ]);
            $attrs = array(
                'uid' => '用户ID',
                'phone' => '手机号',
                'passwd' => '密码',
            );
            $validator->setAttributeNames($attrs);
            if ($validator->fails()) {
                // 如果有错误，提示第一条
                $this->ajaxReturn('0',$validator->errors()->all()[0]);
            }
            $data = ['username'=>$req->phone,'phone'=>$req->phone,'password'=>encrypt($req->passwd)];
            User::where('id',$req->uid)->update($data);
            $this->ajaxReturn('1','非常成功，请继续购物！');
        } catch (\Throwable $e) {
            $this->ajaxReturn('0',$e->getMessage());
        }
    }
    // 添加收货人信息
    public function postAddress(Request $req)
    {
    	$validator = Validator::make($req->input(), [
	        'uid' => 'required|integer',
	        'area1' => 'required|integer',
	        'area2' => 'required|integer',
	        'area3' => 'required|integer',
	        'people' => 'required|max:100',
	        'phone' => 'required|max:20',
	        'address' => 'required|max:255',
	    ]);
	    $attrs = array(
            'uid' => '用户ID',
            'area1' => '省份',
	        'area2' => '城市',
	        'area3' => '县区',
	        'people' => '收货人',
	        'phone' => '手机号',
	        'address' => '地址',
        );
        $validator->setAttributeNames($attrs);
        if ($validator->fails()) {
            // 如果有错误，提示第一条
            $this->ajaxReturn('0',$validator->errors()->all()[0]);
        }
    	try {
    		$data = ['user_id'=>$req->uid,'address'=>$req->address,'people'=>$req->people,'phone'=>$req->phone,'area'=>$req->area1.'-'.$req->area2.'-'.$req->area3.'-'.$req->area4];
    		$address = Address::create($data);
    		$msg = "<li data-aid=".$address->id." class='active'><span class='l_a_left'>".$address->people."</span><span class='l_a_right'>".$address->people." ".$address->area." ".$address->address." ".$address->phone."</span></li>";
            $this->ajaxReturn('1',$msg);
        } catch (\Throwable $e) {
            $this->ajaxReturn('0',$e->getMessage());
            // $this->ajaxReturn('0','添加收货人失败，请稍后再试！');
        }
    }
}
