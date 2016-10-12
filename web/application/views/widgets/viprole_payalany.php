<style>
article.module {margin: 0; width: 100%; border: 0; height: 600px; overflow: auto;}
.echarts {width: 100%; height: 300px;}
</style>
<article class="module width_full">
	<header>
		<ul class="tabs">
			<li><a href="javascript:;">付费金额/人数/次数</a></li>
			<li><a href="javascript:;">ARPU/ARPPU</a></li>
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
							<th align="center">付费金额</th>
							<th align="center">付费人数</th>
							<th align="center">付费次数</th>
							<th align="center">付费率</th>
							<th align="center">ARPU</th>
							<th align="center">ARPPU</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['startdate_sk']."</td>";
								echo "<td align='center'>".$value['payamount']."</td>";
								echo "<td align='center'>".$value['payuser']."</td>";
								echo "<td align='center'>".$value['paycount']."</td>";
								echo "<td align='center'>".$value['payrate']."</td>";
								echo "<td align='center'>".$value['arpu']."</td>";
								echo "<td align='center'>".$value['arppu']."</td>";
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
	var payuser = new Array();
	var paycount = new Array();
	var payamount = new Array();
	for(var i=0;i<json.length;i++){
		date.push(json[i]['startdate_sk']);
		payuser.push(json[i]['payuser']);
		paycount.push(json[i]['paycount']);
		payamount.push(json[i]['payamount']);
	}
	all_arr.push(date,payamount,payuser,paycount);

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
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['付费金额','付费人数','付费次数']
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
		            name: '',
		            min: 0,
		            max: 250,
		            interval: 50,
		            axisLabel: {
		                formatter: '{value}'
		            },
		            splitLine: {
			            show: false
			        }
		        },
		        {
		            type: 'value',
		            name: '',
		            min: 0,
		            max: 25,
		            interval: 5,
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
		            name:'付费金额',
		            type:'line',
		            data:all_arr[1]
		        },
		        {
		            name:'付费人数',
		            type:'bar',
		            data:all_arr[2]
		        },
		        {
		            name:'付费次数',
		            type:'bar',
		            data:all_arr[3]
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
	var arpu = new Array();
	var arppu = new Array();
	for(var i=0;i<json.length;i++){
		date.push(json[i]['startdate_sk']);
		arpu.push(json[i]['arpu']);
		arppu.push(json[i]['arppu']);
	}
	all_arr.push(date,arpu,arppu);
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
		        data:['arpu','arppu']
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
		            name: '',
		            min: 0,
		            max: 250,
		            interval: 50,
		            axisLabel: {
		                formatter: '{value}'
		            },
		            splitLine: {
			            show: false
			        }
		        },
		        {
		            type: 'value',
		            name: '',
		            min: 0,
		            max: 25,
		            interval: 5,
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
		            name:'arpu',
		            type:'line',
		            data:all_arr[1]
		        },
		        {
		            name:'arppu',
		            type:'bar',
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


