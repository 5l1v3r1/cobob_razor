<style>
article.module {margin: 0; width: 100%; border: 0; height: 400px; overflow: auto; background: #fff!important;}
.tab_content {display: none; padding: 10px; background: #fff;}
.tab_content .module_content {height: 250px;}
#container {height: 260px; width: 100%; background: #fff!important;}
</style>
<article class="module width_full">
	<div id="container"></div>
	<header>
		<ul class="tabs">
			<li><a href="javascript:;"><?php echo lang('t_7unlogin_yao') ?></a></li>
			<li><a href="javascript:;"><?php echo lang('t_14unlogin_yao') ?></a></li>
			<li><a href="javascript:;"><?php echo lang('t_30unlogin_yao') ?></a></li>
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
								<th align="center"><?php echo lang('t_dengJi_yao') ?></th>
								<th align="center"><?php echo lang('tag_all_user') ?></th>
								<th align="center"><?php echo lang('t_dengJiFenBuLv_yao') ?></th>
								<th align="center"><?php echo lang('t_fuFeiJinE_yao') ?></th>
								<th align="center"><?php echo lang('t_payUserNum_yao') ?></th>
								<th align="center"><?php echo lang('t_renShuZhanBi_yao') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($alluserdetail_7 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['levelfield']."</td>";
									echo "<td align='center'>".$value['users']."</td>";
									echo "<td align='center'>".round((floatval($value['leveldistribitionrate'])*100),2)."%</td>";
									echo "<td align='center'>".$value['payamount']."</td>";
									echo "<td align='center'>".$value['payusers']."</td>";
									echo "<td align='center'>".round((floatval($value['userrate'])*100),2)."%</td>";
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
								<th align="center"><?php echo lang('t_dengJi_yao') ?></th>
								<th align="center"><?php echo lang('tag_all_user') ?></th>
								<th align="center"><?php echo lang('t_dengJiFenBuLv_yao') ?></th>
								<th align="center"><?php echo lang('t_fuFeiJinE_yao') ?></th>
								<th align="center"><?php echo lang('t_payUserNum_yao') ?></th>
								<th align="center"><?php echo lang('t_renShuZhanBi_yao') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($alluserdetail_14 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['levelfield']."</td>";
									echo "<td align='center'>".$value['users']."</td>";
									echo "<td align='center'>".round((floatval($value['leveldistribitionrate'])*100),2)."%</td>";
									echo "<td align='center'>".$value['payamount']."</td>";
									echo "<td align='center'>".$value['payusers']."</td>";
									echo "<td align='center'>".round((floatval($value['userrate'])*100),2)."%</td>";
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
								<th align="center"><?php echo lang('t_dengJi_yao') ?></th>
								<th align="center"><?php echo lang('tag_all_user') ?></th>
								<th align="center"><?php echo lang('t_dengJiFenBuLv_yao') ?></th>
								<th align="center"><?php echo lang('t_fuFeiJinE_yao') ?></th>
								<th align="center"><?php echo lang('t_payUserNum_yao') ?></th>
								<th align="center"><?php echo lang('t_renShuZhanBi_yao') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($alluserdetail_30 as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['levelfield']."</td>";
									echo "<td align='center'>".$value['users']."</td>";
									echo "<td align='center'>".round((floatval($value['leveldistribitionrate'])*100),2)."%</td>";
									echo "<td align='center'>".$value['payamount']."</td>";
									echo "<td align='center'>".$value['payusers']."</td>";
									echo "<td align='center'>".round((floatval($value['userrate'])*100),2)."%</td>";
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
<script>
$(document).ready(function(){
	$('.tabs li:first').addClass('active');
	$('.tab_container > div:first').show();
	$('.tabs li').bind('click',function(){
		var current = $(this).index();
		$('.tab_container > div').hide();
		$('.tab_container > div').eq(current).show();
		$('.tabs li').removeClass('active');
		$(this).addClass('active');
	});
});
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
	var jsonA = <?php echo json_encode($alluserdetail_7) ?>;
	var jsonB = <?php echo json_encode($alluserdetail_14) ?>;
	var jsonC = <?php echo json_encode($alluserdetail_30) ?>;
	var level = new Array();
	var users_7 = new Array();
	var users_14 = new Array();
	var users_30 = new Array();

	for(var i = 0,r = jsonA.length;i < r;i++){
		level.push(jsonA[i]['levelfield']);
		users_7.push(jsonA[i]['users']);
		users_14.push(jsonB[i]['users']);
		users_30.push(jsonC[i]['users']);
	}

	all_arr = new Array(level,users_7,users_14,users_30);
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
		legend: {
			data:['7日未登录用户','14日未登录用户','30日未登录用户']
		},
		dataZoom: [
			{
				show: true,
				start: 0,
				end: 25,
				handleSize: 8
			}
		],
		xAxis : [
			{
				type : 'category',
				name: '等级',
				data : all_arr[0],
				splitLine: {
		            show: false
		        }
			}
		],
		yAxis : [
			{
				type : 'value',
				name: '用户数',
				splitLine: {
		            show: false
		        }
			}
		],
		series : [
			{
				name:'7日未登录用户',
				smooth: true,
				type:'bar',
				data:all_arr[1]
			},
			{
				name:'14日未登录用户',
				smooth: true,
				type:'bar',
				data:all_arr[2]
			},
			{
				name:'30日未登录用户',
				smooth: true,
				type:'bar',
				data:all_arr[3]
			}
		]
	};
	myChart.setOption(option);
},500);
</script>
