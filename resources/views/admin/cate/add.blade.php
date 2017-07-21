@extends('admin.right')

@section('content')
<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}
    <input type="hidden" name="data[parentid]" value="{{ $pid }}" />

    <table class="table table-striped">
        <tr>
            <td class="td_left">栏目名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-md" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span>最多50字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">缩略图：</td>
            <td>
                <div class="clearfix thumb_btn">
                    <input type="text" readonly="readonly" name="data[thumb]" id="url3" value="{{ old('data.thumb') }}" class="form-control input-md">
                    <div value="选择图片" id="image3"></div>
                </div>
                <img src="" class="thumb-src hidden img-responsive" width="200" alt="">
                <p class="input-info">图片类型jpg/jpeg/gif/png，大小不超过2M</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">SEO标题：</td>
            <td>
                <input type="text" name="data[title]" class="form-control input-md" value="{{ old('data.title') }}">
            </td>
        </tr>
        
        <tr>
            <td class="td_left">关键字：</td>
            <td>
                <input type="text" name="data[keyword]" class="form-control input-md" value="{{ old('data.title') }}">
            </td>
        </tr>

        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control input-lg" rows="5">{{ old('data.describe') }}</textarea>
            </td>
        </tr>

        <tr>
            <td class="td_left">内容：</td>
            <td>
                <!-- 加载编辑器的容器 -->
                <textarea name="data[content]" class="form-control input-lg" id="editor_id">{{ old('data.content') }}</textarea>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">模板：</td>
            <td>
                <input type="text" name="data[theme]" class="form-control input-sm" value="list">
                <p class="input-info">默认list</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ old('data.sort',0) }}" class="form-control input-xs">
                <p class="input-info">数字越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">类型：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[type]" checked="checked" class="input-radio" value="0">
                    栏目</label>
                <label class="radio-inline"><input type="radio" name="data[type]" class="input-radio" value="1">单页</label>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/cate/add',['id'=>$pid]) }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>
    </table>
    


</form>
<script>
     // 上传时要填上sessionId与csrf表单令牌，否则无法通过验证
    KindEditor.ready(function(K) {
        window.editor = K.create('#editor_id',{
            minHeight:350,
            uploadJson : "{{ url('console/attr/uploadimg') }}",
            extraFileUploadParams: {
                session_id : "{{ session('console')->id }}",
            }
        });
        var uploadbutton = K.uploadbutton({
            button : K('#image3')[0],
            fieldName : 'imgFile',
            url : "{{ url('console/attr/uploadimg') }}",
            extraFileUploadParams: {
                session_id : "{{ session('console')->id }}",
            },
            afterUpload : function(data) {
                if (data.error === 0) {
                    var url = K.formatUrl(data.url, 'absolute');
                    K('#url3').val(url);
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
    });
</script>
@endsection