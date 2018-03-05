$(function(){
	$(".confirm").click(function(){
		if (!confirm("确实要进行此操作吗?")){
			return false;
		}else{
			return true;
		}
	});
	$(".checkall").click(function(){
		$(".subcheck").prop("checked", this.checked);
	});
	// 弹出
	$('.btn_modal').on('click',function(){
		var url = $(this).attr('data-url');
		var title = $(this).attr('data-title');
		$('#modal_right').load(url);
		$('#myModalLabel_right').text(title);
		return;
	});
});
// 取子分类
function get_goodcate(pid = '0',subid = '',selectid = '0')
{
    // 取一级子分类
    var goodcateurl = host + '/api/common/goodcate';
    // 取子分类
    $.post(goodcateurl, {pid: pid}, function(d) {
        var ss = jQuery.parseJSON(d);
        if (ss.code == 1) {
            var str = '<option value="">选择分类</option>';
            $.each(ss.msg, function(i,n) {
                str += '<option value="' + n.id + '">' + n.name + '</option>';
            });
            $('#' + subid).html(str);
		    (selectid > 0) && $('#' + subid).val(selectid);
        }
        else
        {
            // 失败的时候重置数量
            console.log(ss.msg);
        }
    });
}
// 取地区
function get_area(pid = '0',subid = 'brand_id',selectid = '0')
{
    // 取一级子分类
    var goodcateurl = host + '/api/common/area';
    // 取子分类
    $.post(goodcateurl, {pid: pid}, function(d) {
        var ss = jQuery.parseJSON(d);
        if (ss.code == 1) {
            var str = '<option value="">选择地区</option>';
            $.each(ss.msg, function(i,n) {
                str += '<option value="' + n.id + '">' + n.areaname + '</option>';
            });
            $('#' + subid).html(str);
		    (selectid > 0) && $('#' + subid).val(selectid);
        }
        else
        {
            // 失败的时候重置数量
            console.log(ss.msg);
        }
    });
}
//导航高亮
function highlight_subnav(url){
    $('.left_list').find('a[href="'+url+'"]').addClass('active').closest('li').addClass('active');
}
/*
阻止默认的提交，使用ajax提交
 */
function ajax_submit()
{
	$("div[name='dosubmit']").trigger('click');
}
var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
// 通用表单提交
function ajax_submit_form(form_id,submit_url)
{
	if(before_request == 0)
    return false;
	var data = $('#' + form_id).serializeArray();
	// 如果有ueditor则同步内容
	/*if ($('.data_content').length > 0) {
		$('.data_content').each(function() {
			var thisId = $(this).attr('id');
			UE.getEditor(thisId).sync();
		});
	}*/
	before_request = 0; // 标识ajax 请求已经发出
    $.ajax({
		type: "POST",
		url: submit_url,
		data: data, // 你的formid                
		error: function(v) {
		    before_request = 1;
            // 提示信息转为json对象，并弹出提示
            var errors = $.parseJSON(v.responseText);
              // console.log(errors.errors);
            $.each(errors.errors, function(index, value) {
            // 弹出提示
				$('#error_alert').text(value).fadeIn('fast').delay(1000).fadeOut();
				// 标识ajax 请求成功，可以再次发送
	    		return false;
		    });
		},
		success: function(v) {
			before_request = 1; // 标识ajax 请求已经返回
			// 验证成功提交表单
			if (v.status == 1) {
				// 弹出提示
				$('#success_alert').text(v.msg).fadeIn('fast').delay(1000).fadeOut(function(){
					// 如果有返回URL，跳转，没有刷新页面
					if (v.url != '') {
						location.href = v.url;
					} else {
						location.href = location.href;
					}
				});
				return true;
			}
			else
			{
				// 弹出提示
				$('#error_alert').text(v.msg).fadeIn('fast').delay(1000).fadeOut();
		    	return false;
			}

		}
	});
}