<style>
	.column {height: 500px; overflow: auto;}
	table td {background: #eee;}
	#user-info-detail {padding: 10px; color: #fff; background: #333; height: 25px; line-height: 25px; text-align: center;}
	#user-info-detail span {margin-right: 20px;}
	#container {height: 260px; width: 100%; background: #fff!important;}
</style>
<section class="column">
	<div id="container"></div>
	<div class="excel">
		<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
		<div>
			<table class="tablesorter" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th align="center">区服</th>
						<th align="center">当日PCU</th>
						<th align="center">该服容量</th>
						<th align="center">容量使用率</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($result as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['server_name']."</td>";
							echo "<td align='center'>".$value['curdate_pcu']."</td>";
							echo "<td align='center'>".$value['server_capacity']."</td>";
							echo "<td align='center'>".(floatval($value['userate'])*100)."%</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<script>
if (myChart){
	myChart = null;
}
var myChart = echarts.init(document.getElementById('container'));
var option = {}
myChart.showLoading({
	animation:false,
	text : '数据加载中 ...',
	textStyle : {fontSize : 20},
	effect : 'whirling'
});
clearTimeout(loadingTicket);
var json = <?php echo json_encode($result) ?>;
console.log(json)
var server_name = new Array();
var curdate_pcu = new Array();
var server_capacity = new Array();
var userate = new Array();
for(var i = 0,r = json.length;i < r;i++){
	server_name.push(json[i]['server_name']);
	curdate_pcu.push(json[i]['curdate_pcu']);
	server_capacity.push(json[i]['server_capacity']);
	userate.push((parseFloat(json[i]['userate'])*100).toFixed(2));
}
all_arr = new Array(server_name,curdate_pcu,server_capacity,userate);
console.log(all_arr)
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	option = {
	    tooltip : {
	        trigger: 'axis'
	    },
	    calculable : true,
	    legend: {
	        data:['当日PCU','该服容量','容量使用率']
	    },
	    xAxis : [
	        {
	            type : 'category',
	            data : all_arr[0]
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value',
	            name : '区服',
	            axisLabel : {
	                formatter: '{value}'
	            },
	            splitLine: {
		            show: false
		        }
	        },
	        {
	            type : 'value',
	            name : '百分比',
	            axisLabel : {
	                formatter: '{value} %'
	            },
	            splitLine: {
		            show: false
		        }
	        }
	    ],
	    series : [

	        {
	            name:'当日PCU',
	            type:'bar',
	            data:all_arr[1]
	        },
	        {
	            name:'该服容量',
	            type:'bar',
	            data:all_arr[2]
	        },
	        {
	            name:'容量使用率',
	            type:'line',
	            yAxisIndex: 1,
	            data:all_arr[3],
	            tooltip : {
	                trigger: 'item',
	                formatter: "{a}:{c} %"
	            }
	        }
	    ]
	};
	myChart.setOption(option);
},500);
</script>