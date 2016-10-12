<style>
body {width: 100%; background: #fff;}
article.module {margin: 0; width: 100%; border: 0; height: 500px; overflow: auto;}
.tab_content .module_content {height: 250px;}
.hide {display: none;}
.nav {background: #e0e0e0; overflow: hidden;}
.nav ul {float: right;}
.nav li {float: left; margin-right: 10px;}
.nav li {display: block; border-radius: 3px; color: #333; cursor: pointer; padding: 5px 10px;}
.nav li.active {background: #333; color: #fff;}
.echarts {width: 100%; height: 300px;}
</style>
<article class="module width_full">
	<div class="nav">
		<ul>
			<li>步骤详情</a></li>
			<li>转化对比</li>
		</ul>
	</div>
	<div class="tab_container">
		<div id="tab1" class="module_content">
			<div id="echarts1" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">步骤</th>
								<th align="center">步骤描述</th>
								<th align="center">步骤激活人数</th>
								<th align="center">步骤通过人数</th>
								<th align="center">步骤通过率</th>
								<th align="center">步骤停留人数</th>
								<th align="center">步骤停留率</th>
								<th align="center">步骤累计停留率</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$allArr = count($result1);
								for ($i=0;$i<$allArr;$i++) {
									echo "<tr>";
									echo "<td align='center'>".$result1[$i]['step']."</td>";//步骤
									echo "<td align='center'>".$result1[$i]['step_desc']."</td>";//步骤描述
									echo "<td align='center'>".$result1[$i]['stepactiveuser']."</td>";//步骤激活人数
									echo "<td align='center'>".$result1[$i]['steppassuser']."</td>";//步骤通过人数
									echo "<td align='center'>".round(((floatval($result1[$i]['steppassuser']))/(floatval($result1[$i]['stepactiveuser']))*100),2)."%</td>";//步骤通过率
									echo "<td align='center'>".$result1[$i]['stepstaycount']."</td>";//步骤停留人数
									echo "<td align='center'>".round(((floatval($result1[$i]['stepstaycount']))/(floatval($result1[$i]['stepactiveuser']))*100),2)."%</td>";//步骤停留率
									if($i == 0){
										echo "<td align='center'>".round(((floatval($result1[$i]['stepstaycount']))/(floatval($result1[$i]['stepactiveuser']))*100),2)."%</td>";//步骤累计停留率
									}
									else{
										echo "<td align='center'>".round((((floatval($result1[$i-1]['stepstaycount']))+(floatval($result1[$i]['stepstaycount'])))/(floatval($result1[0]['stepactiveuser']))*100),2)."%</td>";//步骤累计停留率
									}
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab2" class="module_content">
			<div id="echarts2" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">时间</th>
								<th align="center">任务激活人数</th>
								<th align="center">任务通过人数</th>
								<th align="center">任务通过率</th>
								<th align="center">任务停留人数</th>
								<th align="center">任务停留率</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result2 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['enddate_sk']."</td>";//时间
									echo "<td align='center'>".$value['taskactiveuser']."</td>";//任务激活人数
									echo "<td align='center'>".$value['taskpassuser']."</td>";//任务通过人数
									echo "<td align='center'>".round((floatval($value['taskpassrate'])*100),2)."%</td>";//任务通过率
									echo "<td align='center'>".$value['taskstayuser']."</td>";//任务停留人数
									echo "<td align='center'>".round((floatval($value['taskstayrate'])*100),2)."%</td>";//任务停留率
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

<script>
$(document).ready(function(){
	$('.nav li:first').addClass('active');
	$('.nav li').bind('click',function(){
		$('.nav li').removeClass('active');
		$(this).addClass('active');
		var current = $(this).index();
		$('.tab_container > div').hide();
		$('.tab_container > div').eq(current).show();
	});
	function charts1(){
		var myChart1 = echarts.init(document.getElementById('echarts1'));
		var option = {}
		myChart1.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result1) ?>;
		var taskName = <?php echo json_encode($taskName) ?>;
		var step_desc = new Array();
		var stepactiveuser = new Array();
		var steppassuser = new Array();
		var steppassrate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			step_desc.push(json[i]['step_desc']);
			stepactiveuser.push(json[i]['stepactiveuser']);
			steppassuser.push(json[i]['steppassuser']);
			steppassrate.push((parseFloat(json[i]['steppassuser'])/parseFloat(json[i]['stepactiveuser'])*100).toFixed(2));
		}
		var all_arr = new Array(step_desc,stepactiveuser,steppassuser,steppassrate);
		var loadingTicket = setTimeout(function (){
			myChart1.hideLoading();
			option = {
				title : {
					text: taskName
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:['激活人数', '通过人数','通过率']
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
						name : '人数',
						splitLine: {
				            show: false
				        }
					},
					{
						type : 'value',
						name : '通过率',
						min: 0,
						max: 100,
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
						name:'激活人数',
						type:'bar',
						stack: '人数',
						data:all_arr[1]
					},
					{
						name:'通过人数',
						type:'bar',
						stack: '人数',
						data:all_arr[2]
					},
					{
						name:'通过率',
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
			myChart1.setOption(option);
		},500);
	}
	function charts2(){
		var myChart2 = echarts.init(document.getElementById('echarts2'));
		$('#tab2').hide();
		var option = {}
		myChart2.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result2) ?>;
		var enddate_sk = new Array();
		var taskstayuser = new Array();
		var taskpassuser = new Array();
		var taskpassrate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			enddate_sk.push(json[i]['enddate_sk']);
			taskstayuser.push(json[i]['taskstayuser']);
			taskpassuser.push(json[i]['taskpassuser']);
			taskpassrate.push((parseFloat(json[i]['taskpassrate'])*100).toFixed(2));
		}
		var all_arr = new Array(enddate_sk.reverse(),taskstayuser.reverse(),taskpassuser.reverse(),taskpassrate.reverse());
		var loadingTicket = setTimeout(function (){
			myChart2.hideLoading();
			option = {
				tooltip : {
					trigger: 'axis',
					axisPointer : {
						type : 'shadow'
					}
				},
				legend: {
					data:['停留人数','通过人数','通过率']
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '3%',
					containLabel: true
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
						name : '人数',
						splitLine: {
				            show: false
				        }
					},
					{
						type : 'value',
						name : '通过率',
						min: 0,
						max: 100,
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
						name:'停留人数',
						type:'bar',
						stack: '人数',
						data:all_arr[1]
					},
					{
						name:'通过人数',
						type:'bar',
						stack: '人数',
						data:all_arr[2]
					},
					{
						name:'通过率',
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
			myChart2.setOption(option);
		},500);
	}
	charts1();
	charts2();
});
</script>


