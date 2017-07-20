<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>微信支付-希夷商城</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script>
		function onBridgeReady(){
		    WeixinJSBridge.invoke('getBrandWCPayRequest',{
		           	'appId':"{{ $set->appid }}",
		           	'timeStamp':"{{ $d['timeStamp'] }}",
		           	'nonceStr':"{{ $d['nonceStr'] }}",
		           	'package':"{{ $d['package'] }}",     
		           	'signType': "{{ $d['signType'] }}",
					'paySign': "{{ $d['paySign'] }}",
		       },
		       function(res){
		            if(res.err_msg == 'get_brand_wcpay_request:ok') {
		            	window.location.href = "{{ url('user/order/2') }}";
		            }
		            if(res.err_msg == 'get_brand_wcpay_request:fail')
		            {
		            	alert('fail');
		            }
		        }
		    );
		}
		window.onload = function(){
			if (typeof WeixinJSBridge == 'undefined'){
			   if( document.addEventListener ){
			       document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
			   }else if (document.attachEvent){
			       document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
			       document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
			   }
			}else{
			   onBridgeReady();
			}
		}
	</script>
</head>

<body>
	<div class="bg-primary" style="padding: 15px;">
		<strong>下单成功，请及时支付！</strong>
	</div>
	<div style="margin-top: 40%;" class="text-center">
		<a href="{{ url('/user/order/1') }}" class="btn btn-default">查看订单</a>
		<a href="{{ url('/') }}" class="btn btn-success">继续购物</a>
	</div>
</body>
</html>