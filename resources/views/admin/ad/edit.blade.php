<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">位置：</td>
            <td>
                <select name="data[pos_id]" class="form-control input-sm" id="">
                    @foreach($pos as $p)
                    <option value="{{ $p->id }}"@if($info->pos_id == $p->id) selected="selected" @endif>{{ $p->name }}</option>
                    @endforeach
                </select>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">标题：</td>
            <td>
                <input type="text" name="data[title]" value="{{ $info->title }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">缩略图：</td>
            <td>
                <div class="clearfix thumb_btn">
                    <input type="text" readonly="readonly" name="data[thumb]" id="url3" value="{{ $info->thumb }}" class="form-control input-sm">
                    <div value="选择图片" id="image3"></div>
                </div>
                <p class="input-info"><span class="color_red">*</span>图片类型jpg/jpeg/gif/png，大小不超过2M</p>
                <img src="{{ $info->thumb }}" class="thumb-src mt10 img-responsive" width="300" alt="">
            </td>
        </tr>

        <tr>
            <td class="td_left">链接：</td>
            <td>
                <input type="text" name="data[url]" value="{{ $info->url }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>URL</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">开始时间：</td>
            <td>
                <input type="text" name="data[starttime]" class="form-control input-sm" value="{{ $info->starttime }}" id="laydate3">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">结束时间：</td>
            <td>
                <input type="text" name="data[endtime]" class="form-control input-sm" value="{{ $info->endtime }}" id="laydate4">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ $info->sort }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">状态：</td>
            <td>
               <label class="radio-inline"><input type="radio" name="data[status]"@if($info->status == 1) checked="checked"@endif class="input-radio" value="1">
                    正常</label>
                <label class="radio-inline"><input type="radio" name="data[status]"@if($info->status != 1) checked="checked"@endif class="input-radio" value="0">关闭</label>
            </td>
        </tr>
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/ad/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>

</form>
<!-- 实例化编辑器 -->
<script type="text/javascript">
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