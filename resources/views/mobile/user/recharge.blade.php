@extends('mobile.layout')

@section('content')
  <!-- 提示 -->
  <div class="tips"><i class="iconfont icon-notification"></i>充值后不能提现，请谨慎操作！</div>
  <div class="center_userinfo bgc_f pd20 sec_pay">
    <form action="" method="post" class="pure-form pure-form-stacked">
      {{ csrf_field() }}
      <h3 class="mt20">请选择支付方式：</h3>
      @foreach($paylist as $l)
      <div class="mt20 clearfix pay_mod">
        <input type="radio" name="pay" value="{{ $l->id }}" class="pay_radio">
        <img src="{{ $l->thumb }}" width="64" height="64" class="pay_icon" alt="{{ $l->name }}">
        <p class="pay_text">{{ $l->content }}</p>
      </div>
      @endforeach
      <h3 class="mt20">请输入充值金额：</h3>
      <input type="number" min="0" name="money" placeholder="充值金额" value="" class="pure-input-1">
      <div class="btn_group mt20">
        <input type="reset" class="btn_reset" value="重置">
        <input type="submit" class="btn_submit" value="确认充值">
      </div>
    </form>
  </div>
  <script>
    $(function(){
      $('.pay_mod').click(function() {
        $('.pay_radio').prop('checked', 'false');
        $(this).children('.pay_radio').prop('checked', 'checked');
      });
    })
  </script>
@endsection