<!doctype html>
<html lang="zh-cn">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ cache('config')['sitename'] }}-右侧框架</title>
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
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- 上传用的 js -->
    <script src="{{ $sites['static']}}common/webuploader/webuploader.js"></script>
    <script src="{{ $sites['static']}}common/laydate/laydate.js"></script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="{{ $sites['static']}}common/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="{{ $sites['static']}}common/ueditor/ueditor.all.js"></script>
    <script src="{{ $sites['static']}}admin/js/com.js"></script>
</head>

<body>
    <div class="right_con">        
        <!-- 右侧标题 -->
        <div class="clearfix">
            <h2 class="main_title f-l">{{ $title }}</h2>
            <div class="btn-group main_btn f-l">
                @yield('rmenu')
            </div>
        </div>
        <hr>
        <div class="right-main mt10">
            @yield('content')
        </div>
    </div>
    <!-- 弹出层 -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel_right"></h4>
                </div>
                <div class="modal-body" id="modal_right">
                </div>
            </div>
      </div>
    </div>
    <!-- 提示层 -->
    <div class="alert alert-success dn" id="success_alert" role="alert"></div>
    <div class="alert alert-danger dn" id="error_alert" role="alert"></div>

    @if(session('message'))
    <div class="alert_top alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <script type="text/javascript">
        var host = "{{ config('app.url') }}";
        $(function(){
            $('div.alert_top').delay(1500).slideUp(300);
        })
    </script>
</body>

</html>