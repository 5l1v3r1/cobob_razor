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
<article class="module width_full">
	<div class="nav">
		<ul>
			<li>产出详情</li>
			<li>消耗详情</li>
			<li>行为详情</li>
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
								<th align="center">道具ID</th>
								<th align="center">道具名</th>
								<th align="center">道具类型</th>
								<th align="center">数量</th>
								<th align="center">分布</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result1 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['prop_id']."</td>";
									echo "<td align='center'>".$value['prop_name']."</td>";
									echo "<td align='center'>".$value['prop_type']."</td>";
									echo "<td align='center'>".$value['prop_count']."</td>";
									echo "<td align='center'><a name='".$value['prop_id']."' title='".$functionName."' alt='".$value['prop_count']."' class='view ch' href='javascript:;'>查看</a></td>";
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
								<th align="center">道具ID</th>
								<th align="center">道具名</th>
								<th align="center">道具类型</th>
								<th align="center">数量</th>
								<th align="center">分布</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result2 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['prop_id']."</td>";
									echo "<td align='center'>".$value['prop_name']."</td>";
									echo "<td align='center'>".$value['prop_type']."</td>";
									echo "<td align='center'>".$value['prop_count']."</td>";
									echo "<td align='center'><a name='".$value['prop_id']."' title='".$functionName."' class='view sh' href='javascript:;'>查看</a></td>";
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
								<th align="center">行为ID</th>
								<th align="center">行为名</th>
								<th align="center">次数</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result3 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['prop_id']."</td>";
									echo "<td align='center'>".$value['prop_name']."</td>";
									echo "<td align='center'>".$value['prop_count']."</td>";
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
		var myChart = echarts.init(document.getElementById('echarts1'));
		var option = {}
		myChart.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result1) ?>;
		var prop_name = new Array();
		var prop_report = "";
		var jsonarray = null;
		for(var i = 0,r = json.length;i < r;i++){
			prop_name.push(json[i]['prop_name']);
			var arr = {
						 "name" : json[i]['prop_name'],
						 "value" : json[i]['prop_count']
						}
			if(prop_report == ''){
				prop_report = "[{'name':'"+json[i]['prop_name']+"','value':'"+json[i]['prop_count']+"'}]";
				jsonarray = eval('('+prop_report+')');
			}
			else{
				jsonarray.push(arr);
			}
		}
		var all_arr = new Array(prop_name);
		var loadingTicket = setTimeout(function (){
			myChart.hideLoading();
			option = {
				legend: {
					data: all_arr[0]
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				series : [
					{
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
			myChart.setOption(option);
		},500);
	}
	charts1();
	function charts2(){
		var myChart = echarts.init(document.getElementById('echarts2'));
		var option = {}
		myChart.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result2) ?>;
		var prop_name = new Array();
		var prop_report = "";
		var jsonarray = null;
		for(var i = 0,r = json.length;i < r;i++){
			prop_name.push(json[i]['prop_name']);
			var arr = {
						 "name" : json[i]['prop_name'],
						 "value" : json[i]['prop_count']
						}
			if(prop_report == ''){
				prop_report = "[{'name':'"+json[i]['prop_name']+"','value':'"+json[i]['prop_count']+"'}]";
				jsonarray = eval('('+prop_report+')');
			}
			else{
				jsonarray.push(arr);
			}
		}
		var all_arr = new Array(prop_name);
		var loadingTicket = setTimeout(function (){
			myChart.hideLoading();
			option = {
				legend: {
					data: all_arr[0]
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				series : [
					{
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
			myChart.setOption(option);
			$('#tab2').hide();
		},500);
	}
	charts2();
	function charts3(){
		var myChart = echarts.init(document.getElementById('echarts3'));
		var option = {}
		myChart.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		clearTimeout(loadingTicket);
		var json = <?php echo json_encode($result3) ?>;
		var prop_name = new Array();
		var prop_report = "";
		var jsonarray = null;
		for(var i = 0,r = json.length;i < r;i++){
			prop_name.push(json[i]['prop_name']);
			var arr = {
						 "name" : json[i]['prop_name'],
						 "value" : json[i]['prop_count']
						}
			if(prop_report == ''){
				prop_report = "[{'name':'"+json[i]['prop_name']+"','value':'"+json[i]['prop_count']+"'}]";
				jsonarray = eval('('+prop_report+')');
			}
			else{
				jsonarray.push(arr);
			}
		}
		var all_arr = new Array(prop_name);
		var loadingTicket = setTimeout(function (){
			myChart.hideLoading();
			option = {
				legend: {
					data: all_arr[0]
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				series : [
					{
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
			myChart.setOption(option);
			$('#tab3').hide();
		},500);
	}
	charts3();
	//显示图标
	$('a.view').bind('click',function(){
		var $count = parseFloat($(this).attr('alt'));
		var $type = ($(this).hasClass('ch'))?0:1;
		$('#add-iframe').remove();
		var taskName = $(this).attr('name');
		var functionName = $(this).attr('title');
		var topHTML = '<tr id="add-iframe">\
						<td colspan="12" style="background:#ffffcc;">\
							<div id="addCharts" class="echarts"></div>\
							<table class="tablesorter" cellspacing="0">\
								<thead>\
									<tr>\
										<th align="center">行为ID</th>\
										<th align="center">行为名</th>\
										<th align="center">数量</th>\
										<th align="center">占比</th>\
									</tr>\
								</thead>\
								<tbody id="addChartsCon"></tbody>\
							</table>\
						</td>\
						</tr>';
		$(this).parents('tr').after(topHTML);
		$.get("<?php echo site_url() ?>/sysanaly/functionanalysis/chartsDetail?Name="+taskName+"&functionName="+functionName,function(res){
			var data = JSON.parse(res);
			console.log(data)
			var myChart1 = echarts.init(document.getElementById('addCharts'));
			var option = {}
			myChart1.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			clearTimeout(loadingTicket);
			var behaviour_name = new Array();
			var html = '';
			var prop_report = "";
			var jsonarray = null;
			for(var i = 0,r = data.length;i < r;i++){
				html += '<tr>';
				html += '<td align="center">'+data[i]['behaviour_id']+'</td>';
				html += '<td align="center">'+data[i]['behaviour_name']+'</td>';
				if($type == 0){
					html += '<td align="center">'+data[i]['behaviour_gaincount']+'</td>';
					html += '<td align="center">'+(parseFloat(data[i]['behaviour_gaincount']/$count)*100).toFixed(2)+'%</td>';
					behaviour_name.push(data[i]['behaviour_name']);
					var arr = {
								 "name" : data[i]['behaviour_name'],
								 "value" : data[i]['behaviour_gaincount']
								}
					if(prop_report == ''){
						prop_report = "[{'name':'"+data[i]['behaviour_name']+"','value':'"+data[i]['behaviour_gaincount']+"'}]";
						jsonarray = eval('('+prop_report+')');
					}
					else{
						jsonarray.push(arr);
					}
				}
				else{
					html += '<td align="center">'+data[i]['behaviour_consumecount']+'</td>';
					html += '<td align="center">'+(parseFloat(data[i]['behaviour_gaincount']/$count)*100).toFixed(2)+'%</td>';
					behaviour_name.push(data[i]['behaviour_name']);
					var arr = {
								 "name" : data[i]['behaviour_name'],
								 "value" : data[i]['behaviour_consumecount']
								}
					if(prop_report == ''){
						prop_report = "[{'name':'"+data[i]['behaviour_name']+"','value':'"+data[i]['behaviour_consumecount']+"'}]";
						jsonarray = eval('('+prop_report+')');
					}
					else{
						jsonarray.push(arr);
					}
				}
				html += '</tr>';
				
			}
			$('#addChartsCon').html(html);
			var all_arr = new Array(behaviour_name);
			var loadingTicket = setTimeout(function (){
				myChart1.hideLoading();
				option = {
				legend: {
					data: all_arr[0]
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				series : [
					{
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
				myChart1.setOption(option);
			},500);
		})
	})
});
</script>