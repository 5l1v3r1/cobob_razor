<style>
body {width: 100%; background: #fff;}
article.module {margin: 0; width: 100%; border: 0; height: 500px; overflow: auto;}
.hide {display: none;}
.nav {background: #e0e0e0; overflow: hidden;}
.nav ul {float: right;}
.nav li {float: left; margin-right: 10px;}
.nav li {display: block; border-radius: 3px; color: #333; cursor: pointer; padding: 5px 10px;}
.nav li.active {background: #333; color: #fff;}
.echarts {width: 100%; height: 300px;}
#add-iframe {padding: 10px; background: #ddd;}
</style>
<?php
	//var_dump($result1);
?>
<article class="module width_full">
	<div class="nav">
		<ul>
			<li>产出丨消耗</li>
			<li>消耗角色</li>
			<li>产出角色</li>
			<li>产出分布</li>
			<li>消耗分布</li>
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
								<th align="center">日期</th>
								<th align="center">产出</th>
								<th align="center">消耗</th>
								<th align="center">消耗角色</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result1 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['enddate_sk']."</td>";
									echo "<td align='center'>".$value['virtualmoney_output']."</td>";
									echo "<td align='center'>".$value['virtualmoney_consume']."</td>";
									echo "<td align='center'>".$value['virtualmoney_consumeuser']."</td>";
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
								<th align="center">日期</th>
								<th align="center">产出</th>
								<th align="center">消耗</th>
								<th align="center">消耗角色</th>
								<th align="center">人均消耗</th>
								<th align="center">详情</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result1 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['enddate_sk']."</td>";
									echo "<td align='center'>".$value['virtualmoney_output']."</td>";
									echo "<td align='center'>".$value['virtualmoney_consume']."</td>";
									echo "<td align='center'>".$value['virtualmoney_consumeuser']."</td>";
									echo "<td align='center'>".(floor((floatval($value['virtualmoney_consume'])/floatval($value['virtualmoney_consumeuser']))))."</td>";
									echo "<td align='center'><a name='".$value['enddate_sk']."' class='view' href='javascript:;'>查看</a></td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab3" class="module_content">
			<div id="echarts3" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">日期</th>
								<th align="center">产出</th>
								<th align="center">消耗</th>
								<th align="center">产出角色</th>
								<th align="center">人均产出</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result1 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['enddate_sk']."</td>";
									echo "<td align='center'>".$value['virtualmoney_output']."</td>";
									echo "<td align='center'>".$value['virtualmoney_consume']."</td>";
									echo "<td align='center'>".$value['virtualmoney_outputuser']."</td>";
									echo "<td align='center'>".(floor((floatval($value['virtualmoney_output'])/floatval($value['virtualmoney_outputuser']))))."</td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab4" class="module_content">
			<div id="echarts4" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">产出途径ID</th>
								<th align="center">产出途径</th>
								<th align="center">产出</th>
								<th align="center">产出占比</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result2 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['virtualmoney_outputid']."</td>";
									echo "<td align='center'>".$value['virtualmoney_outputway']."</td>";
									echo "<td align='center'>".$value['virtualmoney_output']."</td>";
									echo "<td align='center'></td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab5" class="module_content">
			<div id="echarts5" class="echarts"></div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">消耗途径ID</th>
								<th align="center">消耗途径</th>
								<th align="center">消耗</th>
								<th align="center">消耗占比</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result3 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['virtualmoney_outputid']."</td>";
									echo "<td align='center'>".$value['virtualmoney_outputway']."</td>";
									echo "<td align='center'>".$value['virtualmoney_output']."</td>";
									echo "<td align='center'></td>";
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
	var $name = '<?php echo $name ?>';
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
		var enddate_sk = new Array();
		var virtualmoney_output = new Array();
		var virtualmoney_consume = new Array();
		var virtualmoney_consumeuser = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			enddate_sk.push(json[i]['enddate_sk']);
			virtualmoney_output.push(json[i]['virtualmoney_output']);
			virtualmoney_consume.push(json[i]['virtualmoney_consume']);
			virtualmoney_consumeuser.push(json[i]['virtualmoney_consumeuser']);
		}
		var all_arr = new Array(enddate_sk.reverse(),virtualmoney_output.reverse(),virtualmoney_consume.reverse(),virtualmoney_consumeuser.reverse());
		var loadingTicket = setTimeout(function (){
			myChart1.hideLoading();
			option = {
				title : {
					text: $name
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:['产出', '消耗']
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
						name : '货币数量',
						splitLine: {
				            show: false
				        }
					}
				],
				series : [
					{
						name:'产出',
						type:'bar',
						data:all_arr[1]
					},
					{
						name:'消耗',
						type:'bar',
						data:all_arr[2]
					}
				]
			};
			myChart1.setOption(option);
		},500);
	}
	charts1();
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
		var json = <?php echo json_encode($result1) ?>;
		var enddate_sk = new Array();
		var virtualmoney_consume = new Array();
		var virtualmoney_rj = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			enddate_sk.push(json[i]['enddate_sk']);
			virtualmoney_consume.push(json[i]['virtualmoney_consume']);
			virtualmoney_rj.push(parseInt(parseFloat(json[i]['virtualmoney_consume'])/parseFloat(json[i]['virtualmoney_consumeuser'])));
		}
		var all_arr = new Array(enddate_sk.reverse(),virtualmoney_consume.reverse(),virtualmoney_rj.reverse());
		var loadingTicket = setTimeout(function (){
			myChart2.hideLoading();
			option = {
				title : {
					text: $name
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:['消耗', '人均消耗']
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
						name : '消耗货币数量',
						splitLine: {
				            show: false
				        }
					},
					{
						type : 'value',
						name : '人均消耗货币数量',
						splitLine: {
				            show: false
				        }
					}
				],
				series : [
					{
						name:'消耗',
						type:'bar',
						data:all_arr[1]
					},
					{
						name:'人均消耗',
						type:'line',
						yAxisIndex: 1,
						data:all_arr[2]
					}
				]
			};
			myChart2.setOption(option);
			$('#tab2').hide();
		},500);
	}
	charts2();
	function charts3(){
		var myChart3 = echarts.init(document.getElementById('echarts3'));
		var option = {}
		myChart3.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result1) ?>;
		var enddate_sk = new Array();
		var virtualmoney_output = new Array();
		var virtualmoney_rj = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			enddate_sk.push(json[i]['enddate_sk']);
			virtualmoney_output.push(json[i]['virtualmoney_output']);
			virtualmoney_rj.push(parseInt(parseFloat(json[i]['virtualmoney_output'])/parseFloat(json[i]['virtualmoney_outputuser'])));
		}
		var all_arr = new Array(enddate_sk.reverse(),virtualmoney_output.reverse(),virtualmoney_rj.reverse());
		var loadingTicket = setTimeout(function (){
			myChart3.hideLoading();
			option = {
				title : {
					text: $name
				},
				tooltip : {
					trigger: 'axis'
				},
				legend: {
					data:['产出', '人均产出']
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
						name : '产出货币数量',
						splitLine: {
				            show: false
				        }
					},
					{
						type : 'value',
						name : '人均产出货币数量',
						splitLine: {
				            show: false
				        }
					}
				],
				series : [
					{
						name:'产出',
						type:'bar',
						data:all_arr[1]
					},
					{
						name:'人均产出',
						type:'line',
						yAxisIndex: 1,
						data:all_arr[2]
					}
				]
			};
			myChart3.setOption(option);
			$('#tab3').hide();
		},500);
	}
	charts3();
	function charts4(){
		var myChart4 = echarts.init(document.getElementById('echarts4'));
		var option = {}
		myChart4.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result2) ?>;
		var virtualmoney_outputway = new Array();
		var virtualmoney_report = "";
		var jsonarray = null;
		for(var i = 0,r = json.length;i < r;i++){
			virtualmoney_outputway.push(json[i]['virtualmoney_outputway']);
			var arr = {
				         "value" : json[i]['virtualmoney_output'],
				         "name" : json[i]['virtualmoney_outputway']
						}
			if(virtualmoney_report == ''){
				virtualmoney_report = "[{'name':'"+json[i]['virtualmoney_outputway']+"','value':'"+json[i]['virtualmoney_output']+"'}]";
				jsonarray = eval('('+virtualmoney_report+')')
			}
			else{
				jsonarray.push(arr);
			}
		}
		var all_arr = new Array(virtualmoney_outputway);
		var loadingTicket = setTimeout(function (){
			myChart4.hideLoading();
			option = {
				 tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					data: all_arr[0]
				},
				series : [
					{
						name: '产出分布',
						type: 'pie',
						radius : '55%',
						center: ['50%', '60%'],
						data:jsonarray,
						itemStyle: {
							emphasis: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}
				]
			};
			myChart4.setOption(option);
			$('#tab4').hide();
		},500);
	}
	charts4();
	function charts5(){
		var myChart5 = echarts.init(document.getElementById('echarts5'));
		var option = {}
		myChart5.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result3) ?>;
		var virtualmoney_outputway = new Array();
		var virtualmoney_report = "";
		var jsonarray = null;
		for(var i = 0,r = json.length;i < r;i++){
			virtualmoney_outputway.push(json[i]['virtualmoney_outputway']);
			var arr = {
				         "value" : json[i]['virtualmoney_output'],
				         "name" : json[i]['virtualmoney_outputway']
						}
			if(virtualmoney_report == ''){
				virtualmoney_report = "[{'name':'"+json[i]['virtualmoney_outputway']+"','value':'"+json[i]['virtualmoney_output']+"'}]";
				jsonarray = eval('('+virtualmoney_report+')')
			}
			else{
				jsonarray.push(arr);
			}
		}
		var all_arr = new Array(virtualmoney_outputway);
		var loadingTicket = setTimeout(function (){
			myChart5.hideLoading();
			option = {
				 tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					data: all_arr[0]
				},
				series : [
					{
						name: '消耗分布',
						type: 'pie',
						radius : '55%',
						center: ['50%', '60%'],
						data:jsonarray,
						itemStyle: {
							emphasis: {
								shadowBlur: 10,
								shadowOffsetX: 0,
								shadowColor: 'rgba(0, 0, 0, 0.5)'
							}
						}
					}
				]
			};
			myChart5.setOption(option);
			$('#tab5').hide();
		},500);
	}
	charts5();
	//显示图标
	$('a.view').bind('click',function(){
		$('#add-iframe').remove();
		var taskName = $(this).attr('name');
		var topHTML = '<tr id="add-iframe">\
						<td colspan="12" style="background:#ffffcc;">\
							<div id="addCharts" class="echarts"></div>\
							<table class="tablesorter" cellspacing="0">\
								<thead>\
									<tr>\
										<th align="center">角色性质</th>\
										<th align="center">人数</th>\
										<th align="center">产出数量</th>\
										<th align="center">消耗数量</th>\
									</tr>\
								</thead>\
								<tbody id="addChartsCon"></tbody>\
							</table>\
						</td>\
						</tr>';
		$(this).parents('tr').after(topHTML);
		$.get("<?php echo site_url() ?>/sysanaly/virtualmoneyanalysis/chartsDetail?Name="+taskName,function(res){
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
			var virtualmoney_viplevel = new Array();
			var virtualmoney_usercount = new Array();
			var virtualmoney_outputcount = new Array();
			var virtualmoney_consumecount = new Array();
			var html = '';
			console.log(data.length);
			console.log(data);
			for(var i = 0,r = data.length;i < r;i++){
				virtualmoney_viplevel.push('vip' + data[i]['virtualmoney_viplevel']);
				virtualmoney_usercount.push(data[i]['virtualmoney_usercount']);
				virtualmoney_outputcount.push(data[i]['virtualmoney_outputcount']);
				virtualmoney_consumecount.push(data[i]['virtualmoney_consumecount']);
				html += '<tr>';
				html += '<td align="center">vip'+data[i]['virtualmoney_viplevel']+'</td>';
				html += '<td align="center">'+data[i]['virtualmoney_usercount']+'</td>';
				html += '<td align="center">'+data[i]['virtualmoney_outputcount']+'</td>';
				html += '<td align="center">'+data[i]['virtualmoney_consumecount']+'</td>';
				html += '</tr>';
			}
			$('#addChartsCon').html(html);
			var all_arr = new Array(virtualmoney_viplevel,virtualmoney_usercount,virtualmoney_outputcount,virtualmoney_consumecount);
			var loadingTicket = setTimeout(function (){
				myChart1.hideLoading();
				option = {
					title : {
						//text: '角色详情分析'
					},
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						data:['人数','产出数量','消耗数量']
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
							name : '每日产出消耗货币数量',
							splitLine: {
					            show: false
					        }
						}
					],
					series : [
						{
							name:'人数',
							type:'bar',
							data:all_arr[1]
						},
						{
							name:'产出数量',
							type:'bar',
							data:all_arr[2]
						},
						{
							name:'消耗数量',
							type:'bar',
							data:all_arr[3]
						}
					]
				};
				myChart1.setOption(option);
			},500);
		})
	});
	//产出分布占比
	var $consume = 0,$gain = 0;
	$('#tab5 .tablesorter tbody tr').each(function(){
		$consume += parseInt($(this).find('td').eq(2).text());
	});
	$('#tab5 .tablesorter tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(2).text());
		var result = parseFloat(num/$consume*100).toFixed(2);
		if(result == 'NaN'){
			result = 0;
		}
		$(this).find('td').eq(3).text(result+'%');
	});
	$('#tab4 .tablesorter tbody tr').each(function(){
		$gain += parseInt($(this).find('td').eq(2).text());
	});
	$('#tab4 .tablesorter tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(2).text());
		var result = parseFloat(num/$gain*100).toFixed(2);
		if(result == 'NaN'){
			result = 0;
		}
		$(this).find('td').eq(3).text(result+'%');
	});
});
</script>


