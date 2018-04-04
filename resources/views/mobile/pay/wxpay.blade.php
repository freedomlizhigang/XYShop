@extends('mobile.layout')

@section('content')
  <!-- 列出订单信息来 -->
  <ul class="list_order clearfix overh">
    <li class="mt20 clearfix overh bgc_f pl20 pr20">
      <!-- top -->
      <header class="l_o_top clearfix pt20 pb20">
        <span class="l_o_t_title"><i class="iconfont icon-evaluate color_main"></i>单号：{{ $order->order_id }}</span>
      </header>
      <!-- goods -->
      <ul class="l_o_goods clearfix">
        @foreach($order->good as $lg)
        <li class="clearfix mb20">
          <a href="{{ url('good',['id'=>$lg->good_id]) }}" class="l_o_g_img"><img src="{{ $lg->good->thumb }}" height="200px" width="200px" alt="{{ $lg->good_title }}"></a>
          <h3 class="l_o_g_title">{{ $lg->good_title }}</h3>
        </li>
        @endforeach
      </ul>
      <!-- footer -->
      <footer class="l_o_footer clearfix pt20 pb20">
        <span class="l_o_f_price">实付款：￥{{ $order->total_prices }}</span>
      </footer>
    </li>
  </ul>

  <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" charset="utf-8">
      wx.config({!! $js->buildConfig(array('chooseWXPay'), false) !!});
      wx.error(function(res){
        $('.alert_msg').text('微信支付配置失败！').slideToggle().delay(1500).slideToggle();
        setTimeout(function(){
          location.href = "{{ url('pay',['oid'=>$oid]) }}";
        },2000);
      });
    $(function(){
      // 支付
      wx.ready(function(){
        wx.chooseWXPay({
          timestamp: {{ $config['timestamp'] }},
          nonceStr: "{{ $config['nonceStr'] }}",
          package: "{{ $config['package'] }}",
          signType: "{{ $config['signType'] }}",
          paySign: "{{ $config['paySign'] }}", // 支付签名
          success: function (res) {
              // 支付成功，转到订单列表页面
              setTimeout(function(){
                location.href = "{{ url('user/orderlist',['sid'=>2]) }}";
              },200);
          },
          error: function(res)
          {
            // 失败，回到上一页上
            setTimeout(function(){
              location.href = "{{ url('pay',['oid'=>$oid]) }}";
            },200);

          },
          cancel: function (res) {  
            // 取消支付后的回调函数
            setTimeout(function(){
              location.href = "{{ url('pay',['oid'=>$oid]) }}";
            },200);
          }
        });
      });
    })
  </script>
@endsection