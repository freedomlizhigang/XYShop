@extends('pc.layout')

@section('title')
    <title>{{ $seo['title'] }}</title>
    <meta name="keywords" content="{{ $seo['keyword'] }}">
    <meta name="description" content="{{ $seo['describe'] }}">
@endsection

@section('content')
<!-- banner -->
<div class="banner text-center">
    <a href="{{ $sites['url']}}" target="_blank"><img data-original="{{ $sites['static']}}pc/images/ads/b1.jpg" class="center-block img-responsive lazy" alt=""></a>
</div>
<!-- timetobuy -->
<section class="box timetobuy clearfix overh">
    <!-- ttb_l -->
    <div class="ttb_l pull-left overh">
        <h2 class="ttb_t2">
            限时抢购<span class="iconfont icon-time"></span><span class="ttb_time">01:05:30</span>
        </h2>
        <div class="ttb_l_con">
            <h3 class="ttb_t3_1">TOTO</h3>
            <a href="#" class="ttb_l_con_a ts_r center-block mt20">
                <img data-original="{{ $sites['static']}}pc/images/ads/ttb_l_1.png" class="img-responsive lazy center-block" alt="">
            </a>
        </div>
    </div>
    <div class="ttb_r  ttb_r_1 pull-left overh">
        <a class="ttb_r_top clearfix">
            <img data-original="{{ $sites['static']}}pc/images/ads/ad_ttb_l1.jpg" class="img-responsive lazy ts_l pull-right" alt="">
            <h3 class="ttb_t3">全球购</h3>
            <p class="ttb_p">品味诗意生活</p>
        </a>
        <a class="ttb_r_bottom clearfix">
            <img data-original="{{ $sites['static']}}pc/images/ads/ad_ttb_l2.jpg" class="img-responsive lazy ts_l pull-right" alt="">
            <h3 class="ttb_t3">闪电购</h3>
            <p class="ttb_p">岂止是优雅</p>
        </a>
    </div>
    <!-- ttb_l -->
    <div class="ttb_l ttb_l_m pull-left overh">
        <h3 class="ttb_t3">积分换购</h3>
        <p class="ttb_p">潮人必备</p>
        <img data-original="{{ $sites['static']}}pc/images/ads/ad_ttb_m.png" class="img-responsive lazy ts_t ad_ttb_m_img" alt="">
    </div>
    <div class="ttb_r ttb_r_2 pull-left overh">
        <a class="ttb_r_bottom clearfix">
            <img data-original="{{ $sites['static']}}pc/images/ads/ad_ttb_r1.jpg" class="img-responsive lazy ts_l pull-right" alt="">
            <h3 class="ttb_t3">闪电购</h3>
            <p class="ttb_p">岂止是优雅</p>
        </a>
        <a class="ttb_r_top clearfix">
            <img data-original="{{ $sites['static']}}pc/images/ads/ad_ttb_r2.jpg" class="img-responsive lazy ts_l pull-right" alt="">
            <h3 class="ttb_t3">全球购</h3>
            <p class="ttb_p">品味诗意生活</p>
        </a>
    </div>
