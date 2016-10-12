<style>
	.echarts {width: 100%; height: 300px;}
	.module_content {margin: 0; width: 100%; border: 0; height: 500px; overflow: auto;}
</style>
<div id="tab" class="module_content">
	<div id="echarts" class="echarts"></div>
	<table class="tablesorter" cellspacing="0">
		<thead>
			<tr>
				<th align="center">时间进度</th>
				<th align="center">日期</th>
				<th align="center">新增用户</th>
				<th align="center">付费人数</th>
				<th align="center">付费金额</th>
				<th align="center">LTV</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($result as $value) {
					echo "<tr>";
					echo "<td align='center'>".$value['timefield']."</td>";
					echo "<td align='center'>".$value['date_sk']."</td>";
					echo "<td align='center'>".$value['newuser']."</td>";
					echo "<td align='center'>".$value['payuser']."</td>";
					echo "<td align='center'>".$value['payamount']."</td>";
					echo "<td align='center'>".$value['ltv']."</td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
</div>
<script>
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($result) ?>;
	if (myChart){
		myChart = null;
	}
	var jsonArr = new Array();//总数组
	var date_sk = new Array();
	var ltv = new Array();
	for(var i = 0,r = jsonData.length;i < r;i++){
		date_sk[i] = jsonData[i]['date_sk'];
		ltv[i] = jsonData[i]['ltv'];
	}
	jsonArr.push(date_sk,ltv);

	var myChart = echarts.init(document.getElementById('echarts'));
	myChart.showLoading({
		animation:false,
		text : '数据加载中 ...',
		textStyle : {fontSize : 20},
		effect : 'whirling'
	});
	// 为echarts对象加载数据 
	clearTimeout(loadingTicket);
	var loadingTicket = setTimeout(function (){
		option = {
			tooltip: {
				trigger: 'axis'
			},
			legend: {
				data:['LTV值']
			},
			grid: {
				left: '3%',
				right: '4%',
				bottom: '3%',
				containLabel: true
			},
			xAxis: {
				type: 'category',
				boundaryGap: false,
				data: jsonArr[0],
				splitLine: {
		            show: false
		        }
			},
			yAxis: {
				type: 'value',
				splitLine: {
		            show: false
		        }
			},
			series: [
				{
					name:'LTV值',
					type:'line',
					stack: '总量',
					data:jsonArr[1]
				}
			]
		};
		myChart.setOption(option);
		myChart.hideLoading();
	},500);
});
</script>