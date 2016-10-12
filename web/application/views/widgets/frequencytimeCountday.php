<style>
	.column {height: 800px; overflow: auto;}
	table td {background: #eee;}
	#user-info-detail {padding: 10px; color: #fff; background: #333; height: 25px; line-height: 25px; text-align: center;}
	#user-info-detail span {margin-right: 20px;}
	#container {height: 300px; width: 100%; background: #fff!important;}
	th,td {text-align: center;}
</style>
<section class="column">
	<div id="container"></div>
	<div class="excel">
		<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
		<div>
			<table class="tablesorter" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th align="center" class="name-1">日游戏次数</th>
						<th align="center" class="name-2">用户数量</th>
						<th align="center" class="name-3">百分比</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($result as $value) {
							echo "<tr>";
							echo "<td>&nbsp;".$value['field']."</td>";
							echo "<td>".$value['rolecount']."</td>";
							echo "<td>".round((floatval($value['rate'])*100),2)."%</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<script>
var type1 = <?php echo json_encode($type1) ?>;
var type2 = <?php echo json_encode($type2) ?>;
var type3 = <?php echo json_encode($type3) ?>;
function innerHTML(text1,text2,text3){
	$('.name-1').text(text1);
	$('.name-2').text(text2);
	$('.name-3').text(text3);
}
if(type2 == 0 && type3 == 0){
	innerHTML('日游戏次数','用户数量','百分比');
}
else if(type2 == 0 && type3 == 1){
	innerHTML('周游戏次数','用户数量','百分比');
}
else if(type2 == 0 && type3 == 2){
	innerHTML('周游戏天数','用户数量','百分比');
}
else if(type2 == 0 && type3 == 3){
	innerHTML('月游戏天数','用户数量','百分比');
}
else if(type2 == 1 && type3 == 0){
	innerHTML('日游戏时长','用户数量','百分比');
}
else if(type2 == 1 && type3 == 1){
	innerHTML('周游戏时长','用户数量','百分比');
}
else if(type2 == 1 && type3 == 2){
	innerHTML('单次游戏时长','用户数量','百分比');
}
else if(type2 == 2){
	innerHTML('游戏时段','用户数量','百分比');
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
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	var json = <?php echo json_encode($result) ?>;
	if(type2 == 0){
		var field = new Array();
		var rolecount = new Array();
		var rate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			field.push(json[i]['field']);
			rolecount.push(json[i]['rolecount']);
			rate.push((parseFloat(json[i]['rate'])*100));
		}
		all_arr = new Array(field,rolecount,rate);
		option = {
		    tooltip : {
		        trigger: 'axis'
		    },
		    xAxis : [
		        {
		            type : 'value',
		            name : '人数',
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    yAxis : [
		        {
		            type : 'category',
		            name : '游戏次数',
		            data : all_arr[0],
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    series : [
		        {
		            name:'用户数量',
		            type:'bar',
		            data:all_arr[1]
		        }
		    ]
		};
	}
	else if(type2 == 1){
		var field = new Array();
		var rolecount = new Array();
		var rate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			field.push(json[i]['field']);
			rolecount.push(json[i]['rolecount']);
			rate.push((parseFloat(json[i]['rate'])*100));
		}
		all_arr = new Array(field,rolecount,rate);
		option = {
		    tooltip : {
		        trigger: 'axis'
		    },
		    xAxis : [
		        {
		            type : 'value',
		            name : '人数',
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    yAxis : [
		        {
		            type : 'category',
		            name : '游戏时长',
		            data : all_arr[0],
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    series : [
		        {
		            name:'用户数量',
		            type:'bar',
		            data:all_arr[1]
		        }
		    ]
		};
	}
	else if(type2 == 2){
		var field = new Array();
		var rolecount = new Array();
		var rate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			field.push(json[i]['field']);
			rolecount.push(json[i]['rolecount']);
			rate.push((parseFloat(json[i]['rate'])*100));
		}
		all_arr = new Array(field,rolecount,rate);
		option = {
		    tooltip : {
		        trigger: 'axis'
		    },
		    xAxis : [
		        {
		            type : 'category',
		            boundaryGap : false,
		            name: '时段',
		            data : all_arr[0],
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    yAxis : [
		        {
		            type : 'value',
		            name: '活跃用户数',
		            splitLine: {
			            show: false
			        }
		        }
		    ],
		    series : [
		        {
		            name:'活跃用户',
		            type:'line',
		            stack: '总量',
		            smooth:true,
		            data:all_arr[1]
		        }
		    ]
		};
	}
	myChart.setOption(option);
},500);
</script>