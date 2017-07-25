@extends('admin.alert')

@section('content')
<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form form-inline" method="get">
		<div class="form-group row">
			<div class="col-xs-4">
				<select name="cate_id_1" id="catid_one" onchange="get_goodcate(this.value,'catid_two',0)" class="form-control">
		            <option value="0">顶级分类</option>
		        </select>
	        </div>
	        <div class="col-xs-4">
		        <select name="cate_id_2" id="catid_two" onchange="get_goodcate(this.value,'catid',0)" class="form-control">
		            <option value="0">二级分类</option>
		        </select>
		    </div>
		    <div class="col-xs-4">
		        <select name="cate_id" id="catid" class="form-control">
		            <option value="0">三级分类</option>
		        </select>
		    </div>
		</div>
		<div class="form-group row">
			<div class="col-xs-7">
				<input type="text" name="q" value="{{ $key }}" class="form-control" placeholder="请输入商品标题关键字..">
			</div>
			<div class="col-xs-5">
				<button class="btn btn-xs btn-info">搜索</button>
			</div>
		</div>
	</form>
</div>
{{ csrf_field() }}
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th>标题</th>
		<th width="100">库存</th>
	</tr>
	@if($type == '2')
	@foreach($list as $a)
	<tr>
		<td><label class="radio-inline good_checkbox_title"><input type="checkbox" name="good_id" class="input-radio" value="{{ $a->id }}" data-price="{{ $a->price }}" data-store="{{ $a->store }}" data-title="{{ $a->title }}"> {{ $a->title }}</label></td>
		<td>{{ $a->store }}</td>
	</tr>
	@endforeach
	@else
	@foreach($list as $a)
	<tr>
		<td><label class="radio-inline good_title"><input type="radio" name="good_id" class="input-radio" value="{{ $a->id }}"> {{ $a->title }}</label></td>
		<td>{{ $a->store }}</td>
	</tr>
	@endforeach
	@endif
</table>
<!-- 分页，appends是给分页添加参数 -->
{!! $list->appends(['q'=>$key])->links() !!}

<script>
	$(function(){
		get_goodcate(0,'catid_one',"{{ $cate_id_1 }}");
        get_goodcate("{{ $cate_id_1 }}",'catid_two',"{{ $cate_id_2 }}");
        get_goodcate("{{ $cate_id_2 }}",'catid',"{{ $cate_id }}");
    });
</script>
@endsection