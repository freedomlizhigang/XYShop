<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User\Address;
use Illuminate\Http\Request;
use Validator;

class UserAddressController extends Controller
{
    // 收货地址
    public function getAddress()
    {
        $pos_id = 'center';
        $title = '收货地址';
        $list = Address::where('user_id',session('member')->id)->where('delflag',1)->orderBy('id','desc')->paginate(20);
        return view(cache('config')['theme'].'.user.address',compact('pos_id','title','list'));
    }
    // 添加收货地址
    public function getAddressAdd()
    {
        session()->put('backurl',url()->previous());
        $pos_id = 'center';
        $title = '添加收货地址';
        return view(cache('config')['theme'].'.user.address_add',compact('pos_id','title'));
    }
    public function postAddressAdd(Request $req)
    {
        $validator = Validator::make($req->input(), [
          'area1' => 'required',
          'area2' => 'required',
          'area3' => 'required',
          'area4' => 'required',
          'data.people' => 'required|max:100',
          'data.phone' => 'required|digits:11',
          'data.address' => 'required|max:255',
        ]);
        $attrs = array(
          'area1' => '省份',
          'area2' => '城市',
          'area3' => '县区',
          'area4' => '社区',
          'data.people' => '收货人',
          'data.phone' => '手机号',
          'data.address' => '地址',
        );
        $validator->setAttributeNames($attrs);
        if ($validator->fails()) {
          return back()->with('message',$validator->errors()->all()[0]);
        }

        $data = $req->input('data');
        $data['area'] = $req->area1.'-'.$req->area2.'-'.$req->area3.'-'.$req->area4;
        $data['user_id'] = session('member')->id;
        Address::create($data);
        return redirect(session('backurl'));
    }
    // 编辑收货地址
    public function getAddressEdit($id = '')
    {
        $pos_id = 'center';
        $title = '修改收货地址';
        $info = Address::findOrFail($id);
        $areaname = explode('-', $info->area);
        return view(cache('config')['theme'].'.user.address_edit',compact('pos_id','title','info','areaname'));
    }
    public function postAddressEdit(Request $req,$id = '')
    {
        $validator = Validator::make($req->input(), [
            'area1' => 'required',
            'area2' => 'required',
            'area3' => 'required',
            'area4' => 'required',
            'data.people' => 'required|max:100',
            'data.phone' => 'required|digits:11',
            'data.address' => 'required|max:255',
        ]);
        $attrs = array(
            'area1' => '省份',
            'area2' => '城市',
            'area3' => '县区',
            'area4' => '社区',
            'data.people' => '收货人',
            'data.phone' => '手机号',
            'data.address' => '地址',
        );
        $validator->setAttributeNames($attrs);
        if ($validator->fails()) {
          return back()->with('message',$validator->errors()->all()[0]);
        }

        $data = $req->input('data');
        $data['area'] = $req->area1.'-'.$req->area2.'-'.$req->area3.'-'.$req->area4;
        Address::where('user_id',session('member')->id)->where('id',$id)->update($data);
        return redirect(url('user/address'))->with('message','修改成功！');
    }
    // 删除地址
    public function getAddressDel($id = '')
    {
        Address::where('id',$id)->update(['delflag'=>0]);
        return back()->with('message','删除成功！');
    }
}
