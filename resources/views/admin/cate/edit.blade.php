@extends('admin.right')

@section('content')
<form action="javascript:ajax_submit();" method="post" id="form_ajax">
	{{ csrf_field() }}
	<table class="table table-striped">
		<tr>
            <td class="td_left">父栏目：</td>
            <td>
                <select name="data[parentid]" id="parentid" class="form-control input-sm">
                	<option value="0">选择栏目</option>
                	{!! $treeHtml !!}
                </select>
            </td>
        </tr>


        <tr>
            <td class="td_left">栏目名称：</td>
            <td>
                <input type="text" name="data[name]" class="form-control input-md" value="{{ $info->name }}">
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
                    {{ $info->thumb }}
                @endcomponent
            </td>
        </tr>

        <tr>
            <td class="td_left">SEO标题：</td>
            <td>
                <input type="text" name="data[title]" class="form-control input-md" value="{{ $info->title }}">
            </td>
        </tr>
        
        <tr>
            <td class="td_left">关键字：</td>
            <td>
                <input type="text" name="data[keyword]" class="form-control input-md" value="{{ $info->title }}">
            </td>
        </tr>

        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control input-lg" rows="5">{{ $info->describe }}</textarea>
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
                    {!! $info->content !!}
                @endcomponent
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">模板：</td>
            <td>
                <input type="text" name="data[theme]" class="form-control input-sm" value="{{ $info->theme }}">
                <p class="input-info">默认list</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ $info->sort }}" class="form-control input-xs">
                <p class="input-info">数字越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">类型：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[type]"@if ($info->type == 0) checked="checked"@endif class="input-radio" value="0">
                    栏目</label>
                <label class="radio-inline"><input type="radio" name="data[type]"@if ($info->type != 0) checked="checked"@endif class="input-radio" value="1">单页</label>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/cate/edit',['id'=>$info->id]) }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>
    </table>
			
</form>

@endsection