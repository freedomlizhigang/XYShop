$(function() {
	// 重新计算字体大小
	$(window).resize(function(){
		htmlFz();
	});
	var body = $('body');
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