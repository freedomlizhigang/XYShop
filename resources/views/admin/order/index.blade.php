@extends('admin.right')


@section('content')

<!-- 选出栏目 -->
<div class="clearfix">
	<form action="" class="form-inline form_excel pull-left" method="get">
        <select name="prom_type" id="prom_type" class="form-control">
            <option value="">订单类型</option>
            <option value="0"@if($prom_type == '0') selected="selected" @endif>普通</option>
            <option value="1"@if($prom_type == '1') selected="selected" @endif>抢购</option>
            <option value="2"@if($prom_type == '2') selected="selected" @endif>团购</option>
        </select>
		<select name="status" id="status" class="form-control">
			<option value="">订单状态</option>
			<option value="0"@if($status == '0') selected="selected" @endif>关闭</option>
			<option value="1"@if($status == '1') selected="selected" @endif>正常</option>
			<option value="2"@if($status == '2') selected="selected" @endif>完成</option>
		</select>
		<select name="paystatus" id="paystatus" class="form-control">
			<option value="">付款状态</option>
			<option value="0"@if($paystatus == '0') selected="selected" @endif>未付</option>
			<option value="1"@if($paystatus == '1') selected="selected" @endif>已付</option>
		</select>
		<select name="shipstatus" id="shipstatus" class="form-control">
			<option value="">发货状态</option>
			<option value="0"@if($shipstatus == '0') selected="selected" @endif>未发货</option>
			<option value="1"@if($shipstatus == '1') selected="selected" @endif>已发货</option>
		</select>
		<input name="starttime" placeholder="开始时间" class="form-control" value="{{ $starttime }}" id="laydate">
		<input name="endtime" placeholder="结束时间" class="form-control" value="{{ $endtime }}" id="laydate2">
		<input type="text" name="key" value="{{ $key }}" class="form-control" placeholder="请输入商品名或订单号查询..">
		<input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="请输入手机号或昵称查询..">
        <button class="btn btn-xs btn-info">查找</button>
    </form>
</div>

