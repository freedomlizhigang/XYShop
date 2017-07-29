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
                <div class="page_content mt30">
                    {!! $info->content !!}
                </div>
            </article>
        </div>
    </section>

@include('home.foot')
@endsection