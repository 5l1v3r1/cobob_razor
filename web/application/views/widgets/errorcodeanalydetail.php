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
	<div class="tab_container">
		<div class="module_content">
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead>
							<tr>
								<th align="center">行为ID</th>
								<th align="center">行为名</th>
								<th align="center">人数</th>
								<th align="center">次数</th>
								<th align="center">人均次数</th>
								<th align="center">分布</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($errorcodeanalyAction as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['actionid']."</td>";
									echo "<td align='center'>".$value['actionname']."</td>";
									echo "<td align='center'>".$value['actionuser']."</td>";
									echo "<td align='center'>".$value['actioncount']."</td>";
									echo "<td align='center'>".$value['useravgcount']."</td>";
									echo "<td align='center'><a class='view' href='javascript:;' title='".$id."'>查看</a></td>";
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
	//显示图标
	$('a.view').bind('click',function(){
		$('#add-iframe').remove();
		var errorid = $(this).attr('title');
		var topHTML = '<tr id="add-iframe">\
						<td colspan="12" style="height:500px; background:#ffffcc;">\
							<div id="addCharts" class="echarts"></div>\
							<table class="tablesorter" cellspacing="0">\
								<thead>\
									<tr>\
										<th align="center">日期</th>\
										<th align="center">人数</th>\
										<th align="center">次数</th>\
										<th align="center">人均次数</th>\
									</tr>\
								</thead>\
								<tbody id="addChartsCon"></tbody>\
							</table>\
						</td>\
						</tr>';
		$(this).parents('tr').after(topHTML);
		$.get("<?php echo site_url() ?>/exceptionanaly/errorcodeanaly/chartsDate?errorid="+errorid,function(res){
			var data = JSON.parse(res);
			console.log(data);
			var myChart = echarts.init(document.getElementById('addCharts'));
			var option = {}
			myChart.showLoading({
				animation:false,
				text : '数据加载中 ...',
				textStyle : {fontSize : 20},
				effect : 'whirling'
			});
			clearTimeout(loadingTicket);
			var startdate_sk = new Array();
			var erroruser = new Array();//人数
			var errorcount = new Array();//次数
			var useravgcount = new Array();//人均次数
			var html = '';
			for(var i = 0,r = data.length;i < r;i++){
				startdate_sk.push(data[i]['startdate_sk']);
				erroruser.push(data[i]['erroruser']);
				errorcount.push(data[i]['errorcount']);
				useravgcount.push(data[i]['useravgcount']);
				html += '<tr>';
				html += '<td align="center">'+data[i]['startdate_sk']+'</td>';
				html += '<td align="center">'+data[i]['erroruser']+'</td>';
				html += '<td align="center">'+data[i]['errorcount']+'</td>';
				html += '<td align="center">'+data[i]['useravgcount']+'</td>';
				html += '</tr>';
			}
			$('#addChartsCon').html(html);
			var all_arr = new Array(startdate_sk,erroruser,errorcount,useravgcount);
			var loadingTicket = setTimeout(function (){
				myChart.hideLoading();
				option = {
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						data:['人均次数','人数','次数']
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
							name : '人数次数',
							axisLabel : {
								formatter: '{value} 人'
							},
							splitLine: {
					            show: false
					        }
						},
						{
							type : 'value',
							name : '人均次数',
							axisLabel : {
								formatter: '{value} 人'
							},
							splitLine: {
					            show: false
					        }
						}
					],
					series : [
						{
							name:'人均次数',
							type:'bar',
							data:all_arr[3]
						},
						{
							name:'人数',
							type:'line',
							data:all_arr[2]
						},
						{
							name:'次数',
							type:'line',
							yAxisIndex: 1,
							data:all_arr[1]
						}
					]
				};
				myChart.setOption(option);
			},500);
		})
	})
});
</script>


