@extends('home.layout')

@section('title')
    <title>会员登录-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
	<div class="container login_bg">
		<form action="" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="ref" value="{{ $ref }}">

			<div class="form-group">
				<input type="text" name="data[username]" value="" class="form-control" placeholder="用户名">
				@if ($errors->has('data.username'))
				<span class="help-block">{{ $errors->first('data.username') }}</span>
				@endif
			</div>


			<div class="form-group">
				<input type="password" name="data[password]" value="" placeholder="密码" class="form-control">
				@if ($errors->has('data.password'))
				<span class="help-block">{{ $errors->first('data.password') }}</span>
				@endif
			</div>

			<div class="clearfix">
				<button type="submit" name="dosubmit" class="btn btn-primary col-xs-12">提交</button>
				<a href="{{ url('oauth/wx') }}" class="btn btn-success col-xs-12 mt10"><span class="login_wx"><img src="{{ $sites['static']}}home/images/wx.png" class="img-responsive" width="20" alt=""></span>微信登录</a>
			</div>
		</form>
	</div>
@include('home.foot')
@endsection