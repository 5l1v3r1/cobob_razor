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
<section class="column" style="height: 900px;" id="highchart"
	<?php if (!isset($delete)) 
	{?>
	style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"
	<?php 
	} ?>>

	<?php if (isset($message)):?>
	<h4 class="alert_success"><?php echo $message; ?></h4>        
	<?php endif; ?>
	<article class="module width_full">
		<header>
			<!-- <div style="float: left; margin-left: 2%; margin-top: 7px;">
					<?php

				if (isset($add)) {
					?>
					<a href="#" onclick="addreport()"> <img
									src="<?php echo base_url(); ?>assets/images/addreport.png"
									title="<?php echo lang('s_suspend_title')?>" style="border: 0" /></a>
					<?php
				} if (isset($delete)) {
					?>
					<a href="#" onclick="deletereport()"> <img
									src="<?php echo base_url(); ?>assets/images/delreport.png"
									title="<?php echo lang('s_suspend_deltitle')?>" style="border: 0" /></a>
					<?php
				} ?>
			</div> -->

			<!-- 新增用户  -->
			<h3 class="h3_fontstyle"><?php echo  lang('t_newuser_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs3" style="position: relative; top: -5px;">
					<!-- 新增&激活 -->
					<li><a ct="userconversion" href="javascript:changeChartName('<?php echo  lang('t_allKinds_yao')?>')"><?php echo  lang('t_allKinds_yao')?></a></li>
					<!-- 转化率 -->
					<li><a ct="userconversion" href="javascript:changeChartName('<?php echo  lang('t_userConversion_yao')?>')"><?php echo  lang('t_userConversion_yao')?></a></li>
				</ul>
			</div>
		</header>
		<!-- 图表 -->
		<div id="container" class="module_content" style="height: 260px;">
			
		</div>
	</article>
</section>

<script type="text/javascript">
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
//On Click Event
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
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
	dataZoom: [
		{
			show: true,
			start: 0,
			end: 25,
			handleSize: 8
		}
	],
	grid: {
		top: '12%',
		left: '5%',
		right: '5%',
		containLabel: true
	},
	xAxis : [
		{
			type : 'category',
			boundaryGap : false,
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
	default_data(1);
},500);
function default_data(type){
	myChart.clear();
	var json = <?php echo json_encode($newUsersData) ?>;
	var date_arr = new Array();
	var deviceactivations_arr = new Array();//设备激活
	var newdevices_arr = new Array();//新增设备
	var newusers_arr = new Array();//新增用户
	var userconversion_arr = new Array();//用户转化率
	for(var i = 0;i < json.length;i++){
		date_arr.push(json[i]['date']);
		deviceactivations_arr.push(json[i]['deviceactivations']);
		newdevices_arr.push(json[i]['newdevices']);
		newusers_arr.push(json[i]['newusers']);
		userconversion_arr.push(parseFloat(json[i]['userconversion'])*100);
	}
	all_arr = new Array(date_arr.reverse(),deviceactivations_arr.reverse(),newusers_arr.reverse(),newdevices_arr.reverse(),userconversion_arr.reverse());
	option.xAxis[0]['data'] = all_arr[0];
	if(type == 2){
		option.series = [
			{
				name:"<?php echo  lang('t_userConversion_yao')?>",
				type:'line',
				smooth: true,
				data:all_arr[4],
				tooltip : {
	                trigger: 'item',
	                formatter: "{c} %"
	            },
			}
		]
		option.legend = {
			data:[
				"<?php echo  lang('t_userConversion_yao')?>"
			]
		}
		option.yAxis = [
			{
				type : 'value',
				axisLabel: {
		            formatter: '{value} %'
		        },
				splitLine: {
					show: false
				}
			}
		]
	}
	else{
		option.yAxis = [
			{
				type : 'value',
				splitLine: {
					show: false
				}
			}
		]
		option.series = [
			{
				name:"<?php echo  lang('t_deviceActivation_yao')?>",
				smooth: true,
				type:'line',
				data:all_arr[1]
			},
			{
				name:"<?php echo  lang('t_newuser_analysis_yao')?>",
				smooth: true,
				type:'line',
				data:all_arr[2]
			},
			{
				name:"<?php echo  lang('t_newDevice_yao')?>",
				smooth: true,
				type:'line',
				data:all_arr[3]
			}
		]
		option.legend = {
			data:[
				"<?php echo  lang('t_deviceActivation_yao')?>",
				"<?php echo  lang('t_newuser_analysis_yao')?>",
				"<?php echo  lang('t_newDevice_yao')?>"
			]
		}
	}
	myChart.setOption(option);
}
function changeChartName(name)
{
	chartName = name;
	if(chartName=="<?php echo lang('t_allKinds_yao')?>"){
		default_data(1);
	}

	if(chartName=="<?php echo lang('t_userConversion_yao')?>"){
		default_data(2);
	}
}
</script>
