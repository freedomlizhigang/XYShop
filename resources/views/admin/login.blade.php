<!doctype html>
<html lang="zh-cn">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <title>{{ cache('config')['sitename'] }}登录</title>
  <meta name="author" content="李潇喃：www.xi-yi.ren" />
  <!-- IE最新兼容 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- 国产浏览器高速/微信开发不要用 -->
  <meta name="renderer" content="webkit">
  <!-- 移动设备禁止缩放 -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <!-- No Baidu Siteapp-->
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="stylesheet" href="{{ $sites['static']}}admin/css/reset.css"></head>

<body>
  <div class="login_bg">
    <div class="login_box">
      <img src="{{ $sites['static']}}admin/images/login_h.png" class="center-block" alt="希夷shop管理中心">
      <form method="POST" action="{{ url('/console/login') }}" class="mt10">
        {!! csrf_field() !!}
        <div class="clearfix mt20">
          <label for="username" class="login_form_left">用户名：</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control login_form_right">
        </div>
        <div class="clearfix mt10">
          <label for="password" class="login_form_left">密码：</label>
          <input type="password" name="password" class="form-control login_form_right">
        </div>
        @if(session('message'))
        <span class="help-block text-center">{{ session('message') }}</span>
        @endif
        <div class="form-group mt10">
          <input type="submit" value="登录" class="login_submit">
        </div>
      </form>
    </div>
  </div>
</body>

</html>