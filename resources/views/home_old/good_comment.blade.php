@extends('home.layout')

@section('title')
    <title>订单评价-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<link href="{{ $sites['static']}}home/css/star-rating.min.css" rel="stylesheet">
<script src="{{ $sites['static']}}home/js/star-rating.min.js"></script>
<div class="bgf">
	<div class="container-fluid mt10 pb50">
		<form action="" class="pure-form pure-form-stacked" method="post">
				{{ csrf_field() }}
				<input type="hidden" name="ref" value="{!! $ref !!}">
				<div class="form-group">
	            	<input type="text" name="data[title]" value="{{ old('data.title') }}" placeholder="评价" class="form-control">
	            	@if ($errors->has('data.title'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.title') }}
	                    </span>
	                @endif
	            </div>

	            <div class="form-group">
	            	<textarea name="data[content]" placeholder="说明" class="form-control" cols="30" rows="4"></textarea>
	            	@if ($errors->has('data.content'))
	                    <span class="help-block">
	                    	{{ $errors->first('data.content') }}
	                    </span>
	                @endif
	            </div>
				


	            <div class="form-group">
	                <input id="input-21d" value="5" type="number" name="data[score]" min=0 max=5 step=0.5 data-size="xs">
	                <script>
	                	$(function(){
	                		$("#input-21d").rating({showCaption:false});
	                	});
	                </script>
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