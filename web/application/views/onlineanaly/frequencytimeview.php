<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 800px;}
.sub-t {height: 28px; border-bottom: 2px solid #333; width: 100%;}
.sub-t a {padding: 5px 10px; width: 80px; text-align: center; margin-top: 1px; color: #333; background: #fff; border-radius: 3px; border: 1px solid #fff; border-bottom: 2px solid #333;  float: left; margin-left: 15px;}
.sub-t a.active {background: #333; color: #fff; border: 1px solid #333; border-bottom: 2px solid #eee;}
.sub-tt {overflow: hidden; background: #eee; width: 100%; height: 40px;}
.sub-tt a {padding: 5px 10px; width: 80px; text-align: center; border-radius: 3px; color: #999; margin-top: 5px; float: left; margin-left: 15px;}
.sub-tt a.active {background: #999;color: #fff;}
.hide {display: none;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('t_frequencyonline_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>活跃用户</a></li>
					<li><a>付费用户</a></li>
					<li><a>非付费用户</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show-1 show">
				<div class="sub-t">
					<a href="javascript:;" class="active">游戏频次</a>
					<a href="javascript:;">游戏时长</a>
					<a href="javascript:;">游戏时段</a>
				</div>
				<div class="sub-tt-0 sub-tt">
					<a href="javascript:;" class="active">日游戏次数</a>
					<a href="javascript:;">周游戏次数</a>
					<a href="javascript:;">周游戏天数</a>
					<a href="javascript:;">月游戏天数</a>
				</div>
				<div class="sub-tt-1 sub-tt hide">
					<a href="javascript:;" class="active">日游戏时长</a>
					<a href="javascript:;">周游戏时长</a>
					<a href="javascript:;">单次游戏时长</a>
				</div>
			</div>
			<div class="show-2 show">
				<div class="sub-t">
					<a href="javascript:;" class="active">游戏频次</a>
					<a href="javascript:;">游戏时长</a>
					<a href="javascript:;">游戏时段</a>
				</div>
				<div class="sub-tt-0 sub-tt">
					<a href="javascript:;" class="active">日游戏次数</a>
					<a href="javascript:;">周游戏次数</a>
					<a href="javascript:;">周游戏天数</a>
					<a href="javascript:;">月游戏天数</a>
				</div>
				<div class="sub-tt-1 sub-tt hide">
					<a href="javascript:;" class="active">日游戏时长</a>
					<a href="javascript:;">周游戏时长</a>
					<a href="javascript:;">单次游戏时长</a>
				</div>
			</div>
			<div class="show-3 show">
				<div class="sub-t">
					<a href="javascript:;" class="active">游戏频次</a>
					<a href="javascript:;">游戏时长</a>
					<a href="javascript:;">游戏时段</a>
				</div>
				<div class="sub-tt-0 sub-tt">
					<a href="javascript:;" class="active">日游戏次数</a>
					<a href="javascript:;">周游戏次数</a>
					<a href="javascript:;">周游戏天数</a>
					<a href="javascript:;">月游戏天数</a>
				</div>
				<div class="sub-tt-1 sub-tt hide">
					<a href="javascript:;" class="active">日游戏时长</a>
					<a href="javascript:;">周游戏时长</a>
					<a href="javascript:;">单次游戏时长</a>
				</div>
			</div>
		</div>
		<div class="echarts"></div>
	</article>
</section>
<script>
$(document).ready(function(){
	var num1=0,num2=0,num3=0;
	$('.submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		num1 = current;
		$('#container > div').hide();
		$('#container > div').eq(current).show();
		showCharts(num1,num2,num3);
	});
	$('.sub-t a').bind('click',function(){
		var current = $(this).index();
		num2 = current;
		$(this).parent().find('a').removeClass('active');
		$(this).addClass('active');
		$(this).parents('.show').find('.sub-tt').hide();
		$(this).parents('.show').find('.sub-tt-'+current).show();
		showCharts(num1,num2,num3);
	});
	$('.sub-tt a').bind('click',function(){
		var current = $(this).index();
		num3 = current;
		$(this).parent().find('a').removeClass('active');
		$(this).addClass('active');
		showCharts(num1,num2,num3);
	});
	showCharts(num1,num2,num3);
	function showCharts(num1,num2,num3){
		var frameCountday = "<iframe src='<?php echo site_url() ?>/onlineanaly/frequencytime/chartsCountday?type1="+num1+"&type2="+num2+"&type3="+num3+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$('.echarts').html(frameCountday);
	}
});
</script>