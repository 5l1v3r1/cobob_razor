<style>
	.column {height: 500px; overflow: auto;}
	table td {background: #eee;}
	#user-info-detail {padding: 10px; color: #fff; background: #333; height: 25px; line-height: 25px; text-align: center;}
	#user-info-detail span {margin-right: 20px;}
	#container {height: 350px; width: 100%; background: #fff!important;}
</style>
<section class="column">
	<div id="container"></div>
	<div class="excel">
		<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
		<div>
			<table class="tablesorter" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th align="center"><?php echo  lang('t_tongJiShiJian_yao')?></th>
						<th align="center"><?php echo  lang('t_shiShiZaiXianRenShu_yao')?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($result as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['count_time']."</td>";
							echo "<td align='center'>".$value['onlineusers']."</td>";
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
var count_time = new Array();
var onlineusers = new Array();
for(var i = 0,r = json.length;i < r;i++){
	count_time.push(json[i]['count_time']);
	onlineusers.push(json[i]['onlineusers']);
}
all_arr = new Array(count_time,onlineusers);
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	option = {
	    tooltip : {
	        trigger: 'axis'
	    },
	    calculable : true,
	    dataZoom: [
            {
                show: true,
                start: 0,
                end: 25,
                handleSize: 8
            }
        ],
        grid: {
            top: '10%',
            left: '10%',
            containLabel: true
        },
	    legend: {
	        data:['实时在线']
	    },
	    xAxis : [
	        {
	            type : 'category',
	            data : all_arr[0],
	            splitLine: {
		            show: false
		        }
	        }
	    ],
	    yAxis : [
	        {
	            type : 'value',
	            axisLabel : {
	                formatter: '{value}'
	            },
	            splitLine: {
		            show: false
		        }
	        }
	    ],
	    series : [

	        {
	            name:'实时在线',
	            smooth: true,
	            type:'line',
	            data:all_arr[1]
	        }
	    ]
	};
	myChart.setOption(option);
},500);
</script>