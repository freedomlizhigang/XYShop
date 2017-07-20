@extends('admin.right')

@section('content')
<form action="" id="form_ajax" method="post">
	{{ csrf_field() }}
    
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
            	@if($id == '0')
            	<label for="catid">选择分类：<span class="color_red">*</span>必填，商品归哪个分类</label>
            	<select name="data[cate_id]" id="catid" class="form-control">
            		<option value="0">选择分类</option>
            		{!! $cate !!}
            	</select>
                @else
                <input type="hidden" name="data[cate_id]" value="{{ $id }}"/>
                @endif
            </div>

            <div class="form-group">
                <label for="title">商品标题：<span class="color_red">*</span>不超过255字符</label>
            	<input type="text" name="data[title]" value="{{ old('data.title') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="pronums">商品编码：<span class="color_red">*</span></label>
                <input type="text" name="data[pronums]" value="{{ old('data.pronums') }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="price">价格：<span class="color_red">*</span>数字</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="data[price]" value="" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="store">库存：数字</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="data[store]" value="10000" class="form-control">
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="weight">单件重量：数字</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="data[weight]" value="1" class="form-control">
                    </div>
                </div>
            </div>


    

            <div class="form-group">
                <label for="tags">标签：</label>
                <div class="row">
                    <div class="col-xs-6">
                        <select name="data[tags]" class="form-control">
                            <option value="">无</option>
                            @foreach($tags as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="isxs">是否限时抢购：</label>
                <label class="radio-inline"><input type="radio" name="data[isxs]" class="input-radio" value="1">
                    启用</label>
                <label class="radio-inline"><input type="radio" name="data[isxs]" checked="checked" class="input-radio" value="0">禁用</label>
            </div>

            <div class="form-group">
                <label for="starttime">开始时间：</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="data[starttime]" class="form-control" value="" id="laydate">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="endtime">结束时间：</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="data[endtime]" class="form-control" value="" id="laydate2">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="isxl">是否限量抢购：</label>
                <label class="radio-inline"><input type="radio" name="data[isxl]" class="input-radio" value="1">
                    启用</label>
                <label class="radio-inline"><input type="radio" name="data[isxl]" checked="checked" class="input-radio" value="0">禁用</label>
            </div>
            
            <div class="form-group">
                <label for="xlnums">限制数量：数字，0为不限制</label>
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="data[xlnums]" value="0" class="form-control">
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="keyword">关键字：不超过255字符</label>
                <textarea name="data[keyword]" class="form-control">{{ old('data.keyword') }}</textarea> 
            </div>

            <div class="form-group">
            	<label for="describe">描述：不超过255字符</label>
            	<textarea name="data[describe]" class="form-control" rows="4">{{ old('data.describe') }}</textarea> 
            </div>

            <div class="form-group">
                <label for="thumb">缩略图：图片类型jpg/jpeg/gif/png，大小不超过2M</label>
                <div class="clearfix row thumb_btn">
                    <div class="col-xs-6">
                        <input type="text" readonly="readonly" name="data[thumb]" id="url3" value="{{ old('data.thumb') }}" class="form-control">
                    </div>
                    <div value="选择图片" id="image3"></div>
                </div>
                <img src=""  width="150" class="img-responsive thumb-src hidden"" alt="">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="content">内容：<span class="color_red">*</span></label>
        <!-- 加载编辑器的容器 -->
        <textarea name="data[content]" class="form-control" id="editor_id">{{ old('data.content') }}</textarea> 
    </div>
    
    
    <!-- 产品规格 -->
    <div id="good_spec" class="form-group">
        
    </div>
    
    <!-- 产品属性 -->
    <div id="good_attr" class="form-group">
        
    </div>
    <script>
        $(function(){
            // 修改产品分类时，取出对应的属性及规格
            $('#catid').change(function() {
                var cid = $('#catid').val();
                var attr_url = "{{url('/console/good/goodattr')}}";
                var spec_url = "{{url('/console/good/goodspec')}}";
                var good_id = 0;
                // 属性
                $.get(attr_url,{'cid':cid,'good_id':good_id},function(d){
                    $("#good_attr").html(d);

                });
                // 规格
                $.get(spec_url,{'cid':cid,'good_id':good_id},function(d){
                    $("#good_spec").html(d);
                    ajaxGetSpecInput(); // 触发完  马上触发 规格输入框
                });
            });
        })
    </script>


    <div class="form-group">
        <label for="sort">排序：数字</label>
        <div class="row">
            <div class="col-xs-1">
                <input type="text" name="data[sort]" value="0" class="form-control">
            </div>
        </div>
    </div>

    <div class="btn-group mt10">
        <button type="reset" name="reset" class="btn btn-warning">重填</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/good/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>


<!-- 实例化编辑器 -->
<script type="text/javascript">
    // 上传时要填上sessionId与csrf表单令牌，否则无法通过验证
    KindEditor.ready(function(K) {
        window.editor = K.create('#editor_id',{
            minHeight:350,
            uploadJson : "{{ url('console/attr/uploadimg') }}",
            extraFileUploadParams: {
                session_id : "{{ session('user')->id }}",
            }
        });
        var uploadbutton = K.uploadbutton({
            button : K('#image3')[0],
            fieldName : 'imgFile',
            url : "{{ url('console/attr/uploadimg') }}",
            extraFileUploadParams: {
                session_id : "{{ session('user')->id }}",
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
    laydate({
        elem: '#laydate',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
    laydate({
        elem: '#laydate2',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
</script>

@endsection