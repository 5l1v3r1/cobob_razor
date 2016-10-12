<style>
article.module {margin: 0; width: 100%; border: 0; height: 500px; overflow: auto; overflow-x: hidden;}
.tab_content .module_content {height: 300px; }
.hide {display: none;}
</style>
<article class="module width_full">
	<header>
		<ul class="tabs">
			<li><a href="javascript:setchatsoption(1);">升级时长分布</a></li>
			<li><a href="javascript:setchatsoption(2);"><?php echo lang('t_riShengJiShiChangQuShi_yao') ?></a></li>
			<li><a href="javascript:setchatsoption(3);"><?php echo lang('t_riTingZhiLvQuShi_yao') ?></a></li>
		</ul>
	</header>
	<div class="tab_container">
		<div class="tab_content">
			<div id="levelalycontainer" class="module_content">
				//
			</div>
			<table class="tablesorter" id="t0" cellspacing="0">
				<thead>
					<tr>
						<th align="center">升级时长</th>
						<th align="center">活跃角色</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($totalupgradetime_result as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['upgradetime']."</td>";
							echo "<td align='center'>".$value['dauusers']."</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t1" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">日升级时长（m）</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($gamestoprate_result as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['date']."</td>";
							echo "<td align='center'>".$value['daylevelupgrade']."</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t2" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">停滞率</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($gamestoprate_result as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['date']."</td>";
							echo "<td align='center'>".(floatval($value['gamestoprate'])*100)."%</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<!-- end of .tab_container -->
</article>
<script>
$(document).ready(function(){
	$('.tabs li:first').addClass('active');
	$('.tabs li').bind('click',function(){
		$('.tabs li').removeClass('active');
		$(this).addClass('active');
		var current = $(this).index();
		$('.tablesorter').hide();
		$('#t'+current).show();
	});
});
</script>
<?php
$allArr = array();
array_push($allArr,$gamestoprate_result,$totalupgradetime_result);
?>
<script type="text/javascript">
// 基于准备好的dom，初始化echarts图表
var myChart = echarts.init(document.getElementById('levelalycontainer'));
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
	default_data(1);
},500);
function setchatsoption(type){
	default_data(type);
}
function default_data(type){
	myChart.clear();
	option.legend = {data:['']};
	var data = <?php echo json_encode($allArr) ?>;
	var json = data[0];
	var $totallevelupgrade = data[1];
	if(type == 1){
		var upgradetime = new Array();
		var dauusers = new Array();
		for(var i = 0;i < $totallevelupgrade.length;i++){
			upgradetime.push($totallevelupgrade[i]['upgradetime']);
			dauusers.push($totallevelupgrade[i]['dauusers']);
		}
		all_arr = new Array(upgradetime,dauusers);
		option.xAxis = {
			type: 'value',
			boundaryGap: [0, 0.01],
			splitLine: {
	            show: false
	        }
		}
		option.series = [
			{
				name:"升级时长分布",
				type:'bar',
				stack: '总量',
				data:all_arr[1]
			}
		];
		option.yAxis = [
				{
					type : 'category',
					data : all_arr[0],
					splitLine: {
			            show: false
			        }
				}
			]
		option.legend = {
			data:[
				"升级时长分布"
			]
		};
	}
	else if(type == 2){
		var date_arr = new Array();
		var daylevelupgrade_arr = new Array();
		for(var i = 0;i < json.length;i++){
			date_arr.push(json[i]['date']);
			daylevelupgrade_arr.push(json[i]['daylevelupgrade']);
		}
		all_arr = new Array(date_arr.reverse(),daylevelupgrade_arr.reverse());
		option.xAxis = {
			type: 'category',
			boundaryGap: false,
			data : all_arr[0],
			splitLine: {
	            show: false
	        }
		}
		option.series = [
			{
				name:"日升级时长(单位：分钟)",
				smooth: true,
				type:'line',
				data:all_arr[1]
			}
		];
		option.yAxis = [
				{
					type : 'value',
					splitLine: {
			            show: false
			        }
				}
			]
		option.legend = {
			data:[
				"日升级时长(单位：分钟)"
			]
		};
	}
	else if(type == 3){
		var date_arr = new Array();
		var gamestoprate_arr = new Array();
		for(var i = 0;i < json.length;i++){
			date_arr.push(json[i]['date']);
			gamestoprate_arr.push((parseFloat(json[i]['gamestoprate'])*100).toFixed(2));
		}
		all_arr = new Array(date_arr.reverse(),gamestoprate_arr.reverse());
		option.xAxis = {
			type: 'category',
			boundaryGap: false,
			data : all_arr[0],
			splitLine: {
	            show: false
	        }
		}
		option.series = [
			{
				name:"<?php echo lang('t_riTingZhiLvQuShi_yao')?>",
				smooth: true,
				type:'line',
				stack: '总量',
				data:all_arr[1],
				tooltip : {
	                trigger: 'item',
	                formatter: "{a}:{c} %"
	            }
			}
		];
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
		option.legend = {
			data:[
				"<?php echo lang('t_riTingZhiLvQuShi_yao')?>"
			]
		};
	}
	myChart.setOption(option);
}
</script>

