@extends('mobile.layout')

@section('content')
  <!-- 列出订单信息来 -->
  <ul class="list_order clearfix overh">
    <li class="mt20 clearfix overh bgc_f pl20 pr20">
      <!-- top -->
      <header class="l_o_top clearfix pt20 pb20">
        <span class="l_o_t_title"><i class="iconfont icon-evaluate color_main"></i>单号：{{ $order->order_id }}</span>
      </header>
      <!-- footer -->
      <footer class="l_o_footer clearfix pt20 pb20">
        <span class="l_o_f_price">实付款：￥{{ $order->money }}</span>
      </footer>
    </li>
  </ul>

  <script type="text/javascript" charset="utf-8">
    function onBridgeReady(){
       WeixinJSBridge.invoke(
           'getBrandWCPayRequest', {!! $config !!},
           function(res){     
                alert(res.err_msg);
                if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                    // 支付成功，转到订单列表页面
                  setTimeout(function(){
                    location.href = "{{ url('user/center') }}";
                  },200);
                }     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
           }
       ); 
    }
    if (typeof WeixinJSBridge == "undefined"){
       if(document.addEventListener){
           document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
       }else if (document.attachEvent){
           document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
           document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
       }
    }else{
       onBridgeReady();
    }
  </script>
@endsection