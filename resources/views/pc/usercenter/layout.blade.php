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
    <!-- header -->
    @include('home.usercenter.head')
    <!-- 主内容 -->
    <div class="usercenter_bg">
        <section class="box user_center clearfix">
            @include('pc.usercenter.aside')
            <!-- info -->
            @yield('content')
            
        </section>
    </div>
    

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
</body>
</html>