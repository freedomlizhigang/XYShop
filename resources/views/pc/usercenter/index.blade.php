@extends('pc.usercenter.layout')


@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection



<!-- 内容 -->
@section('content')

<div class="u_c_i pull-left">
    <!-- <div class="u_c_i_title">我的订单</div> -->
    <div class="u_c_info clearfix">
        <div class="pull-left u_c_my">
            <img src="{{ session('member')->thumb }}" alt="{{ session('member')->nickname }}" class="img-circle u_c_thumb">
            <div class="u_c_my_info">
                <h4>{{ isset(session('member')->nickname) ? session('member')->nickname : session('member')->username }} <span class="u_group">高级会员</span></h4>
                <p class="u_c_info_p">账户安全：<span class="text-success">{{ session('member')->groupname }}</span></p>
                <p class="u_c_info_p">账户余额：<span class="text-danger">￥{{ session('member')->user_money }}</span></p>
                <p class="u_c_info_p">会员积分：<span class="text-primary">{{ session('member')->points }}</span></p>
            </div>
        </div>
        <div class="pull-left u_c_orderinfo">
            <div class="u_c_o_list iconfont icon-vipcard">
                <p>待付款<span class="color_vice">2</span></p>
            </div>
            <div class="u_c_o_list iconfont icon-file">
                <p>待收货<span class="color_vice">2</span></p>
            </div>
            <div class="u_c_o_list iconfont icon-goods">
                <p>待自提<span class="color_vice">3</span></p>
            </div>
            <div class="u_c_o_list iconfont icon-comment_light">
                <p>待评价<span class="color_vice">5</span></p>
            </div>
        </div>
    </div>
</div>

@endsection