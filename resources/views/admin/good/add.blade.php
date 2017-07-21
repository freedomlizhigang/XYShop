@extends('admin.right')

@section('content')
<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">选择分类：</td>
            <td>
                <select name="data[cate_id]" id="catid" class="form-control input-sm">
                    <option value="0">选择分类</option>
                    {!! $cate !!}
                </select>
                <p class="input-info"><span class="color_red">*</span>必填，商品归哪个分类</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">商品标题：</td>
            <td>
                <input type="text" name="data[title]" value="{{ old('data.title') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">商品编码：</td>
            <td>
                <input type="text" name="data[pronums]" value="{{ old('data.pronums') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">价格：</td>
            <td>
                <input type="text" name="data[price]" value="{{ old('data.price',1) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">库存：</td>
            <td>
                <input type="text" name="data[store]" value="{{ old('data.store',10000) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">单件重量：</td>
            <td>
                <input type="text" name="data[weight]" value="{{ old('data.weight',1) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">标签：</td>
            <td>
                <select name="data[tags]" class="form-control input-sm">
                    <option value="">无</option>
                    @foreach($tags as $t)
                    <option value="{{ $t->name }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <td class="td_left">是否限时：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[isxs]" class="input-radio" value="1">
                    启用</label>
                <label class="radio-inline"><input type="radio" name="data[isxs]" checked="checked" class="input-radio" value="0">禁用</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">开始时间：</td>
            <td>
                <input type="text" name="data[starttime]" class="form-control input-sm" value="{{ old('data.starttime') }}" id="laydate">
            </td>
        </tr>

        <tr>
            <td class="td_left">结束时间：</td>
            <td>
                <input type="text" name="data[endtime]" class="form-control input-sm" value="{{ old('data.endtime') }}" id="laydate2">
            </td>
        </tr>
    
        <tr>
            <td class="td_left">是否限量：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[isxl]" class="input-radio" value="1">
                    启用</label>
                <label class="radio-inline"><input type="radio" name="data[isxl]" checked="checked" class="input-radio" value="0">禁用</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">限制数量：</td>
            <td>
                <input type="text" name="data[xlnums]" value="{{ old('data.xlnums',0) }}" class="form-control input-xs">
                <p class="input-info">数字，0为不限制</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">关键字：</td>
            <td>
                <input type="text" name="data[keyword]" value="{{ old('data.keyword') }}" class="form-control input-md">
            </td>
        </tr>


        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control" rows="5">{{ old('data.describe') }}</textarea>
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
            <td class="td_left">内容：</td>
            <td>
                <textarea name="data[content]" class="form-control" id="editor_id">{{ old('data.content') }}</textarea> 
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">产品规格：</td>
            <td>
                <div id="good_spec" class="form-group"></div>
            </td>
        </tr>

        <tr>
            <td class="td_left">产品属性：</td>
            <td>
                <div id="good_attr" class="form-group"></div>
            </td>
        </tr>

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
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/good/add') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>


    </table>


</form>


<!-- 实例化编辑器 -->
<script type="text/javascript">
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