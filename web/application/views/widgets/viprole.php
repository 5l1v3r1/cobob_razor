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
			<div style="float: left; margin-left: 2%; margin-top: 7px;">
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
			</div>

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
$(document).ready(function() {    
	//load Overview of User Behavior report
	//var myurl="http://localhost/razor/web/index.php?/report/productbasic/getUsersDataByTime?date="+new Date().getTime();
});
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
	// var option = myChart.getOption();
	myChart.clear();
	var json = <?php echo json_encode($newUsersData) ?>;
	// console.log(json);
	var date = function(){
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
			var result = parseFloat(json[i]['userconversion'])/parseFloat(json[i]['newusers'])*100;
			if(isNaN(result)){
				result = 0;
			}
			userconversion_arr.push(result);
		}
		all_arr = new Array(date_arr.reverse(),deviceactivations_arr.reverse(),newdevices_arr.reverse(),newusers_arr.reverse(),userconversion_arr.reverse());
	}
	date();
	console.log(all_arr);
	option.xAxis[0]['data'] = all_arr[0];
	if(type == 2){
		option.series = [
			{
				name:"<?php echo  lang('t_userConversion_yao')?>",
				type:'line',
				stack: '总量',
				tooltip:{
					formatter:function(params,ticket,callback){
						var res = params[0].name;
						for (var i = 0, l = params.length; i < l; i++) {
							res += '<br / >' + params[i].seriesName + ' : ' + params[i].value + '%';
						}
						setTimeout(function (){
							callback(ticket, res);
						}, 10)
						return 'loading';
					}
				},
				data:all_arr[4]
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
						show: true,
						interval: 'auto',
						formatter: '{value} %'
					},
					splitLine: {
						show: false
					}
				}
			]
	}
	else{
		option.yAxis = [];
		option.series = [
			{
				name:"<?php echo  lang('t_deviceActivation_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[1]
			},
			{
				name:"<?php echo  lang('t_newuser_analysis_yao')?>",
				type:'line',
				stack: '总量',
				data:all_arr[2]
			},
			{
				name:"<?php echo  lang('t_newDevice_yao')?>",
				type:'line',
				stack: '总量',
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
		default_data();
	}

	if(chartName=="<?php echo lang('t_userConversion_yao')?>"){
		default_data(2);
	}
}
</script>
