@extends('home.layout')

@section('title')
    <title>收货地址-{{ cache('config')['sitename'] }}</title>
@endsection

@section('content')
<div class="bgf">
	<div class="container-fluid mt10">
		<a href="{{ url('user/address/add') }}" class="btn btn-sm btn-success">添加地址</a>
	</div>

	<div class="container-fluid mt10">
		@foreach($list as $l)
		<div class="mt10 user_address_list">
			<h5>
			@if($l->default)
			<span class="label label-primary">默认</span>
			@endif
			{{ $l->people }}：{{ $l->phone }}</h5>
			<p>{{ $l->area }} - {{ $l->address }}</p>
			<a href="{{ url('user/address/edit',['id'=>$l->id]) }}"><span class="glyphicon glyphicon-edit"></span>修改</a>
			<a href="{{ url('user/address/del',['id'=>$l->id]) }}"><span class="glyphicon glyphicon-trash"></span>删除</a>
		</div>
		@endforeach
	</div>
</div>
@include('home.foot')
@endsection