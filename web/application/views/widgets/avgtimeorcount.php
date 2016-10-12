<style>
	#main-con {height: 900px; overflow: auto;}
	#container {height: 300px; width: 100%;}
	table th,table td {text-align: center;}
</style>
<div id="main-con">
	<div id="container"></div>
	<div class="excel">
		<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
		<div>
			<table class="tablesorter" cellspacing="0">
				<thead>
					<tr>
						<th>日期</th>
						<th>每用户游戏次数</th>
						<th>每用户游戏时长(分钟)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$d = $_GET['date'];
						if($d == 'week'){
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td>".$value['startdate_sk']." ~ ".$value['enddate_sk']."</td>";
								echo "<td>".$value['avggamecount']."</td>";
								echo "<td>".$value['avggametime']."</td>";
								echo "</tr>";
							}
						}
						elseif($d == 'month'){
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td>".date('Y-m',strtotime($value['startdate_sk']))."</td>";
								echo "<td>".$value['avggamecount']."</td>";
								echo "<td>".$value['avggametime']."</td>";
								echo "</tr>";
							}
						}
						else{
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td>".$value['startdate_sk']."</td>";
								echo "<td>".$value['avggamecount']."</td>";
								echo "<td>".$value['avggametime']."</td>";
								echo "</tr>";
							}
						}
					?>
				</tbody>
			</table>
			<footer><div class="submit_link hasPagination"></div></footer>
		</div>
	</div>
</div>

<script>
var myChart = echarts.init(document.getElementById('container'));
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
	var jsonData = <?php echo json_encode($result) ?>;
	var jsonArr = new Array();//总数组
	var startdate_sk = new Array();
	var avggamecount = new Array();
	var avggametime = new Array();
	for(var i = 0,r = jsonData.length;i < r;i++){
		startdate_sk[i] = jsonData[i]['startdate_sk'];
		avggamecount[i] = jsonData[i]['avggamecount'];
		avggametime[i] = jsonData[i]['avggametime'];
	}
	jsonArr.push(startdate_sk.reverse(),avggamecount.reverse(),avggametime.reverse());
	option = {
	    tooltip : {
	        trigger: 'axis'
	    },
	    calculable : true,
	    legend: {
	        data:['游戏时长','游戏次数']
	    },
	    xAxis : [
	        {
	            type : 'category',
	            data : jsonArr[0],
	            splitLine: {
		            show: false
		        }
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value',
	            axisLabel : {
	                formatter: '{value} 分'
	            },
	            splitLine: {
		            show: false
		        }
	        },
	        {
	            type : 'value',
	            axisLabel : {
	                formatter: '{value} 次'
	            },
	            splitLine: {
		            show: false
		        }
	        }
	    ],
	    series : [

	        {
	            name:'游戏时长',
	            type:'bar',
	            data:jsonArr[2]
	        },
	        {
	            name:'游戏次数',
	            type:'line',
	            yAxisIndex: 1,
	            data:jsonArr[1]
	        }
	    ]
	};
	myChart.setOption(option);
},500);
</script>