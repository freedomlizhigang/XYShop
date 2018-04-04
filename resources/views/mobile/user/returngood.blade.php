@extends('mobile.layout')

@section('content')
  <div class="tips"><i class="iconfont icon-notification"></i>提交申请后等待后台管理员与您联系！</div>
  <div class="center_userinfo bgc_f pd20">
    <h3>{{ $ordergood->good_title }}</h3>
    <form action="" method="post" class="pure-form pure-form-stacked">
      {{ csrf_field() }}
      <textarea name="mark" rows="5" class="pure-input-1" placeholder="售后说明"></textarea>
      <div class="btn_group mt20">
        <input type="reset" class="btn_reset" value="重置">
        <input type="submit" class="btn_submit" value="提交">
      </div>
    </form>
  </div>  
@endsection