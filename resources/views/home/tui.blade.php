@extends('home.layout')

@section('title')
    <title>退货申请-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<div class="bgf">

	<div class="container-fluid mt10 pb50">
		<form action="" class="pure-form pure-form-stacked" method="post">
			{{ csrf_field() }}

            <div class="form-group">
            	<textarea name="mark" placeholder="退货理由" class="form-control" rows="4"></textarea>
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