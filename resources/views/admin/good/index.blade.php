@extends('admin.right')

@section('rmenu')
@if(App::make('com')->ifCan('good-add'))
<a href="{{ url('/console/good/add') }}" class="btn btn-xs btn-default btn_modal"><span class="glyphicon glyphicon-plus"></span> 添加商品</a>
@endif

@endsection


@section('content')
<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form-inline pull-left" method="get">
		<select name="cate_id" id="catid" class="form-control">
			<option value="">请选择栏目</option>
			{!! $cate !!}
		</select>
		<select name="status" id="status" class="form-control">
			<option value="">请选择状态</option>
			<option value="0"@if($status == '0') selected @endif>下架</option>
			<option value="1"@if($status == '1') selected @endif>在售</option>
		</select>
		<select name="sort" id="sort" class="form-control">
			<option value="sort"@if($sort == 'sort') selected @endif>排序</option>
			<option value="store"@if($sort == 'store') selected @endif>库存</option>
		</select>
		开始时间：<input type="text" name="starttime" class="form-control" value="{{ $starttime }}" id="laydate">
		结束时间：<input type="text" name="endtime" class="form-control" value="{{ $endtime }}" id="laydate2">
		<button class="btn btn-xs btn-info">查找</button>
	</form>

	<form action="" class="form-inline pull-right" method="get">
		<input type="hidden" name="cate_id" value="{{ $cate_id }}">
		<input type="text" name="q" value="{{ $key }}" class="form-control" placeholder="请输入商品标题关键字..">
		<button class="btn btn-xs btn-info">搜索</button>
	</form>
</div>
<form action="" class="form-inline form_submit" method="get">
{{ csrf_field() }}
<table class="table table-striped table-hover mt10">
	<tr class="active">
		<th width="30"><input type="checkbox" class="checkall"></th>
		<th width="80">排序</th>
		<th width="50">ID</th>
		<th>标题</th>
		<th width="100">分类</th>
		<th width="100">价格</th>
		<th width="100">库存</th>
		<th width="80">状态</th>
		<th width="180">修改时间</th>
		<th width="280">操作</th>
	</tr>
	@foreach($list as $a)
	<tr>
		<td><input type="checkbox" name="sids[]" class="check_s" value="{{ $a->id }}"></td>
		<td><input type="text" min="0" name="sort[{{$a->id}}]" value="{{ $a->sort }}" class="form-control input-xs"></td>
		<td>{{ $a->id }}</td>
		<td>
			<img src="{{ $a->thumb }}" width="100" height="auto" class="img-responsive pull-left img-rounded mr10" alt="">
			@if($a->tags == '')
			<span class="text-danger">{{ $a->tags }}</span>
			@endif
			@if($a->isxs == 1)
			<span class="text-primary">[限时]</span>
			@endif
			@if($a->isxl == 1)
			<span class="text-success">[限量]</span>
			@endif
			{{ $a->title }}
			@foreach($a->goodspecprice as $gp)
			<br /><span class="label label-info">{{ $gp->key_name }}</span>
			@endforeach
		</td>
		<td>@if(isset(cache('goodcateCache')[$a->cate_id])){{ cache('goodcateCache')[$a->cate_id]['name'] }}@endif</td>
		<td>{{ $a->price }}￥</td>
		<td>{{ $a->store }}</td>
		<td>
			@if($a->status == 1)
			<span class="text-success">在售</span>
			@elseif($a->status == 0)
			<span class="text-danger">下架</span>
			@endif
		</td>
		<td>{{ $a->updated_at }}</td>
		<td>
			@if(App::make('com')->ifCan('good-edit'))
			<a href="{{ url('/console/good/edit',$a->id) }}" class="btn btn-xs btn-info glyphicon glyphicon-edit"></a>
			@endif
			<a href="{{ url('/shop/good',['id'=>$a->id]) }}" target="_blank" class="btn btn-xs btn-success glyphicon glyphicon-eye-open"></a>
			@if(App::make('com')->ifCan('good-del') && $a->status == 0)
			<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>1]) }}" title="上架" class="btn btn-xs btn-success glyphicon glyphicon-ok-circle"></a>
			@endif
			@if(App::make('com')->ifCan('good-del') && $a->status == 1)
			<a href="{{ url('/console/good/del',['id'=>$a->id,'status'=>0]) }}" title="下架" class="btn btn-xs btn-warning glyphicon glyphicon-ban-circle"></a>
			@endif
			@if(App::make('com')->ifCan('manzeng-add'))
			<div data-url="{{ url('/console/manzeng/add',['id'=>$a->id]) }}" data-title="添加满赠" title="添加满赠" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-primary glyphicon glyphicon-plus btn_modal"></div>
			@endif
			@if(App::make('com')->ifCan('tuan-add'))
			<div data-url="{{ url('/console/tuan/add',['id'=>$a->id]) }}" data-title="添加团购" title="添加团购" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-primary glyphicon glyphicon-plus btn_modal"></div>
			@endif
		</td>
	</tr>
	@endforeach
