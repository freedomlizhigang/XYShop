@extends('mobile.layout')


@section('content')
<div class="center_userinfo bgc_f pd20">
    <form action="" method="post" class="pure-form pure-form-stacked">
        {{ csrf_field() }}
        <input type="text" name="data[card_id]" placeholder="卡号" value="{{ old('data.card_id') }}" class="pure-input-1">
        <input type="text" name="data[card_pwd]" placeholder="密码" value="{{ old('data.card_pwd') }}" class="pure-input-1">
        <div class="mt20">
            <input type="reset" class="btn_reset" value="重置">
            <input type="submit" class="btn_submit" value="激活">
        </div>
    </form>
</div>
@endsection