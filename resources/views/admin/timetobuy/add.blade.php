@extends('admin.right')

@section('content')

<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">
        <tr>
            <td class="td_left">标题：</td>
            <td>
                <input type="text" name="data[title]" value="{{ old('data.title') }}" class="form-control input-md">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">抢购价：</td>
            <td>
                <input type="text" name="data[price]" value="{{ old('data.price',0) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>数字，价格</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">库存：</td>
            <td>
                <input type="number" min="0" name="data[good_num]" value="{{ old('data.good_num',0) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>参加此次活动的库存</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">限购量：</td>
            <td>
                <input type="number" min="0" name="data[buy_max]" value="{{ old('data.buy_max',1) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>数字，每人限购数量</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" id="" class="form-control input-md" rows="5">{{ old('data.describe') }}</textarea>
                <p class="input-info">不要太长</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">开始时间：</td>
            <td>
                <input type="text" name="data[starttime]" class="form-control input-sm" value="{{ old('data.starttime') }}" id="laydate3">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">结束时间：</td>
            <td>
                <input type="text" name="data[endtime]" class="form-control input-sm" value="{{ old('data.endtime') }}" id="laydate4">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ old('data.sort',0) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">状态：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[status]" checked="checked" class="input-radio" value="1">
                    进行中</label>
                <label class="radio-inline"><input type="radio" name="data[status]" class="input-radio" value="0">关闭</label>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">商品：</td>
            <td>
                <input type="text" name="data[good_title]" class="good_select_title form-control input-sm" value="{{ old('data.good_title') }}">
                <input type="hidden" name="data[good_id]" class="good_select_id" value="{{ old('data.good_id') }}">
                <p class="btn btn-xs btn-default btn_good" data-toggle="modal" data-target="#myModal_hd">选择商品</p>
            </td>
        </tr>

        
        <tr>
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/timetobuy/add') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>



    </table>

</form>

<div class="modal bs-example-modal-lg fade" id="myModal_hd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">选择商品</h4>
      </div>
      <div class="modal-body">
        <iframe src="" id="good_select" name="good_select" frameborder="0" height="600px" width="100%" scrolling="auto" allowtransparency="true"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-info btn_good_select">提交</button>
      </div>
    </div>
  </div>
</div>


<!-- 实例化编辑器 -->
<script type="text/javascript">
    $(function(){
        // 选商品
        $('.btn_good').click(function(){
            var url = "{{ url('console/good/select') }}";
            $('#good_select').attr("src",url);
            return;
        });
        // 提交
        $('.btn_good_select').click(function(){
            var iframe = $(window.frames["good_select"].document);
            var good_id = iframe.find('input:checked').val();
            var good_title = iframe.find('input:checked').parent('.good_title').text();
            $('.good_select_id').val(good_id);
            $('.good_select_title').val(good_title);
            $('#myModal_hd').modal('hide');
        });
    });
    laydate({
        elem: '#laydate3',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
    laydate({
        elem: '#laydate4',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
</script>
@endsection