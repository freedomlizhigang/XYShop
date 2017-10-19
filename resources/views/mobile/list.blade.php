@extends('mobile.layout')

@section('content')

  <!-- 搜索 -->
  @component('mobile.common.search',['issub'=>1])
  @endcomponent
  
  <!-- 循环分类信息 -->
  <section class="tab_select bgc_f pd10 mt_sub clearfix overh">
    <a href="{{ url()->current() }}?sort=sort&sc=desc" class="t_s_link @if($sort == 'sort') active @endif">综合<i class="iconfont icon-unfold"></i></a>
    <a href="{{ url()->current() }}?sort=is_new&sc=desc" class="t_s_link @if($sort == 'is_new') active @endif">新品<i class="iconfont icon-unfold"></i></a>
    <a href="{{ url()->current() }}?sort=sales&sc=desc" class="t_s_link @if($sort == 'sales') active @endif">销量<i class="iconfont icon-unfold"></i></a>
    <a href="{{ url()->current() }}?sort=shop_price&sc={{ $sc == 'desc' ? 'asc' : 'desc' }}" class="t_s_link @if($sort == 'shop_price') active @endif">价格<i class="iconfont icon-order"></i></a>
  </section>
  <section class="sec_cate mt20 clearfix">
    <ul class="list_good clearfix">
      @foreach($list as $l)
      <li>
        <a href="{{ url('good',['id'=>$l->id]) }}" class="l_g_img"><img src="{{ $l->thumb }}" width="345" height="345" alt="{{ $l->title }}"></a>
        <a href="{{ url('good',['id'=>$l->id]) }}" class="l_g_t slh">
          @if($l->prom_tag != '')
          <i class="label label-red">{{ $l->prom_tag }}</i>
          @endif
          @if($l->new_tag != '')
          <i class="label label-red">{{ $l->new_tag }}</i>
          @endif
          @if($l->pos_tag != '')
          <i class="label label-red">{{ $l->pos_tag }}</i>
          @endif
          @if($l->hot_tag != '')
          <i class="label label-red">{{ $l->hot_tag }}</i>
          @endif
          {{ $l->title }}</a>
        <div class="l_g_info clearfix">
          <span class="l_g_price color_main">￥{{ $l->shop_price }}</span>
          <span class="l_g_btn_addcart iconfont icon-cart"></span>
        </div>
      </li>
      @endforeach
    </ul>
    <div class="pages">
        {!! $list->appends(['sort'=>$sort,'sc'=>$sc])->links() !!}
    </div>
  </section>

  <!-- 底 -->
  @include('mobile.common.footer')
  <!-- 公用底 -->
  @include('mobile.common.pos_menu')
@endsection