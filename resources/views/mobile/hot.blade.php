@extends('mobile.layout')

@section('content')
  
  <ul class="list_promotion overh">
    @foreach($list as $l)
    <li class="clearfix pd20 bgc_f mb20">
      <h3>{{ $l->title }} <i class="f-r label label-red">{{ $l->type === 1 ? '折扣' : '减价' }}</i></h3>
      <p class="mt10 color_6">活动时间：<span class="color_fen">{{ substr($l->starttime,0,10) }}</span> 至 <span class="color_fen">{{ substr($l->endtime,0,10) }}</span></p>
      <a href="{{ url('hot',['id'=>$l->id]) }}" class="db mt20"><img data-original="{{ $l->thumb }}" class="lazy" width="750" height="335" alt="{{ $l->title }}"></a>
    </li>
    @endforeach
  </ul>
  <div class="pages">
    {!! $list->links() !!}
  </div>
@endsection