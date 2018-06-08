@extends('mobile.layout')

@section('content')
  <!-- 头 -->
  <header class="center_head overh clearfix">
    <img src="{{ $info->thumb }}" height="200" width="200" alt="{{ $info->nickname }}" class="c_header">
    <div class="c_h_info">
      <h3>{{ $info->nickname }} <em class="font_sm">{{ $info->group->name }}</em></h3>
      <p>账户余额：<em class="font_md">￥{{ $info->user_money }}</em>，积分：<em class="font_md">{{ $info->points }}</em>
      <!-- 判断签到 -->
      @if(is_null($sign))
      <a href="{{ url('user/signin') }}" class="sign"><i class="iconfont icon-camera"></i>每日签到</a>
      @endif
      </p>
    </div>
  </header>
  <!-- 订单 -->
  <div class="center_order bgc_f mt20">
    <h4 class="t4_center pr"><i class="iconfont icon-list color_cheng"></i>我的订单<a href="{{ url('user/orderlist') }}" class="ps t4_center_r">全部订单<i class="iconfont icon-right"></i></a></h4>
    <div class="c_o_list clearfix overh">
      <a href="{{ url('user/orderlist',['sid'=>1]) }}" class="c_o_link"><i class="iconfont icon-vipcard"></i><em>待付款</em></a>
      <a href="{{ url('user/orderlist',['sid'=>2]) }}" class="c_o_link"><i class="iconfont icon-deliver"></i><em>待收货</em></a>
      <a href="{{ url('user/orderlist',['sid'=>3]) }}" class="c_o_link"><i class="iconfont icon-repair"></i><em>售后</em></a>
    </div>
  </div>
  <!-- 其它信息 -->
  <div class="center_overh mt20 bgc_f">
    <h4 class="t4_center pr"><i class="iconfont icon-my_light color_cheng"></i>个人信息<a href="{{ url('userinfo') }}" class="ps t4_center_r">修改<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-pay color_main"></i>会员充值<a href="{{ url('user/recharge') }}" class="ps t4_center_r">立即充值<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-vipcard color_fen"></i>充值卡激活<a href="{{ url('user/card') }}" class="ps t4_center_r">激活<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-sortlight color_lan"></i>消费记录<a href="{{ url('consume') }}" class="ps t4_center_r">查看全部<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-moneybag color_red"></i>分享赚余额<a href="{{ url('user/distribution/shareurl') }}" class="ps t4_center_r">立即开赚<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-people_list_light color_zi"></i>赚钱记录<a href="{{ url('user/distribution/logs') }}" class="ps t4_center_r">赚钱记录<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-edit_light color_cheng"></i>收货地址<a href="{{ url('user/address') }}" class="ps t4_center_r">管理<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-present color_fen"></i>我的优惠券<a href="{{ url('user/coupon') }}" class="ps t4_center_r">看可用<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-attention_light color_shenred"></i>修改密码<a href="{{ url('passwd') }}" class="ps t4_center_r">谨慎操作<i class="iconfont icon-right"></i></a></h4>
    <h4 class="t4_center pr"><i class="iconfont icon-warn color_zi"></i>退出登陆<a href="{{ url('logout') }}" class="ps t4_center_r">我要离开<i class="iconfont icon-right"></i></a></h4>
  </div>
@endsection