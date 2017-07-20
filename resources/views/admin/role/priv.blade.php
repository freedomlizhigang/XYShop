@extends('admin.right')

@section('content')

<div class="priv-tree">
	<form action="" class="" method="post">
	{{ csrf_field() }}
	{!! $treePriv !!}

	<button type="submit" name="dosubmit" class="btn btn-xs btn-info clearfix">提交</button>
	</form>
</div>

<script>
	$(function(){
		$(".check-mr").bind('change',function(){
			if($(this).is(":checked"))
			{
				$(this).prop('checked',true);
				$(this).parent('.checkbox-inline').parent('li').children('ul').find('.check-mr').prop('checked',true);
			}
			else
			{
				$(this).prop("checked",false);
				$(this).parent('.checkbox-inline').parent('li').children('ul').find('.check-mr').prop("checked",false);
			}
		});
		var urlArr = [{!! $rids !!}];
		$(".check-mr").each(function(s){
			var thisVal = $(this).val();
			$.each(urlArr,function(i){
				if(urlArr[i] == thisVal){
					$(".check-mr").eq(s).prop("checked",true);
				}
			});
		});
	});
</script>

@endsection