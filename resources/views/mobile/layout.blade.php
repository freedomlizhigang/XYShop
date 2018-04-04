<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ isset($title) ? $title.'-' : '' }}{{ cache('config')['title'] }}</title>
    <meta name="keywords" content="{{ isset($keyword) ? $keyword : cache('config')['keyword'] }}">
    <meta name="description" content="{{ isset($describe) ? $describe : cache('config')['describe'] }}">
    <!-- Bootstrap -->
    <link href="{{ $sites['static']}}mobile/css/iconfont.css" rel="stylesheet">
    <link href="{{ $sites['static']}}mobile/css/home.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ $sites['static']}}common/js/lazyload.min.js"></script>
    <script src="{{ $sites['static']}}mobile/js/jquery.touchslider.min.js"></script>
    <!-- 公用js文件 -->
    <script>
       var host = "{{ config('app.url') }}/";
       var ajaxLock = true;
       var uid = "{{ session('member')->id }}";
    </script>
    <script src="{{ $sites['static']}}mobile/js/com.js"></script>
</head>

<body class="pr">
  <div class="main-box pr">
    <!-- 主内容 -->
    @yield('content')
  </div>
    
    <!-- 底 -->
    @include('mobile.common.footer')
    <!-- 公用底 -->
    @include('mobile.common.pos_menu')
    
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
    <!-- ajax返回提示 -->
    <div class="alert_home alert_msg hidden">
    </div>
    <!-- 补充手机号 -->
    @if(session()->has('nophone'))
    <div class="pos_bg_1"></div>
    <div class="nophone">
        <!-- <i class="pos_close iconfont icon-close"></i> -->
        <h4>请完善以下信息，以防止个人信息丢失。</h4>
        <form action="javascript:;" class="pure-form pure-form-stacked">
            <input type="text" class="user_phone pure-input-1" placeholder="常用手机号">
            <input type="password" class="user_passwd pure-input-1" placeholder="登录密码，6-15位">
            <div class="btn_group mt20">
                <input type="reset" class="btn_reset" value="重置">
                <div class="btn_submit">提交</div>
              </div>
        </form>
    </div>
    <script>
    $(function(){
      $('.btn_submit').click(function(){
        if(!ajaxLock)return false;
        var phone = $('.user_phone').val();
        var passwd = $('.user_passwd').val();
        var url = "{{ url('api/user/perfect') }}";
        ajaxLock = 0;
        $.post(url,{uid:uid,phone:phone,passwd:passwd},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == '1') {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            $('.pos_bg_1,.nophone').fadeOut();
          }
          else
          {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
          }
          ajaxLock = 1;
          return;
        }).error(function() {
          ajaxLock = 1;
          return;
        });
      });
    })
    </script>
    @endif
    

    <!-- <script id="__bs_script__">
      document.write("<script async src='http://www.xyshop.com:3000/browser-sync/browser-sync-client.js?v=2.18.13'><\/script>".replace("www.xyshop.com", location.hostname));
      </script> -->
</body>
</html>