@extends('mobile.layout')

@section('content')

  <!-- 提示 -->
  <div class="tips"><i class="iconfont icon-notification"></i>温馨提示：请补充订单信息！</div>
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
    <div class="c_g_nums ps">共{{ count($goodlists) }}件<i class="iconfont icon-right"></i></div>
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
  <input type="hidden" name="oid" class="oid" value="{{ $oid }}">
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
  <script>
    $(function(){
      // 提交订单功能
      $('.show_btn_submitorder').on('click',function() {
        if(ajaxLock == 0)return false;
        var that = $(this);
        var aid = $('.address_id').val();
        var ziti = $('.ziti').val();
        var oid = $('.oid').val();
        var mark = $('.mark').val();
        ajaxLock = 0;
        $.post( host +'api/good/editorder',{uid:uid,aid:aid,ziti:ziti,mark:mark,oid:oid},function(d){
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
    })
  </script>
@endsection