@extends('home.layout')


@section('title')
    <title>购物车-{{ cache('config')['sitename'] }}</title>
@endsection


@section('content')
<div class="container-fluid bgf">
	<div class="user_order_list clearfix">
		@foreach($orders as $o)
        <div class="user_order_list_top clearfix">
            <h5>
                <span class="order_font">订单单号：</span>{{ $o->order_id }}
                @if($o->tuan_id != 0)
                <span class="label label-success">团</span>
                @endif
            </h5>
            <p class="clearfix"><span class="order_font">订单总价：</span><strong class="color_2">￥{{ $o->total_prices }}</strong></p>
            <p class="created_at"><span class="order_font">下单时间：</span>{{ $o->created_at }}</p>
            @if($o->shipstatus == 1)
            <p class="created_at"><span class="order_font">发货时间：</span>{{ $o->ship_at }}</p>
            @endif
            @if($o->orderstatus == 2 || $o->orderstatus == 0)
            <p class="created_at"><span class="order_font">完成时间：</span>{{ $o->confirm_at }}</p>
            @endif
            @if($status == 1)
        	<a href="{{ url('shop/order/pay',['oid'=>$o->id]) }}" class="btn btn-sm btn-success topay"><span class="glyphicon glyphicon-jpy topay"></span>去支付</a>
        	<div class="btn btn-sm btn-default topay remove_order" data-oid="{{ $o->id }}"><span class="glyphicon glyphicon-remove-circle"></span> 取消</div>
            @endif
            @if($status == 2)
        	<span class="color_l pull-right">已支付</span>
            <div class="btn btn-sm btn-default topay remove_order" data-oid="{{ $o->id }}"><span class="glyphicon glyphicon-remove-circle"></span> 取消</div>
            @endif
            @if($status == 3)
            <a href="{{ url('shop/order/ship',['oid'=>$o->id]) }}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-compressed topay"></span>确认收货</a>
        	</p>
            @endif
            @if($status == 4 || $status == 5)
            <p><span class="order_font">订单状态：</span>
	            @if($o->orderstatus == 0)
            	<span class="color-red">已关闭</span>
            	@elseif($o->orderstatus == 1)
            	<span class="color-green">正常</span>
            	<!-- 三天内完成的可申请退货 -->
            	@elseif($o->orderstatus == 2)
            	<span class="color-success">已完成</span>
            	@elseif($o->orderstatus == 3)
            	<span class="text-warning">已申请退货</span>
            	@else
            	<span class="text-danger">结束</span>
            	@endif
            </p>
            @endif
        </div>
			<div class="good_cart_list good_cart_list_order overh">
			@foreach($o->good as $l)
			<div class="mt5 good_cart_list_div">
				<div class="media">
					<a href="{{ url('/shop/good',['id'=>$l->good->id]) }}" class="pull-left"><img src="{{ $l->good->thumb }}" width="100" class="media-object img-thumbnail" alt=""></a>
					<div class="media-body">
						<h4 class="mt5 cart_h4"><a href="{{ url('/shop/good',['id'=>$l->good->id]) }}">{{ $l->good->title }}</a>
                        </h4>
                        @if($l->good_spec_name != '')<span class="btn btn-sm btn-info mt10">{{ $l->good_spec_name }}</span>@endif
                        <!-- 价格 -->
                        <p class="fs12">价格：<span class="good_prices color_l">￥{{ $l->price }}</span></p>
                        <p class="fs12">数量：<span class="good_prices color_l">{{ $l->nums }}</span></p>
                        <p class="fs12">小计：<span class="good_prices color_l">￥{{ $l->total_prices }}</span></p>
                        <p class="clearfix fs12">
                            @if($l->commentstatus == 0 && $o->orderstatus == 2)
                            <a href="{{ url('shop/good/comment',['oid'=>$o->id,'gid'=>$l->good->id]) }}" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-edit"></span>提交评价</a>
                            @endif
                            <!-- 三天内完成的可申请退货 -->
                            @if($o->orderstatus == 2 && (time() - strtotime($o->updated_at)) < 259200 && $l->status == 1)
                            <a href="{{ url('user/order/tui',['id'=>$o->id,'gid'=>$l->good->id]) }}" class="btn btn-sm btn-default topay"><span class="glyphicon glyphicon-transfer"></span>申请退货</a>
                            @endif
                        </p>
					</div>
				</div>
			</div>
			@endforeach
		</div>
   		@endforeach
	</div>
	<div class="pages">
	    {!! $orders->links() !!}
	</div>
</div>

<!-- 加载中 -->
<div class="pos_bg">
    <div class="pos_text">取消中...</div>
</div>

<script>
    $(function(){
        var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
        // 移除订单
        $(".remove_order").on('click',function(){
            if(before_request == 0)return false;
            $('.pos_bg').show();
            var that = $(this);
            var oid = that.attr('data-oid');
            before_request = 0;
            $.post(host+'api/common/good/removeorder',{oid:oid},function(d){
                var ss = jQuery.parseJSON(d);
                if (ss.code == 1) {
                    // 刷新页面
                    location.reload();
                }
                else
                {
                    alert('取消失败，请稍后再试！');
                    $('.pos_bg').hide();
                }
                before_request = 1;
                return;
            }).error(function() {
                before_request = 1;
                return;
            });
        });
    })
</script>
@include('home.foot')
@endsection