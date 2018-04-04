@extends('mobile.layout')

@section('content')
  <!-- 用户信息表单 -->
  <div class="center_userinfo bgc_f pd20">
    <form action="" method="post" class="pure-form pure-form-stacked">
      {{ csrf_field() }}
      <select name="area1" id="area1" onchange="get_area(this.value,'area2',0)" class="pure-input-1">
        <option value="选择省份">选择省份</option>
      </select>
      <select name="area2" id="area2" onchange="get_area(this.value,'area3',0)" class="pure-input-1">
        <option value="选择地区">选择地区</option>
      </select>
      <select name="area3" id="area3" onchange="get_community(this.value,'area4',0)" class="pure-input-1">
        <option value="选择区县">选择区县</option>
      </select>
      <select name="area4" id="area4" class="pure-input-1">
        <option value="选择区域">选择区域</option>
      </select>
      <input type="text" name="data[people]" placeholder="昵称" value="{{ old('data.people') }}" class="pure-input-1">
      <input type="text" name="data[phone]" placeholder="电话" value="{{ old('data.phone') }}" class="pure-input-1">
      <input type="text" name="data[address]" placeholder="地址" value="{{ old('data.address') }}" class="pure-input-1">
      <label class="pure-radio">
        <input type="radio" name="data[default]" value="1" checked="checked"> 默认
        <input type="radio" name="data[default]" value="0"> 否
      </label>
      <div class="mt20">
        <input type="reset" class="btn_reset" value="重置">
        <input type="submit" class="btn_submit" value="添加">
      </div>
    </form>
  </div>
  <script>
    $(function(){
      // 加载省份信息
      get_area(0,'area1',0);
    })
  </script>
@endsection