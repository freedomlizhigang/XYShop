<form action="javascript:;" method="post" id="form_ajax">
	{{ csrf_field() }}
	<input type="hidden" name="oldparentid" value="{{ $info->parentid }}">

	<table class="table table-striped">

		<tr>
            <td class="td_left">父菜单：</td>
            <td>
                <select name="data[parentid]" id="parentid" class="form-control input-sm">
					<option value="0">顶级菜单</option>
					{!! $treeSelect !!}
				</select>
            </td>
        </tr>

        <tr>
            <td class="td_left">菜单名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ $info->name }}">
                <p class="input-info"><span class="color_red">*</span>最多50字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">URL：</td>
            <td>
                <input type="text" name="data[url]" value="{{ $info->url }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>如 menu/add</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">权限名称：</td>
            <td>
                <input type="text" name="data[label]" value="{{ $info->label }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>如 menu-addmenu</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">ICON：</td>
            <td>
                <input type="text" name="data[icon]" value="{{ $info->icon }}" class="form-control input-sm">
                <p class="input-info">二级分类时填写，boostrap里的图标</p>
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
                <label class="radio-inline"><input type="radio" name="data[display]"@if ($info['display'] == 1) checked="checked"@endif class="input-radio" value="1">
					是</label>
				<label class="radio-inline"><input type="radio" name="data[display]"@if ($info['display'] != 1) checked="checked"@endif class="input-radio" value="0">否</label>
            </td>
        </tr>
    </table>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/menu/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>