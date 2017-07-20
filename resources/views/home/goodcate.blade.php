@extends('home.layout')

@section('title')
    <title>@if($info->seotitle != ''){{ $info->seotitle }}@else{{ $info->name }}@endif</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection


@section('content')

<section class="container-fluid overh">
	
	<div class="row">
		
		<div class="col-xs-3">
			<ul class="allcate">
				@foreach($allcate as $a)
				<li @if($a->id == $info->id) class="active" @endif><a href="{{ url('/shop/goodcate',['id'=>$a->id]) }}">{{ $a->name }}</a></li>
				@endforeach
			</ul>
		</div>

		<div class="col-xs-9 goodcate_list">
			<div class="good_cate_ad mt10">
				<a href="{{ $ad->url }}"><img src="{{ $ad->thumb }}" class="img-responsive" alt="{{ $ad->title }}"></a>
			</div>
			<h3 class="goodcate_h3 mt10">{{ $info->name }}</h3>
			<div class="row subcate">
				@foreach($subcate as $l)
				<div class="col-xs-4 subcate_div">
					<a href="{{ url('/shop/goodlist',['id'=>$l->id]) }}"><img src="{{ $l->thumb }}" class="img-responsive" alt=""></a>
					<a href="{{ url('/shop/goodlist',['id'=>$l->id]) }}" class="db subcate_name text-center">{{ $l->name }}</a>
				</div>
				@endforeach
			</div>
		</div>


	</div>

</section>

	
@include('home.foot')
@endsection
