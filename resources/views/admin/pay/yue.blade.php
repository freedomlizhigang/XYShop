@extends('admin.right')

@section('content')
<form action="" class="form-inline" id="form_ajax" method="post">
    {{ csrf_field() }}
    <table class="table table-striped">
        <tr>
            <td class="td_left">支付方式：</td>
            <td>
                <img src="{{ $info['thumb'] }}" width="30" height="auto" alt="">
            </td>
        </tr>
        
        <tr>
            <td class="td_left">支付介绍：</td>
            <td>
                {{ $info['content'] }}
            </td>
        </tr>

        <tr>
            <td class="td_left">状态：</td>
            <td>
                <label class="radio-inline"><input type="radio" name="data[paystatus]" checked="checked" class="input-radio" value="1">
                    启用</label>
                <label class="radio-inline"><input type="radio" name="data[paystatus]" class="input-radio" value="0">禁用</label>
            </td>
        </tr>

        <tr>
            <td class="td_left"></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/pay/edit/3') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>

    </table>
</form>
@endsection