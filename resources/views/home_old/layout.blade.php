<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @yield('title')
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ $sites['static']}}home/css/home.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body>


    <!-- 主内容 -->
    @yield('content')
    
    <!-- 提示信息 -->
    @if(session('message'))
    <div class="alert alert-success alert_shop" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <p>{{ session('message') }}</p>
    </div>
    @endif

    <!-- 公用js文件 -->
    <script>
       var host = "{{ config('app.url') }}/";
    </script>
    <script src="{{ $sites['static']}}home/js/com.js"></script>

    <!-- <script id="__bs_script__">
      document.write("<script async src='http://www.jxf.com:3000/browser-sync/browser-sync-client.js?v=2.18.8'><\/script>".replace("www.jxf.com", location.hostname));
      </script> -->
</body>
</html>