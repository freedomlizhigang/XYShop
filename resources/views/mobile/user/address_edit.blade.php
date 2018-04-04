@extends('mobile.layout')

@section('content')
  <!-- 用户信息表单 -->
  <div class="center_userinfo bgc_f pd20">
    <form action="" method="post" class="pure-form pure-form-stacked">
      {{ csrf_field() }}
      <select name="area1" id="area1" onchange="get_area(this.value,'area2',0)" class="pure-input-1">
        <option value="{{ $areaname[0] }}">{{ $areaname[0] }}</option>
      </select>
      <select name="area2" id="area2" onchange="get_area(this.value,'area3',0)" class="pure-input-1">
        <option value="{{ $areaname[1] }}">{{ $areaname[1] }}</option>
      </select>
      <select name="area3" id="area3" onchange="get_community(this.value,'area4',0)" class="pure-input-1">
        <option value="{{ $areaname[2] }}">{{ $areaname[2] }}</option>
      </select>
      <select name="area4" id="area4" class="pure-input-1">
        <option value="{{ $areaname[3] }}">{{ $areaname[3] }}</option>
      </select>
      <input type="text" name="data[people]" placeholder="昵称" value="{{ $info->people }}" class="pure-input-1">
      <input type="text" name="data[phone]" placeholder="电话" value="{{ $info->phone }}" class="pure-input-1">
      <input type="text" name="data[address]" placeholder="地址" value="{{ $info->address }}" class="pure-input-1">
      <label class="pure-radio">
        <input type="radio" name="data[default]" value="1"@if($info->default === 1) checked="checked"@endif> 默认
        <input type="radio" name="data[default]" value="0"@if($info->default === 0) checked="checked"@endif> 否
      </label>
      <div class="mt20">
        <input type="reset" class="btn_reset" value="重置">
        <input type="submit" class="btn_submit" value="修改">
      </div>
    </form>
  </div>
  <script>
    $(function(){
      // 加载省份信息
      get_area(0,'area1',"{{ $areaname[0] }}");
    })
  </script>
@endsection