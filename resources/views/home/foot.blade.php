<!-- 导航 -->
<nav class="navbar navbar-fixed-bottom menu_main">
    <ul class="container-fluid class">
        <li><a href="{{ url('/') }}"@if($info->pid == 0) class="active" @endif>
            <span class="glyphicon glyphicon-home"></span>
            <p>首页</p>
        </a></li>
        <li><a href="{{ url('/shop/goodcate') }}"@if($info->pid == 2) class="active" @endif>
            <span class="glyphicon glyphicon-menu-hamburger"></span>
            <p>分类</p>
        </a></li>
        <li><a href="{{ url('/shop/cart') }}"@if($info->pid == 3) class="active" @endif>
            <span class="glyphicon glyphicon-shopping-cart"></span>
            <p>购物车</p>
        </a></li>
        <li><a href="{{ url('/user/center') }}"@if($info->pid == 4) class="active" @endif>
            <span class="glyphicon glyphicon-user"></span>
            <p>用户中心</p>
        </a></li>
    </ul>
</nav>
<!-- footer -->
<footer class="foot container-fluid text-center">
    <!-- <ul class="foot_nav">
        @foreach(App::make('tag')->cate(0,8) as $c)
        <li><a href="{{ url('cate',['url'=>$c->url]) }}">{{ $c->name }}</a></li>
        @endforeach
    </ul> -->
    <p>版权所有：{{ cache('config')['sitename'] }} 冀ICP备16022533号</p>
</footer>