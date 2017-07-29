@extends('home.layout')

@section('title')
    <title>充值卡激活-{{ cache('config')['sitename'] }}</title>
@endsection

@section('content')
<div class="bgf">
	<div class="container-fluid mt10 pb50">
		<form action="" class="pure-form pure-form-stacked" method="post">
			{{ csrf_field() }}

            <div class="form-group">
            	<input type="text" name="data[card_id]" value="{{ old('data.card_id') }}" placeholder="卡号" class="form-control">
            	@if ($errors->has('data.card_id'))
                    <span class="help-block">
                    	{{ $errors->first('data.card_id') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
            	<input type="text" name="data[card_pwd]" value="{{ old('data.card_pwd') }}" placeholder="密码" class="form-control">
            	@if ($errors->has('data.card_pwd'))
                    <span class="help-block">
                    	{{ $errors->first('data.card_pwd') }}
                    </span>
                @endif
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