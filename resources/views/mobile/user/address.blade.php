@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <ul class="list_address clearfix overh bgc_f pd20">
    @foreach($list as $l)
    <li class="clearfix">
      <div class="f-l con">
        <h4>
          @if($l->default == 1)
          <i class="label label-red">默认</i>
          @endif
          {{ $l->people }} - {{ $l->phone }}</h4>
        <p>{{ $l->area }}-{{ $l->address }}</p>
      </div>
      <a href="{{ url('user/address/edit',['id'=>$l->id]) }}" class="f-r iconfont icon-right"></a>
      <a href="{{ url('user/address/del',['id'=>$l->id]) }}" class="f-r iconfont icon-delete"></a>
    </li>
    @endforeach
  </ul>
  <div class="pages">
      {!! $list->links() !!}
  </div>
@endsection