@extends('admin.right')

@section('content')
<form action="javascript:;" method="post" id="form_ajax">
    {{ csrf_field() }}
    <table class="table table-striped">

        <tr>
            <td class="td_left">选择分类：</td>
            <td>
                <select name="" id="catid_one" onchange="get_goodcate(this.value,'catid_two',0)" class="form-control input-sm">
                    <option value="0">顶级分类</option>
                </select>
                <select name="" id="catid_two" onchange="get_goodcate(this.value,'catid',0);get_brand(document.getElementById('catid_one').value,this.value,'brand_id',0)" class="form-control input-sm">
                    <option value="0">二级分类</option>
                </select>
                <select name="data[cate_id]" id="catid" class="form-control input-sm">
                    <option value="0">三级分类</option>
                </select>
                <p class="input-info"><span class="color_red">*</span>必填，商品归哪个分类</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">商品标题：</td>
            <td>
                <input type="text" name="data[title]" value="{{ old('data.title') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>不超过255字符</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">商品编码：</td>
            <td>
                <input type="text" name="data[pronums]" value="{{ old('data.pronums') }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span></p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">产品规格：</td>
            <td>
                <div id="good_spec" class="form-group"></div>
            </td>
        </tr>

        <tr>
            <td class="td_left">市场价：</td>
            <td>
                <input type="text" name="data[market_price]" value="{{ old('data.market_price',1) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">成本价：</td>
            <td>
                <input type="text" name="data[cost_price]" value="{{ old('data.cost_price',1) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">本店价：</td>
            <td>
                <input type="text" name="data[shop_price]" value="{{ old('data.shop_price',1) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">库存：</td>
            <td>
                <input type="text" name="data[store]" value="{{ old('data.store',10000) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">单件重量：</td>
            <td>
                <input type="text" name="data[weight]" value="{{ old('data.weight',1000) }}" class="form-control input-sm">
                <p class="input-info"><span class="color_red">*</span>数字，单位：克</p>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">推荐：</td>
            <td>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-xs btn-info">
                        <input type="radio" name="data[is_pos]" autocomplete="off" value="1"> 是
                    </label>
                    <label class="btn btn-xs btn-info active">
                        <input type="radio" name="data[is_pos]" autocomplete="off" checked value="0"> 否
                    </label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="td_left">新品：</td>
            <td>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-xs btn-info">
                        <input type="radio" name="data[is_new]" autocomplete="off" value="1"> 是
                    </label>
                    <label class="btn btn-xs btn-info active">
                        <input type="radio" name="data[is_new]" autocomplete="off" checked value="0"> 否
                    </label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="td_left">热卖：</td>
            <td>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-xs btn-info">
                        <input type="radio" name="data[is_hot]" autocomplete="off" value="1"> 是
                    </label>
                    <label class="btn btn-xs btn-info active">
                        <input type="radio" name="data[is_hot]" autocomplete="off" checked value="0"> 否
                    </label>
                </div>
            </td>
        </tr>
 
        <tr>
            <td class="td_left">上架时间：</td>
            <td>
                <input type="text" name="data[lasttime]" value="{{ old('data.lasttime',date('Y-m-d H:i:s')) }}" class="form-control input-sm" id="lasttime">
                <p class="input-info"><span class="color_red">*</span>从此刻开始商品可以被查到</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">下架时间：</td>
            <td>
                <input type="text" name="data[lowertime]" value="{{ old('data.lowertime',date('Y-m-d H:i:s')) }}" class="form-control input-sm" id="lowertime">
                <p class="input-info"><span class="color_red">*</span>从此刻开始商品不再能被查到</p>
            </td>
        </tr>

        <tr>
            <td class="td_left">关键字：</td>
            <td>
                <input type="text" name="data[keyword]" value="{{ old('data.keyword') }}" class="form-control input-md">
            </td>
        </tr>


        <tr>
            <td class="td_left">描述：</td>
            <td>
                <textarea name="data[describe]" class="form-control" rows="5">{{ old('data.describe') }}</textarea>
            </td>
        </tr>
        
        <tr>
            <td class="td_left">缩略图：</td>
            <td>
                @component('admin.component.good_thumb')
                    @slot('filed_name')
                        thumb
                    @endslot
                    {{ old('data.thumb') }}
                @endcomponent
            </td>
        </tr>

        <tr>
            <td class="td_left">相册：</td>
            <td>
                @component('admin.component.album')
                    @slot('filed_name')
                        album
                    @endslot
                    {{ old('data.album') }}
                @endcomponent
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
            <td class="td_left">排序：</td>
            <td>
                <input type="text" name="data[sort]" value="{{ old('data.sort',0) }}" class="form-control input-xs">
                <p class="input-info"><span class="color_red">*</span>越大越靠前</p>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <div class="btn-group">
                    <button type="reset" name="reset" class="btn btn-xs btn-warning">重填</button>
                    <div onclick='ajax_submit_form("form_ajax","{{ url('/console/good/add') }}")' name="dosubmit" class="btn btn-xs btn-info">提交</div>
                </div>
            </td>
        </tr>


    </table>


</form>


<script type="text/javascript">
    $(function(){
        get_goodcate(0,'catid_one',0);
        // 修改产品分类时，取出对应的属性及规格
        var spec_url = "{{url('/console/good/goodspec')}}";
        var good_id = '';
        // 规格
        $.get(spec_url,{'good_id':good_id},function(d){
            $("#good_spec").html(d);
            ajaxGetSpecInput(); // 触发完  马上触发 规格输入框
        });
    });
    laydate({
        elem: '#lasttime',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
    laydate({
        elem: '#lowertime',
        format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
        istime: true,
    });
</script>

@endsection