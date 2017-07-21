@extends('admin.right')

@section('content')
<form action="javascript:ajax_submit();" method="post" id="form_ajax">
	{{ csrf_field() }}
	<!-- 提交返回用的url参数 -->
	<input type="hidden" name="ref" value="{!! $ref !!}">
	
	<table class="table table-striped">
	    <tr>
	        <td class="td_left">选择栏目：</td>
	        <td>
	            <select name="data[catid]" id="catid" class="form-control input-sm">
	                <option value="0">选择栏目</option>
	                {!! $cate !!}
	            </select>
	            <p class="input-info"><span class="color_red">*</span>必填，文章归哪个栏目</p>
	        </td>
	    </tr>

	    <tr>
	        <td class="td_left">文章标题：</td>
	        <td>
	            <input type="text" name="data[title]" value="{{ $info->title }}" class="form-control">
	            <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
	        </td>
	    </tr>
	    
	    <tr>
	        <td class="td_left">描述：</td>
	        <td>
	            <textarea name="data[describe]" class="form-control input-lg" rows="5">{{ $info->describe }}</textarea>
	        </td>
	    </tr>

	     <tr>
	        <td class="td_left">缩略图：</td>
	        <td>
	            <div class="clearfix thumb_btn">
	                <input type="text" readonly="readonly" name="data[thumb]" id="url3" value="{{ $info->thumb }}" class="form-control input-md">
	                <div value="选择图片" id="image3"></div>
	            </div>
	            <p class="input-info">图片类型jpg/jpeg/gif/png，大小不超过2M</p>
	            <img src="{{ $info->thumb }}" class="thumb-src img-responsive" width="200" alt="">
	        </td>
	    </tr>

	    <tr>
	        <td class="td_left">内容：</td>
	        <td>
	            <!-- 加载编辑器的容器 -->
	            <textarea name="data[content]" class="form-control input-lg" id="editor_id">{{ $info->content }}</textarea>
	            <p class="input-info"><span class="color_red">*</span></p>
	        </td>
	    </tr>

	    <tr>
	        <td class="td_left">来源：</td>
	        <td>
	            <input type="text" name="data[source]" value="{{ $info->source }}" class="form-control input-sm">
	        </td>
	    </tr>

	    <tr>
	        <td class="td_left">排序：</td>
	        <td>
	            <input type="text" name="data[sort]" value="{{ $info->sort }}" class="form-control input-xs">
	            <p class="input-info">数字越大越靠前</p>
	        </td>
	    </tr>

	    <tr>
	        <td></td>
	        <td>
	            <div class="btn-group">
	                <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
	                <div onclick='ajax_submit_form("form_ajax","{{ url('/console/art/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
	            </div>
	        </td>
	    </tr>
	</table>

</form>

<script>
	$('#catid option[value=' + {{ $info->catid }} + ']').prop('selected','selected');

	// 上传时要填上sessionId与csrf表单令牌，否则无法通过验证
	KindEditor.ready(function(K) {
		window.editor = K.create('#editor_id',{
			minHeight:350,
			uploadJson : "{{ url('console/attr/uploadimg') }}",
            extraFileUploadParams: {
				session_id : "{{ session('console')->id }}",
            },
            afterCreate : function() {this.sync();}, 
            afterBlur: function(){this.sync();}
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