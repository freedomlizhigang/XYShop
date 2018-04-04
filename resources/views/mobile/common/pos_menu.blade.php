<!-- 固定底 -->
@if(isset($pos_id))
<div class="pos_foot">
  <a href="{{ url('/') }}" class="p_f_link iconfont icon-home @if($pos_id == 'home') color_main @endif"><em>首页</em></a>
  <a href="{{ url('catelist') }}" class="p_f_link iconfont icon-sortlight @if($pos_id == 'catelist') color_main @endif"><em>分类</em></a>
  <a href="{{ url('hot') }}" class="p_f_link iconfont icon-hot @if($pos_id == 'hot') color_main @endif"><em>热卖</em></a>
  <a href="{{ url('cart') }}" class="p_f_link iconfont icon-cart @if($pos_id == 'cart') color_main @endif"><em>购物车</em></a>
  <a href="{{ url('center') }}" class="p_f_link iconfont icon-my_light @if($pos_id == 'center') color_main @endif"><em>我的</em></a>
</div>
@endif