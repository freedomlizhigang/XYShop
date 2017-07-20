<div class="left_tab clearfix">
	<div class="left_tab_title pull-left">
	@foreach($leftmenu as $lm)
		<h3 class="left_h3">
			<span class="center-block {{ $lm['icon'] }}" aria-hidden="true"></span>
			{{ mb_substr($lm['name'],0,2) }}
		</h3>
	@endforeach
	</div>
	<div class="left_tab_content pull-right">
	@foreach($leftmenu as $lm)
		<ul class="left_list">
			@foreach($lm['submenu'] as $slm)
			<li class="sub_menu clearfix" id="left_menu{{ $slm['id'] }}"><a href="javascript:;" onclick="_LM({{ $slm['id'] }},'/console/{{ $slm['url'] }}')" class="sub_menu_a">{{ $slm['name'] }}</a></li>
			@endforeach
		</ul>
	@endforeach
	</div>
</div>
<script>
	$(function(){
		$('.left_list').hide().first().show();
		$('.left_h3').first().addClass('active');
		$('.left_h3').on('click',function(){
			var thisIndex = $(this).index();
			$(this).addClass('active').siblings('.left_h3').removeClass('active');
			$('.left_list').hide().removeClass('active_click').eq(thisIndex).show().addClass('active_click');
			$('.left_list').eq(thisIndex).find('a').first().trigger('click');
		});
	})
</script>
