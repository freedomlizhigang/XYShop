$(function(){
	// 初始化弹出框
	$('.alert_shop').delay(1500).slideUp(300);
	
	// 点选择及取消时改价格
	$('.selected_checkbox').change(function(event) {
		var that = $(this);
    	var gid = that.attr('data-gid');
		// 判断是选中还是没选中
		if (that.is(':checked')) {
			var price = $('.total_price_' + gid).attr('data-price');
			$('.total_price_' + gid).text(price);
		}
		else
		{
    		$('.total_price_' + gid).text('0');
		}
    	total_prices();
		return;
	});
	/*打开添加购物车*/
	$('.good_addcart').on('click',function(){
		$('.cartnum').val($('.cart_num').text());
		$('#myModal').modal('show');
	});
	/*打开直接购买*/
	$('.good_firstorder').on('click',function(){
		$('.cartnum').val($('.first_cart_num').text());
		$('#myModal_order').modal('show');
	});
	
	// 确认功能
	$(".confirm").click(function(){
		if (!confirm("确实要进行此操作吗?")){
			return false;
		}else{
			return true;
		}
	});
	// 购物车数量变化
	$('.cart_dec').click(function(event) {
		var num = parseInt($('.cart_num').text());
		if (num > 1) {
			$('.cart_num').text(num - 1);
			$('.cartnum').val(num - 1);
		}
	});
	$('.cart_inc').click(function(event) {
		var num = parseInt($('.cart_num').text());
		$('.cart_num').text(num + 1);
		$('.cartnum').val(num + 1);
	});
	// 购物车页面
	$('.cart_dec_cart').on('click',function(event) {
		var gid = $(this).attr('data-gid');
		var num = parseInt($('.cart_num_cart_' + gid).text());
		if (num > 1) {
			$('.cart_num_cart_' + gid).text(num - 1);
			$('.cart_num_' + gid).val(num - 1);
		}
		// 计算总价
		cartNumsChange('cart_num_' + gid,num,0);
		return;
	});
	$('.cart_inc_cart').on('click',function(event) {
		var gid = $(this).attr('data-gid');
		var num = parseInt($('.cart_num_cart_' + gid).text());
		$('.cart_num_cart_' + gid).text(num + 1);
		$('.cart_num_' + gid).val(num + 1);
		// 计算总价
		cartNumsChange('cart_num_' + gid,num);
		return;
	});
	
	// 购物车数量变化
	$('.first_cart_dec').click(function(event) {
		var num = parseInt($('.first_cart_num').text());
		if (num > 1) {
			$('.first_cart_num').text(num - 1);
			$('.cartnum').val(num - 1);
		}
	});
	$('.first_cart_inc').click(function(event) {
		var num = parseInt($('.first_cart_num').text());
		$('.first_cart_num').text(num + 1);
		$('.cartnum').val(num + 1);
	});

	/*
	图片高度调整
	 */
	$(document).ready(function(){
		var imgs = $('.good_thumb img').first();
		var imgW = imgs.width();
		imgW = imgW == 0 ? '400' : imgW;
		var imgH = imgW == 400 ? 'auto' : imgW;
		$('.good_thumb img').width(imgW).height(imgH);
	});
})

var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
// 更新总价
function total_prices()
{
	var total_price = 0;
	$('.one_total_price').each(function(index, el) {
		var v = $(el).text();
		total_price = total_price + parseFloat(v);
	});
	$('.total_prices').html('￥' + total_price.toFixed(2));
}
// 取购物车数量
function cartnum(uid)
{
	$.post(host + 'api/good/cartnums',{uid:uid},function(data){
		$('.good_alert_num').html(data);
	});
}
// 购物车商品数量变化,type = 1 增加
function cartNumsChange(className = '',oldnum = 1,type = 1)
{
	if(before_request == 0)return false;
	var that = $('.' + className);
	var cid = that.attr('data-cid');
	var num = that.val();
	var price = that.attr('data-price');
	var new_prices = parseFloat(price) * parseInt(num);
	var url = host + "/api/good/changecart";
	// 更新购物车
	before_request = 0;
	$.post(url,{cid:cid,num:num,price:price,type:type},function(d){
		var ss = jQuery.parseJSON(d);
		if (ss.code == 1) {
	    	$('.total_price_' + cid).html(new_prices.toFixed(2));
	    	$('.total_price_' + cid).attr('data-price',new_prices.toFixed(2));
	    	that.val(ss.msg);
	    	total_prices();
		}
		else
		{
			// 失败的时候重置数量
			that.val(num);
			$('.cart_num_cart_' + cid).text(oldnum);
			alert(ss.msg);
		}
		before_request = 1;
		return;
	}).error(function() {
		before_request = 1;
		return;
	});
}