<!-- menu -->
<div class="menu_bg">
    <nav class="menu box clearfix">
        <!-- cates+drop -->
        <div class="menu_drop pull-left pr">
            <h2 class="menu_t2">全部商品分类<span class="iconfont icon-cascades pull-right menu_t2_icon"></span></h2>
            <div class="menu_drop_con pr @if(!isset($ishome) || !$ishome) dn @endif">
                <ul class="menu_drop_list">
                @foreach(app('tag')->getMenu() as $c)
                    <li>
                        <h3 class="menu_drop_t3"><span class="iconfont icon-home"></span><a href="{{ url('list',['id'=>$c['id']]) }}">{{ $c['name'] }}</a></h3>
                        <p class="menu_drop_p">
                            @foreach($c['child_menu'] as $cc)
                            @if($loop->index < 3)
                            <a href="{{ url('list',['id'=>$cc['id']]) }}">{{ $cc['name'] }}</a>@if($loop->index < 2)/@endif
                            @endif
                            @endforeach
                        </p>
                        <div class="menu_drop_right dn ps">
                            <div class="menu_drop_right_h3 clearfix">
                            @foreach($c['child_menu'] as $cc)
                                <a href="{{ url('list',['id'=>$cc['id']]) }}" class="menu_drop_right_h3_link">{{ $cc['name'] }}<i class="iconfont icon-right"></i></a>
                            @endforeach
                            </div>
                            @foreach($c['child_menu'] as $cc)
                            <dl class="menu_drop_dl clearfix">
                                <dt class="pull-left">
                                    <a href="{{ url('list',['id'=>$cc['id']]) }}">{{ $cc['name'] }}<i class="iconfont icon-right"></i></a>
                                </dt>
                                <dd class="pull-left">
                                    @foreach($cc['sub_menu'] as $ccs)
                                    <a href="{{ url('list',['id'=>$ccs['id']]) }}">{{ $ccs['name'] }}</a>
                                    @endforeach
                                </dd>
                            </dl>
                            @endforeach
                        </div>
                    </li>
                @endforeach
                </ul>
                <!-- ad_menu -->
                <a href="{{ $sites['url']}}" target="_blank" class="ad_menu_left mt10 overh center-block"><img data-original="{{ $sites['static']}}pc/images/ads/ad_menu.jpg" class="center-block img-responsive ts_r lazy" alt=""></a>
            </div>
        </div>
        <!-- nav -->
        <ul class="navitems clearfix pull-left">
            <li><a href="{{ $sites['url']}}">首页</a></li>
            <li><a href="#">秒杀</a></li>
            <li><a href="#">优惠券</a></li>
            <li><a href="#">闪购</a></li>
            <li><a href="#">拍卖</a></li>
            <li><a href="#">服装城</a></li>
            <li><a href="#">超市</a></li>
            <li><a href="#">生鲜</a></li>
        </ul>
        <!-- ad_menu -->
        <a href="{{ $sites['url']}}" target="_blank" class="pull-right ad_mainmenu_right">
            <img data-original="{{ $sites['static']}}pc/images/ads/ad_menu.png" width="190" height="40" alt="" class="lazy">
        </a>
    </nav>
</div>