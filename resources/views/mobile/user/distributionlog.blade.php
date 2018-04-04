@extends('mobile.layout')

@section('content')
  <!-- 订单列表 -->
  <ul class="list_distribution clearfix overh bgc_f pd20">
    @foreach($list as $l)
    <li class="clearfix">
      <p><strong class="color_main dis-money">{{ $l->money }}</strong> - <em class="color_9">{{ $l->created_at }}</em></p>
      <p class="mt5"><em class="color_9">下级：</em><i class="color_6 dis-son">{{ optional($l->son)->nickname }}</i> - <em class="color_9">下下级：</em><i class="color_6 dis-sun">{{ optional($l->sun)->nickname }}</i></p>
    </li>
    @endforeach
  </ul>
  <div class="pages">
      {!! $list->links() !!}
  </div>
  <style>
    .list_distribution li {
      padding:10px;
      margin-bottom: 10px;
      background: #F8F8F8;
      border-radius: 5px;
    }
    .dis-money {
      font-size: 14px;
    }
  </style>
@endsection