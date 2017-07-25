<!doctype html>
<html lang="zh-cn">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ cache('config')['sitename'] }}-弹出</title>
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
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ $sites['static']}}admin/js/com.js"></script>
</head>

<body style="padding: 0">
    @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <div class="container-fluid">
        @yield('content')
    </div>
    <script type="text/javascript">
        var host = "{{ config('app.url') }}";
    </script>
</body>

</html>