@extends('mobile.layout')

@section('content')
  <!-- 产品相册 -->
  <section class="sec_album clearfix overh">
    @foreach(explode(',',$good->album) as $ga)
    <img src="{{ $ga }}" width="750px" height="635px" alt="{{ $good->title }}">
    @endforeach
  </section>

  <!-- 团 -->
  <!-- <div class="tuaninfo clearfix overh bgc_f">
    <div class="ti_left f-l overh">
      <em class="font_lg">团购</em>
      <i>数量：200</i>
    </div>
    <div class="ti_right overh f-l">
      <div class="ti_r_t overh bgc_m">
        2017-10-12 10:00 至 2017-10-12 24:00
      </div>
      <div class="ti_r_b overh bgc_sr">
        <del>原价：￥10000.00</del> 团购价：<span class="font_md">￥8999.00</span>
      </div>
    </div>
  </div> -->
  <!-- 产品信息 -->
  <section class="goodinfo clearfix mt20 bgc_f pd20">
    <h1 class="good_title">{{ $good->title }}</h1>
    <div class="g_i_prices mt10 clearfix">
      <span class="gi_price font_lg color_main f-l">￥{{ $good->shop_price }}</span>
      <span class="label label-hui f-r">库存：<i class="color_main">{{ $good->sales }}+</i></span>
      <span class="label label-hui f-r">销量：<i class="color_cheng">{{ $good->store }}+</i></span>
    </div>
    <p class="ti_title color_9">{{ $good->describe }}</p>
  </section>
  <!-- 规格 -->
  <section class="good_spec mt20 clearfix bgc_f pd20">
    <h4 class="t4_show color_9">已选</h4>
    <div class="g_s_info">
      <span class="g_s_name">冰蓝</span><span><i class="g_s_num">1</i>件</span>
    </div>
    <div class="clearfix g_s_select">
      <!-- 规格开始 -->
      @if(count($filter_spec) > 0)
      @foreach($filter_spec as $ks => $gs)
        <dl class="g_spec clearfix">
          <dt>{{ $ks }}</dt>
          <dd>
            @foreach($gs as $kks => $ggs)
            <a href="javascript:;" onclick="select_filter(this)" @if($kks == 0) class="active"@endif data-item_id="{{ $ggs['item_id'] }}"><input type="radio" name="goods_spec[{{$ks}}]" class="hidden"@if($kks == 0) checked="checked"@endif value="{{ $ggs['item_id'] }}">{{ $ggs['item'] }}</a>
            @endforeach
            <input type="hidden" name="spec_key" class="spec_key" value="">
          </dd>
        </dl>
      @endforeach
      <script>
        $(function(){
          get_goods_price();
        })
        /**
         * 切换规格
         */
        function select_filter(obj)
        {
            $(obj).addClass('active').siblings('a').removeClass('active');
            $(obj).children('input').prop('checked','checked');
            $(obj).siblings('a').children('input').attr('checked',false);// 让隐藏的 单选按钮选中
            // 更新商品价格
            get_goods_price();
        }
        function get_goods_price()
        {
            var price = "{{$good->shop_price}}"; // 商品起始价
            var store = "{{$good->store}}"; // 商品起始库存
            var spec_goods_price = {!! $good_spec_price !!};  // 规格 对应 价格 库存表 //alert(spec_goods_price['28_100']['price']);
            // 如果有属性选择项
            if(spec_goods_price != null && spec_goods_price !='')
            {
                goods_spec_arr = new Array();
                $("input[name^='goods_spec']:checked").each(function(){
                    goods_spec_arr.push($(this).val());
                });
                var spec_key = '_' + goods_spec_arr.sort(sortNumber).join('_') + '_';  //排序后组合成 key
                // console.log(spec_key);
                $(".spec_key").val(spec_key);
                price = spec_goods_price[spec_key]['price']; // 找到对应规格的价格
                store = spec_goods_price[spec_key]['store']; // 找到对应规格的库存
            }
            $('#store').html(store);    //对应规格库存显示出来
            $(".price").html(price); // 变动价格显示
            $("input[name='gp']").val(price);
        }
        /***用作 sort 排序用*/
        function sortNumber(a,b)
        {
            return a - b;
        }
        </script>
      @endif
      <!-- 规格结束 -->
    </div>
  </section>
  <!-- 领券 -->
  <section class="good_coupon mt20 clearfix bgc_f pd20">
    <h4 class="t4_show color_9">领券</h4>
    <ul class="list_coupon mt20 clearfix">
      <li>
        <span class="l_c_price f-l">￥200</span>
        <span class="l_c_btn f-r">领取</span>
        <span class="l_c_info f-l">满4000可用</span>
      </li>
      <li>
        <span class="l_c_price f-l">￥200</span>
        <span class="l_c_info f-l">满4000可用</span>
        <span class="l_c_btn f-r">领取</span>
      </li>
      <li>
        <span class="l_c_price f-l">￥200</span>
        <span class="l_c_info f-l">满4000可用</span>
        <span class="l_c_btn f-r">领取</span>
      </li>
    </ul>
  </section>
  <!-- ad -->
  <div class="ads mt20">
    @foreach(app('tag')->ad(4,1,1) as $k => $c)
      <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="570px" height="180px" alt="{{ $c->title }}"></a>
    @endforeach
  </div>
  <!-- 店 -->
  <section class="sec_dian bgc_f clearfix mt20 overh pd20">
    <a href="{{ url('/') }}" class="s_d_logo f-l"><img src="{{ $sites['static']}}home/images/logo_s.png" width="180px" height="60px" alt=""></a>
    <div class="s_d_info">
      <h2><a href="{{ url('/') }}">ThinkPad京东官方旗舰店</a></h2>
      <p><a href="{{ url('/') }}">最好的手机电脑官方商城</a></p>
    </div>
  </section>
  <!-- 详情 -->
  <section class="clearfix overh sec_show pd20">
    {!! $good->content !!}
  </section>


  <!-- 底 -->
  @include('mobile.common.footer')
  <div class="pos_foot">
    <a href="{{ url('/') }}" class="p_f_link iconfont icon-home"><em>首页</em></a>
    <a href="{{ url('cart') }}" class="p_f_link iconfont icon-cart"><em>购物车</em></a>
    <span class="show_btn_addcart">加入购物车</span>
    <span class="show_btn_createorder">直接购买</span>
  </div>
@endsection