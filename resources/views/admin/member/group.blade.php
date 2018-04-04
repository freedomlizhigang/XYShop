<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <table class="table table-striped">
        <tr>
            <td class="td_left">会员组：</td>
            <td>
                <select name="gid" class="form-control">
                    @foreach($group as $g)
                    <option value="{{ $g->id }}" @if($info->gid == $g->id) selected="selected" @endif>{{ $g->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        
    </table>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/user/editgroup',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>