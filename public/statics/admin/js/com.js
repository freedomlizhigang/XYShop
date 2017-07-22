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
	if ($('#editor_id').length > 0) {
		// console.log($('#editor_id').val());
		data.push({name:'data[content]',value:$('#editor_id').val()});
	}
	before_request = 0; // 标识ajax 请求已经发出
    $.ajax({
		type: "POST",
		url: submit_url,
		data: data, // 你的formid                
		error: function(v) {
		    before_request = 1;
			// console.log(v.responseText);
			// 提示信息转为json对象，并弹出提示
		    var errors = $.parseJSON(v.responseText);
		    $.each(errors, function(index, value) {
		    	// 弹出提示
				$('#error_alert').text(value).fadeIn('fast').delay(1000).fadeOut();
				// 标识ajax 请求成功，可以再次发送
		    });
	    	return false;
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