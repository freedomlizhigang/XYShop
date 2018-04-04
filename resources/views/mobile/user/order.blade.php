@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <section class="tab_order bgc_f clearfix overh">
    <a href="{{ url('user/orderlist') }}" class="t_o_link @if($sid == '') active @endif">全部</a>
    <a href="{{ url('user/orderlist',['sid'=>1]) }}" class="t_o_link @if($sid == '1') active @endif">待付款</a>
    <a href="{{ url('user/orderlist',['sid'=>2]) }}" class="t_o_link @if($sid == '2') active @endif">待收货</a>
    <a href="{{ url('user/orderlist',['sid'=>3]) }}" class="t_o_link @if($sid == '3') active @endif">已完成</a>
    <a href="{{ url('user/orderlist',['sid'=>4]) }}" class="t_o_link @if($sid == '4') active @endif">已取消</a>
  </section>
  @if($list->count() == 0)
  <p class="pd20 bgc_f">暂时还没有订单啊~</p>
  @else
  <ul class="list_order clearfix overh">
    @foreach($list as $l)
    <li class="mt20 clearfix overh bgc_f pl20 pr20">
      <!-- top -->
      <header class="l_o_top clearfix pt20 pb20">
        <span class="l_o_t_title"><i class="iconfont icon-evaluate color_main"></i><a href="{{ url('user/orderinfo',['id'=>$l->id]) }}">单号：{{ $l->order_id }}</a></span>
        <span class="l_o_t_right f-r">
          @if($l->orderstatus == '1')
            @if($l->paystatus == 0)
            <a href="{{ url('pay',['oid'=>$l->id]) }}" class="label label-red">去支付</a>
            @endif
            @if($l->shipstatus == 1 && $l->paystatus == 1)
            <i class="label label-red order_confirm" data-uid="{{ $l->user_id }}" data-oid="{{ $l->id }}">确认收货</i>
            @endif
            @if($l->shipstatus == 0 && $l->paystatus == 1)
            <i class="label label-hui">待发货</i>
            @endif
          @elseif($l->orderstatus == '2')
          <i class="color_main">已完成</i>
          @else
          <i class="color_main">已取消</i>
          @endif
        </span>
        @if($l->shipstatus == 1 && $l->paystatus == 1)
        <p class="mt10 color_9">发货时间：{{ $l->ship_at }}</p>
        @endif
      </header>
      @if($l->good->count() == 1)
      <!-- goods -->
      <ul class="l_o_goods clearfix">
        @foreach($l->good as $lg)
        <li class="clearfix mb20">
          <a href="{{ $lg->good->url }}" class="l_o_g_img"><img data-original="{{ $lg->good->thumb }}" class="lazy" height="200px" width="200px" alt="{{ $lg->good_title }}"></a>
          <h3 class="l_o_g_title slh">{{ $lg->good_title }}</h3>
        </li>
        @endforeach
      </ul>
      @else
      <!-- goods -->
      <ul class="l_o_goods l_o_goods_2 clearfix">
        @foreach($l->good as $lg)
        <li>
          <a href="{{ $lg->good->url }}" class="l_o_g_img"><img data-original="{{ $lg->good->thumb }}" class="lazy" height="200px" width="200px" alt="{{ $lg->good_title }}"></a>
        </li>
        @endforeach
      </ul>
      @endif
      <!-- footer -->
      <footer class="l_o_footer clearfix pt20 pb20">
        <span class="l_o_f_price">实付款：￥{{ $l->total_prices }}</span>
        @if($l->orderstatus == '1' && $l->shipstatus == 0)
        <span class="label label-hui f-r order_cancel" data-uid="{{ $l->user_id }}" data-oid="{{ $l->id }}">取消订单</span>
        @endif
      </footer>
    </li>
    @endforeach
  </ul>
  @endif
  <div class="pages">
      {!! $list->links() !!}
  </div>
  <script>
    $(function(){
      // 确认收货
      $('.order_confirm').click(function(){
        var that = $(this);
        var oid = that.attr('data-oid');
        if (uid !== that.attr('data-uid')) {
          $('.alert_msg').text('不是自己的不要操作！').slideToggle().delay(1500).slideToggle();
          return;
        }
        var url = "{{ url('api/good/confirmorder') }}";
        ajaxLock = 0;
        $.post(url,{oid:oid},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == '1') {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            // 刷新页面
            setTimeout(function(){
              window.location.href = "{{ url('user/orderlist') }}" + '/3';
            },2000);
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
      // 取消订单
      $('.order_cancel').click(function(){
        var that = $(this);
        var oid = that.attr('data-oid');
        if (uid !== that.attr('data-uid')) {
          $('.alert_msg').text('不是自己的不要操作！').slideToggle().delay(1500).slideToggle();
          return;
        }
        var url = "{{ url('api/good/removeorder') }}";
        ajaxLock = 0;
        $.post(url,{oid:oid},function(d){
          var ss = jQuery.parseJSON(d);
          if (ss.code == '1') {
            $('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
            // 刷新页面
            setTimeout(function(){
              location.reload();
            },2000);
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