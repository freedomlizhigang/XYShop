<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Shop-VueJS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap -->
    <!-- <link href="{{ $sites['static']}}home/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="{{ $sites['static']}}home/css/home.css" rel="stylesheet"> -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <!-- <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
</head>

<body>
    <!-- 如果要自动登陆，把用户信息存成全局变量，在创建页面的时候传给vuex -->
    <script>
        window.userinfo = {!! $user !!};
        window.thisHost = "{{ config('app.url') }}" + '/api/';
    </script>
    
    <div id="app">
    </div>
    <script src="{{ config('url') }}/js/app.js"></script>

    <!-- 主内容 -->
    <!-- @yield('content') -->
    <script id="__bs_script__">
        document.write("<script async src='http://www.xyshop.com:3000/browser-sync/browser-sync-client.js?v=2.18.13'><\/script>".replace("www.xyshop.com", location.hostname));
    </script>
    <style>
        .swiper-container {
            width: 600px;
            height: 300px;
            font-size: 20px;
            line-height: 2;
        }
    </style>
</body>
</html>