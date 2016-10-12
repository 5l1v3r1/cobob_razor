<style>
article.width_full {margin: 0; width: 100%; border: 0; height: 800px; overflow: auto; overflow-x: hidden; background: #fff!important;}
.tab_content .module_content {height: 300px; }
.hide {display: none;}
.charts-contianer {width: 100%; height: 250px;}
#addCharts {width: 100%; height: 250px;}
#add-iframe {height: 500px; overflow: auto;}
</style>
<article class="module width_full">
	<header>
		<ul class="tabs">
			<li><a href="javascript:">总消耗产出</a></li>
			<li><a href="javascript:">产出分布</a></li>
			<li><a href="javascript:">消耗分布</a></li>
			<li><a href="javascript:">商城购买</a></li>
			<li><a href="javascript:">系统赠送</a></li>
			<li><a href="javascript:">功能产出</a></li>
			<li><a href="javascript:">活动产出</a></li>
		</ul>
	</header>
	<div class="tab_container">
		<div id="container0" class="charts-contianer"></div>
		<div id="container1" class="charts-contianer hide"></div>
		<div id="container2" class="charts-contianer hide"></div>
		<div id="container3" class="charts-contianer hide"></div>
		<div id="container4" class="charts-contianer hide"></div>
		<div id="container5" class="charts-contianer hide"></div>
		<div id="container6" class="charts-contianer hide"></div>
		<div class="tab_content">
			<table class="tablesorter" id="t0" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">日产出</th>
						<th align="center">日消耗</th>
						<th align="center">消耗产出比</th>
						<th align="center">详情</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($totalCG as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['startdate_sk']."</td>";
							echo "<td align='center'>".$value['totalgaincount']."</td>";
							echo "<td align='center'>".$value['totalconsumecount']."</td>";
							echo "<td align='center'>".$value['propcgrate']."</td>";
							echo "<td align='center'><a class='view' href='javascript:;' title='".$propname."' alt='".$proptype."' name='total'>查看</a></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t1" cellspacing="0">
				<thead>
					<tr>
						<th align="center">分布类别</th>
						<th align="center">数量</th>
						<th align="center">占比</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($outputDistrbute as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['action_name']."</td>";
							echo "<td align='center'>".$value['prop_count']."</td>";
							echo "<td align='center'></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t2" cellspacing="0">
				<thead>
					<tr>
						<th align="center">分布类别</th>
						<th align="center">数量</th>
						<th align="center">占比</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($consumeDistrbute as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['action_name']."</td>";
							echo "<td align='center'>".$value['prop_count']."</td>";
							echo "<td align='center'></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t3" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">商城购买</th>
						<th align="center">人数</th>
						<th align="center">人均数量</th>
						<th align="center">详情</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($peopleCount as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['startdate_sk']."</td>";
							echo "<td align='center'>".$value['shopbuy']."</td>";
							echo "<td align='center'>".$value['shopbuyuser']."</td>";
							echo "<td align='center'>".$value['shopbuyrate']."</td>";
							echo "<td align='center'><a class='view' href='javascript:;' title='".$propname."' alt='".$proptype."' name='001'>查看</a></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t4" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">系统赠送</th>
						<th align="center">人数</th>
						<th align="center">人均数量</th>
						<th align="center">详情</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($peopleCount as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['startdate_sk']."</td>";
							echo "<td align='center'>".$value['systemdonate']."</td>";
							echo "<td align='center'>".$value['systemdonateuser']."</td>";
							echo "<td align='center'>".$value['systemdonaterate']."</td>";
							echo "<td align='center'><a class='view' href='javascript:;' title='".$propname."' alt='".$proptype."' name='002'>查看</a></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t5" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">功能产出</th>
						<th align="center">人数</th>
						<th align="center">人均数量</th>
						<th align="center">详情</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($peopleCount as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['startdate_sk']."</td>";
							echo "<td align='center'>".$value['functiongaincount']."</td>";
							echo "<td align='center'>".$value['functiongaincountuser']."</td>";
							echo "<td align='center'>".$value['functiongaincountrate']."</td>";
							echo "<td align='center'><a class='view' href='javascript:;' title='".$propname."' alt='".$proptype."' name='003'>查看</a></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
			<table class="tablesorter hide" id="t6" cellspacing="0">
				<thead>
					<tr>
						<th align="center">日期</th>
						<th align="center">活动产出</th>
						<th align="center">人数</th>
						<th align="center">人均数量</th>
						<th align="center">详情</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($peopleCount as $value) {
							echo "<tr>";
							echo "<td align='center'>".$value['startdate_sk']."</td>";
							echo "<td align='center'>".$value['activitygaincount']."</td>";
							echo "<td align='center'>".$value['activitygaincountuser']."</td>";
							echo "<td align='center'>".$value['activitygaincountrate']."</td>";
							echo "<td align='center'><a class='view' href='javascript:;' title='".$propname."' alt='".$proptype."' name='004'>查看</a></td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<!-- end of .tab_container -->
</article>
<?php
	$all_arr = array();
	array_push($all_arr,$propname,$proptype,$totalCG,$outputDistrbute,$consumeDistrbute,$peopleCount);
?>
<script>
$(document).ready(function(){
	$('.tabs li:first').addClass('active');
	$('.tabs li').bind('click',function(){
		$('.tabs li').removeClass('active');
		$(this).addClass('active');
		var current = $(this).index();
		$('.tablesorter').hide();
		$('#t'+current).show();
		$('.charts-contianer').hide();
		$('#container'+current).show();
		showCharts(current);
	});

	//产出分布占比计算
	var t1num = 0;
	$('#t1 tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(1).text());
		t1num += num;
	});
	$('#t1 tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(1).text());
		$(this).find('td').eq(2).text((num/t1num*100).toFixed(2) + '%');
	});
	//消耗分布占比计算
	var t2num = 0;
	$('#t2 tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(1).text());
		t2num += num;
	});
	$('#t2 tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(1).text());
		$(this).find('td').eq(2).text((num/t2num*100).toFixed(2) + '%');
	});

	var all_arr = <?php echo json_encode($all_arr) ?>;
	var propname = all_arr[0];
	var proptype = all_arr[1];
	console.log(all_arr);
	function showCharts(index){
		if (myChart){
			myChart = null;
		}
		if(index == 0){
			var myChart = echarts.init(document.getElementById('container0'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[2];
			var jsonArr = new Array();//总数组
			var startdate_sk = new Array();
			var totalconsumecount = new Array();
			var totalgaincount = new Array();
			var propcgrate = new Array();

			for(var i = 0,r = jsonData.length;i < r;i++){
				startdate_sk[i] = jsonData[i]['startdate_sk'];
				totalconsumecount[i] = jsonData[i]['totalconsumecount'];
				totalgaincount[i] = jsonData[i]['totalgaincount'];
				propcgrate[i] = jsonData[i]['propcgrate'];
			}
			jsonArr.push(startdate_sk.reverse(),totalconsumecount.reverse(),totalgaincount.reverse(),propcgrate.reverse());
			myChart.hideLoading();
			option = {
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['消耗产出比','日产出','日消耗']
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: jsonArr[0],
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value',
			            name: '产出比',
			            splitLine: {
				            show: false
				        }
			        },
			        {
			            type: 'value',
			            name: '道具数',
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    series: [
			        {
			            name:'消耗产出比',
			            type:'bar',
			            data:jsonArr[3]
			        },
			        {
			            name:'日产出',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[2]
			        },
			        {
			            name:'日消耗',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[1]
			        }
			    ]
			};
			myChart.setOption(option);
		}
		else if(index == 1){
			var myChart = echarts.init(document.getElementById('container1'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[3];
			var jsonArr = new Array();//总数组
			var action_name = new Array();
			var prop_count = new Array();

			var jsonstr="[]";
			var jsonarray = eval('('+jsonstr+')');
			for(var i=0;i<all_arr[3].length;i++){
				action_name.push(all_arr[3][i]['action_name']);
				prop_count.push(all_arr[3][i]['prop_count']);
				var arr  =
				     {
				         "value" : all_arr[3][i]['prop_count'],
				         "name" : all_arr[3][i]['action_name']
				     }
				jsonarray.push(arr);
			}
			myChart.hideLoading();
			option = {
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        left: 'center',
			        data: action_name
			    },
			    series : [
			        {
			            name: '占比',
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
		}
		else if(index == 2){
			var myChart = echarts.init(document.getElementById('container2'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[4];
			var jsonArr = new Array();//总数组
			var action_name = new Array();
			var prop_count = new Array();

			var jsonstr="[]";
			var jsonarray = eval('('+jsonstr+')');
			for(var i=0;i<all_arr[4].length;i++){
				action_name.push(all_arr[4][i]['action_name']);
				prop_count.push(all_arr[4][i]['prop_count']);
				var arr  =
				     {
				         "value" : all_arr[4][i]['prop_count'],
				         "name" : all_arr[4][i]['action_name']
				     }
				jsonarray.push(arr);
			}
			myChart.hideLoading();
			option = {
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        left: 'center',
			        data: action_name
			    },
			    series : [
			        {
			            name: '占比',
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
		}
		else if(index == 3){
			var myChart = echarts.init(document.getElementById('container3'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[5];
			var jsonArr = new Array();//总数组
			var startdate_sk = new Array();
			var shopbuyrate = new Array();
			var shopbuy = new Array();
			var shopbuyuser = new Array();

			for(var i = 0,r = jsonData.length;i < r;i++){
				startdate_sk[i] = jsonData[i]['startdate_sk'];
				shopbuyrate[i] = jsonData[i]['shopbuyrate'];
				shopbuy[i] = jsonData[i]['shopbuy'];
				shopbuyuser[i] = jsonData[i]['shopbuyuser'];
			}
			jsonArr.push(startdate_sk.reverse(),shopbuyrate.reverse(),shopbuy.reverse(),shopbuyuser.reverse());
			myChart.hideLoading();
			option = {
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['人均数量','商城购买','人数']
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: jsonArr[0],
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value',
			            name: '人均数量',
			            splitLine: {
				            show: false
				        }
			        },
			        {
			            type: 'value',
			            name: '道具数量/人数',
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    series: [
			        {
			            name:'人均数量',
			            type:'bar',
			            data:jsonArr[1]
			        },
			        {
			            name:'商城购买',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[2]
			        },
			        {
			            name:'人数',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[3]
			        }
			    ]
			};
			myChart.setOption(option);
		}
		else if(index == 4){
			var myChart = echarts.init(document.getElementById('container4'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[5];
			var jsonArr = new Array();//总数组
			var startdate_sk = new Array();
			var systemdonaterate = new Array();
			var systemdonate = new Array();
			var systemdonateuser = new Array();

			for(var i = 0,r = jsonData.length;i < r;i++){
				startdate_sk[i] = jsonData[i]['startdate_sk'];
				systemdonaterate[i] = jsonData[i]['systemdonaterate'];
				systemdonate[i] = jsonData[i]['systemdonate'];
				systemdonateuser[i] = jsonData[i]['systemdonateuser'];
			}
			jsonArr.push(startdate_sk.reverse(),systemdonaterate.reverse(),systemdonate.reverse(),systemdonateuser.reverse());
			myChart.hideLoading();
			option = {
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['人均数量','系统赠送','人数']
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: jsonArr[0],
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value',
			            name: '人均数量',
			            splitLine: {
				            show: false
				        }
			        },
			        {
			            type: 'value',
			            name: '道具数量/人数',
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    series: [
			        {
			            name:'人均数量',
			            type:'bar',
			            data:jsonArr[1]
			        },
			        {
			            name:'系统赠送',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[2]
			        },
			        {
			            name:'人数',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[3]
			        }
			    ]
			};
			myChart.setOption(option);
		}
		else if(index == 5){
			var myChart = echarts.init(document.getElementById('container5'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[5];
			var jsonArr = new Array();//总数组
			var startdate_sk = new Array();
			var functiongaincountrate = new Array();
			var functiongaincount = new Array();
			var functiongaincountuser = new Array();

			for(var i = 0,r = jsonData.length;i < r;i++){
				startdate_sk[i] = jsonData[i]['startdate_sk'];
				functiongaincountrate[i] = jsonData[i]['functiongaincountrate'];
				functiongaincount[i] = jsonData[i]['functiongaincount'];
				functiongaincountuser[i] = jsonData[i]['functiongaincountuser'];
			}
			jsonArr.push(startdate_sk.reverse(),functiongaincountrate.reverse(),functiongaincount.reverse(),functiongaincountuser.reverse());
			myChart.hideLoading();
			option = {
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['人均数量','功能产出','人数']
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: jsonArr[0],
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value',
			            name: '人均数量',
			            splitLine: {
				            show: false
				        }
			        },
			        {
			            type: 'value',
			            name: '道具数量/人数',
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    series: [
			        {
			            name:'人均数量',
			            type:'bar',
			            data:jsonArr[1]
			        },
			        {
			            name:'功能产出',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[2]
			        },
			        {
			            name:'人数',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[3]
			        }
			    ]
			};
			myChart.setOption(option);
		}
		else if(index == 6){
			var myChart = echarts.init(document.getElementById('container6'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = all_arr[5];
			var jsonArr = new Array();//总数组
			var startdate_sk = new Array();
			var activitygaincountrate = new Array();
			var activitygaincount = new Array();
			var activitygaincountuser = new Array();

			for(var i = 0,r = jsonData.length;i < r;i++){
				startdate_sk[i] = jsonData[i]['startdate_sk'];
				activitygaincountrate[i] = jsonData[i]['activitygaincountrate'];
				activitygaincount[i] = jsonData[i]['activitygaincount'];
				activitygaincountuser[i] = jsonData[i]['activitygaincountuser'];
			}
			jsonArr.push(startdate_sk.reverse(),activitygaincountrate.reverse(),activitygaincount.reverse(),activitygaincountuser.reverse());
			myChart.hideLoading();
			option = {
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['人均数量','活动产出','人数']
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: jsonArr[0],
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value',
			            name: '人均数量',
			            splitLine: {
				            show: false
				        }
			        },
			        {
			            type: 'value',
			            name: '道具数量/人数',
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    series: [
			        {
			            name:'人均数量',
			            type:'bar',
			            data:jsonArr[1]
			        },
			        {
			            name:'活动产出',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[2]
			        },
			        {
			            name:'人数',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[3]
			        }
			    ]
			};
			myChart.setOption(option);
		}
	}
	showCharts(0);

	//显示图标
	$('a.view').bind('click',function(){
		var type = $(this).attr('alt');
		var prop_name = $(this).attr('title');
		var action_type = $(this).attr('name');
		$('#add-iframe').remove();
		var topHTML = '<tr id="add-iframe">\
						<td colspan="12" style="background:#ffffcc;">\
							<div id="addCharts" class="echarts"></div>\
							<table class="tablesorter" cellspacing="0">\
								<thead>\
									<tr>\
										<th align="center">VIP等级</th>\
										<th align="center">角色数</th>\
										<th align="center">获得数量</th>\
										<th align="center">消耗数量</th>\
									</tr>\
								</thead>\
								<tbody id="addChartsCon"></tbody>\
							</table>\
						</td>\
						</tr>';
		$(this).parents('tr').after(topHTML);
		$.get("<?php echo site_url() ?>/sysanaly/propanaly/chartsDetail?type="+type+"&prop_name="+prop_name+"&action_type="+action_type,function(res){
			var data = JSON.parse(res);
			console.log(data)
			var html = '';
			for(var i=0;i<data.length;i++){
				html += "<tr>\
					<td align='center'>"+data[i].viplevel+"</td>\
					<td align='center'>"+data[i].rolecount+"</td>\
					<td align='center'>"+data[i].propgaincount+"</td>\
					<td align='center'>"+data[i].propconsumecount+"</td>\
				</tr>";
			}
			$('#addChartsCon').html(html);
			var myChart = echarts.init(document.getElementById('addCharts'));
			var option = {};
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			//总消耗产出
			var jsonData = data;
			var jsonArr = new Array();//总数组
			var viplevel = new Array();
			var rolecount = new Array();
			var propgaincount = new Array();
			var propconsumecount = new Array();

			for(var i = 0,r = jsonData.length;i < r;i++){
				viplevel[i] = jsonData[i]['viplevel'];
				rolecount[i] = jsonData[i]['rolecount'];
				propgaincount[i] = jsonData[i]['propgaincount'];
				propconsumecount[i] = jsonData[i]['propconsumecount'];
			}
			jsonArr.push(viplevel,propgaincount,propconsumecount,rolecount);
			myChart.hideLoading();
			option = {
			    tooltip: {
			        trigger: 'axis'
			    },
			    legend: {
			        data:['获得数量','消耗数量','人数']
			    },
			    xAxis: [
			        {
			            type: 'category',
			            data: jsonArr[0],
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    yAxis: [
			        {
			            type: 'value',
			            name: '数量',
			            splitLine: {
				            show: false
				        }
			        },
			        {
			            type: 'value',
			            name: 'VIP角色数',
			            splitLine: {
				            show: false
				        }
			        }
			    ],
			    series: [
			        {
			            name:'获得数量',
			            type:'bar',
			            data:jsonArr[1]
			        },
			        {
			            name:'消耗数量',
			            type:'bar',
			            data:jsonArr[2]
			        },
			        {
			            name:'人数',
			            type:'line',
			            yAxisIndex: 1,
			            data:jsonArr[3]
			        }
			    ]
			};
			myChart.setOption(option);
		})
	})
});
</script>