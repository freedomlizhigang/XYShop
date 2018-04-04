@extends('mobile.layout')

@section('content')
  <!-- 用户信息表单 -->
  <div class="center_userinfo bgc_f pd20">
    <form action="" method="post" class="pure-form pure-form-stacked">
      {{ csrf_field() }}
      <input type="text" name="data[nickname]" placeholder="昵称" value="{{ $info->nickname }}" class="pure-input-1">
      <input type="text" name="data[phone]" placeholder="电话" value="{{ $info->phone }}" class="pure-input-1">
      <input type="text" name="data[email]" placeholder="邮箱" value="{{ $info->email }}" class="pure-input-1">
      <input type="text" name="data[address]" placeholder="地址" value="{{ $info->address }}" class="pure-input-1">
      <input type="date" name="data[birthday]" placeholder="生日" value="{{ $info->birthday }}" class="pure-input-1">
      <div class="mt15">
        <input type="radio" name="data[sex]" value="1"@if($info->sex === 1 || $info->sex === 0) checked="checked"@endif> 男
        <input type="radio" name="data[sex]" value="2"@if($info->sex === 2) checked="checked"@endif> 女
      </div>
      <div class="btn_group mt20">
        <input type="reset" class="btn_reset" value="重置">
        <input type="submit" class="btn_submit" value="修改">
      </div>
    </form>
  </div>
@endsection