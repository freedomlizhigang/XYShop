@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <section class="tab_order bgc_f clearfix overh">
    <a href="{{ url('user/orderlist') }}" class="t_o_link @if($sid == '') active @endif">全部</a>
    <a href="{{ url('user/orderlist',['sid'=>1]) }}" class="t_o_link @if($sid == '1') active @endif">待付款</a>
    <a href="{{ url('user/orderlist',['sid'=>2]) }}" class="t_o_link @if($sid == '2') active @endif">待收货</a>
    <a href="{{ url('user/orderlist',['sid'=>3]) }}" class="t_o_link @if($sid == '3') active @endif">已完成</a>
    <a href="{{ url('user/orderlist',['sid'=>4]) }}" class="t_o_link @if($sid == '4') active @endif">已关闭</a>
  </section>
  <ul class="list_order clearfix overh">
    @foreach($list as $l)
    <li class="mt20 clearfix overh bgc_f pl20 pr20">
      <!-- top -->
      <header class="l_o_top clearfix pt20 pb20">
        <span class="l_o_t_title"><i class="iconfont icon-evaluate color_main"></i>单号：{{ $l->order_id }}</span>
        <span class="l_o_t_right f-r">
          @if($l->orderstatus == '1')
            @if($l->paystatus == 0)
            <i class="color_main">去支付</i>
            @endif
            @if($l->shipstatus == 1 && $l->paystatus == 1)
            <i class="color_main">确认收货</i>
            @endif
          @elseif($l->orderstatus == '2')
          <i class="color_main">已完成</i>
          @else
          <i class="color_main">已关闭</i>
          @endif
        </span>
      </header>
      @if($l->good->count() == 1)
      <!-- goods -->
      <ul class="l_o_goods clearfix">
        @foreach($l->good as $lg)
        <li class="clearfix mb20">
          <a href="{{ url('good',['id'=>$lg->good_id]) }}" class="l_o_g_img"><img src="{{ $lg->good->thumb }}" height="200px" width="200px" alt="{{ $lg->good_title }}"></a>
          <h3 class="l_o_g_title">{{ $lg->good_title }}</h3>
        </li>
        @endforeach
      </ul>
      @else
      <!-- goods -->
      <ul class="l_o_goods l_o_goods_2 clearfix">
        @foreach($l->good as $lg)
        <li>
          <a href="{{ url('good',['id'=>$lg->good_id]) }}" class="l_o_g_img"><img src="{{ $lg->good->thumb }}" height="200px" width="200px" alt="{{ $lg->good_title }}"></a>
        </li>
        @endforeach
      </ul>
      @endif
      <!-- footer -->
      <footer class="l_o_footer clearfix pt20 pb20">
        <span class="l_o_f_price">实付款：￥{{ $l->total_prices }}</span>
        <i class="iconfont icon-delete f-r ml10 color_9"></i>
        @if($l->orderstatus != '1')
        <span class="label label-red f-r">再次购买</span>
        @endif
      </footer>
    </li>

    @endforeach
  </ul>
  <div class="pages">
      {!! $list->links() !!}
  </div>
  <!-- 底 -->
  @include('mobile.common.footer')
  <!-- 公用底 -->
  @include('mobile.common.pos_menu')
@endsection