<!doctype html>
<html lang="zh-cn">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>泰华集团吉鲜蜂商城订单信息</title>
    <meta name="author" content="李潇喃：www.www.xi-yi.ren" />
    <!-- IE最新兼容 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 国产浏览器高速/微信开发不要用 -->
     <meta name="renderer" content="webkit">
     
    <!-- 移动设备禁止缩放 -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <!-- 上传用的 css -->
    <link rel="stylesheet" href="{{ $sites['static']}}admin/css/reset.css">
    <style>
        @media print {
            body {font-size: 9pt; line-height: 1.2;}
            td {padding: 0px !important;}
            h4 {font-size: 12pt;}
            @page {
                size: 9cm 24cm;
                margin: 0cm;
            }
        }
    </style>
</head>

<body>
    <h4 class="text-center" style="margin-bottom: 5px">{{ cache('config')['title'] }}订单信息</h4>
    <table class="table">
        <tr>
            <td>收货人：{{ !is_null($order->address) ? $order->address->people : $order->user->nickname }}</td>
            <td>订单编号：{{ $order->order_id }}</td>
            <td>下单时间：{{ $order->created_at }}</td>
        </tr>
        <tr>
            <td>联系方式：{{ !is_null($order->address) ? $order->address->phone : $order->user->phone }}</td>
            <td>支付方式：{{ $order->pay_name }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">收货地址：{{ !is_null($order->address) ? $order->address->area.$order->address->address : $order->zitidian->address }}</td>
            <td class="text-right">电话：{{ cache('config')['phone'] }}</td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <td>商品名称</td>
            <td>规格</td>
            <td>价格</td>
            <td>数量</td>
            <td>小计</td>
        </tr>
        @foreach($order->good as $g)
        <tr>
            <td>{{ $g->good_title }}</td>
            <td>{{ $g->good_spec_name }}</td>
            <td>{{ $g->price }}</td>
            <td>{{ $g->nums }}</td>
            <td>{{ $g->total_prices }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-right">商品总金额：{{ $order->total_prices }}</td>
        </tr>
    </table>
    <p>客户给商家的留言：{{ $order->mark }}</p>
    <p>发货备注：{{ $order->shopmark }}</p>
</body>
</html>