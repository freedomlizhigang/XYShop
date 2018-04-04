@extends('mobile.layout')

@section('content')
  <!-- 修改密码 -->
  <div class="center_userinfo bgc_f pd20">
    <form action="" method="post" class="pure-form pure-form-stacked">
      {{ csrf_field() }}
      <input type="password" name="passwd" placeholder="新密码" value="" class="pure-input-1">
      <input type="password" name="passwd_confirmation" placeholder="确认密码" value="" class="pure-input-1">
      <div class="btn_group mt20">
        <input type="reset" class="btn_reset" value="重置">
        <input type="submit" class="btn_submit" value="修改">
      </div>
    </form>
  </div>
@endsection