<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="section_id">
            部门：
            <span class="color_red">*</span>
        </label>
        <select name="data[section_id]" id="data[section_id]" class="form-control">
            <option value="">请选择</option>
            @foreach($section as $r)
            <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="role_id">
            角色：
            <span class="color_red">*</span>
        </label>
        @foreach($rolelist as $r)
        <label class="checkbox-inline"><input type="checkbox" name="role_id[]" value="{{ $r->
            id }}"> {{ $r->name }}</label>
        @endforeach
    </div>
    <div class="form-group">
        <label for="name">
            用户名：
            <span class="color_red">*</span>
        </label>
        <input type="text" name="data[name]" class="form-control" value="{{ old('data.name') }}">
    </div>
    <div class="form-group">
        <label for="realname">
            真实姓名：
            <span class="color_red">*</span>
        </label>
        <input type="text" name="data[realname]" class="form-control" value="{{ old('data.realname') }}">
    </div>
    <div class="form-group">
        <label for="name">
            邮箱：
            <span class="color_red">*</span>
        </label>
        <input type="text" name="data[email]" class="form-control" value="{{ old('data.email') }}">
    </div>
    <div class="form-group">
        <label for="name">
            密码：
            <span class="color_red">*</span>
        </label>
        <input type="password" name="data[password]" class="form-control" value="{{ old('data.password') }}">
    </div>
    <div class="form-group">
        <label for="name">
            确认密码：
            <span class="color_red">*</span>
        </label>
        <input type="password" name="data[password_confirmation]" class="form-control" value="{{ old('data.password_confirmation') }}">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <div onclick='ajax_submit_form("form_ajax","{{ url('/console/admin/add') }}")' name="dosubmit" class="btn btn-info">提交</div>
    </div>
</form>