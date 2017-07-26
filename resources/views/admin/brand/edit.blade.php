<form action="javascript:;" method="post" class="form-inline" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">
        
        <tr>
            <td class="td_left">选择分类：</td>
            <td>
                <select name="data[goodcate_parentid]" onchange="get_goodcate(this.value,'catid_two',0)" id="catid_one" class="form-control input-sm">
                    <option value="0">顶级分类</option>
                </select>
                <select name="data[goodcate_id]" id="catid_two" class="form-control">
                    <option value="0">二级分类</option>
                </select>
                <p class="input-info"><span class="color_red">*</span>必填，品牌归哪个分类</p>
            </td>
        </tr>
        
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
                @component('admin.component.thumb')
                    @slot('filed_name')
                        thumb
                    @endslot
                    {{ $info->thumb }}
                @endcomponent
            </td>
        </tr>

        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control input-lg" rows="5">{{ $info->describe }}</textarea>
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
    $(function(){
        get_goodcate(0,'catid_one',"{{ $info->goodcate_parentid }}");
        get_goodcate("{{ $info->goodcate_parentid }}",'catid_two',"{{ $info->goodcate_id }}");
    });
</script>