<label>商品规格：</label>
<table class="table table-striped table-bordered">
@foreach($list as $l)
<tr>
	<td class="text-right" width="100">{{ $l->name }}：</td>
	<td>
		@foreach($l->goodspecitem as $k => $gsi)
		<span class="btn btn-sm @if(in_array($gsi->id,$items_ids)) btn-success @else btn-info @endif" data-spec_id='{{ $l->id }}' data-item_id='{{ $gsi->id }}'>{{ $gsi->item }} <span class="glyphicon glyphicon-plus"></span></span>
		@endforeach
	</td>
</tr>
@endforeach
</table>
<!--ajax 返回 规格对应的库存-->
<div id="goods_spec_table2">
	<table class='table table-bordered' id='spec_input_tab'>
		<tr>
			<td><b>价格</b></td>
            <td><b>库存</b></td>
        </tr>
	</table>
</div>

<script>
	$(function(){
		// 按钮切换 class
	   $("#good_spec .btn").click(function(){
	    	   if($(this).hasClass('btn-success'))
	    	   {
	    		   $(this).removeClass('btn-success');
	    		   $(this).addClass('btn-info');		   
	    	   }
	    	   else
	    	   {
	    		   $(this).removeClass('btn-info');
	    		   $(this).addClass('btn-success');		   
	    	   }
	    	   ajaxGetSpecInput();	  	   	 
	    });
	})
	/**
	*  点击商品规格处罚 下面输入框显示
	*/
	function ajaxGetSpecInput()
	{
	  var spec_arr = {};// 用户选择的规格数组 	  	  
		// 选中了哪些属性	  
		$("#good_spec .btn-success").each(function(){
			var spec_id = $(this).attr('data-spec_id');
			var item_id = $(this).attr('data-item_id');
			// if($.type(spec_arr[spec_id]) !== "array"){spec_arr[spec_id] = [];}
			if(!spec_arr.hasOwnProperty(spec_id)){spec_arr[spec_id] = [];}
	    	spec_arr[spec_id].push(item_id);
		});
		ajaxGetSpecInput2(spec_arr); // 显示下面的输入框
	}
	/**
	* 根据用户选择的不同规格选项 
	* 返回 不同的输入框选项
	*/
	function ajaxGetSpecInput2(spec_arr)
	{		
	    var goods_id = $("input[name='goods_id']").val();
		$.post("{{ url('/console/good/goodspecinput') }}",{'spec_arr':spec_arr,'goods_id':goods_id},
			function(d){
				// console.log(d);
				   $("#goods_spec_table2").html('')
				   $("#goods_spec_table2").append(d);
				   hbdyg();  // 合并单元格
		});
	}
		
	 // 合并单元格
	function hbdyg() {
        var tab = document.getElementById("spec_input_tab"); //要合并的tableID
        var maxCol = 2, val, count, start;  //maxCol：合并单元格作用到多少列  
        if (tab != null) {
            for (var col = maxCol - 1; col >= 0; col--) {
                count = 1;
                val = "";
                for (var i = 0; i < tab.rows.length; i++) {
                    if (val == tab.rows[i].cells[col].innerHTML) {
                        count++;
                    } else {
                        if (count > 1) { //合并
                            start = i - count;
                            tab.rows[start].cells[col].rowSpan = count;
                            for (var j = start + 1; j < i; j++) {
                                tab.rows[j].cells[col].style.display = "none";
                            }
                            count = 1;
                        }
                        val = tab.rows[i].cells[col].innerHTML;
                    }
                }
                if (count > 1) { //合并，最后几行相同的情况下
                    start = i - count;
                    tab.rows[start].cells[col].rowSpan = count;
                    for (var j = start + 1; j < i; j++) {
                        tab.rows[j].cells[col].style.display = "none";
                    }
                }
            }
        }
    }
</script>