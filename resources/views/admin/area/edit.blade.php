<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">地区名称：</td>
            <td>
                <input type="text" name="data[areaname]" class="form-control input-sm" value="{{ $info->areaname }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ $info->sort }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>数字越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">是否显示：</td>
            <td>
               <label class="radio-inline"><input type="radio" name="data[is_show]"@if($info->is_show == 1) checked="checked"@endif class="input-radio" value="1">
                   是</label>
               <label class="radio-inline"><input type="radio" name="data[is_show]"@if($info->is_show != 1) checked="checked"@endif class="input-radio" value="0">否</label>
            </td>
        </tr>
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/area/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>