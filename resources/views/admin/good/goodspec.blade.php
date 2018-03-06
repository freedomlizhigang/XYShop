<!-- 添加新规格 -->
<input type="hidden" name="good_id" value="{{ $gid }}">
<form action="javascript:;" method="get" class="form form-inline">
    <div class="form-group">
        <input type="text" class="form-control good-spec" name="good_spec" placeholder="添加新规格">
        <input type="text" class="form-control good-spec-item" name="good_spec_item" placeholder="属性值">
    </div>
    <div class="form-group">
        <span class="btn btn-xs btn-primary btn-goodspec">添加</span>
    </div>
</form>
<!-- 新规格参数 -->
<table class="table table-striped spec-select mt10">
</table>
<!--ajax 返回 规格对应的库存-->
<div id="goods_spec_table2">
	<table class='table' id='spec_input_tab'>
		<tr>
			<td><b>价格</b></td>
            <td><b>库存</b></td>
        </tr>
	</table>
</div>

<script>
    var good_id = $("input[name='good_id']").val();
    $(function(){
        // 初始化规格
        ajaxGetSpec();
        // 添加规格
        $('.btn-goodspec').click(function() {
            var url = "{{ url('/console/goodspec/add') }}";
            var goodspec = $('.good-spec').val();
            var goodspecitem = $('.good-spec-item').val();
            $.post(url,{'goodspec':goodspec,'good_id':good_id,'goodspecitem':goodspecitem},function(d){
                if (!d.code) {
                    $('#error_alert').text(d.msg).slideToggle().delay(1500).slideToggle();
                    return;
                }
                $('#success_alert').text(d.msg).slideToggle().delay(1500).slideToggle();
                $('.good-spec-item').val('');
                // 结果更新
                ajaxGetSpec();
            });
        });
        // 删除规格
        $('.spec-select').on('click', '.btn-spec-del', function() {
            var url = "{{ url('/console/goodspec/del') }}" + '/' + $(this).attr('data-id');
            $.get(url,function(d){
                if (!d.code) {
                    $('#error_alert').text(d.msg).slideToggle().delay(1500).slideToggle();
                    return;
                }
                $('#success_alert').text(d.msg).slideToggle().delay(1500).slideToggle();
                // 结果更新
                ajaxGetSpec();
            });
        });
		// 按钮切换 class
	   $(".spec-select").on('click','.btn',function(){
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
    // 加载出来所有的规格
    function ajaxGetSpec()
    {
        $.get("{{ url('/console/good/goodspecstr') }}",{'good_id':good_id},function(d){
            // console.log(d);
            $('.spec-select').html(d);
            // 找出来所有已经有的规格，对比一下生成出来输入框什么的
            ajaxGetSpecInput(); // 显示下面的输入框
        });
    }
	/**
	*  点击商品规格 下面输入框显示
	*/
	function ajaxGetSpecInput()
	{
	  var spec_arr = {};// 用户选择的规格数组 	  	  
		// 选中了哪些属性	  
		$(".spec-select .btn-success").each(function(){
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
		$.post("{{ url('/console/good/goodspecinput') }}",{'spec_arr':spec_arr,'goods_id':good_id},function(d){
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