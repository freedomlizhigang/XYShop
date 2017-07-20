<label>商品属性：</label>
<table class="table table-striped table-bordered">
@if(count($good_attrs) > 0)
@foreach($list as $l)
<tr>
	<td class="text-right" width="100">{{ $l->name }}：</td>
	<td>
		@if($l->input_type == 0)
		<input type="text" class="form-control" value="{{ $good_attrs[$l->id]['good_attr_value'] }}" name="good_attr[{{$l->id}}]" >
		@endif
		@if($l->input_type == 1)
			@if($l->type == 1)
			<select name="good_attr[{{$l->id}}]" class="form-control">
				@foreach(explode('，',$l->value) as $v)
				<option value="{{ $v }}" @if($good_attrs[$l->id]['good_attr_value'] == $v)  selected="selected" @endif>{{$v}}</option>
				@endforeach
			</select>
			@elseif($l->type == 2)
			<div class="form-inline">
			@foreach(explode('，',$l->value) as $v)
				<label class="checkbox mr10">
					<input type="checkbox"@if(in_array($v,$good_attrs[$l->id]['good_attr_value'])))  checked="checked" @endif name="good_attr[{{$l->id}}][]" value="{{ $v }}">{{$v}}
				</label>
			@endforeach
			</div>
			@endif
		@endif
		@if($l->input_type == 2)
		<textarea name="good_attr[{{$l->id}}]" class="form-control" rows="5">{{ $good_attrs[$l->id]['good_attr_value'] }}</textarea>
		@endif
	</td>
</tr>
@endforeach
@else
@foreach($list as $l)
<tr>
	<td class="text-right" width="100">{{ $l->name }}：</td>
	<td>
		@if($l->input_type == 0)
		<input type="text" class="form-control" value="" name="good_attr[{{$l->id}}]" >
		@endif
		@if($l->input_type == 1)
			@if($l->type == 1)
			<select name="good_attr[{{$l->id}}]" class="form-control">
				@foreach(explode('，',$l->value) as $v)
				<option value="{{ $v }}">{{$v}}</option>
				@endforeach
			</select>
			@elseif($l->type == 2)
			<div class="form-inline">
			@foreach(explode('，',$l->value) as $v)
				<label class="checkbox mr10">
					<input type="checkbox" name="good_attr[{{$l->id}}][]" value="{{ $v }}">{{$v}}
				</label>
			@endforeach
			</div>
			@endif
		@endif
		@if($l->input_type == 2)
		<textarea name="good_attr[{{$l->id}}]" class="form-control" rows="5"></textarea>
		@endif
	</td>
</tr>
@endforeach
@endif
</table>