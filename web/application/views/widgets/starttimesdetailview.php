<style>
body {background: #fff;}
article.module {margin: 0; width: 100%; border: 0; height: 500px; overflow: auto;}
#levelalycontainer {height: 250px;}
.hide {display: none;}
</style>
<article class="module width_full">
	<header>
		<ul class="tabs">
			<li><a href="javascript:setchatsoption(1);">次数分布</a></li>
			<li><a href="javascript:setchatsoption(2);">时段分布</a></li>
		</ul>
	</header>
	<div class="tab_container">
		<div id="levelalycontainer" class="module_content"></div>
		<div id="tab1" class="tab_content">
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0" id="s0">
						<thead>
							<tr>
								<th align="center">次数</th>
								<th align="center">启动人数</th>
								<th align="center">登录人数</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($PeoplesbycountData as $value) {
									echo "<tr>";
									echo "<td align='center'>&nbsp;".$value['counts']."</td>";
									echo "<td align='center'>".$value['startpeoples']."</td>";
									echo "<td align='center'>".$value['loginpeoples']."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab2" class="tab_content hide">
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0" id="s0">
						<thead>
							<tr>
								<th align="center">时间点</th>
								<th align="center">启动人数</th>
								<th align="center">登录人数</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($PeoplesbytimeData as $value) {
									echo "<tr>";
									echo "<td align='center'>&nbsp;".$value['timefield']."</td>";
									echo "<td align='center'>".$value['startpeoples']."</td>";
									echo "<td align='center'>".$value['loginpeoples']."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- end of .tab_container -->
</article>
<?php
$all_arr = array();
array_push($all_arr,$PeoplesbycountData,$PeoplesbytimeData);
?>
<script>
$(document).ready(function(){
	$('.tabs li:first').addClass('active');
	$('.tabs li').bind('click',function(){
		$('.tabs li').removeClass('active');
		$(this).addClass('active');
		var current = $(this).index();
		$('.tab_content').hide();
		$('.tab_content').eq(current).show();
	});
});
</script>
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
},500);
function setchatsoption(type){
	default_data(type);
}
function default_data(type){
	var $all_arr = <?php echo json_encode($all_arr) ?>;
	if(type == 1){
		var json = $all_arr[0];
		var date_arr = new Array();
		var counts = new Array();
		var startpeoples = new Array();
		var loginpeoples = new Array();
		for(var i = 0;i < json.length;i++){
			date_arr.push(json[i]['date']);
			counts.push(json[i]['counts']);
			startpeoples.push(json[i]['startpeoples']);
			loginpeoples.push(json[i]['loginpeoples']);
		}
		all_arr = new Array(counts,startpeoples,loginpeoples);
		option.xAxis[0]['data'] = all_arr[0];
		option.series = [
			{
				name:"启动人数",
				smooth: true,
				type:'line',
				stack: '总量',
				data:all_arr[1]
			},
			{
				name:"登录人数",
				smooth: true,
				type:'line',
				stack: '总量',
				data:all_arr[2]
			}
		];
		option.yAxis = [
				{
					type : 'value',
					name : '人数',
					splitLine: {
			            show: false
			        }
				}
			]
		option.legend = {
			data:['启动人数','登录人数']
		};
	}
	else if(type == 2){
		var json = $all_arr[1];
		console.log(json)
				var date_arr = new Array();
		var timefield = new Array();
		var startpeoples = new Array();
		var loginpeoples = new Array();
		for(var i = 0;i < json.length;i++){
			date_arr.push(json[i]['date']);
			timefield.push(json[i]['timefield']);
			startpeoples.push(json[i]['startpeoples']);
			loginpeoples.push(json[i]['loginpeoples']);
		}
		all_arr = new Array(timefield,startpeoples,loginpeoples);
		option.xAxis[0]['data'] = all_arr[0];
		option.series = [
			{
				name:"启动人数",
				smooth: true,
				type:'line',
				stack: '总量',
				data:all_arr[1]
			},
			{
				name:"登录人数",
				smooth: true,
				type:'line',
				stack: '总量',
				data:all_arr[2]
			}
		];
		option.yAxis = [
				{
					type : 'value',
					name : '人数',
					splitLine: {
			            show: false
			        }
				}
			]
		option.legend = {
			data:['启动人数','登录人数']
		};
	}
	myChart.setOption(option);
}
default_data(1);
</script>