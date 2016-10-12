<style>
article.module {margin: 0; width: 100%; border: 0; height: 600px; overflow: auto;}
.echarts {width: 100%; height: 300px;}
</style>
<article class="module width_full">
	<header>
		<ul class="tabs">
			<li><a href="javascript:;">流失人数</a></li>
			<li><a href="javascript:;">流失等级</a></li>
		</ul>
	</header>
	<div class="tab_container">
		<div class="tab_content">
			<div id="container1" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">日期</th>
								<th align="center">3日流失</th>
								<th align="center">3日流失率</th>
								<th align="center">7日流失</th>
								<th align="center">7日流失率</th>
								<th align="center">14日流失</th>
								<th align="center">14日流失率</th>
								<th align="center">30日流失</th>
								<th align="center">30日流失率</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['startdate_sk']."</td>";
									echo "<td align='center'>".$value['dayleave3']."</td>";
									echo "<td align='center'>".$value['dayleaverate3']."%</td>";
									echo "<td align='center'>".$value['dayleave7']."</td>";
									echo "<td align='center'>".$value['dayleaverate7']."%</td>";
									echo "<td align='center'>".$value['dayleave14']."</td>";
									echo "<td align='center'>".$value['dayleaverate14']."%</td>";
									echo "<td align='center'>".$value['dayleave30']."</td>";
									echo "<td align='center'>".$value['dayleaverate30']."%</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="tab_content">
			<div id="container2" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">用户等级</th>
								<th align="center">3日流失</th>
								<th align="center">3日流失率</th>
								<th align="center">7日流失</th>
								<th align="center">7日流失率</th>
								<th align="center">14日流失</th>
								<th align="center">14日流失率</th>
								<th align="center">30日流失</th>
								<th align="center">30日流失率</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($userlevel as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['userlevel']."</td>";
									echo "<td align='center'>".$value['dayleave3']."</td>";
									echo "<td align='center'>".$value['dayleaverate3']."%</td>";
									echo "<td align='center'>".$value['dayleave7']."</td>";
									echo "<td align='center'>".$value['dayleaverate7']."%</td>";
									echo "<td align='center'>".$value['dayleave14']."</td>";
									echo "<td align='center'>".$value['dayleaverate14']."%</td>";
									echo "<td align='center'>".$value['dayleave30']."</td>";
									echo "<td align='center'>".$value['dayleaverate30']."%</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</article>
<?php
$all_arr = array();
array_push($all_arr,$result,$userlevel);
?>
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
var php_arr = <?php echo json_encode($all_arr) ?>;
var json = php_arr[0];
var $userlevel = php_arr[1];
function charts1(){
	var all_arr = new Array();
	var date = new Array();
	var dayleave3 = new Array();
	var dayleave7 = new Array();
	var dayleave14 = new Array();
	var dayleave30 = new Array();
	for(var i=0;i<json.length;i++){
		date.push(json[i]['startdate_sk']);
		dayleave3.push(json[i]['dayleave3']);
		dayleave7.push(json[i]['dayleave7']);
		dayleave14.push(json[i]['dayleave14']);
		dayleave30.push(json[i]['dayleave30']);
	}
	all_arr.push(date,dayleave3,dayleave7,dayleave14,dayleave30);

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
		        data:['3日流失','7日流失','14日流失','30日流失']
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
		            name:'3日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[1]
		        },
		        {
		            name:'7日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[2]
		        },
		        {
		            name:'14日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[3]
		        },
		        {
		            name:'30日流失',
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
	var userlevel = new Array();
	var dayleave3 = new Array();
	var dayleave7 = new Array();
	var dayleave14 = new Array();
	var dayleave30 = new Array();
	for(var i=0;i<$userlevel.length;i++){
		userlevel.push($userlevel[i]['userlevel']);
		dayleave3.push($userlevel[i]['dayleave3']);
		dayleave7.push($userlevel[i]['dayleave7']);
		dayleave14.push($userlevel[i]['dayleave14']);
		dayleave30.push($userlevel[i]['dayleave30']);
	}
	all_arr.push(userlevel,dayleave3,dayleave7,dayleave14,dayleave30);

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
		    tooltip : {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['3日流失','7日流失','14日流失','30日流失']
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
		            name:'3日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[1]
		        },
		        {
		            name:'7日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[2]
		        },
		        {
		            name:'14日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[3]
		        },
		        {
		            name:'30日流失',
		            type:'line',
		            areaStyle: {normal: {}},
		            data:all_arr[4]
		        }
		    ]
		};
		myChart.setOption(option);
	},500);
	$('.tab_content:eq(1)').hide();
}
charts2();
</script>


