<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */
?>
<section class="column" style="height: 900px;" id="highchart">

	<?php if (isset($message)):?>
	<h4 class="alert_success"><?php echo $message; ?></h4>
	<?php endif; ?>
	<article class="module width_full">
		<header>
			<!-- 付费数据  -->
			<h3 class="h3_fontstyle"><?php echo  lang('t_paydata_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<!-- 付费金额 -->
					<li class="active"><a href="javascript:changeChartName('<?php echo  lang('t_fuFeiJinE_yao')?>');"><?php echo  lang('t_fuFeiJinE_yao')?></a></li>
					<!-- 付费人数 -->
					<li><a href="javascript:changeChartName('<?php echo  lang('t_fuFeiRenShu_yao')?>');"><?php echo  lang('t_fuFeiRenShu_yao')?></a></li>
					<!-- 付费次数 -->
					<li><a href="javascript:changeChartName('<?php echo  lang('t_fuFeiCiShu_yao')?>');"><?php echo  lang('t_fuFeiCiShu_yao')?></a></li>
				</ul>
			</div>
		</header>
		<!-- 图表 -->
		<div id="container" class="module_content" style="height: 260px;">
			
		</div>
	</article>
</section>

<script type="text/javascript">
// 基于准备好的dom，初始化echarts图表
var myChart = echarts.init(document.getElementById('container'));
var option = {}
myChart.showLoading({
	animation:false,
	text : '数据加载中 ...',
	textStyle : {fontSize : 20},
	effect : 'whirling'
});
option = {
	tooltip : {
		trigger: 'axis'
	},
	calculable : true,
	xAxis : [
		{
			type : 'category',
			boundaryGap : false,
			data : [],
			splitLine: {
	            show: false
	        }
		}
	],
	yAxis : [
		{
			type : 'value',
			splitLine: {
	            show: false
	        }
		}
	]
};

// 为echarts对象加载数据 
clearTimeout(loadingTicket);
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	default_data();
},500);
function default_data(type){
	myChart.clear();
	var json = <?php echo json_encode($result) ?>;
	//console.log(json);
	var date = function(){
		var date_arr = new Array();
		var daupayamount_arr = new Array();//活跃用户-付费金额
		var daupaycount_arr = new Array();//活跃用户-付费人数
		var daupayuser_arr = new Array();//活跃用户-付费次数
		var firstdaypayamount_arr = new Array();//首次付费用户-付费金额
		var firstdaypaycount_arr = new Array();//首次付费用户-付费人数
		var firstdaypayuser_arr = new Array();//首次付费用户-付费次数
		var firstpayamount_arr = new Array();//首日付费用户-付费金额
		var firstpaycount_arr = new Array();//首日付费用户-付费人数
		var firstpayuser_arr = new Array();//首日付费用户-付费次数
		for(var i = 0;i < json.length;i++){
			date_arr.push(json[i]['date']);
			daupayamount_arr.push(json[i]['daupayamount']);
			daupaycount_arr.push(json[i]['daupaycount']);
			daupayuser_arr.push(json[i]['daupayuser']);
			firstdaypayamount_arr.push(json[i]['firstdaypayamount']);
			firstdaypaycount_arr.push(json[i]['firstdaypaycount']);
			firstdaypayuser_arr.push(json[i]['firstdaypayuser']);
			firstpayamount_arr.push(json[i]['firstpayamount']);
			firstpaycount_arr.push(json[i]['firstpaycount']);
			firstpayuser_arr.push(json[i]['firstpayuser']);
		}
		all_arr = new Array(date_arr,daupayamount_arr,daupaycount_arr,daupayuser_arr,firstdaypayamount_arr,firstdaypaycount_arr,firstdaypayuser_arr,firstpayamount_arr,firstpaycount_arr,firstpayuser_arr);
	}
	date();
	console.log(all_arr);
	option.xAxis[0]['data'] = all_arr[0];
	if(type == 1){
		option.series = [
			{
				name:"<?php echo  lang('t_huoYueWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[1]
			},
			{
				name:"<?php echo  lang('t_shouCiFuFeiWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[4]
			},
			{
				name:"<?php echo  lang('t_shouRiFuFeiWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[7]
			}
		]
		option.legend = {
			data:[
				"<?php echo  lang('t_huoYueWanJia_yao')?>",
				"<?php echo  lang('t_shouCiFuFeiWanJia_yao')?>",
				"<?php echo  lang('t_shouRiFuFeiWanJia_yao')?>"
			]
		}
	}
	else if(type == 2){
		option.series = [
			{
				name:"<?php echo  lang('t_huoYueWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[2]
			},
			{
				name:"<?php echo  lang('t_shouCiFuFeiWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[5]
			},
			{
				name:"<?php echo  lang('t_shouRiFuFeiWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[8]
			}
		]
	}
	else if(type == 3){
		option.series = [
			{
				name:"<?php echo  lang('t_huoYueWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[3]
			},
			{
				name:"<?php echo  lang('t_shouCiFuFeiWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[6]
			},
			{
				name:"<?php echo  lang('t_shouRiFuFeiWanJia_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[9]
			}
		]
	}
	myChart.setOption(option);
}
function changeChartName(name)
{	
	chartName = name;
	if(chartName=="<?php echo lang('t_fuFeiJinE_yao')?>"){
		default_data(1);
	}
	if(chartName=="<?php echo lang('t_fuFeiRenShu_yao')?>"){
		default_data(2);
	}
	if(chartName=="<?php echo lang('t_fuFeiCiShu_yao')?>"){
		default_data(3);
	}
}
$(document).ready(function(){
	$('.submit_link .tabs li').bind('click',function(){
		$('.submit_link .tabs li').removeClass('active');
		$(this).addClass('active');
	});
	default_data(1);
});
</script>
