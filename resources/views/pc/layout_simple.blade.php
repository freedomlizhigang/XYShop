<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @yield('title')
    <!-- Bootstrap -->
    <link href="{{ $sites['static']}}pc/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $sites['static']}}pc/css/home.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ $sites['static']}}common/js/lazyload.min.js"></script>
    <!-- common -->
    <script>
        var host = "{{ config('app.url') }}/";
    </script>
    <script src="{{ $sites['static']}}pc/js/com.js"></script>
</head>

<body>
    <!-- top_info -->
    <div class="top_info">
        <div class="box clearfix">
            <div class="pull-left top_info_left top_address">
                北京 <span class="iconfont icon-locationfill"></span>
            </div>
            <div class="pull-right top_info_right text-right">
                @if(session()->has('member'))
                <a href="{{ url('/user/center') }}" class="top_user_info">欢迎回来：<em>{{ isset(session('member')->nickname) ? session('member')->nickname : session('member')->username }}</em></a>
                @else
                <a href="{{ url('/user/login') }}">请登录</a>|<a href="{{ url('/user/register') }}">免费注册</a>
                @endif
                |<a href="{{ url('/order/list') }}">我的订单</a>|<a href="{{ url('/help') }}">帮助中心</a>|<a href="javascript:;">手机商城</a>
            </div>
        </div>
    </div>
    <!-- logo + search -->
    <header class="head_simple box clearfix pr">
        <!-- logo -->
        <div class="pull-left">
            <a href="{{ $sites['url']}}"><img src="{{ $sites['static']}}pc/images/logo.png" alt="{{ cache('config')['title'] }}"></a>
        </div>
        <!-- search -->
        <div class="search_top pull-right mt15">
            <form action="#" class="form-inline form_search_top clearfix">
                <input type="text" class="form-control form_search_top_key pull-left" placeholder="输入关键字查找热门商品..." />
                <div class="form_search_top_btn pull-left iconfont icon-search"></div>
            </form>
        </div>
    </header>

    <!-- 主内容 -->
    @yield('content')

    @include('pc.foot')
    
    @if(session('message'))
    <div class="alert_home">
        {{ session('message') }}
    </div>
    <script type="text/javascript">
        $(function(){
            $('div.alert_home').delay(1500).slideUp(300);
        })
    </script>
    @endif
    <div class="alert_home alert_msg dn">
    </div>
</body>
</html>