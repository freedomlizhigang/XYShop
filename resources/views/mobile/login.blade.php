<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ cache('config')['title'] }}用户登录</title>
    <meta name="keywords" content="{{ isset($keyword) ? $keyword : cache('config')['keyword'] }}">
    <meta name="description" content="{{ isset($describe) ? $describe : cache('config')['describe'] }}">
    <!-- Bootstrap -->
    <link href="{{ $sites['static']}}mobile/css/iconfont.css" rel="stylesheet">
    <link href="{{ $sites['static']}}mobile/css/home.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ $sites['static']}}common/js/lazyload.min.js"></script>
    <script src="{{ $sites['static']}}mobile/js/com.js"></script>
</head>

<body class="pr">
    <header class="login-top">{{ cache('config')['title'] }}用户登录</header>
    <section class="login_box mt20 clearfix">
        <form action="" method="post" class="pure-form pure-form-stacked">
            {{ csrf_field() }}
            <input type="text" name="phone" placeholder="手机号" value="{{ old('phone') }}" class="pure-input-1">
            <input type="password" name="passwd" placeholder="密码" value="{{ old('password') }}" class="pure-input-1 mt20">
            <div class="mt20">
                <input type="submit" class="btn_submit" value="登录">
                <input type="reset" class="btn_reset mt10" value="重置">
            </div>
        </form>
    </section>
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
</body>
</html>