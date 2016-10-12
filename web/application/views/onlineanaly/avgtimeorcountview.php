<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 900px;}
.sub-tabs {overflow: hidden; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;}
.sub-tabs li {float: left; list-style: none; margin-left: 20px; padding: 2px 10px; cursor: pointer; border-radius: 5px;}
.sub-tabs li.active {background: #333; color: #fff;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('t_avgonline_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>活跃用户</a></li>
					<li><a>付费用户</a></li>
					<li><a>非付费用户</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show-1">
				<div class="sub-tabs">
					<li class="active">每日</li>
					<li>每周</li>
					<li>每月</li>
				</div>
				<div class="echarts"></div>
			</div>
			<div class="show-2">
				<div class="sub-tabs">
					<li class="active">每日</li>
					<li>每周</li>
					<li>每月</li>
				</div>
				<div class="echarts"></div>
			</div>
			<div class="show-3">
				<div class="sub-tabs">
					<li class="active">每日</li>
					<li>每周</li>
					<li>每月</li>
				</div>
				<div class="echarts"></div>
			</div>
		</div>
	</article>
</section>
<script>
$(document).ready(function(){
	var frame = null,num = 0;
	innerFrame(num,0);
	$('.submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		num = current;
		$('#container > div').hide();
		$('#container > div').eq(current).show();
		$('.sub-tabs:eq('+num+') li').removeClass('active');
		$('.sub-tabs:eq('+num+') li:first').addClass('active');
		innerFrame(num,0);
	});
	$('.sub-tabs li').bind('click',function(){
		var current = $(this).index();
		$(this).parent().find('li').removeClass('active');
		$(this).addClass('active');
		innerFrame(num,current);
	});
	function innerFrame(params1,params2){
		switch(params1){
			case 0:
				params1 = 'dauuser';
				break;
			case 1:
				params1 = 'payuser';
				break;
			case 2:
				params1 = 'unpayuser';
				break;
		};
		switch(params2){
			case 0:
				params2 = 'day';
				break;
			case 1:
				params2 = 'week';
				break;
			case 2:
				params2 = 'month';
				break;	
		}
		frame = "<iframe src='<?php echo site_url() ?>/onlineanaly/avgtimeorcount/echarts?type="+params1+"&date="+params2+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$('.echarts iframe').remove();
		$('.echarts').eq(num).html(frame);
	}
});
</script>