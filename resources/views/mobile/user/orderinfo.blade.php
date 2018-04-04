@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <ul class="list_order clearfix overh">
    <li class="mt20 clearfix overh bgc_f pl20 pr20">
      <!-- top -->
      <header class="l_o_top clearfix pt20 pb20">
        <span class="l_o_t_title"><i class="iconfont icon-evaluate color_main"></i>单号：{{ $order->order_id }}</span>
        <span class="l_o_t_right f-r">
          @if($order->orderstatus == '1')
            @if($order->paystatus == 0)
            <a href="{{ url('pay',['oid'=>$order->id]) }}" class="label label-red">去支付</a>
            @endif
            @if($order->shipstatus == 1 && $order->paystatus == 1)
            <i class="label label-red order_confirm" data-uid="{{ $order->user_id }}" data-oid="{{ $order->id }}">确认收货</i>
            @endif
            @if($order->shipstatus == 0 && $order->paystatus == 1)
            <i class="label label-hui">待发货</i>
            @endif
          @elseif($order->orderstatus == '2')
          <i class="color_main">已完成</i>
          @else
          <i class="color_main">已关闭</i>
          @endif
        </span>
        @if($order->shipstatus == 1 && $order->paystatus == 1)
        <p class="mt10 color_9">发货时间：{{ $order->ship_at }}</p>
        @endif
      </header>
      <!-- goods -->
      <ul class="l_o_goods clearfix">
        @foreach($order->good as $lg)
        <li class="clearfix mb20">
          <a href="{{ $lg->good->url }}" class="l_o_g_img"><img src="{{ $lg->good->thumb }}" height="200px" width="200px" alt="{{ $lg->good_title }}"></a>
          <h3 class="l_o_g_title slh">{{ $lg->good_title }}</h3>
          <!-- 完成的，三天内 -->
          @if($order->orderstatus === 2 && strtotime($order->confirm_at) >= time()-259200 && $lg->shipstatus === 1)
          <a href="{{ url('user/returngood',['ogid'=>$lg->id]) }}" class="db label label-hui f-r mt10">申请退换</a>
          @endif
        </li>
        @endforeach
      </ul>
      <!-- footer -->
      <footer class="l_o_footer clearfix pt20 pb20">
        <span class="l_o_f_price">实付款：￥{{ $order->total_prices }}</span>
        @if($order->orderstatus == '1' && $order->shipstatus == 0)
        <span class="label label-hui f-r order_cancel" data-uid="{{ $order->user_id }}" data-oid="{{ $order->id }}">关闭订单</span>
        @endif
      </footer>
    </li>
  </ul>
  <script>
    $(function(){
      // 确认收货
      $('.order_confirm').click(function(){
        var that = $(this);
        var oid = that.attr('data-oid');
        if (uid !== that.attr('data-uid')) {
          $('.alert_msg').text('不是自己的不要操作！').slideToggle().delay(1500).slideToggle();
          return;
        }
        var url = "{{ url('api/good/confirmorder') }}";
        ajaxLock = 0;
        $.post(url,{oid:oid},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == '1') {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            // 刷新页面
            setTimeout(function(){
              window.location.href = "{{ url('user/orderlist') }}" + '/3';
            },2000);
          }
          else
          {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
          }
          ajaxLock = 1;
          return;
        }).error(function() {
          ajaxLock = 1;
          return;
        });
      });
      // 取消订单
      $('.order_cancel').click(function(){
        var that = $(this);
        var oid = that.attr('data-oid');
        if (uid !== that.attr('data-uid')) {
          $('.alert_msg').text('不是自己的不要操作！').slideToggle().delay(1500).slideToggle();
          return;
        }
        var url = "{{ url('api/good/removeorder') }}";
        ajaxLock = 0;
        $.post(url,{oid:oid},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == '1') {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            // 刷新页面
            setTimeout(function(){
              location.reload();
            },2000);
          }
          else
          {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
          }
          ajaxLock = 1;
          return;
        }).error(function() {
          ajaxLock = 1;
          return;
        });
      });
    })
  </script>
@endsection