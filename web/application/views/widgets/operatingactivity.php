<style>
article.module {margin: 0; width: 100%; border: 0; height: 600px; overflow: auto; background: #fff!important;}
.tab_content {display: none; padding: 10px; background: #fff;}
#container {height: 300px; width: 100%; background: #fff!important;}
.import-excel {margin: 0;}
article.module {overflow: scroll; overflow-x:hidden;}
#addCharts {width: 100%; height: 250px;}
</style>
<article class="module width_full">
	<div id="container"></div>
	<header>
		<ul class="tabs">
			<li><a href="javascript:;">产出详情</a></li>
			<li><a href="javascript:;">消耗详情</a></li>
			<li><a href="javascript:;">行为详情</a></li>
		</ul>
	</header>
	<div class="tab_container">
		<div id="tab1" class="tab_content">
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table width="100%" class="tablesorter" cellspacing="0">
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
									echo "<td align='center'>".$value['propid']."</td>";
									echo "<td align='center'>".$value['propname']."</td>";
									echo "<td align='center'>".$value['proptype']."</td>";
									echo "<td align='center'>".$value['propcount']."</td>";
									echo "<td align='center'><a href='javascript:;' name='".$value['activity_issue']."' title='".$value['propid']."' class='view'>查看</a></td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab2" class="tab_content">
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table width="100%" class="tablesorter" cellspacing="0">
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
									echo "<td align='center'>".$value['propid']."</td>";
									echo "<td align='center'>".$value['propname']."</td>";
									echo "<td align='center'>".$value['proptype']."</td>";
									echo "<td align='center'>".$value['propcount']."</td>";
									echo "<td align='center'><a href='javascript:;' name='".$value['activity_issue']."' title='".$value['propid']."' class='view'>查看</a></td>";
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="tab3" class="tab_content">
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table width="100%" class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">行为ID</th>
								<th align="center">行为名</th>
								<th align="center">数量</th>
								<th align="center">占比</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result3 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['propid']."</td>";
									echo "<td align='center'>".$value['propname']."</td>";
									echo "<td align='center'>".$value['propcount']."</td>";
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
<?php 
$all_arr = Array();
array_push($all_arr,$result1,$result2,$result3);
?>
<script>
var current = 0;
$(document).ready(function(){
	$('.tabs li:first').addClass('active');
	$('.tab_container > div:first').show();
	$('.tabs li').bind('click',function(){
		current = $(this).index();
		$('.tab_container > div').hide();
		$('.tab_container > div').eq(current).show();
		$('.tabs li').removeClass('active');
		$(this).addClass('active');
		charts(current);
	});
	var zb = 0;
	$('#tab3 tbody tr').each(function(){
		zb += parseInt($(this).find('td:eq(2)').text());
	});
	$('#tab3 tbody tr').each(function(){
		var s = parseInt($(this).find('td:eq(2)').text());
		$(this).find('td:eq(3)').text((s/zb*100).toFixed(2)+'%');
	});
	charts(current);
});
var all_arr = <?php echo json_encode($all_arr); ?>;
function charts(type){
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
	var loadingTicket = setTimeout(function (){
		myChart.hideLoading();

		var propname = new Array();
		var propcount = new Array();
		var jsonstr="[]";
		var jsonarray = eval('('+jsonstr+')');
		for(var i=0;i<all_arr[type].length;i++){
			propname.push(all_arr[type][i]['propname']);
			propcount.push(all_arr[type][i]['propcount']);
			var arr  =
			     {
			         "value" : all_arr[type][i]['propcount'],
			         "name" : all_arr[type][i]['propname']
			     }
			jsonarray.push(arr);
		}

		option = {
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
		        left: 'center',
		        data: propname
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
	},500);
}
//显示图标
$('a.view').bind('click',function(){
	$('#add-iframe').remove();
	var propid = $(this).attr('title');
	var activity_issue = $(this).attr('name');
	var detailstype;
	if(current == 0){
		detailstype = 'output';
	}
	else if(current == 1){
		detailstype = 'consume';
	}
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
	$.get("<?php echo site_url() ?>/sysanaly/operatingactivity/distributeddetails?propid="+propid+"&activity_issue="+activity_issue+"&detailstype="+detailstype,function(res){
		var data = JSON.parse(res);
		console.log(data);
		var html = '';
		for(var i=0;i<data.length;i++){
			html += "<tr>";
			html += "<td align='center'>"+data[i]['actionid']+"</td>";
			html += "<td align='center'>"+data[i]['actionname']+"</td>";
			html += "<td align='center'>"+data[i]['actioncount']+"</td>";
			html += "<td align='center'></td>";
			html += "</tr>";
		}
		$('#addChartsCon').html(html);
		var allNum = 0;
		$('#addChartsCon tr').each(function(){
			var t = parseInt($(this).find('td:eq(2)').text());
			allNum += t;
		});
		$('#addChartsCon tr').each(function(){
			var t = parseInt($(this).find('td:eq(2)').text());
			$(this).find('td:eq(3)').text((t/allNum*100).toFixed(2)+'%');
		});
		if (myChart){
			myChart = null;
		}
		var myChart = echarts.init(document.getElementById('addCharts'));
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

			var actionname = new Array();
			var actioncount = new Array();
			var jsonstr="[]";
			var jsonarray = eval('('+jsonstr+')');
			for(var i=0;i<data.length;i++){
				actionname.push(data[i]['actionname']);
				actioncount.push(data[i]['actioncount']);
				var arr  =
				     {
				         "value" : data[i]['actioncount'],
				         "name" : data[i]['actionname']
				     }
				jsonarray.push(arr);
			}

			option = {
			    tooltip : {
			        trigger: 'item',
			        formatter: "{a} <br/>{b} : {c} ({d}%)"
			    },
			    legend: {
			        left: 'center',
			        data: actionname
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
		},500);

	});
});
</script>
