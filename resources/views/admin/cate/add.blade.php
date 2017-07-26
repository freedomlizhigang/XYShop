@extends('admin.right')

@section('content')
<form action="javascript:ajax_submit();" method="post" id="form_ajax">
    {{ csrf_field() }}
    <input type="hidden" name="data[parentid]" value="{{ $pid }}" />

    <table class="table table-striped">
        <tr>
            <td class="td_left">栏目名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-md" value="{{ old('data.name') }}">
                <p class="input-info"><span class="color_red">*</span>最多50字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">缩略图：</td>
            <td>
                @component('admin.component.thumb')
                    @slot('filed_name')
                        thumb
                    @endslot
                    {{ old('data.thumb') }}
                @endcomponent
            </td>
        </tr>

        <tr>
            <td class="td_left">SEO标题：</td>
            <td>
                <input type="text" name="data[title]" class="form-control input-md" value="{{ old('data.title') }}">
            </td>
        </tr>
        
        <tr>
            <td class="td_left">关键字：</td>
            <td>
                <input type="text" name="data[keyword]" class="form-control input-md" value="{{ old('data.title') }}">
            </td>
        </tr>

        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control input-lg" rows="5">{{ old('data.describe') }}</textarea>
            </td>
        </tr>

        <tr>
            <td class="td_left">内容：</td>
            <td>
                @component('admin.component.ueditor')
                    @slot('id')
                        container
                    @endslot
                    @slot('filed_name')
                        content
                    @endslot
                    {!! old('data.content') !!}
                @endcomponent
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">模板：</td>
            <td>
                <input type="text" name="data[theme]" class="form-control input-sm" value="list">
                <p class="input-info">默认list</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ old('data.sort',0) }}" class="form-control input-xs">
                <p class="input-info">数字越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">类型：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[type]" checked="checked" class="input-radio" value="0">
                    栏目</label>
                <label class="radio-inline"><input type="radio" name="data[type]" class="input-radio" value="1">单页</label>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/cate/add',['id'=>$pid]) }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>
    </table>
    


</form>
@endsection