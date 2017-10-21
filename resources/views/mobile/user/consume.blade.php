@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <ul class="list_consume clearfix pd20 overh bgc_f">
    @foreach($consume as $l)
    <li class="clearfix">
      <span>{{ $l->created_at }}</span>
      <span class="label label-red f-r">{{ $l->type ? '充值' : '消费' }}</span>
      <p>{{ $l->mark }}</p>
    </li>
    @endforeach
  </ul>
  <div class="pages">
      {!! $consume->links() !!}
  </div>
  <!-- 底 -->
  @include('mobile.common.footer')
  <!-- 公用底 -->
  @include('mobile.common.pos_menu')
@endsection