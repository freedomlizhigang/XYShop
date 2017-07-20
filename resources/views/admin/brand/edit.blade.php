<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">名称：</td>
            <td>
                <input type="text" name="data[name]" value="{{ $info->name }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">Logo：</td>
            <td>
                <div class="clearfix thumb_btn">
                    <input type="text" readonly="readonly" name="data[icon]" id="url3" value="{{ $info->icon }}" class="form-control input-sm">
                    <div value="选择图片" id="image3"></div>
                </div>
                <img src="{{ $info->icon }}" class="thumb-src mt10 img-responsive" width="120" alt="">
            </td>
        </tr>

        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control" rows="5">{{ $info->describe }}</textarea>
            </td>
        </tr>

    </table>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/brand/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>
<!-- 实例化编辑器 -->
<script>
    // 上传时要填上sessionId与csrf表单令牌，否则无法通过验证
    var uploadbutton = KindEditor.uploadbutton({
        button : $('#image3'),
        fieldName : 'imgFile',
        url : "{{ url('console/attr/uploadimg') }}",
        extraFileUploadParams: {
            session_id : "{{ session('user')->id }}",
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
</script>