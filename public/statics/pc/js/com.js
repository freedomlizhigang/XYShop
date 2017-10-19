$(function() {
	// 弹出框
	$('.pop_close').click(function(event) {
		$('.pop').hide();
	});
	// 图片懒加载
    $(".lazy").lazyload({
         effect : "fadeIn",
         threshold : 400,
         failure_limit : 200, // 加大这个数值可以显示出float布局过的图片，但是太大影响性能，自己做取舍
    });
	// ad_top重新计算一下，防止不是1920的图片出现
	var ad_top_img = $('.ad_top_img');
	ad_top_img.load(function(){
		var imgW = ad_top_img.width() == 0 ? 960 : ad_top_img.width()/2;
		ad_top_img.css('margin-left','-' + imgW + 'px').css('left','50%');
	});
	// banner
	var banner_img = $('.banner').children('img').first();
	banner_img.load(function(){
		var imgW = banner_img.width() == 0 ? 960 : banner_img.width()/2;
		banner_img.css('margin-left','-' + imgW + 'px').css('left','50%');
	});

	
	// 点选择及取消时改价格
	$('.selected_checkbox').change(function(event) {
		var that = $(this);
    	var gid = that.attr('data-gid');
		// 判断是选中还是没选中
		if (that.is(':checked')) {
			var price = $('.total_price_' + gid).text();
			$('.total_price_' + gid).attr('data-price',price);
		}
		else
		{
    		$('.total_price_' + gid).attr('data-price','0');
		}
    	total_prices();
		return;
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
	$('.num_dec').click(function(event) {
		var num = parseInt($('.num_num').text());
		if (num > 1) {
			$('.num_num').text(num - 1);
			$('.cartnum').val(num - 1);
		}
	});
	$('.num_inc').click(function(event) {
		var num = parseInt($('.num_num').text());
		$('.num_num').text(num + 1);
		$('.cartnum').val(num + 1);
	});
	// 购物车页面数量变化
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

});
var before_request = 1; // 标识上一次ajax 请求有没回来, 没有回来不再进行下一次
// 更新总价
function total_prices()
{
	var total_price = 0;
	$('.one_total_price').each(function(index, el) {
		var v = $(el).attr('data-price');
		total_price = total_price + parseFloat(v);
	});
	$('.total_prices').html('￥' + total_price.toFixed(2));
}
// 取购物车数量
function cartnum(uid)
{
	$.post(host + 'api/good/cartnums',{uid:uid},function(data){
		$('.cart_nums').html(data);
	});
}
// 购物车商品数量变化,type = 1 增加
function cartNumsChange(className,oldnum,type)
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
// 取地区
function get_area(pid,subid,selectid)
{
    // 取一级子分类
    var goodcateurl = host + 'api/common/area';
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
// 取乡镇
function get_community(pid,subid,selectid)
{
    // 取一级子分类
    var goodcateurl = host + 'api/common/community';
    // 取子分类
    $.post(goodcateurl, {areaid3: pid}, function(d) {
        var ss = jQuery.parseJSON(d);
        if (ss.code == 1) {
            var str = '<option value="">选择地区</option>';
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