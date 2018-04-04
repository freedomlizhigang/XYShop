<!-- 顶部搜索 -->
<section class="top_search m_a @if($issub) top_search_sub @endif clearfix">
  <form action="{{ url('search') }}">
    <input type="text" name="key" value="{{ isset($key) ? $key : '' }}" placeholder="输入要搜索的内容.." class="t_s_input">
    <button class="t_s_btn iconfont icon-search_list_light"></button>
  </form>
</section>