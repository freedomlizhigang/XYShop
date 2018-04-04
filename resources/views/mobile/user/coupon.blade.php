@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <ul class="list_coupon list_coupon_user clearfix overh bgc_f pd20">
    @foreach($list as $l)
    <li class="@if($l->status == 0 || strtotime($l->endtime) <= time()) used @endif">
      <div class="f-l l_c_u_right">
        <p class="l_c_price">{{ $l->coupon->title }}</p>
        <p class="l_c_info">到期时间：{{ $l->endtime }}</p>
      </div>
      <div class="f-l l_c_u_left pr">
        <span class="l_c_price f-l">￥{{ $l->coupon->lessprice }}</span>
        <span class="l_c_btn f-r">@if($l->status == 0 || strtotime($l->endtime) <= time())失效 @else可用 @endif</span>
        <span class="l_c_info f-l">满{{ $l->coupon->price }}可用</span>
      </div>
    </li>
    @endforeach
  </ul>
  <div class="pages">
      {!! $list->links() !!}
  </div>
@endsection