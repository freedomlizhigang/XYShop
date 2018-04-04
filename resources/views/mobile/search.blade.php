@extends('mobile.layout')

@section('content')

  <!-- 搜索 -->
  @component('mobile.common.search',['issub'=>1,'key'=>$key])
  @endcomponent
  
  <!-- 循环分类信息 -->
  <section class="tab_select bgc_f pd10 mt_sub clearfix overh">
    <a href="{{ url()->current() }}?key={{$key}}&sort=sort&sc=desc" class="t_s_link @if($sort == 'sort') active @endif">综合<i class="iconfont icon-unfold"></i></a>
    <a href="{{ url()->current() }}?key={{$key}}&sort=is_new&sc=desc" class="t_s_link @if($sort == 'is_new') active @endif">新品<i class="iconfont icon-unfold"></i></a>
    <a href="{{ url()->current() }}?key={{$key}}&sort=sales&sc=desc" class="t_s_link @if($sort == 'sales') active @endif">销量<i class="iconfont icon-unfold"></i></a>
    <a href="{{ url()->current() }}?key={{$key}}&sort=shop_price&sc={{ $sc == 'desc' ? 'asc' : 'desc' }}" class="t_s_link @if($sort == 'shop_price') active @endif">价格<i class="iconfont @if($sc == 'desc' && $sort == 'shop_price') icon-unfold @elseif($sc == 'asc' && $sort == 'shop_price') icon-fold @else icon-unfold @endif"></i></a>
  </section>
  <section class="sec_cate mt20 clearfix">
    @if($list->count() == 0)
    <p class="pd20 bgc_f">没有找到你要的商品啊，搜索个其他的试试吧~</p>
    @else
    <ul class="list_good clearfix">
      @foreach($list as $l)
      <li>
        <a href="{{ $l->url }}" class="l_g_img"><img data-original="{{ $l->thumb }}" width="345" height="345" alt="{{ $l->title }}" class="lazy"></a>
        <a href="{{ $l->url }}" class="l_g_t slh">
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
          <a class="l_g_btn_addcart iconfont icon-cart" href="{{ $l->url }}"></a>
        </div>
      </li>
      @endforeach
    </ul>
    @endif
    <div class="pages">
        {!! $list->appends(['sort'=>$sort,'sc'=>$sc,'key'=>$key])->links() !!}
    </div>
  </section>
@endsection