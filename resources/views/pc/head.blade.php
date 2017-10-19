<!-- ad_top -->
    <div class="ad_top text-center">
        <a href="{{ $sites['url']}}" target="_blank"><img data-original="{{ $sites['static']}}pc/images/ads/ad_top.jpg" class="center-block ad_top_img lazy" alt=""></a>
    </div>
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
    <header class="head box pr">
        <!-- logo -->
        <div class="logo ps">
            <h1 class="logo_t1">
                <a href="{{ $sites['url']}}"><img src="{{ $sites['static']}}pc/images/logo.png" alt="{{ cache('config')['title'] }}"></a>
            </h1>
            <h2 class="logo_t2">希夷，多快好省</h2>
        </div>
        <!-- search -->
        <div class="search_top pull-left">
            <form action="#" class="form-inline form_search_top clearfix">
                <input type="text" class="form-control form_search_top_key pull-left" placeholder="输入关键字查找热门商品..." />
                <div class="form_search_top_btn pull-left iconfont icon-search"></div>
            </form>
            <p class="search_keywords mt5">
                <a href="#">魅族Pro7</a><a href="#">机械革命</a><a href="#">园艺肥</a><a href="#">6折清仓</a><a href="#">抽奖赢空调</a><a href="#">衣服烘干机</a><a href="#">游戏显卡</a>
            </p>
        </div>
        <!-- cart -->
        <div class="cart pull-right">
            <h3 class="cart_t3 pr"><a href="{{ url('shop/cart') }}" target="_blank"><span class="iconfont icon-cart"></span> 我的购物车 <span class="cart_nums ps">0</span></a></h3>
        </div>
    </header>