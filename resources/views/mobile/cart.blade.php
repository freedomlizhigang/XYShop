@extends('mobile.layout')

@section('content')

  <!-- 提示 -->
  <div class="tips"><i class="iconfont icon-notification"></i>温馨提示：请仔细核对商品后结算订单！</div>
  <!-- 商品 -->
  <section class="sec_cart_goods bgc_f pd20 mt20 clearfix overh pr">
    <ul class="cart_good_select clearfix">
      @if(count($goodlists) == 0)
      <li class="clearfix mb20">
        <h3 class="c_g_s_title slh">购物车是空的，先去购物吧！</h3>
      </li>
      @endif
      @foreach($goodlists as $c)
      <li class="clearfix mb20 c_g_s_li_{{ $c->id }}">
        <header class="clearfix">
          <div class="select_input">
            <input type="checkbox" name="cart_id[]" checked="checked" class="cart_checkbox" value="{{ $c->id }}" >
          </div>
          <div class="change_nums clearfix mt10">
            <div class="f-l color_main">￥<i class="one_total_price total_price_{{ $c->id }}" data-price="{{ $c->total_prices }}">{{ $c->total_prices }}</i></div>
            <input type="hidden" min="1" value="{{ $c->nums }}" data-uid="{{ session('member')->id }}" data-cid="{{ $c->id }}" data-price="{{ $c->price }}" class="form-control input-nums change_cart cart_num_{{ $c->id }}">
            <span class="num_plus iconfont icon-add1" data-cid="{{ $c->id }}"></span>
            <span class="num_nums cart_num_cart_{{ $c->id }}">{{ $c->nums }}</span>
            <span class="num_reduce iconfont icon-move" data-cid="{{ $c->id }}"></span>
          </div>
        </header>
        <section class="mt20 clearfix">
          <a href="{{ $c->good->url }}" class="c_g_s_img"><img src="{{ $c->good->thumb }}" height="200" width="200" alt="{{ $c->good_title }}"></a>
          <h3 class="c_g_s_title slh">{{ $c->good_title }}</h3>
          @if($c->good_spec_name != '')<p class="f-l label mt10">{{ $c->good_spec_name }}</p>@endif
          <!-- 删除 -->
          <span class="c_g_s_del f-r color_9 iconfont icon-delete mt10 pr20" data-cid="{{ $c->id }}"></span>
        </section>
      </li>
      @endforeach
    </ul>
  </section>
  <!-- 固定底 -->
  <div class="pos_foot pos_foot_cart">
    <span class="cart_prices color_main">总额：￥<em class="cart_prices_num font_lg">{{ $total_prices }}</em></span>
    <span class="show_btn_tosubmit">去结算<span class="font_sm">(<i class="cart_total_nums"></i>件)</span></span>
  </div>
  <script>
    $(function(){
      // 取数量
      cartnum(uid);
      // 提交到结算页面
      $('.show_btn_tosubmit').on('click',function(){
        if(ajaxLock == 0)return false;
        var cid = '.';
        $('.cart_checkbox').each(function(){
          var that = $(this);
          var id = that.val();
          // 判断是选中还是没选中
          if (that.is(':checked')) {
            cid += id + '.';
          }
        });
        ajaxLock = 0;
        $.post(host+'createorder',{cid:cid},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == 1) {
            $('.alert_msg').text('提交成功！').slideToggle().delay(1500).slideToggle();
            setTimeout(function(){
              window.location.href = "{{ url('createorder') }}" + "?rid=" + ss.msg;
            },1500);
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
      // 删除购物车
      $('.c_g_s_del').click(function(){
        if(ajaxLock == 0)return false;
        var that = $(this);
        var cid = that.attr('data-cid');
        ajaxLock = 0;
        $.post(host+'api/good/removecart',{cid:cid},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == 1) {
            // 重新取购物车数量，计算总价
            // 删除对应的结构
            $('.c_g_s_li_' + cid).remove();
            total_prices();
            cartnum(uid);
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
@endsection