@extends('home.layout')

@section('title')
    <title>{{ $info->title }}</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection


@section('content')


<section class="container-fluid bgf overh">

	<div class="hd_list mt10">
		
		@foreach($list as $l)
		<div class="hd_list_div pr">
			<a href="{{ url('/shop/hd/list',['id'=>$l->id]) }}"><img src="{{ $l->thumb }}" class="img-responsive" alt=""></a>
			<div class="hd_info clearfix">
				<h4 class="hd_title text-nowarp"><a class="text-success" href="{{ url('/shop/hd/list',['id'=>$l->id]) }}">{{ $l->title }}</a></h4>
				<p class="hd_time">活动时间：{{ str_limit($l->starttime,10,'') }} 至 {{ str_limit($l->endtime,10,'') }}</p>
			</div>
		</div>
		@endforeach


	</div>

	<div class="pages">
        {!! $list->links() !!}
    </div>

</section>

	
@include('home.foot')
@endsection
