@extends('admin.right')

@section('content')
<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">标题：</td>
            <td>
                <input type="text" name="data[title]" value="{{ old('data.title') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>
    
        <tr>
            <td class="td_left">类型：</td>
            <td>
                <select name="data[type]" class="form-control input-sm">
                    <option value="1">折扣</option>
                    <option value="2">减价</option>
                </select>
            </td>
        </tr>

        <tr>
            <td class="td_left">值：</td>
            <td>
                <input type="number" min="0" name="data[type_val]" value="{{ old('data.type_val',0) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>折扣直接写数值，减价写减去的金额</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">缩略图：</td>
            <td>
                <div class="clearfix thumb_btn">
                    <input type="text" readonly="readonly" name="data[thumb]" id="url3" value="{{ old('data.thumb') }}" class="form-control input-sm">
                    <div value="选择图片" id="image3"></div>
                </div>
                <p class="input-info"><span class="color_red">*</span>图片类型jpg/jpeg/gif/png，大小不超过2M</p>
                <img src="" class="thumb-src mt10 hidden img-responsive" width="300" alt="">
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
                    正常</label>
                <label class="radio-inline"><input type="radio" name="data[status]" class="input-radio" value="0">关闭</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">商品：</td>
            <td>
                <table class="table-bordered table">
                    <tr>
                        <td>商品名称</td>
                        <td>价格</td>
                        <td>库存</td>
                        <td>操作</td>
                    </tr>
                    <tbody class="good_lists">
                        
                    </tbody>
                </table>
                <p class="btn btn-xs btn-default btn_good mt5" data-toggle="modal" data-target="#myModal_hd">选择商品</p>
            </td>
        </tr>

        
        <tr>
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/promotion/add') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
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
        <iframe src="" id="good_select" name="good_select" height="600px" frameborder="0" width="100%" scrolling="auto" allowtransparency="true"></iframe>
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
            var url = "{{ url('console/good/select/2') }}";
            $('#good_select').attr("src",url);
            return;
        });
        // 提交
        $('.btn_good_select').click(function(){
            var iframe = $(window.frames["good_select"].document);
            var good_title = '';
            iframe.find('input:checked').each(function(){
                var n = $(this);
                var good_id = n.val();
                good_title += "<tr class='good_tr_" + good_id + "'><td>" + n.attr('data-title') + "</td><td>" + n.attr('data-price') + "￥</td><td>" + n.attr('data-store') + "</td><td><input name='good_id[]' type='hidden' value='" + good_id + "' /><span data-id='" + good_id + "' class='btn_del_good btn btn-xs btn-danger glyphicon glyphicon-trash'></span></td></tr>";

            });
            $('.good_lists').append(good_title);
            // 增加移除功能
            $('.good_lists').delegate('.btn_del_good','click',function(){
                console.log($(this).attr('data-id'));
                $('.good_tr_' + $(this).attr('data-id')).remove();
            });
            $('#myModal_hd').modal('hide');
        });
    });
    // 上传时要填上sessionId与csrf表单令牌，否则无法通过验证
    var uploadbutton = KindEditor.uploadbutton({
        button : $('#image3')[0],
        fieldName : 'imgFile',
        url : "{{ url('console/attr/uploadimg') }}",
        extraFileUploadParams: {
            session_id : "{{ session('console')->id }}",
        },
        afterUpload : function(data) {
            if (data.error === 0) {
                var url = KindEditor.formatUrl(data.url, 'absolute');
                $('#url3').val(url);
                $('.thumb-src').attr('src',url).removeClass('hidden');
            } else {
                alert(data.message);
            }
        },
        afterError : function(str) {
            alert('自定义错误信息: ' + str);
        }
    });
    uploadbutton.fileBox.change(function(e) {
        uploadbutton.submit();
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