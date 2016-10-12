<style>
	.column {height: 500px; overflow: auto; background: #fff;}
	table td {background: #eee;}
	#user-info-detail {padding: 10px; color: #fff; background: #333; height: 25px; line-height: 25px; text-align: center;}
	#container {height: 240px; width: 100%; margin-bottom: 30px;}
	#user-info-detail span {margin-right: 20px;}
</style>
<section class="column">
	<header>
		<h3 class="h3_fontstyle"></h3>
		<div class="submit_link">
			<ul class="tabs" style="position: relative; top: -5px;">
				<!-- 每日在线时长及趋势 -->
				<li><a ct="userconversion" href="javascript:changeChartName('<?php echo  lang('t_meiRiZaiXianShiChangJiQuShi_yao')?>')"><?php echo  lang('t_meiRiZaiXianShiChangJiQuShi_yao')?></a></li>
				<!-- 每日登陆次数及趋势 -->
				<li><a ct="userconversion" href="javascript:changeChartName('<?php echo  lang('t_meiRiDengLuCiShuJiQuShi_yao')?>')"><?php echo  lang('t_meiRiDengLuCiShuJiQuShi_yao')?></a></li>
				<!-- 每日付费金额及趋势 -->
				<li><a ct="userconversion" href="javascript:changeChartName('<?php echo  lang('t_meiRiFuFeiJinEJiQuShi_yao')?>')"><?php echo  lang('t_meiRiFuFeiJinEJiQuShi_yao')?></a></li>
			</ul>
		</div>
	</header>
	<div id="container"></div>
	<div id="user-info-detail">
		<span><?php echo  lang('t_zhangHuID_yao')?>: <?php echo $userAcount->roleId; ?></span>
 		<span><?php echo  lang('t_zhangHuMing_yao')?>: <?php echo $userAcount->account; ?></span>
		<span><?php echo  lang('t_dengJi_yao')?>: <?php echo $userAcount->rolelevel; ?></span>
		<span><?php echo  lang('t_vipDengJi_yao')?>: <?php echo $userAcount->roleviplevel; ?></span>
		<span><?php echo  lang('t_zongFuFei_yao')?>: <?php echo $userAcount->totalpayamount; ?></span>
		<span><?php echo  lang('t_quDao_yao')?>: <?php echo $userAcount->channel_name; ?></span>
		<span><?php echo  lang('t_quFu_yao')?>: <?php echo $userAcount->server_name; ?></span>
	</div>
	<table class="tablesorter" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="center"><?php echo  lang('g_date')?></th>
				<th align="center"><?php echo  lang('t_zaiXianShiChangmin_yao')?></th>
				<th align="center"><?php echo  lang('t_dengLuCiShu_yao')?></th>
				<th align="center"><?php echo  lang('t_fuFeiJinE_yao')?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($result as $value) {
					echo "<tr>";
					echo "<td align='center'>".$value['date']."</td>";
					echo "<td align='center'>".$value['gametimemin']."</td>";
					echo "<td align='center'>".$value['logintimes']."</td>";
					echo "<td align='center'>".$value['payamount']."</td>";
					echo "</tr>";
				}
			?>
		</tbody>
	</table>
</section>
<script>
//When page loads...
$(".tab_content").hide(); //Hide all content
$(".submit_link ul.tabs li:first").addClass("active"); //Activate first tab
$(".submit_link ul.tabs li").bind('click',function(){
	$(".submit_link ul.tabs li").removeClass('active');
	$(this).addClass('active');
});

var jsonData = <?php echo json_encode($result) ?>;
var jsonArr = new Array();//总数组
var date = new Array();
var gametimemin = new Array();//在线时长min
var logintimes = new Array();//登录次数
var payamount = new Array();//付费金额
console.log(jsonData);
for(var i = 0,r = jsonData.length;i < r;i++){
	date[i] = jsonData[i]['date'];
	gametimemin[i] = jsonData[i]['gametimemin'];
	logintimes[i] = jsonData[i]['logintimes'];
	payamount[i] = jsonData[i]['payamount'];
}
jsonArr.push(date,gametimemin,logintimes,payamount);

if (myChart){
	myChart = null;
}
var myChart = echarts.init(document.getElementById('container'));
var current_status = 1;
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
	defaultCharts(current_status);
},500);
function changeChartName(type){
	if(type == "<?php echo  lang('t_meiRiZaiXianShiChangJiQuShi_yao')?>"){
		current_status = 1;
		defaultCharts(current_status);
	}
	else if(type == "<?php echo  lang('t_meiRiDengLuCiShuJiQuShi_yao')?>"){
		current_status = 2;
		defaultCharts(current_status);
	}
	else if(type == "<?php echo  lang('t_meiRiFuFeiJinEJiQuShi_yao')?>"){
		current_status = 3;
		defaultCharts(current_status);
	}
}
function defaultCharts(type){
	if(type == 1){
		option = {
			title : {
				subtext:['分钟']
			},
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:["<?php echo  lang('t_meiRiZaiXianShiChangJiQuShi_yao')?>"]
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0]
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:"<?php echo  lang('t_meiRiZaiXianShiChangJiQuShi_yao')?>",
					type:'line',
					stack: '总量',
					data:jsonArr[1]
				}
			]
		};
	}
	else if(type == 2){
		option = {
			title : {
				subtext:['登陆次数']
			},
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:["<?php echo  lang('t_meiRiDengLuCiShuJiQuShi_yao')?>"]
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0]
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:"<?php echo  lang('t_meiRiDengLuCiShuJiQuShi_yao')?>",
					type:'line',
					stack: '总量',
					data:jsonArr[2]
				}
			]
		};
	}
	else if(type == 3){
		option = {
			title : {
				subtext:['付费金额']
			},
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:["<?php echo  lang('t_meiRiFuFeiJinEJiQuShi_yao')?>"]
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0]
				}
			],
			yAxis : [
				{
					type : 'value'
				}
			],
			series : [
				{
					name:"<?php echo  lang('t_meiRiFuFeiJinEJiQuShi_yao')?>",
					type:'line',
					stack: '总量',
					data:jsonArr[3]
				}
			]
		};
	}
	myChart.setOption(option);
}
</script>