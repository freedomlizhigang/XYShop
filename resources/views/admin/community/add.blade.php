<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    
    <table class="table table-striped">

        <tr>
            <td class="td_left">省：</td>
            <td>
                <select name="data[areaid1]" id="areaid1" class="form-control input-sm"></select>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">市：</td>
            <td>
                <select name="data[areaid2]" id="areaid2" class="form-control input-sm"></select>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">县：</td>
            <td>
                <select name="data[areaid3]" id="areaid3" class="form-control input-sm"></select>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">社区名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-sm" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span></p>
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
               <label class="radio-inline"><input type="radio" name="data[is_show]" checked="checked" class="input-radio" value="1">
                    是</label>
                <label class="radio-inline"><input type="radio" name="data[is_show]" class="input-radio" value="0">否</label>
            </td>
        </tr>
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/community/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>

<script>
    $(function(){
        var url = "{{ url('console/area/get') }}";
        $.get(url + '/0',function(str){
            $("#areaid1").html(str);
            var areaid2 = $("#areaid1").val();
            $.get(url + '/' + areaid2,function(str){
                $("#areaid2").html(str);
                var areaid3 = $("#areaid2").val();
                $.get(url + '/' + areaid3,function(str){
                    $("#areaid3").html(str);
                })
            })
        })
        $('#areaid1').change(function(event) {
            var ids = $(this).val();
            $.get(url + '/' + ids,function(str){
                $("#areaid2").html(str);
            })
        });
        $('#areaid2').change(function(event) {
            var ids = $(this).val();
            $.get(url + '/' + ids,function(str){
                $("#areaid3").html(str);
            })
        });
    })
</script>