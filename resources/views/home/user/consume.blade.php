@extends('home.layout')

@section('title')
    <title>消费记录-{{ cache('config')['sitename'] }}</title>
@endsection

@section('content')
<div class="bgf">

	<div class="container-fluid mt10">
		<ul>
			@foreach($list as $l)
			<li class="clearfix">{{ $l->created_at }} - {{ $l->mark }} <span class="pull-right text-success"><span class="text-danger">@if($l->type == 1)+@else-@endif</span>{{ $l->price }}</span></li>
			@endforeach
		</ul>
		<div class="pages">
	        {!! $list->links() !!}
	    </div>
	</div>
</div>
@include('home.foot')
@endsection