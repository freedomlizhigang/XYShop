@extends('admin.right')

@section('content')
<form action="javascript:;" class="form-inline" id="form_ajax" method="post">
    {{ csrf_field() }}
    <!-- 提交返回用的url参数 -->
    
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
            <td class="td_left">商户ID：</td>
            <td>
                <input type="text" name="set[alipay_partner]" class="form-control input-md" value="{{ $setting['alipay_partner'] }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">商户KEY：</td>
            <td>
                <input type="text" name="set[alipay_key]" class="form-control input-md" value="{{ $setting['alipay_key'] }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">商户账号：</td>
            <td>
                <input type="text" name="set[alipay_account]" class="form-control input-md" value="{{ $setting['alipay_account'] }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">手机网站APPID：</td>
            <td>
                <input type="text" name="set[alipay_appid]" class="form-control input-md" value="{{ $setting['alipay_appid'] }}">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>


        <tr>
            <td class="td_left">商户私钥：</td>
            <td>
            <textarea name="set[alipay_privatekey]" rows="6" class="form-control input-md">{{ $setting['alipay_privatekey'] }}</textarea>
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>

        <tr>
            <td class="td_left">支付宝公钥：</td>
            <td>
                <textarea name="set[alipay_publickey]" rows="6" class="form-control input-md">{{ $setting['alipay_publickey'] }}</textarea>
                <p class="input-info"><span class="color_red">*</span></p>
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
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/pay/edit/1') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>

    </table>

</form>
@endsection