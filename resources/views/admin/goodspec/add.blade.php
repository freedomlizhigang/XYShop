<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">商品分类：</td>
            <td>
                <select name="data[good_cate_id]" class="form-control input-sm">
                    <option value="">选择栏目</option>
                    {!! $treeHtml !!}
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">名称：</td>
            <td>
                <input type="text" name="data[name]" value="{{ old('data.name') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">筛选：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[search_type]" class="input-radio" value="0">否</label>
                <label class="radio-inline"><input type="radio" name="data[search_type]" checked="checked" class="input-radio" value="1">是</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">规格项：</td>
            <td>
                <textarea name="items" class="form-control" rows="5">{{ old('items') }}</textarea>
                <p class="input-info">一行为一个规格项</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ old('data.sort',0) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>越大越靠前</p>
            </td>
        </tr>

    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/goodspec/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>