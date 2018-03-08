$(function() {
    // 图片懒加载
    $(".lazy").lazyload({
         effect : "fadeIn",
         threshold : 200,
         failure_limit : 100, // 加大这个数值可以显示出float布局过的图片，但是太大影响性能，自己做取舍
    });
	// 给购物车页面变化数量加锁，防止连点
	// 购物车页面数量变化
	$('.num_reduce').on('click',function(event) {
		if (ajaxLock) {
			var cid = $(this).attr('data-cid');
			var num = parseInt($('.cart_num_cart_' + cid).text());
			if (num > 1) {
				$('.cart_num_cart_' + cid).text(num - 1);
				$('.cart_num_' + cid).val(num - 1);
			}
			// 计算总价
			cartNumsChange('cart_num_' + cid,num,0);
			return;
		}
	});
	$('.num_plus').on('click',function(event) {
		if (ajaxLock) {
			var cid = $(this).attr('data-cid');
			var num = parseInt($('.cart_num_cart_' + cid).text());
			$('.cart_num_cart_' + cid).text(num + 1);
			$('.cart_num_' + cid).val(num + 1);
			// 计算总价
			cartNumsChange('cart_num_' + cid,num);
			return;
		}
	});
	// 点选择及取消时改价格
	$('.cart_checkbox').change(function(event) {
		var that = $(this);
        var cid = that.val();
		// 判断是选中还是没选中
		if (that.is(':checked')) {
			var price = $('.total_price_' + cid).text();
			$('.total_price_' + cid).attr('data-price',price);
  		var nums = parseInt($('.cart_total_nums').text()) + parseInt($('.cart_num_cart_' + cid).text());
		}
		else
		{
    		$('.total_price_' + cid).attr('data-price','0');
    		var nums = parseInt($('.cart_total_nums').text()) - parseInt($('.cart_num_cart_' + cid).text());
		}
  	total_prices();
  	$('.cart_total_nums').html(nums);
		return;
	});
	// 购物车数量变化
	$('.num_dec').click(function(event) {
		var num = parseInt($('.num_num').text());
		if (num > 1) {
			$('.g_s_num,.num_num').text(num - 1);
			$('.nums').val(num - 1);
		}
	});
	$('.num_inc').click(function(event) {
		var num = parseInt($('.num_num').text());
		$('.g_s_num,.num_num').text(num + 1);
		$('.nums').val(num + 1);
	});
	// 关闭弹出的提交按钮
	$(".pos_close").click(function(){
		$(".pos_bg,.pos_bg_1,.pos_alert_con,.nophone").fadeOut();
	});
	// 重新计算字体大小
	$(window).resize(function(){
		htmlFz();
	});
	var body = $(document);
	var topSearch = $('.top_search');
	$(window).scroll(function() {
		if (body.scrollTop() > 70) {
			topSearch.addClass('bgc_m');
		}
		else
		{
			topSearch.removeClass('bgc_m');
		}
	});
});
function htmlFz()
{
	var dpr, rem, f;
	var docEl = document.documentElement;
	var f = document.querySelector('html');
	rem = docEl.clientWidth > 750 ? 75 : (docEl.clientWidth / 10);
	window.onresize = function(){
		htmlFz();
	};
	f.style.fontSize = rem + 'px';
}
htmlFz();
// 购物车商品数量变化,type = 1 增加
function cartNumsChange(className,oldnum,type)
{
	if(ajaxLock == 0)return false;
	var that = $('.' + className);
	var cid = that.attr('data-cid');
	var num = that.val();
	var price = that.attr('data-price');
	var new_prices = parseFloat(price) * parseInt(num);
	var uid = that.attr('data-uid');
	var url = host + "/api/good/changecart";
	// 更新购物车
	ajaxLock = 0;
	$.post(url,{cid:cid,num:num,price:price,type:type},function(d){
        // console.log(d);
		var ss = jQuery.parseJSON(d);
		if (ss.code == 1) {
	    	$('.total_price_' + cid).html(new_prices.toFixed(2));
	    	$('.total_price_' + cid).attr('data-price',new_prices.toFixed(2));
	    	that.val(ss.msg);
			cartnum(uid);
	    	total_prices();
			$('.alert_msg').text('更新成功！').slideToggle().delay(1500).slideToggle();
		}
		else
		{
			// 失败的时候重置数量
			that.val(num);
			$('.cart_num_cart_' + cid).text(oldnum);
			// alert(ss.msg);
			$('.alert_msg').text(ss.msg).slideToggle().delay(1500).slideToggle();
		}
		ajaxLock = 1;
		return;
	}).error(function() {
		ajaxLock = 1;
		return;
	});
}
// 更新总价
function total_prices()
{
	var total_price = 0;
	$('.one_total_price').each(function(index, el) {
		var v = $(el).attr('data-price');
		total_price = total_price + parseFloat(v);
	});
	$('.cart_prices_num').html(total_price.toFixed(2));
}
// 取购物车数量
function cartnum(uid)
{
	$.post(host + 'api/good/cartnums',{uid:uid},function(data){
		$('.cart_total_nums').html(data);
	});
}
// 取地区
function get_area(pid,subid,selectid)
{
    // 取一级子分类
    var goodcateurl = host + 'api/common/area2';
    // 取子分类
    $.post(goodcateurl, {pid: pid}, function(d) {
        var ss = jQuery.parseJSON(d);
        if (ss.code == 1) {
            var str = '<option value="">选择地区</option>';
            $.each(ss.msg, function(i,n) {
                str += '<option value="' + n.areaname + '">' + n.areaname + '</option>';
            });
            $('#' + subid).html(str);
		    (selectid != 0) && $('#' + subid).val(selectid);
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
    var goodcateurl = host + 'api/common/community2';
    // 取子分类
    $.post(goodcateurl, {areaid3: pid}, function(d) {
        var ss = jQuery.parseJSON(d);
        if (ss.code == 1) {
            var str = '<option value="">选择地区</option>';
            $.each(ss.msg, function(i,n) {
                str += '<option value="' + n.name + '">' + n.name + '</option>';
            });
            $('#' + subid).html(str);
		    (selectid != 0) && $('#' + subid).val(selectid);
        }
        else
        {
            // 失败的时候重置数量
            console.log(ss.msg);
        }
    });
}