@extends('mobile.layout')

@section('content')

  <!-- 搜索 -->
  @component('mobile.common.search',['issub'=>1])
  @endcomponent
  <!-- 分类列表 -->
  <section class="clearfix list_cate overh">
    <ul class="l_c_left bgc_f f-l">
      @foreach($one as $o)
      @if($o->child)
      <li @if($o->id == $id) class="active"@endif><a href="{{ url('catelist',['id'=>$o->id]) }}">{{ $o->mobilename }}</a></li>
      @else
      <li @if($o->id == $id) class="active"@endif><a href="{{ url('list',['id'=>$o->id]) }}">{{ $o->mobilename }}</a></li>
      @endif
      @endforeach
    </ul>
    <div class="l_c_right f-r pd20">
      <!-- ad -->
      @foreach(app('tag')->ad(4,1,1) as $k => $c)
        <a href="{{ $c->url }}"><img src="{{ $c->thumb }}" width="570" height="180" alt="{{ $c->title }}"></a>
      @endforeach
      
      <!-- 按二级分类再循环 -->
      @foreach($cates as $c)
      <div class="pd20 mt20 bgc_f">
        <h3 class="t3_cate"><a href="{{ url('list',['id'=>$c->id]) }}">{{ $c->mobilename }}</a></h3>
        <ul class="l_c_subcate clearfix">
          @foreach(app('tag')->catelist($c->id,8) as $g)
          <li>
            @if($g->thumb != '')
            <a href="{{ url('list',['id'=>$g->id]) }}" class="l_c_s_img db_ma"><img data-original="{{ $g->thumb }}" width="70" height="70" alt="{{ $g->mobilename }}" class="lazy"></a>
            @endif
            <a href="{{ url('list',['id'=>$g->id]) }}" class="l_c_s_title">{{ $g->mobilename }}</a>
          </li>
          @endforeach
        </ul>
      </div>
      @endforeach
    </div>
  </section>
@endsection