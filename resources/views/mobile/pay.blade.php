@extends('mobile.layout')

@section('content')
  
  <div class="tips"><i class="iconfont icon-notification"></i>订单结算页面！</div>
  <form action="{{ url('order/pay',['oid'=>$order->id]) }}" method="get">
    {{ csrf_field() }}
    <section class="bgc_f sec_pay clearfix overh pd20">
      <div class="mt20">
        <h4 class="t4_pay">订单已提交!</h4>
        <p class="p_pay_orderid">单号：<span class="text-info">{{ $order->order_id }}</span></p>
      </div>
      @foreach($paylist as $l)
      <div class="mt20 clearfix pay_mod">
        <input type="radio" name="pay" value="{{ $l->id }}" class="pay_radio">
        <img src="{{ $l->thumb }}" width="64" height="64" class="pay_icon" alt="{{ $l->name }}">
        <p class="pay_text">{{ $l->content }}</p>
      </div>
      @endforeach
      <div class="pay_mod_2 mt20 clearfix">
        <p class="pay_text color_main">可用余额：{{ $user->user_money }}元，<a href="{{ url('user/recharge') }}" class="label label-hui">立即充值</a></p>
      </div>
      <p class="cart_send">总计：<strong class="total_prices color_main">￥{{ $order->total_prices }}</strong></p>
      <input type="submit" value="去支付" class="sendtoconfirm mt20">
    </section>
  </form>
  <script>
    $(function(){
      $('.pay_mod').click(function() {
        $('.pay_radio').prop('checked', 'false');
        $(this).children('.pay_radio').prop('checked', 'checked');
      });
    })
  </script>

  @foreach(app('tag')->ad(4,2,1) as $k => $c)
  <div class="ads mt20">
    <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="750" height="190" alt="{{ $c->title }}"></a>
  </div>
  @endforeach

@endsection