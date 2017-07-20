<form action="javascript:;" method="post" id="form_ajax">
	{{ csrf_field() }}
	<input type="hidden" name="data[parentid]" value="{{ $pid }}">
	<table class="table table-striped">
        <tr>
            <td class="td_left">菜单名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span>最多50字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">URL：</td>
            <td>
                <input type="text" name="data[url]" value="{{ old('data.url') }}" class="form-control  input-sm">
                <p class="input-info"><span class="color_red">*</span>如 menu/add</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">权限名称：</td>
            <td>
                <input type="text" name="data[label]" value="{{ old('data.label') }}" class="form-control  input-sm">
                <p class="input-info"><span class="color_red">*</span>如 menu-addmenu</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">ICON：</td>
            <td>
                <input type="text" name="data[icon]" value="{{ old('data.icon') }}" class="form-control  input-sm">
                <p class="input-info">二级分类时填写，boostrap里的图标</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ old('data.sort',0) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>数字越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">是否显示：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[display]" checked="checked" class="input-radio" value="1">
					是</label>
				<label class="radio-inline"><input type="radio" name="data[display]" class="input-radio" value="0">否</label>
            </td>
        </tr>
    </table>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/menu/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>