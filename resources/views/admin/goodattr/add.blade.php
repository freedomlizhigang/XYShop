<form action="javascript:;" method="post" class="form-inline" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">商品分类：</td>
            <td>
                <select name="" id="catid_one" onchange="get_goodcate(this.value,'catid_two',0)" class="form-control">
                    <option value="0">顶级分类</option>
                </select>
                <select name="" id="catid_two" onchange="get_goodcate(this.value,'catid',0);get_brand(document.getElementById('catid_one').value,this.value,'brand_id',0)" class="form-control">
                    <option value="0">二级分类</option>
                </select>
                <select name="data[good_cate_id]" id="catid" class="form-control">
                    <option value="0">三级分类</option>
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
            <td class="td_left">检索：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[search_type]" class="input-radio" value="0">不需要检索 </label>
                <label class="radio-inline"><input type="radio" name="data[search_type]" checked="checked" class="input-radio" value="1">关键字检索</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">取值方式：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[type]" checked="checked" class="input-radio" value="0">唯一属性</label>
                <label class="radio-inline"><input type="radio" name="data[type]" class="input-radio" value="1">单选</label>
                <label class="radio-inline"><input type="radio" name="data[type]" class="input-radio" value="2">多选</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">录入方式：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[input_type]" checked="checked" class="input-radio" value="0">手工录入</label>
                <label class="radio-inline"><input type="radio" name="data[input_type]" class="input-radio" value="1">从列表中选择</label>
                <label class="radio-inline"><input type="radio" name="data[input_type]" class="input-radio" value="2">多行文本框</label>
            </td>
        </tr>

        <tr>
            <td class="td_left">可选值列表：</td>
            <td>
                <textarea name="data[value]" class="form-control input-lg" rows="5">{{ old('data.value') }}</textarea>
                <p class="input-info"><span class="color_red">*</span>录入方式为手工或者多行文本时，此输入框不需填写。</p>
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
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/goodattr/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>

<script type="text/javascript">
    $(function(){
        get_goodcate(0,'catid_one',0);
    });
</script>