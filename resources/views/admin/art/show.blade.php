<!doctype html>
<html lang="zh-cn">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>{{ $info->title }}</title>
    <meta name="author" content="李潇喃：www.muzisheji.com" />
    <!-- IE最新兼容 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 国产浏览器高速/微信开发不要用 -->
    <meta name="renderer" content="webkit">
     
    <!-- 移动设备禁止缩放 -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="stylesheet" href="{{ $sites['static']}}reset.css">
</head>

<body>
    @if(session('message'))
    <div class="alert">
        {{ session('message') }}
    </div>
    <script type="text/javascript">
        setTimeout(function(){
            document.querySelector('.alert').style.display = 'none';
        }, 2500);
    </script>
    @endif
   <div class="art_box">
        <h1 class="art_h1">{{ $info->title }}</h1>
        <div class="art_times_cate">{{ date('Y-m-d H:i:s',$info->inputtime) }} {{ $info->cate->name }}</div>
        <div class="art_con">
           {!! $info->content !!}
        </div>
        <div class="source">
            来源：<a href="{{ $info->url }}">{{ $info->source }}</a>
        </div>
   </div>
</body>

</html>