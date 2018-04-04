@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <ul class="list_consume clearfix pd20 overh bgc_f">
    @foreach($consume as $l)
    <li class="clearfix">
      <span>{{ $l->created_at }}</span>
      @if($l->type)
      <span class="label label-red f-r">充值 +{{ $l->price }}</span>
      @else
      <span class="label label-hui f-r">消费 -{{ $l->price }}</span>
      @endif
      <p>{{ $l->mark }}</p>
    </li>
    @endforeach
  </ul>
  <div class="pages">
      {!! $consume->links() !!}
  </div>
@endsection