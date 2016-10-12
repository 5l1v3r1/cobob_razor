<style>
article.module {margin: 0; width: 100%; border: 0; height: 600px; overflow: auto;}
.echarts {width: 100%; height: 300px;}
</style>
<article class="module width_full">
	<header>
		<ul class="tabs">
			<li><a href="javascript:;">该VIP数量</a></li>
			<li><a href="javascript:;">次数/时长</a></li>
		</ul>
	</header>
	<div class="tab_container">
		<div class="tab_content">
			<div id="container1" class="echarts"></div>
		</div>
		<div class="tab_content">
			<div id="container2" class="echarts"></div>
		</div>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr>
							<th align="center">日期</th>
							<th align="center">新增VIP</th>
							<th align="center">去掉VIP</th>
							<th align="center">当前VIP</th>
							<th align="center">活跃VIP</th>
							<th align="center">日均游戏次数</th>
							<th align="center">日均游戏时长</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['startdate_sk']."</td>";
								echo "<td align='center'>".$value['newvip']."</td>";
								echo "<td align='center'>".$value['outvip']."</td>";
								echo "<td align='center'>".$value['currentvip']."</td>";
								echo "<td align='center'>".$value['dauvip']."</td>";
								echo "<td align='center'>".$value['dayavggamecount']."</td>";
								echo "<td align='center'>".$value['pergametime']."</td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</article>
<script>
$(document).ready(function(){
	$('.tabs li:first').addClass('active');
	$('.tabs li').bind('click',function(){
		$('.tabs li').removeClass('active');
		$(this).addClass('active');
		var current = $(this).index();
		$('.tab_container .tab_content').hide();
		$('.tab_container .tab_content').eq(current).show();
	});
});
var json = <?php echo json_encode($result) ?>;
function charts1(){
	console.log(json);
	var all_arr = new Array();
	var date = new Array();
	var newvip = new Array();
	var outvip = new Array();
	var currentvip = new Array();
	var dauvip = new Array();
	for(var i=0;i<json.length;i++){
		date.push(json[i]['startdate_sk']);
		newvip.push(json[i]['newvip']);
		outvip.push(json[i]['outvip']);
		currentvip.push(json[i]['currentvip']);
		dauvip.push(json[i]['dauvip']);
	}
	all_arr.push(date,newvip,outvip,currentvip,dauvip);

	var myChart = echarts.init(document.getElementById('container1'));
	var option = {}
	myChart.showLoading({
		animation:false,
		text : '数据加载中 ...',
		textStyle : {fontSize : 20},
		effect : 'whirling'
	});
	clearTimeout(loadingTicket);
	var loadingTicket = setTimeout(function (){
		myChart.hideLoading();
		option = {
		    tooltip : {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['新增VIP','去掉VIP','当前VIP','活跃VIP']
		    },
		    grid: {
		        left: '5%',
		        right: '5%',
		        bottom: '0%',
		        containLabel: true
		    },
		    xAxis : [
		        {
		            type : 'category',
		            boundaryGap : false,
		            data : all_arr[0],
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
		    ],
		    series : [
		        {
		            name:'新增VIP',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[1]
		        },
		        {
		            name:'去掉VIP',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[2]
		        },
		        {
		            name:'当前VIP',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[3]
		        },
		        {
		            name:'活跃VIP',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[4]
		        }
		    ]
		};
		myChart.setOption(option);
	},500);
}
charts1();
function charts2(){
	var all_arr = new Array();
	var date = new Array();
	var dayavggamecount = new Array();
	var pergametime = new Array();
	for(var i=0;i<json.length;i++){
		date.push(json[i]['startdate_sk']);
		dayavggamecount.push(json[i]['dayavggamecount']);
		pergametime.push(json[i]['pergametime']);
	}
	all_arr.push(date,dayavggamecount,pergametime);
	console.log(all_arr);
	var myChart = echarts.init(document.getElementById('container2'));
	var option = {}
	myChart.showLoading({
		animation:false,
		text : '数据加载中 ...',
		textStyle : {fontSize : 20},
		effect : 'whirling'
	});
	clearTimeout(loadingTicket);
	var loadingTicket = setTimeout(function (){
		myChart.hideLoading();
		option = {
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['日均游戏次数','每次游戏时长']
		    },
		    grid: {
		        left: '5%',
		        right: '5%',
		        bottom: '0%',
		        containLabel: true
		    },
		    xAxis: [
		        {
		            type: 'category',
		            data: all_arr[0],
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    yAxis: [
		        {
		            type: 'value',
		            name: '游戏次数',
		            axisLabel: {
		                formatter: '{value}'
		            },
		            splitLine: {
			            show: false
			        }
		        },
		        {
		            type: 'value',
		            name: '游戏时长',
		            axisLabel: {
		                formatter: '{value}'
		            },
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    series: [
		        {
		            name:'日均游戏次数',
		            type:'bar',
		            data:all_arr[1]
		        },
		        {
		            name:'每次游戏时长',
		            type:'line',
		            yAxisIndex: 1,
		            data:all_arr[2]
		        }
		    ]
		};
		myChart.setOption(option);
	},500);
	$('.tab_content:eq(1)').hide();
}
charts2();
</script>