</table>
<!-- 添加进专题功能 -->
<div class="clearfix">
	<div class="pull-left" data-toggle="buttons">
		<div class="btn-group">
			<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
		</div>
		
		<select name="cate_id" id="catid" class="form-control">
			<option value="">请选择栏目</option>
			{!! $cate !!}
		</select>
		
		@if(App::make('com')->ifCan('good-allcate'))
		<span class="btn btn-xs btn-info btn_allcate">修改分类</span>
		@endif


		@if(App::make('com')->ifCan('good-sort'))
		<span class="btn btn-xs btn-warning btn_sort">排序</span>
		@endif

		@if(App::make('com')->ifCan('huodong-good'))
		<span class="btn btn-success btn_huodong btn-xs" data-toggle="modal" data-target="#myModal_hd">添加到活动</span>
		@endif


		@if(App::make('com')->ifCan('good-allstatus'))
		<span class="btn btn-xs btn-info btn_allstatus_1">批量上架</span>
		@endif
		<input type="hidden" name="status" class="allstatus">

		@if(App::make('com')->ifCan('good-allstatus'))
		<span class="btn btn-xs btn-warning btn_allstatus_2">批量下架</span>
		@endif

		@if(App::make('com')->ifCan('good-alldel'))
		<span class="btn btn-xs btn-danger btn_del">批量删除</span>
		@endif
	</div>
	</form>
	<!-- 分页，appends是给分页添加参数 -->
	<div class="pull-right">
		<div class="pull-left mr10 mt5">总共 {{ $count }} 条</div>
		{!! $list->appends(['cate_id' =>$cate_id,'q'=>$key,'status'=>$status,'starttime'=>$starttime,'endtime'=>$endtime])->links() !!}
	</div>
</div>

<div class="modal fade" id="myModal_hd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      	<iframe src="" id="hd_good2" frameborder="0" width="100%" height="600" scrolling="auto" allowtransparency="true"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>

<!-- 选中当前栏目 -->
<script>
	$(function(){
		// 活动
		$('.btn_huodong').click(function(){
			// 取到商品ID
			var gids = '';
			$('.check_s').each(function(s){
				if($(this).is(":checked"))
				{
					gids += $(this).val() + '|';
				}
			});
			if (gids == '') {
				alert('请先选择商品！');
				return false;
			}
			var url = "{{ url('console/huodong/good') }}" + '/' + gids;
			$('#hd_good2').attr("src","{{ url('console/huodong/good') }}" + '/' + gids);
			$('#myModalLabel').text('活动');
			return;
		});
		// 下、上架
		$('.btn_allcate').click(function(){
			if (!confirm("确实要修改分类吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/good/allcate') }}",'method':'post'}).submit();
			}
		});
		// 下、上架
		$('.btn_allstatus_2').click(function(){
			if (!confirm("确实要下架吗?")){
				return false;
			}else{
				$('.allstatus').val('0');
				$('.form_submit').attr({'action':"{{ url('/console/good/allstatus') }}",'method':'post'}).submit();
			}
		});
		$('.btn_allstatus_1').click(function(){
			if (!confirm("确实要上架吗?")){
				return false;
			}else{
				$('.allstatus').val('1');
				$('.form_submit').attr({'action':"{{ url('/console/good/allstatus') }}",'method':'post'}).submit();
			}
		});

		$('.btn_sort').click(function(){
			$('.form_submit').attr({'action':"{{ url('/console/good/sort') }}",'method':'post'}).submit();
		});

		$('.btn_del').click(function(){
			if (!confirm("确实要删除吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/good/alldel') }}",'method':'post'}).submit();
			}
		});
		$(".checkall").bind('change',function(){
			if($(this).is(":checked"))
			{
				$(".check_s").each(function(s){
					$(".check_s").eq(s).prop("checked",true);
				});
			}
			else
			{
				$(".check_s").each(function(s){
					$(".check_s").eq(s).prop("checked",false);
				});
			}
		});
		$('#catid option[value=' + {{ $cate_id }} + ']').prop('selected','selected');
	})
	laydate({
        elem: '#laydate',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
</script>
@endsection