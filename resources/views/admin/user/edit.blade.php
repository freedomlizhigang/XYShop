<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}
    
    <table class="table table-striped">
        <tr>
            <td class="td_left">用户名：</td>
            <td>
                {{ $info->name }}
            </td>
        </tr>

        <tr>
            <td class="td_left">部门：</td>
            <td>
                <select name="data[section_id]" id="data[section_id]" class="form-control input-sm">
                    <option value="">请选择</option>
                    @foreach($section as $r)
                    <option value="{{ $r->id }}"@if($r->id == $info->section_id) selected="selected"@endif>{{ $r->name }}</option>
                    @endforeach
                </select>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        <tr>
            <td class="td_left">角色：</td>
            <td>
                @foreach($rolelist as $r)
                <label class="checkbox-inline"><input type="checkbox" class="check-mr" name="role_id[]" value="{{ $r->
                    id }}"> {{ $r->name }}</label>
                @endforeach
            </td>
        </tr>

        <tr>
            <td class="td_left">真实姓名：</td>
            <td>
                <input type="text" name="data[realname]" class="form-control input-sm" value="{{ old('data.realname',$info->realname) }}">
            </td>
        </tr>

        <tr>
            <td class="td_left">邮箱：</td>
            <td>
                <input type="text" name="data[email]" class="form-control input-sm" value="{{ old('data.email',$info->email) }}">
            </td>
        </tr>

        <tr>
            <td class="td_left">电话：</td>
            <td>
                <input type="text" name="data[phone]" class="form-control input-sm" value="{{ old('data.phone',$info->phone) }}">
            </td>
        </tr>
        
        @if($info['id'] != 1)
        <tr>
            <td class="td_left">状态：</td>
            <td>
                <input type="radio" name="data[status]"@if ($info['status'] == 1) checked="checked"@endif class="input-radio" value="1">
                启用
                <input type="radio" name="data[status]"@if ($info['status'] != 1) checked="checked"@endif class="input-radio" value="0">禁用
            </td>
        </tr>
        @endif
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/admin/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>

<script>
    $(function(){
        var rids = [{!! $rids !!}];
        $(".check-mr").each(function(s){
            var thisVal = $(this).val();
            $.each(rids,function(i){
                if(rids[i] == thisVal){
                    $(".check-mr").eq(s).prop("checked",true);
                }
            });
        });
    })
</script>