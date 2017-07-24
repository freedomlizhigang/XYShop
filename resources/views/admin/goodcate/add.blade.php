@extends('admin.right')

@section('content')
<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
	<input type="hidden" name="data[parentid]" value="{{ $pid }}" />
    <table class="table table-striped">

        <tr>
            <td class="td_left">分类名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span>最多100字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">手机名称：</td>
            <td>
                <input type="text" name="data[mobilename]" value="{{ old('data.mobilename') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>最多100字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">首页显示：</td>
            <td>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-xs btn-info active">
                        <input type="radio" name="data[ishome]" autocomplete="off" checked value="1"> 显示
                    </label>
                    <label class="btn btn-xs btn-info">
                        <input type="radio" name="data[ishome]" autocomplete="off" value="0"> 隐藏
                    </label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="td_left">菜单显示：</td>
            <td>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-xs btn-info active">
                        <input type="radio" name="data[ismenu]" autocomplete="off" checked value="1"> 显示
                    </label>
                    <label class="btn btn-xs btn-info">
                        <input type="radio" name="data[ismenu]" autocomplete="off" value="0"> 隐藏
                    </label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="td_left">缩略图：</td>
            <td>
                <div class="clearfix thumb_btn">
                    <input type="text" readonly="readonly" name="data[thumb]" id="url3" value="{{ old('data.thumb') }}" class="form-control input-sm">
                    <div value="选择图片" id="image3"></div>
                </div>
                <p class="input-info">图片类型jpg/jpeg/gif/png，大小不超过2M</p>
                <img src="" class="img-responsive thumb-src hidden mt10" width="130" alt="">
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
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/goodcate/add',['pid'=>$pid]) }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>

    </table>


</form>
<script>
	KindEditor.ready(function(K) {
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