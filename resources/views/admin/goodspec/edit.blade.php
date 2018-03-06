<form action="javascript:;" method="post" class="form-inline" id="form-spec-edit">
    {{ csrf_field() }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel_right">修改规格</h4>
    </div>

    <table class="table table-striped">
        <tr>
            <td class="td_left">名称：</td>
            <td>
                <input type="text" name="spec[name]" value="{{ $info->name }}" class="form-control input-sm spec-edit">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">规格项：</td>
            <td>
                <textarea name="items" class="form-control input-lg spec-item-edit" rows="5">@foreach($info->goodspecitem as $k => $gsi){{PHP_EOL}}{{ $gsi->item }}@endforeach</textarea>
                <p class="input-info">一行为一个规格项</p>
            </td>
        </tr>


    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div class="btn btn-info btn-spec-edit">提交</div>
    </div>
</form>
<script>
    $(function(){
        // 修改现在有的规格
        $('#form-spec-edit').on('click', '.btn-spec-edit', function(e) {
            var specedit = $('.spec-edit').val();
            var specitemedit = $('.spec-item-edit').val();
            var url = "{{ url('/console/goodspec/edit',$info->id) }}";
            $.post(url,{'goodspec':specedit,'goodspecitem':specitemedit,'good_id':good_id},function(d){
                console.log(d)
                if (!d.code) {
                    $('#error_alert').text(d.msg).slideToggle().delay(1500).slideToggle();
                    return;
                }
                $('#success_alert').text(d.msg).slideToggle().delay(1500).slideToggle();
                $('#myModal').modal('hide');
                // 结果更新
                ajaxGetSpec();
            });
        });
    })
</script>