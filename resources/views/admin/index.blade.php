<!doctype html>
<html lang="zh-cn">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ cache('config')['sitename'] }}管理中心</title>
    <meta name="author" content="李潇喃：www.www.xi-yi.ren" />
    <!-- IE最新兼容 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 国产浏览器高速/微信开发不要用 -->
     <meta name="renderer" content="webkit">
     
    <!-- 移动设备禁止缩放 -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="stylesheet" href="{{ $sites['static']}}admin/css/reset.css">
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ $sites['static']}}admin/js/com.js"></script>
</head>

<body class="box">
    <div class="mainbox">
        <header class="top clearfix overh">
            <h1 class="logo overh">{{ cache('config')['sitename'] }}</h1>
            <nav class="menu clearfix overh">
                <ul id="mainmenu" class="clearfix">
                @foreach($mainmenu as $mm)
                    <li><a href="javascript:;" data-menuid="{{ $mm['id'] }}"@if($mm['id'] == 1) class="active"@endif>{{ $mm['name'] }}</a></li>
                @endforeach
                </ul>
            </nav>
            <span class="userinfo">
                <a href="/" target="_blank">网站首页，</a>
                欢迎回来：{{ session('console')->name }} |
                <a href="{{ url('/console/logout') }}">退出</a>
            </span>
        </header>
        <div class="leftbg"></div>
        <!-- 左侧菜单 -->
        <aside class="left overh" id="subnav">
        </aside>
        <section class="right overh">
            <iframe name="right" id="rightMain" src="{{ url('/console/index/main') }}" frameborder="false" scrolling="auto" style="border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>
        </section>
        <footer class="copyright clearfix">
            @ <a href="http://www.xi-yi.ren/" target="_blank" class="color_f60">console</a>&<a href="https://github.com/laravel/laravel" target="_blank">Laravel</a>
        </footer>
    </div>
    <script>
        $(function(){
            // 加载默认左侧菜单
            $("#subnav").load('/console/index/left/1');
            // 点击切换左侧菜单列表
            $(".logo").click(function(){
                $("#rightMain").attr('src','/console/index/main');
            });
            $("#mainmenu li a").click(function(){
                var mid = $(this).attr('data-menuid');
                $("#subnav").load("/console/index/left/"+mid);
                $(this).addClass('active').parent('li').siblings('li').children('a').removeClass('active');
            });
            // 右侧高度
            var RHeight = $('body').height() - 55;
            $('#rightMain').height(RHeight);
            $(window).resize(function() {
                RHeight = $('body').height() - 55;
                $('#rightMain').height(RHeight);
            });
        })
        // 左侧菜单点击添加效果
        function _LM(menuid,targetUrl)
        {
            // 添加样式
            $('.sub_menu_a ,.sub_menu').removeClass('active');
            $('#left_menu'+menuid+' ,#left_menu'+menuid+' a').addClass('active');
            // 改变右侧内容区域
            $("#rightMain").attr('src',targetUrl);
        }
    </script>
</body>

</html>