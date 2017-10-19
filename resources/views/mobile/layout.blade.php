<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $seo->title }}</title>
    <meta name="keywords" content="{{ $seo->keyword }}">
    <meta name="description" content="{{ $seo->describe }}">
    <!-- Bootstrap -->
    <link href="{{ $sites['static']}}mobile/css/iconfont.css" rel="stylesheet">
    <link href="{{ $sites['static']}}mobile/css/home.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ $sites['static']}}mobile/js/jquery.touchslider.min.js"></script>
</head>

<body>


    <!-- 主内容 -->
    @yield('content')
    

    
    <!-- 弹出提示层 -->
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
    <div class="alert_home alert_msg hidden">
        dsd
    </div>

    <!-- 公用js文件 -->
    <script>
       var host = "{{ config('app.url') }}/";
       var ajaxLock = true;
       var uid = "{{ session('member')->id }}";
    </script>
    <script src="{{ $sites['static']}}mobile/js/com.js"></script>

    <script id="__bs_script__">
      document.write("<script async src='http://www.xyshop.com:3000/browser-sync/browser-sync-client.js?v=2.18.13'><\/script>".replace("www.xyshop.com", location.hostname));
      </script>
</body>
</html>