@extends('mobile.layout')

@section('content')
  <!-- 产品相册 -->
  <section class="sec_album clearfix overh">
      <div class="touchslider touchslider-shop pr">
        <div class="touchslider-viewport pr">
          <div style="width: 10000px;">
          @foreach(explode(',',$good->album) as $ga)
          <div class="touchslider-item"><img src="{{ $ga }}" width="750" height="635" alt="{{ $good->title }}"></div>
          @endforeach
          </div>
        </div>
        <div class="touchslider-nav ps">
          @foreach(explode(',',$good->album) as $ga)
          <a class="touchslider-nav-item @if($loop->first) touchslider-nav-item-current @endif"></a>
          @endforeach
        </div>
      </div>
    <script>
      $(function(){
        $(".touchslider-shop").touchSlider({mouseTouch: true,autoplay:true,delay:3500});
      })
    </script>
  </section>
  <!-- 产品信息 -->
  <section class="goodinfo clearfix mt20 bgc_f pd20">
    <h1 class="good_title">
      @if($good->prom_tag != '')
      <i class="label label-red">{{ $good->prom_tag }}</i>
      @endif
      @if($good->new_tag != '')
      <i class="label label-red">{{ $good->new_tag }}</i>
      @endif
      @if($good->pos_tag != '')
      <i class="label label-red">{{ $good->pos_tag }}</i>
      @endif
      @if($good->hot_tag != '')
      <i class="label label-red">{{ $good->hot_tag }}</i>
      @endif
      @if($prom_val != '')
      <i class="label label-red">{{ $prom_val }}</i>
      @endif
      {{ $good->title }}</h1>
    <div class="g_i_prices mt10 clearfix">
      <span class="gi_price font_lg color_main f-l">￥<i class="shop_price">{{ $good->shop_price }}</i></span>
      <span class="label label-hui f-r">库存：<i class="color_main store">{{ $good->store }}+</i></span>
      <span class="label label-hui f-r">销量：<i class="color_main">{{ $good->sales }}+</i></span>
    </div>
    @if($prom_title != '')
    <p class="mt10 color_fen">
      {{ $prom_title }}
    </p>
    @endif
    <p class="ti_title color_9">{{ $good->describe }}</p>
  </section>
  <!-- 规格 -->
  <section class="good_spec mt20 clearfix bgc_f pd20">
    <h4 class="t4_show color_9">已选</h4>
    <div class="g_s_info pos_show">
      <span class="g_s_name"></span><span><i class="g_s_num">1</i>件</span>
    </div>
  </section>
  <!-- 领券 -->
  <section class="good_coupon mt20 clearfix bgc_f pd20">
    <h4 class="t4_show color_9">领券</h4>
    <ul class="list_coupon mt20 clearfix">
      @foreach($coupon as $c)
      <li>
        <span class="l_c_price f-l">￥{{ $c->lessprice }}</span>
        <span class="l_c_btn f-r" data-cid="{{ $c->id }}">领取</span>
        <span class="l_c_info f-l">满{{ $c->price }}可用</span>
      </li>
      @endforeach
      <script>
        $(function(){
          $('.l_c_btn').click(function(){
            if(!ajaxLock)return false;
            var cid = $(this).attr('data-cid');
            var url = "{{ url('api/coupon/get') }}";
            ajaxLock = 0;
            $.post(url,{cid:cid,uid:uid},function(d){
              var ss = jQuery.parseJSON(d);
              if (ss.code == '1') {
                // console.log(ss);
                $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
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
    </ul>
  </section>
  <!-- ad -->
  <div class="ads mt20">
    @foreach(app('tag')->ad(4,1,1) as $k => $c)
      <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="570" height="180" alt="{{ $c->title }}"></a>
    @endforeach
  </div>
  <!-- 店 -->
  <section class="sec_dian bgc_f clearfix mt20 overh pd20">
    <a href="{{ url('/') }}" class="s_d_logo f-l"><img src="{{ $sites['static']}}mobile/images/logo_s.png" width="180" height="60" alt=""></a>
    <div class="s_d_info">
      <h2><a href="{{ url('/') }}">ThinkPad京东官方旗舰店</a></h2>
      <p><a href="{{ url('/') }}">最好的手机电脑官方商城</a></p>
    </div>
  </section>
  <!-- 详情 -->
  <section class="clearfix overh sec_show pd20 bgc_f">
    {!! $good->content !!}
  </section>

  <!-- 规格选项 -->
  <div class="pos_bg hidden"></div>
  <div class="pos_alert_con hidden">
    <i class="pos_close iconfont icon-close"></i>
    <div class="clearfix g_s_select">
      <!-- 规格开始 -->
      @if(count($filter_spec) > 0)
      <div class="g_i_r_spec mt20">
      @foreach($filter_spec as $ks => $gs)
        <dl class="g_spec clearfix">
          <dt>{{ $ks }}</dt>
          <dd>
            @foreach($gs as $kks => $ggs)
            <a href="javascript:;" onclick="select_filter(this)" @if($kks == 0) class="active"@endif data-item_id="{{ $ggs['item_id'] }}"><input type="radio" name="goods_spec[{{$ks}}]" class="hidden"@if($kks == 0) checked="checked"@endif value="{{ $ggs['item_id'] }}">{{ $ggs['item'] }}</a>
            @endforeach
          </dd>
        </dl>
      @endforeach
      </div>
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
            var price = '{{$good->shop_price}}'; // 商品起始价
            var store = '{{$good->store}}'; // 商品起始库存
            var spec_goods_price = {!! $good_spec_price !!};  // 规格 对应 价格 库存表 //alert(spec_goods_price['28_100']['price']);
            // 如果有属性选择项
            if(spec_goods_price != null && spec_goods_price !='')
            {
                goods_spec_arr = new Array();
                $('input[name^="goods_spec"]:checked').each(function(){
                    goods_spec_arr.push($(this).val());
                });
                var spec_key = '_' + goods_spec_arr.sort(sortNumber).join('_') + '_';  //排序后组合成 key
                // console.log(spec_key);
                $('.spec_key').val(spec_key);
                price = spec_goods_price[spec_key]['price']; // 找到对应规格的价格
                store = spec_goods_price[spec_key]['store']; // 找到对应规格的库存
            }
            $('.store').html(store);    //对应规格库存显示出来
            $('.shop_price').html(price); // 变动价格显示
            $('.price').val(price); // 提交时价格
            $('.g_s_name').text(spec_goods_price[spec_key]['item_name']);
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
    <div class="g_nums clearfix">
      <i class="f-l">数量</i>
      <span class="g_num_con">
        <span class="num_dec">-</span>
        <span class="num_num">1</span>
        <span class="num_inc">+</span>
      </span>
    </div>
    <div class="btn-submit mt20">确定</div>
  </div>
  <!-- 购物车要提交的表单内容 -->
  <div class="submit_con hidden">
    <form action="javascript:;" class="form_data">
      <!-- ID -->
      <input type="text" name="good_id" class="good_id" value="{{ $good->id }}">
      <!-- 规格 -->
      <input type="text" name="spec_key" class="spec_key" value="">
      <!-- 数量 -->
      <input type="text" name="nums" class="nums" value="1">
      <!-- 价格 -->
      <input type="text" name="shop_price" class="price" value="{{ $good->shop_price }}">
    </form>
    <!-- 购物车、直接买 -->
    <script>
      $(function(){
        // 弹出确认的按钮
        $(".btn-addcart ,.pos_show").click(function(){
          $(".pos_bg,.pos_alert_con").fadeIn();
          $('.btn-submit').removeClass('btn-createorder-submit').addClass('btn-addcart-submit');
        });
        $(".btn-createorder").click(function(){
          $(".pos_bg,.pos_alert_con").fadeIn();
          $('.btn-submit').removeClass('btn-addcart-submit').addClass('btn-createorder-submit');
        });
        // 添加到购物车
        $(".pos_alert_con").on("click",".btn-addcart-submit",function(){addcart(0);});
        // 直接购买
        $(".pos_alert_con").on("click",".btn-createorder-submit",function(){addcart(1);});
      })
      function addcart(iscreate)
      {
        if(!ajaxLock)return false;
        var sid = "{{ session()->getId() }}";
        var gid = $('.good_id').val();
        var num = $('.nums').val();
        var spec_key = $('.spec_key').val();
        var gp = $('.price').val();
        var type = 'promotion';
        var url = "{{ url('api/good/addcart') }}";
        ajaxLock = 0;
        $.post(url,{gid:gid,spec_key:spec_key,num:num,gp:gp,sid:sid,uid:uid,type:type},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == '1') {
            // 成功以后跳转到购物车页面上
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            $(".pos_bg,.pos_alert_con").fadeOut();
            // alert(ss.msg);
            if (iscreate) {
              setTimeout(function(){
                window.location.href = "{{ url('cart') }}";
              },2000);
            }
          }
          else
          {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            // alert(ss.msg);
            if (ss.code == '2') {
              setTimeout(function(){
                window.location.href = "{{ url('user/login') }}";
              },2000);
            }
          }
          ajaxLock = 1;
          return;
        }).error(function() {
          ajaxLock = 1;
          return;
        });
      }
    </script>
  </div>
  <div class="pos_foot">
    <a href="{{ url('/') }}" class="p_f_link iconfont icon-home"><em>首页</em></a>
    <a href="{{ url('cart') }}" class="p_f_link iconfont icon-cart"><em>购物车</em></a>
    <span class="btn-createorder">直接购买</span>
    <span class="btn-addcart">加入购物车</span>
  </div>
  <!-- 分享 -->
  @include('mobile.common.share')
@endsection