<form action="" class="form-inline form_submit" method="get">
{{ csrf_field() }}
<table class="table table-bordered table-striped mt15">
	<tr>
		<th width="30"><input type="checkbox" class="checkall"></th>
		<th>订单状态</th>
		<th>操作</th>
		<th>订单号</th>
		<th>总价</th>
		<th>支付状态</th>
		<th>发货状态</th>
		<th>下单时间</th>
	</tr>
	@foreach($orders as $o)
    <tr>
    	<td><input type="checkbox" name="sids[]" class="check_s" value="{{ $o->id }}"></td>
    	<td>
            @if($o->prom_type == 0)
            <span class="label label-default">普</span>
            @elseif($o->prom_type == 1)
            <span class="label label-info">抢</span>
            @elseif($o->prom_type == 2)
            <span class="label label-danger">团</span>
            @endif
            @if($o->orderstatus == 0)
        	<span class="color_red">已关闭</span>
        	@elseif($o->orderstatus == 1)
        	<span class="color-blue">正常</span>
    		@else
        	<span class="text-success">已完成</span>
        	@endif
        </td>
    	<td>
    	@if(App::make('com')->ifCan('order-print'))
    	<a href="{{ url('/console/order/print',['id'=>$o->id]) }}" target="_blank" class="btn btn-xs btn-primary">打印</a> 
		@endif
    	@if($o->orderstatus == 1)
		@if(App::make('com')->ifCan('order-del'))
    	<a href="{{ url('/console/order/del',['id'=>$o->id]) }}" class="btn btn-xs btn-danger confirm">关闭</a> 
		@endif
		@if(App::make('com')->ifCan('order-ship') && $o->shipstatus == 0)
		<div data-url="{{ url('/console/order/ship',['id'=>$o->id]) }}" data-title="发货" data-toggle='modal' data-target='#myModal' class="btn btn-xs btn-success btn_modal">发货</div>
		@endif
		@endif
    	</td>
        <td>@if(!is_null($o->user)){{ $o->user->nickname }}：@endif{{ $o->order_id }}</td>
        <td><span class="color_2">￥{{ $o->total_prices }}</span></td>
        <td>
        	@if($o->paystatus == 0)
        	<span class="text-primary">未支付</span>
        	@else
        	<span class="text-success">已支付</span>
        	@endif
        </td>
        <td>
        	@if($o->shipstatus == 0)
        	<span class="text-primary">未发货</span>
        	@else
        	<span class="text-success">{{ $o->ship_at }}</span>
        	@endif
        </td>
        <td>{{ $o->created_at }}</td>
    </tr>
	<tr>
		<td colspan="8">
			@if(!is_null($o->address))
    		<p>{{ $o->address->area }}{{ $o->address->address }}</p>
    		<p>{{ $o->address->people }}：{{ $o->address->phone }}</p>
    		@endif
    	</td>
	</tr>
    <tr>
    	<td colspan="8">
    		<table class="table">
    		@foreach($o->good as $l)
			<tr>
				<td width="55%">
					<img src="{{ $l->good->thumb }}" class="img-responsive img-thumbnail mr10 pull-left" width="100" alt="">
					<h5 class="mt10">
						<a href="{{ url('/good',['id'=>$l->good->id]) }}" target="_blank">{{ $l->good_title }}</a>
                    </h5>
					@if($l->good_spec_name != '')<p class="label label-warning mt10">{{ $l->good_spec_name }}</p>@endif
				</td>
				<td width="15%">数量：{{ $l->nums }}</td>
				<td width="15%">单价：<span class="good_prices color_1">￥{{ $l->price }}</span></td>
				<td width="15%">小计：<span class="color_2">￥<span class="one_total_price">{{ $l->total_prices }}</span></span></td>
			</tr>
			@endforeach
			</table>
    	</td>
    </tr>
		@endforeach
</table>
</form>
<div class="clearfix">
	<div class="pull-left" data-toggle="buttons">
		<div class="btn-group">
			<label class="btn btn-xs btn-primary"><input type="checkbox" autocomplete="off" class="checkall">全选</label>
		</div>
		@if(App::make('com')->ifCan('order-allship'))
		<span class="btn btn-xs btn-success btn_allship">发货</span>
		@endif
		@if(App::make('com')->ifCan('order-allclose'))
		<span class="btn btn-xs btn-warning btn_close">关闭</span>
		@endif
	</div>
	<div class="pull-right">
	    {!! $orders->appends(['q'=>$q,'key'=>$key,'status'=>$status,'prom_type'=>$prom_type,'starttime'=>$starttime,'endtime'=>$endtime,'shipstatus'=>$shipstatus,'paystatus'=>$paystatus])->links() !!}
	</div>
</div>
<script>
	$(function(){
		// 导出订单表
		$('.btn_order').click(function(){
			$('.form_excel').attr('action',"{{ url('/console/index/excel_order') }}").submit();
		});
		// 确实要全都关闭吗
		$('.btn_close').click(function(){
			if (!confirm("确实要全都关闭吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/order/allclose') }}",'method':'post'}).submit();
			}
		});
		// 确实要全都发货吗
		$('.btn_allship').click(function(){
			if (!confirm("确实要全都发货吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/order/allship') }}",'method':'post'}).submit();
			}
		});
		// 确实要全都自提吗
		$('.btn_ziti').click(function(){
			if (!confirm("确实要全都自提吗?")){
				return false;
			}else{
				$('.form_submit').attr({'action':"{{ url('/console/order/allziti') }}",'method':'post'}).submit();
			}
		});
		// 全选
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
	})
	laydate({
        elem: '#laydate',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime:true,
        istoday: true, //是否显示今天
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
        istoday: true, //是否显示今天
    });
</script>
@endsection