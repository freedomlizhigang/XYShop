<!-- top_info -->
<div class="top_info">
    <div class="box clearfix">
        <div class="pull-left top_info_left top_address">
            北京 <span class="iconfont icon-locationfill"></span>
        </div>
        <div class="pull-right top_info_right text-right">
            @if(session()->has('member'))
            <a href="{{ url('/user/center') }}" class="top_user_info">欢迎回来：<em>{{ isset(session('member')->nickname) ? session('member')->nickname : session('member')->username }}</em></a>
            @else
            <a href="{{ url('/user/login') }}">请登录</a>|<a href="{{ url('/user/register') }}">免费注册</a>
            @endif
            |<a href="{{ url('/order/list') }}">我的订单</a>|<a href="{{ url('/help') }}">帮助中心</a>|<a href="javascript:;">手机商城</a>
        </div>
    </div>
</div>
<!-- logo + search -->
<div class="bg_usercenter_top">
<header class="head_usercenter clearfix box">
    <!-- logo -->
    <div class="logo_usercenter h-img pull-left">
        <a href="{{ $sites['url']}}"><img src="{{ $sites['static']}}pc/images/logo_u.png" alt="{{ cache('config')['title'] }}"></a>
    </div>
    <!-- search -->
    <div class="search_top pull-left">
        <form action="#" class="form-inline form_search_top clearfix">
            <input type="text" class="form-control form_search_top_key pull-left" placeholder="输入关键字查找热门商品..." />
            <div class="form_search_top_btn pull-left iconfont icon-search"></div>
        </form>
    </div>
    <!-- cart -->
    <div class="cart pull-right mt10">
        <h3 class="cart_t3 pr"><a href="{{ url('shop/cart') }}" target="_blank"><span class="iconfont icon-cart"></span> 我的购物车 <span class="cart_nums ps">0</span></a></h3>
    </div>
</header>
</div>