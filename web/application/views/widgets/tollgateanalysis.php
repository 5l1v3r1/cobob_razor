<style>
body {width: 100%; background: #fff;}
article.module {margin: 0; width: 100%; border: 0; height: 500px; overflow: auto;}
.hide {display: none;}
.nav {background: #e0e0e0; overflow: hidden;}
.nav ul {float: right;}
.nav li {float: left; margin-right: 10px;}
.nav li {display: block; border-radius: 3px; color: #333; cursor: pointer; padding: 5px 10px;}
.nav li.active {background: #333; color: #fff;}
.echarts {width: 100%; height: 440px;}
#echarts2 {height: 300px;}
#add-iframe {padding: 10px; background: #ddd;}
</style>
<article class="module width_full">
	<div class="nav">
		<ul>
			<li>关卡详情</li>
			<li>通关概率</li>
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
								<th align="center">小关卡</th>
								<th align="center">小关卡名称</th>
								<th align="center">攻打次数</th>
								<th align="center">胜利次数</th>
								<th align="center">失败次数</th>
								<th align="center">胜率</th>
								<th align="center">攻打人数</th>
								<th align="center">通过人数</th>
								<th align="center">通关率</th>
								<th align="center">扫荡次数</th>
								<th align="center">扫荡人数</th>
								<th align="center">平均通关时长(单位：分钟)</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['tollgate_id']."</td>";
									echo "<td align='center'>".$value['tollgate_name']."</td>";
									echo "<td align='center'>".$value['tollgate_attackcount']."</td>";
									echo "<td align='center'>".$value['tollgate_successcount']."</td>";
									echo "<td align='center'>".$value['tollgate_failcount']."</td>";
									echo "<td align='center'>".round((floatval($value['tollgate_successcount'])/floatval($value['tollgate_attackcount'])*100),2)."%</td>";
									echo "<td align='center'>".$value['tollgate_attackuser']."</td>";
									echo "<td align='center'>".$value['tollgate_passuser']."</td>";
									echo "<td align='center'>".round((floatval($value['tollgate_passuser'])/floatval($value['tollgate_attackuser'])*100),2)."%</td>";
									echo "<td align='center'>".$value['tollgate_sweepcount']."</td>";
									echo "<td align='center'>".$value['tollgate_sweepuser']."</td>";
									echo "<td align='center'>".round($value['avg_passtime'],2)."</td>";
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
								<th align="center">小关卡</th>
								<th align="center">小关卡名称</th>
								<th align="center">攻打次数</th>
								<th align="center">胜利次数</th>
								<th align="center">失败次数</th>
								<th align="center">胜率</th>
								<th align="center">攻打人数</th>
								<th align="center">通过人数</th>
								<th align="center">通关率</th>
								<th align="center">扫荡次数</th>
								<th align="center">扫荡人数</th>
								<th align="center">平均通关时长(单位：分钟)</th>
								<th align="center">详情</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['tollgate_id']."</td>";
									echo "<td align='center'>".$value['tollgate_name']."</td>";
									echo "<td align='center'>".$value['tollgate_attackcount']."</td>";
									echo "<td align='center'>".$value['tollgate_successcount']."</td>";
									echo "<td align='center'>".$value['tollgate_failcount']."</td>";
									echo "<td align='center'>".round((floatval($value['tollgate_successcount'])/floatval($value['tollgate_attackcount'])*100),2)."%</td>";
									echo "<td align='center'>".$value['tollgate_attackuser']."</td>";
									echo "<td align='center'>".$value['tollgate_passuser']."</td>";
									echo "<td align='center'>".round((floatval($value['tollgate_passuser'])/floatval($value['tollgate_attackuser'])*100),2)."%</td>";
									echo "<td align='center'>".$value['tollgate_sweepcount']."</td>";
									echo "<td align='center'>".$value['tollgate_sweepuser']."</td>";
									echo "<td align='center'>".round($value['avg_passtime'],2)."</td>";
									echo "<td align='center'><a name='".$value['tollgate_id']."' class='view' href='javascript:;'>查看</a></td>";
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
<?php var_dump($result); ?>
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
		var json = <?php echo json_encode($result) ?>;
		var taskName = <?php echo json_encode($taskName) ?>;
		var tollgate_name = new Array();
		var tollgate_successcount = new Array();
		var tollgate_failcount = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			tollgate_name.push(json[i]['tollgate_name']);
			tollgate_successcount.push(json[i]['tollgate_successcount']);
			tollgate_failcount.push(json[i]['tollgate_failcount']);
		}
		var all_arr = new Array(tollgate_name,tollgate_successcount,tollgate_failcount);
		var loadingTicket = setTimeout(function (){
			myChart1.hideLoading();
			option = {
				title : {
					text: <?php echo json_encode($taskName) ?>
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:['胜利次数', '失败次数']
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
						splitLine: {
							show: false
						}
					}
				],
				dataZoom: [
					{
						show: true,
						start: 0,
						end: 25,
						handleSize: 8
					}
				],
				series : [
					{
						name:'胜利次数',
						type:'bar',
						stack: '人数',
						// label: {
						// 	normal: {
						// 		show: true,
						// 		position: 'inside'
						// 	}
						// },
						data:all_arr[1]
					},
					{
						name:'失败次数',
						type:'bar',
						stack: '人数',
						// label: {
						// 	normal: {
						// 		show: true,
						// 		position: 'inside'
						// 	}
						// },
						data:all_arr[2]
					}
				]
			};
			myChart1.setOption(option);
		},500);
	}
	function charts2(){
		var myChart2 = echarts.init(document.getElementById('echarts2'));
		var option = {}
		myChart2.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result) ?>;
		var tollgate_name = new Array();
		var tollgate_successrate = new Array();
		var tollgate_passrate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			tollgate_name.push(json[i]['tollgate_name']);
			var a = (parseFloat(json[i]['tollgate_successcount'])/(parseFloat(json[i]['tollgate_attackcount']))*100).toFixed(2);
			var b = (parseFloat(json[i]['tollgate_passuser'])/(parseFloat(json[i]['tollgate_attackuser']))*100).toFixed(2);
			(a == 'NaN')?a=0:'';
			(b == 'NaN')?b=0:'';
			tollgate_successrate.push(a);
			tollgate_passrate.push(b);
		}
		var all_arr = new Array(tollgate_name,tollgate_successrate,tollgate_passrate);
		var loadingTicket = setTimeout(function (){
			myChart2.hideLoading();
			option = {
				tooltip : {
					trigger: 'axis',
					formatter: function(params) {
						return '胜率: ' + params[0].value + ' % <br/>' + '通关率: ' + params[1].value + ' %';
					}
				},
				legend: {
					data:['胜率', '通关率']
				},
				xAxis : [
					{
						type : 'category',
						boundaryGap : [0, 0.01],
						data : all_arr[0],
						splitLine: {
							show: false
						}
					}
				],
				yAxis : [
					{
						type : 'value',
						axisLabel: {
							formatter: '{value} %'
						},
						splitLine: {
							show: false
						}
					}
				],
				dataZoom: [
					{
						show: true,
						start: 0,
						end: 25,
						handleSize: 8
					}
				],
				series : [
					{
						name:'胜率',
						smooth: true,
						type:'line',
						data:all_arr[1]
					},
					{
						name:'通关率',
						smooth: true,
						type:'line',
						data:all_arr[2]
					}
				]
			};
			myChart2.setOption(option);
			$('#tab2').hide();
		},500);
	}
	charts1();
	charts2();
	//显示图标
	$('a.view').bind('click',function(){
		$('#add-iframe').remove();
		var taskName = $(this).attr('name');
		var topHTML = '<tr id="add-iframe">\
						<td colspan="13" style="height:500px; background:#ffffcc;">\
							<div id="addCharts" class="echarts"></div>\
							<table class="tablesorter" cellspacing="0">\
								<thead>\
									<tr>\
										<th align="center">时间</th>\
										<th align="center">小关卡名称</th>\
										<th align="center">攻打次数</th>\
										<th align="center">胜利次数</th>\
										<th align="center">失败次数</th>\
										<th align="center">胜率</th>\
										<th align="center">攻打人数</th>\
										<th align="center">通过人数</th>\
										<th align="center">通关率</th>\
										<th align="center">扫荡次数</th>\
										<th align="center">扫荡人数</th>\
										<th align="center">平均通关时长(单位：分钟)</th>\
									</tr>\
								</thead>\
								<tbody id="addChartsCon"></tbody>\
							</table>\
						</td>\
						</tr>';
		$(this).parents('tr').after(topHTML);
		$.get("<?php echo site_url() ?>/sysanaly/tollgateanalysis/chartsDetail?taskName="+taskName,function(res){
			var data = JSON.parse(res);
			var myChart1 = echarts.init(document.getElementById('addCharts'));
			var option = {}
			myChart1.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			clearTimeout(loadingTicket);
			var enddate_sk = new Array();
			var tollgate_attackcount = new Array();
			var tollgate_successcount = new Array();
			var tollgate_failcount = new Array();
			var tollgate_successrate = new Array();
			var tollgate_attackuser = new Array();
			var tollgate_passuser = new Array();
			var tollgate_passrate = new Array();
			var tollgate_sweepcount = new Array();
			var tollgate_sweepuser = new Array();
			var avg_passtime = new Array();
			var html = '';
			console.log(data.length);
			console.log(data);
			for(var i = 0,r = data.length;i < r;i++){
				enddate_sk.push(data[i]['enddate_sk']);
				tollgate_attackcount.push(data[i]['tollgate_attackcount']);
				tollgate_successcount.push(data[i]['tollgate_successcount']);
				tollgate_failcount.push(data[i]['tollgate_failcount']);
				tollgate_successrate.push(parseFloat(data[i]['tollgate_successrate'])*100);
				tollgate_attackuser.push(data[i]['tollgate_attackuser']);
				tollgate_passuser.push(data[i]['tollgate_passuser']);
				tollgate_passrate.push(parseFloat(data[i]['tollgate_passrate'])*100);
				tollgate_sweepcount.push(data[i]['tollgate_sweepcount']);
				tollgate_sweepuser.push(data[i]['tollgate_sweepuser']);
				avg_passtime.push(data[i]['avg_passtime']);
				html += '<tr>';
				html += '<td align="center">'+data[i]['enddate_sk']+'</td>';
				html += '<td align="center">'+data[i]['tollgate_name']+'</td>';
				html += '<td align="center">'+data[i]['tollgate_attackcount']+'</td>';
				html += '<td align="center">'+data[i]['tollgate_successcount']+'</td>';
				html += '<td align="center">'+data[i]['tollgate_failcount']+'</td>';
				html += '<td align="center">'+(parseFloat(data[i]['tollgate_successrate'])*100).toFixed(2)+'%</td>';
				html += '<td align="center">'+data[i]['tollgate_attackuser']+'</td>';
				html += '<td align="center">'+data[i]['tollgate_passuser']+'</td>';
				html += '<td align="center">'+(parseFloat(data[i]['tollgate_passrate'])*100).toFixed(2)+'%</td>';
				html += '<td align="center">'+data[i]['tollgate_sweepcount']+'</td>';
				html += '<td align="center">'+data[i]['tollgate_sweepuser']+'</td>';
				html += '<td align="center">'+data[i]['avg_passtime']+'</td>';
				html += '</tr>';
			}
			$('#addChartsCon').html(html);
			var all_arr = new Array(enddate_sk,tollgate_attackcount,tollgate_successcount,tollgate_failcount,tollgate_successrate,tollgate_attackuser,tollgate_passuser,tollgate_passrate,tollgate_sweepcount,tollgate_sweepuser);
			var loadingTicket = setTimeout(function (){
				myChart1.hideLoading();
				option = {
					title : {
						text: data[0]['tollgate_name']+'胜率'
					},
					tooltip : {
						trigger: 'axis',
						formatter: function (params,ticket,callback) {
							var res = params[0].name;
							for (var i = 0, l = params.length; i < l; i++) {
								res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + '%';
							}
							setTimeout(function (){
								// 仅为了模拟异步回调
								callback(ticket, res);
							}, 100)
							return 'loading';
						}
					},
					legend: {
						data:['胜率','通关率']
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
								formatter: '{value} %'
							},
							splitLine: {
								show: false
							}
							
						}
					],
					series : [
						{
							name:'胜率',
							type:'line',
							data:all_arr[4]
						},
						{
							name:'通关率',
							type:'line',
							data:all_arr[7]
						}
					]
				};
				myChart1.setOption(option);
			},500);
		})
	})
});
</script>


