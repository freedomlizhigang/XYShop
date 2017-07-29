@extends('home.layout')

@section('title')
    <title>修改收货地址-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<div class="bgf">

	<div class="container-fluid mt10 pb50">
		<form action="" class="pure-form pure-form-stacked" method="post">
			{{ csrf_field() }}
				
				<div class="form-group">
	            	<input type="text" name="data[people]" value="{{ $info->people }}" placeholder="联系人" class="form-control">
	            	@if ($errors->has('data.people'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.people') }}
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
	                <select name="data[area]" id="" class="form-control">
	                	<option value="">区域</option>
	                    @foreach($area as $a)
	                    <option value="{{ $a->name }}" @if($a->name == $info->area) selected="selected"@endif>{{ $a->name }}</option>
	                    @endforeach
	                </select>
	                @if ($errors->has('data.area'))
	                    <span class="help-block">
	                        {{ $errors->first('data.area') }}
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
	                <label for="default">默认：</label>
	                <label class="radio-inline"><input type="radio" name="data[default]"@if($info->default == '1') checked="checked" @endif class="input-radio" value="1">
	                    是</label>
	                <label class="radio-inline"><input type="radio" name="data[default]"@if($info->default == '0') checked="checked" @endif class="input-radio" value="0">否</label>
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