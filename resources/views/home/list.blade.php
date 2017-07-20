@extends('home.layout')

@section('title')
    <title>{{ $info->title }}</title>
    <meta name="keywords" content="{{ $info->keyword }}">
    <meta name="description" content="{{ $info->describe }}">
@endsection

@section('banner')
    @include('default.banner')
@endsection


@section('content')
<section class="container-fluid mt20">
    <div class="row">
        <!-- 左边 -->
        @include('default.aside')
        <!-- 右边 -->
        <article class="col-xs-12 col-md-9">
            <ol class="breadcrumb">
                <li><a href="/">首页</a></li>
                {{ App::make('tag')->catpos($info->id) }}
            </ol>
            <h3 class="h3_cate"><span class="h3_cate_span">{{ $info->name }}</span></h3>
            <ul class="list_news">
                @foreach($list as $a)
                <li class="media">
                    @if($a->thumb != '') <a class="media-left"><img src="{{ $a->thumb }}" width="100" alt=""></a>@endif
                    <div class="media-body">
                        <h4><a href="{{ url('/post',['url'=>$a->url]) }}" class="text-nowrap list_news_title">{{ $a->title }}</a></h4>
                        @if($a->describe != '')<p>{{ substr($a->describe,'0','135') }}..</p>@endif
                        <span class="list_news_time_n">{{ $a->updated_at->format('Y-m-d') }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="pages">
                {!! $list->links() !!}
            </div>
        </article>
    </div>
</section>
@include('home.foot')
@endsection