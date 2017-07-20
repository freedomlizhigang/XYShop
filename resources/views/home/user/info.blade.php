@extends('home.layout')

@section('title')
    <title>个人信息-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<div class="bgf">

	<div class="container-fluid mt10 pb50">
		<form action="" class="pure-form pure-form-stacked" method="post">
			{{ csrf_field() }}
				
				<div class="form-group">
	            	<input type="text" name="data[nickname]" value="{{ $info->nickname }}" placeholder="昵称" class="form-control">
	            	@if ($errors->has('data.nickname'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.nickname') }}
	                    </span>
	                @endif
	            </div>

	            <div class="form-group">
	            	<input type="text" name="data[phone]" value="{{ $info->phone }}" placeholder="手机号" class="form-control">
	            	@if ($errors->has('data.phone'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.phone') }}
	                    </span>
	                @endif
	            </div>

	            <div class="form-group">
	            	<input type="text" name="data[email]" value="{{ $info->email }}" placeholder="邮箱" class="form-control">
	            	@if ($errors->has('data.email'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.email') }}
	                    </span>
	                @endif
	            </div>

	            <div class="form-group">
	            	<input type="text" name="data[address]" value="{{ $info->address }}" placeholder="地址" class="form-control">
	            	@if ($errors->has('data.address'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.address') }}
	                    </span>
	                @endif
	            </div>

	            <div class="form-group">
	            	<input type="text" name="data[birthday]" value="{{ $info->birthday }}" placeholder="出生日期" class="form-control">
	            	@if ($errors->has('data.birthday'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.birthday') }}
	                    </span>
	                @endif
	            </div>

	            <div class="form-group">
	                <label for="sex">性别：</label>
	                <label class="radio-inline"><input type="radio" name="data[sex]"@if($info->sex == '1') checked="checked" @endif class="input-radio" value="1">
	                    男</label>
	                <label class="radio-inline"><input type="radio" name="data[sex]"@if($info->sex == '2') checked="checked" @endif class="input-radio" value="2">女</label>
	            </div>


		    <div class="btn-group">
		        <button type="reset" name="reset" class="btn btn-warning">重填</button>
		        <button type="submit" name="dosubmit" class="btn btn-info">提交</button>
		    </div>
		</form>
	</div>
</div>
@include('home.foot')
@endsection