</section>
<!-- floor -->
<section class="box floor clearfix overh">
    <!-- floor_top -->
    <div class="floor_top clearfix">
        <h2 class="floor_t2 pull-left">品牌特卖<span class="floor_t2_span">知名品牌，特价销售</span></h2>
        <p class="floor_menu pull-right text-right">
            <a href="#">卫浴</a><a href="#">开关</a><a href="#">地板</a><a href="#">墙纸</a><a href="#">瓷砖</a><a href="#">更多 >></a>
        </p>
    </div>
    <!-- floor_middle -->
    <div class="floor_middle clearfix">
        <!-- floor_middle_left -->
        <div class="floor_m_l pull-left pr">
            <div class="floor_m_l_t">
                <h3 class="floor_m_t3">卡贝 恒温花洒</h3>
                <h4 class="floor_m_t4">全铜加厚主体</h4>
                <a href="#" class="floor_m_a">去看看 ></a>
            </div>
            <div class="floor_m_l_b ps">
                <a href="#"><img data-original="{{ $sites['static']}}pc/images/ads/lc_1_l.png" class="img-responsive lazy center-block ts_r ps" alt=""></a>
            </div>
        </div>
        <div class="floor_m_r pull-right clearfix">
            <div class="floor_banner pull-left overh pr">
                <a href="#"><img data-original="{{ $sites['static']}}pc/images/ads/lc_1_m.png" class="img-responsive lazy center-block" alt=""></a>
            </div>
            <ul class="floor_list_good clearfix">
                <li class="pr">
                    <a href="#" class="list_good_img"><img data-original="{{ $sites['static']}}pc/images/img1.png" class="img-responsive lazy center-block ts_t" alt=""></a>
                    <a href="#" class="list_good_font ts_r">
                        <h4 class="list_good_t4">普乐美水槽大单槽</h4>
                        <p class="list_good_p">柔丝表面 加厚槽体</p>
                        <p class="list_good_price">¥1189.00</p>
                    </a>
                    <span class="tags ps">最新上架</span>
                </li>
                <li class="pr">
                    <a href="#" class="list_good_img"><img data-original="{{ $sites['static']}}pc/images/img2.png" class="img-responsive lazy center-block ts_t" alt=""></a>
                    <a href="#" class="list_good_font ts_r">
                        <h4 class="list_good_t4">德式不锈钢P弯</h4>
                        <p class="list_good_p">全铜主体 防臭防堵</p>
                        <p class="list_good_price">¥89.00</p>
                    </a>
                </li>
                <li class="pr">
                    <a href="#" class="list_good_img"><img data-original="{{ $sites['static']}}pc/images/img3.png" class="img-responsive lazy center-block ts_t" alt=""></a>
                    <a href="#" class="list_good_font ts_r">
                        <h4 class="list_good_t4">卡贝铝合金门锁</h4>
                        <p class="list_good_p">晶钻工艺 多层电镀</p>
                        <p class="list_good_price">¥189.00</p>
                    </a>
                </li>
                <li class="pr">
                    <a href="#" class="list_good_img"><img data-original="{{ $sites['static']}}pc/images/img4.png" class="img-responsive lazy center-block ts_t" alt=""></a>
                    <a href="#" class="list_good_font ts_r">
                        <h4 class="list_good_t4">福田点开关 带LED灯</h4>
                        <p class="list_good_p">精致入微 点滴设计</p>
                        <p class="list_good_price">¥23.00</p>
                    </a>
                </li>
                <li class="pr">
                    <a href="#" class="list_good_img"><img data-original="{{ $sites['static']}}pc/images/img5.png" class="img-responsive lazy center-block ts_t" alt=""></a>
                    <a href="#" class="list_good_font ts_r">
                        <h4 class="list_good_t4">拖把池龙头</h4>
                        <p class="list_good_p">德系精工，优质全铜</p>
                        <p class="list_good_price">¥55.00</p>
                    </a>
                </li>
                <li class="pr">
                    <a href="#" class="list_good_img"><img data-original="{{ $sites['static']}}pc/images/img6.png" class="img-responsive lazy center-block ts_t" alt=""></a>
                    <a href="#" class="list_good_font ts_r">
                        <h4 class="list_good_t4">智洁釉马桶</h4>
                        <p class="list_good_p">静音节水</p>
                        <p class="list_good_price">¥1059.00</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- floor_bottom -->
    <div class="floor_bottom clearfix">
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_1.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_2.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_3.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_4.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_5.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_6.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_7.png" class="img-responsive lazy" alt=""></a>
        <a href="#"><img data-original="{{ $sites['static']}}pc/images/brand_8.png" class="img-responsive lazy" alt=""></a>
    </div>
</section>
<!-- like me -->
<section class="like_me box clearfix overh">
    <h2 class="like_t2">猜你喜欢<span class="iconfont icon-like"></span></h2>
    <ul class="list_good_com clearfix mt20">
        <li>
            <a href="#" class="list_good_com_a">
                <img data-original="{{ $sites['static']}}pc/images/good_thumb.png" class="img-responsive lazy" alt="">
                <p class="list_good_com_price">¥1059.00</p>
                <h5 class="list_good_com_t5">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</h5>
                <p class="list_good_com_p text-right">0人付款</p>
            </a>
        </li>
        <li>
            <a href="#" class="list_good_com_a">
                <img data-original="{{ $sites['static']}}pc/images/good_thumb.png" class="img-responsive lazy" alt="">
                <p class="list_good_com_price">¥1059.00</p>
                <h5 class="list_good_com_t5">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</h5>
                <p class="list_good_com_p text-right">0人付款</p>
            </a>
        </li>
        <li>
            <a href="#" class="list_good_com_a">
                <img data-original="{{ $sites['static']}}pc/images/good_thumb.png" class="img-responsive lazy" alt="">
                <p class="list_good_com_price">¥1059.00</p>
                <h5 class="list_good_com_t5">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</h5>
                <p class="list_good_com_p text-right">0人付款</p>
            </a>
        </li>
        <li>
            <a href="#" class="list_good_com_a">
                <img data-original="{{ $sites['static']}}pc/images/good_thumb.png" class="img-responsive lazy" alt="">
                <p class="list_good_com_price">¥1059.00</p>
                <h5 class="list_good_com_t5">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</h5>
                <p class="list_good_com_p text-right">0人付款</p>
            </a>
        </li>
        <li>
            <a href="#" class="list_good_com_a">
                <img data-original="{{ $sites['static']}}pc/images/good_thumb.png" class="img-responsive lazy" alt="">
                <p class="list_good_com_price">¥1059.00</p>
                <h5 class="list_good_com_t5">插电式LED台灯护眼卧室主播补光直播电脑桌大学生用长臂工作超亮</h5>
                <p class="list_good_com_p text-right">0人付款</p>
            </a>
        </li>
    </ul>
</section>


@endsection