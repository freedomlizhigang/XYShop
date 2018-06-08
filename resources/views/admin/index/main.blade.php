@extends('admin.right')


@if(App::make('com')->ifCan('index-consume'))
@section('rmenu')
    <a href="{{ url('/console/index/consume') }}" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-eye-open"></span> 消费情况</a>
@endsection
@endif


@section('content')

<div class="todays mt10">
    <form action="" class="form-inline form_excel" method="get">
        开始时间：<input type="text" name="starttime" class="form-control mr10" value="{{ $starttime }}" id="laydate">
        到：<input type="text" name="endtime" class="form-control" value="{{ $endtime }}" id="laydate2">
        <button class="btn btn-xs btn-info btn_search">查询</button>
        @if(App::make('com')->ifCan('index-excel_goods'))
        <button class="btn btn-xs btn-success btn_goods">导出销售统计表</button>
        @endif
        @if(App::make('com')->ifCan('index-excel_store'))
        <button class="btn btn-xs btn-warning btn_store">导出库房表</button>
        @endif
    </form>
</div>

<!-- 总订单-销售额-已收款-代发货 -->
<div class="todays todays-total mt20 rows clearfix">
    <div class="col-xs-3"><span class="label label-primary">总订单量：<span class="nums">{{ $data['today_ordernum'] }} 个</span></span></div>
    <div class="col-xs-3"><span class="label label-success">销售额：<span class="nums">{{ $data['today_prices'] }} 元</span></span></div>
    <div class="col-xs-3"><span class="label label-info">已收款数：<span class="nums">{{ $data['today_prices_real'] }} 元</span></span></div>
    <div class="col-xs-3"><span class="label label-danger">待发货：<span class="nums">{{ $data['today_ship'] }} 件</span></span></div>
</div>
<div class="todays rows clearfix">
    <!-- 销量统计折线图,新用户折线图 -->
    <div class="col-xs-4 mt20">
        <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
        <div class="todays-bg bg-info pd15">
            <h3 class="todays-t3">销量统计</h3>
            <div id="main"></div>
        </div>
    </div>
    <div class="col-xs-4 mt20">
        <div class="todays-bg bg-warning pd15">
            <h3 class="todays-t3">新注册用户数量</h3>
            <div id="main2"></div>
            <script type="text/javascript">
                $(function(){
                    var w = $('.todays').width();
                    $("#main ,#main2 ,.getheight").width(w/3).height((w/3)*4/6);
                    // 基于准备好的dom，初始化echarts实例
                    var myChart = echarts.init(document.getElementById('main'));
                    // 指定图表的配置项和数据
                    var option = {
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: {!! $order_chart->pluck('datetime') !!}
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            name: '销售额（元）',
                            type: 'line',
                            itemStyle: {
                                normal: {
                                    color: "#D06E6B",
                                    lineStyle: {
                                        color: "#D06E6B"
                                    }
                                }
                            },
                            data: {!! $order_chart->pluck('tprices') !!},
                            areaStyle: {normal: {}},
                            smooth: true
                        }]
                    };
                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                    // 基于准备好的dom，初始化echarts实例
                    var myChart2 = echarts.init(document.getElementById('main2'));
                    // 指定图表的配置项和数据
                    var option2 = {
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: {!! $new_user_chart->pluck('datetime') !!}
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            name: '新增',
                            type: 'line',
                            itemStyle: {
                                normal: {
                                    color: "#D48265",
                                    lineStyle: {
                                        color: "#D48265"
                                    }
                                }
                            },
                            data: {!! $new_user_chart->pluck('news') !!},
                            areaStyle: {normal: {}},
                            smooth: true
                        }]
                    };
                    // 使用刚指定的配置项和数据显示图表。
                    myChart2.setOption(option2);
                })
            </script>
        </div>
    </div>
    <!-- 今日销量最高商品10件,今日新用户 -->
    <div class="col-xs-4 mt20">
        <div class="todays-bg bg-success pd15">
            <h3 class="todays-t3">今日销量最高10件商品</h3>
            <ul class="top-goods getheight">
                @foreach($top_goods as $tg)
                <li><span class="label label-success mr5">{{ $tg->total_nums }}</span><a href="{{ url('good',$tg->good_id) }}" target="_blank">{{ $tg->good_title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
    .todays .col-xs-3,.todays .col-xs-4 {padding:0 5px;}
    .todays-bg {border-radius: 5px;}
    .todays-t3 { font-size: 18px; font-weight: normal; margin-bottom: 10px;}
    .top-goods li {line-height: 2.4; height: 2.4em; overflow: hidden; margin-bottom: 5px; border-bottom: #fff dashed 1px;}
    .new-user li {line-height: 2.4; height: 2.4em; overflow: hidden; width: 46%; margin: 0 2%; margin-bottom: 5px; border-bottom: #fff solid 1px; float: left;}
    .todays-total .label {font-weight: normal;display: inline-block; width: 100%; padding: 15px 25px;}
    .nums {font-size: 26px;font-weight: normal; display: block; margin-top: 10px;}
    .good_ship {margin-top: 20px;}
</style>

<script>
    $(function(){
        $('.btn_search').click(function(){
            $('.form_excel').attr('action',"").submit();
        });
        $('.btn_goods').click(function(){
            $('.form_excel').attr('action',"{{ url('/console/index/excel_goods') }}").submit();
        });
        $('.btn_store').click(function(){
            $('.form_excel').attr('action',"{{ url('/console/index/excel_store') }}").submit();
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
        format: 'YYYY-MM-DD 24:00:00', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
        istoday: true, //是否显示今天
    });
</script>

@endsection