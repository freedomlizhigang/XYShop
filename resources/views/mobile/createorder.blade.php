@extends('mobile.layout')

@section('content')

  <!-- 提示 -->
  <div class="tips"><i class="iconfont icon-notification"></i>温馨提示：请仔细核对订单信息后提交订单！</div>
  <!-- 配送地址 -->
  <div class="cart_address pd20 clearfix">
    <i class="iconfont icon-location c_a_left"></i>
    <div class="c_a_info">
    @if(!is_null($default_address))
      <p class="font_md">{{ $default_address->people }} {{ $default_address->phone }}</p>
      <p>{{ $default_address->area.'-'.$default_address->address }}</p>
    @else
      <p class="font_md">请选择收货地址</p>
    @endif
    </div>
    <i class="iconfont icon-right c_a_right"></i>
  </div>
  <!-- 商品 -->
  <section class="sec_cart_goods bgc_f pd20 mt20 clearfix overh pr">
    <ul class="cart_goods clearfix">
      @foreach($goodlists as $c)
      <li>
        <a href="{{ $c->good->url }}" class="c_g_img"><img src="{{ $c->good->thumb }}" height="200" width="200" alt="{{ $c->good_title }}"></a>
        <p><i class="iconfont icon-close"></i>{{ $c->nums }}</p>
      </li>
      @endforeach
    </ul>
    <div class="c_g_nums ps">共{{ $count }}件<i class="iconfont icon-right"></i></div>
  </section>
  <!-- 支付配送 -->
  <div class="bgc_f cart_payship clearfix pr pt20">
    <h4 class="t4_cart">支付配送</h4>
    <div class="pr f-r cart_right">
      <p>在线支付</p>
      <p>工作日、双休日与假日均可送货</p>
      <i class="iconfont icon-right ps"></i>
    </div>
  </div>
  <!-- 积分 -->
  <div class="bgc_f mt20 cart_points pt20 pb20 clearfix pr">
    <h4 class="t4_cart">积分<span class="coupon_can">共{{ $points }}可用</span></h4>
    <div class="pr f-r cart_right">
      <span class="cart_point_title">未使用</span>
      <i class="iconfont icon-right ps c_c_right"></i>
    </div>
  </div>
  <!-- 优惠 -->
  <div class="bgc_f mt20 cart_coupon pt20 pb20 clearfix pr">
    <h4 class="t4_cart">优惠券<span class="coupon_can">{{ $coupon->count() }}张可用</span></h4>
    <div class="pr f-r cart_right">
      <span class="cart_coupon_title">未使用</span>
      <i class="iconfont icon-right ps c_c_right"></i>
    </div>
  </div>
  <!-- 赠品 -->
  @if(!is_null($gift))
  <div class="bgc_f mt20 cart_gift pt20 pb20 clearfix pr">
    <h4 class="t4_cart_2">赠品，不与优惠券同时使用</h4>
    <div class="pd20 clearfix">
      <a href="{{ url('good',['id'=>$gift->good_id]) }}" class="c_g_s_img"><img src="{{ $gift->good->thumb }}" height="200" width="200" alt="{{ $gift->good_title }}"></a>
      <h3 class="c_g_s_title slh">{{ $gift->good_title }}</h3>
    </div>
  </div>
  @endif
  <!-- 备注 -->
  <div class="bgc_f mt20 pd20 clearfix pr">
    <textarea name="shopmark" placeholder="添加备注信息" rows="3" class="mark"></textarea>
  </div>
  <!-- 固定底 -->
  <div class="pos_foot pos_foot_cart">
    <span class="cart_prices color_main">实付款：￥<em class="cart_prices_num font_lg">{{ $total_prices }}</em></span>
    <span class="show_btn_submitorder">提交订单</span>
  </div>
  <!-- 要提交的信息 -->
  <input type="hidden" name="address_id" class="address_id" value="{{ is_null($default_address) ? 0 : $default_address->id }}">
  <input type="hidden" name="ziti" class="ziti" value="0">
  <input type="hidden" name="coupon_id" class="coupon_id" value="0">
  <!-- 地址、优惠券 -->
  <div class="pos_bg hidden"></div>
  <div class="pos_alert_con select_address hidden">
    <i class="pos_close iconfont icon-close"></i>
    <ul>
      @foreach($address as $a)
      <li class="clearfix s_a_li" data-aid="{{ $a->id }}">
        <p class="font_md">{{ $a->people }} {{ $a->phone }}</p>
        <p>{{ $a->address }}</p>
      </li>
      @endforeach
    </ul>
    <a href="{{ url('user/address/add') }}" class="btn_add_address">添加新收货地址</a>
  </div>
  <div class="pos_alert_con select_coupon hidden">
    <i class="pos_close iconfont icon-close"></i>
    <ul class="list_coupon clearfix">
      @foreach($coupon as $c)
      <li class="s_c_li" data-cid="{{ $c->id }}" data-title="{{ $c->title }}">
        <span class="l_c_price f-l">￥{{ $c->lessprice }}</span>
        <span class="l_c_info f-l">满{{ $c->price }}可用</span>
      </li>
      @endforeach
      <li class="s_c_li" data-cid="0" data-title="不使用">
        <span class="l_c_price f-l">不使用</span>
        <span class="l_c_info f-l">不使用</span>
      </li>
    </ul>      
  </div>
  <div class="pos_alert_con select_points hidden">
    <i class="pos_close iconfont icon-close"></i>
    <div class="pure-form pd20 clearfix">

    <select name="points" id="points" class="f-l">
      @foreach($point_select as $c)
      <option value="{{ $c }}">{{ $c }}</option>
      @endforeach
    </select>
    <p class="mt15 f-r">每次使用最少：<i class="color_main">{{ $pointconfig->block }}</i> 分</p>
    </div>
  </div>
  <script>
    $(function(){
      // 提交订单功能
      $('.show_btn_submitorder').on('click',function() {
        if(ajaxLock == 0)return false;
        var that = $(this);
        var aid = $('.address_id').val();
        var ziti = $('.ziti').val();
        var cid = '{{ $cid_str }}';
        var yid = $('.coupon_id').val();
        var mark = $('.mark').val();
        var point = $('#points').val();
        ajaxLock = 0;
        $.post( host +'api/good/addorder',{cid:cid,uid:uid,aid:aid,ziti:ziti,yid:yid,mark:mark,point:point},function(d){
          var ss = jQuery.parseJSON(d);
          // console.log(ss);
          if (ss.code == 1) {
            $('.alert_msg').text('创建订单成功，请及时支付~').slideToggle().delay(1500).slideToggle();
            var oid = ss.msg;
            setTimeout(function(){
                // 跳转到订单页面
                window.location.href = "{{ url('pay') }}" + '/' + oid;
            },1500);
          }
          else
          {
            // alert(ss.msg);
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
          }
          ss = null;
          ajaxLock = 1;
          return;
        }).error(function() {
          ajaxLock = 1;
          return;
        });
      });
      // 地址
      $('.cart_address').click(function(){
        $('.pos_bg,.select_address').fadeIn();
      });
      $('.s_a_li').click(function(){
        var that = $(this);
        var str = that.html();
        $('.c_a_info').html(str);
        $('.address_id').val(that.attr('data-aid'));
        $('.pos_bg,.pos_alert_con').fadeOut();
      });
      // 优惠
      $('.cart_coupon').click(function(){
        $('.pos_bg,.select_coupon').fadeIn();
      });
      $('.s_c_li').click(function(){
        var that = $(this);
        $('.cart_coupon_title').text(that.attr('data-title'));
        $('.coupon_id').val(that.attr('data-cid'));
        $('.pos_bg,.pos_alert_con').fadeOut();
      });
      // 积分
      $('.cart_points').click(function(){
        $('.pos_bg,.select_points').fadeIn();
      });
      $('#points').change(function(){
        var that = $(this);
        $('.cart_point_title').text(that.val() + '分');
        $('.cart_coupon_title').text('未使用');
        $('.coupon_id').val('0');
        $('.pos_bg,.pos_alert_con').fadeOut();
      });
    })
  </script>
@endsection