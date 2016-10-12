<section class="column" style="height: 900px;" id="highchart">
	<article class="module width_full">
		<header>
			<!-- 付费数据  -->
			<h3 class="h3_fontstyle">
				<?php echo  lang('t_shiShiGaiKuang_yao')?>
				<!-- <span class="grey">(注:此统计项不满足渠道、区服、时间筛选要求）</span> -->
			</h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a href="javascript:changeChartName('<?php echo  lang('t_shiShiZaiXian_yao')?>');"><?php echo  lang('t_shiShiZaiXian_yao')?></a></li>
					<li><a href="javascript:changeChartName('新增用户');">新增用户</a></li>
					<li><a href="javascript:changeChartName('<?php echo  lang('t_huoYueYongHu_yao')?>');"><?php echo  lang('t_huoYueYongHu_yao')?></a></li>
					<li><a href="javascript:changeChartName('<?php echo  lang('t_fuFeiJinE_yao')?>');"><?php echo  lang('t_fuFeiJinE_yao')?></a></li>
				</ul>
			</div>
		</header>
		<!-- 图表 -->
		<div id="container" class="module_content" style="height: 260px;">
			
		</div>
	</article>
</section>
<?php
	$all_arr = array();
	array_push($all_arr,$result,$y_result);
?>
<script type="text/javascript">
// 基于准备好的dom，初始化echarts图表
var myChart = echarts.init(document.getElementById('container'));
option = {
	// title : {
	// 	text: '实时数据时间：',
	// 	subtext: '每5分钟更新一次'
	// },
	tooltip : {
		trigger: 'axis'
	},
	dataZoom: [
		{
			show: true,
			start: 0,
			end: 100
		}
	],
	grid: {
        top: '12%',
        left: '5%',
        right: '5%',
        containLabel: true
    }
};
myChart.showLoading({
	animation:false,
	text : '数据加载中 ...',
	textStyle : {fontSize : 20},
	effect : 'whirling'
});
var $all_data = <?php echo json_encode($all_arr) ?>;
var jsonData = $all_data[0];
var y_jsonData = $all_data[1];

var jsonArr = new Array();//总数组
var count_time = new Array();
var onlineusers = new Array();//实时在线
var newusers = new Array();//新增用户
var dauusers = new Array();//活跃用户
var payamount = new Array();//付费金额

for(var i = 0,r = y_jsonData.length;i < r;i++){
	if(jsonData[i]){
		count_time[i] = jsonData[i]['count_time'];
		onlineusers[i] = jsonData[i]['onlineusers'];
		newusers[i] = jsonData[i]['newusers'];
		dauusers[i] = jsonData[i]['dauusers'];
		payamount[i] = jsonData[i]['payamount'];
	}
	else{
		count_time[i] = '-';
		onlineusers[i] = '-';
		newusers[i] = '-';
		dauusers[i] = '-';
		payamount[i] = '-';
	}
}
jsonArr.push(count_time,onlineusers,newusers,dauusers,payamount);
var y_jsonArr = new Array();//总数组
var y_count_time = new Array();
var y_onlineusers = new Array();//实时在线
var y_newusers = new Array();//新增用户
var y_dauusers = new Array();//活跃用户
var y_payamount = new Array();//付费金额
for(var i = 0,r = y_jsonData.length;i < r;i++){
	y_count_time[i] = y_jsonData[i]['count_time'];
	y_onlineusers[i] = y_jsonData[i]['onlineusers'];
	y_newusers[i] = y_jsonData[i]['newusers'];
	y_dauusers[i] = y_jsonData[i]['dauusers'];
	y_payamount[i] = y_jsonData[i]['payamount'];
}
y_jsonArr.push(y_count_time,y_onlineusers,y_newusers,y_dauusers,y_payamount);
function default_data(type){
	myChart.clear();
	option.xAxis = [{
		type : 'category',
		boundaryGap : false,
		data : y_jsonArr[0],
		splitLine: {
            show: false
        }
	}]
	if(type == 1){
		option.yAxis = [{
			type : 'value',
			splitLine: {
	            show: false
	        }
		}]
		option.legend = {
			data:['昨日实时在线','今日实时在线']
		}
		option.series = [
			{
				name:'昨日实时在线',
				smooth: true,
				type:'line',
				data:y_jsonArr[1]
			},
			{
				name:'今日实时在线',
				smooth: true,
				type:'line',
				data:jsonArr[1]
			}
		]
	}
	else if(type == 2){
		option.yAxis = [{
			type : 'value',
			splitLine: {
	            show: false
	        }
		}]
		option.legend = {
			data:['今日新增用户','昨日新增用户']
		}
		option.series = [
			{
				name:'今日新增用户',
				smooth: true,
				type:'line',
				data:jsonArr[2]
			},
			{
				name:'昨日新增用户',
				smooth: true,
				type:'line',
				data:y_jsonArr[2]
			}
		]
	}
	else if(type == 3){
		option.yAxis = [{
			type : 'value',
			axisLabel : {
				formatter: '{value}'
			},
			splitLine: {
	            show: false
	        }
		}]
		option.legend = {
			data:['今日活跃用户','昨日活跃用户']
		}
		option.series = [
			{
				name:'今日活跃用户',
				smooth: true,
				type:'line',
				data:jsonArr[3]
			},
			{
				name:'昨日活跃用户',
				smooth: true,
				type:'line',
				data:y_jsonArr[3]
			}
		]
	}
	else if(type == 4){
		option.yAxis = [{
			type : 'value',
			axisLabel : {
				formatter: '{value}'
			},
			splitLine: {
	            show: false
	        }
		}]
		option.legend = {
			data:['今日付费金额','昨日付费金额']
		}
		option.series = [
			{
				name:'今日付费金额',
				smooth: true,
				type:'line',
				data:jsonArr[4]
			},
			{
				name:'昨日付费金额',
				smooth: true,
				type:'line',
				data:y_jsonArr[4]
			}
		]
	}
	myChart.setOption(option);
	myChart.hideLoading();
}
function changeChartName(name)
{	
	chartName = name;
	if(chartName=="<?php echo lang('t_shiShiZaiXian_yao')?>"){
		default_data(1);
	}
	if(chartName=="新增用户"){
		default_data(2);
	}
	if(chartName=="<?php echo lang('t_huoYueYongHu_yao')?>"){
		default_data(3);
	}
	if(chartName=="<?php echo lang('t_fuFeiJinE_yao')?>"){
		default_data(4);
	}
}
$(document).ready(function(){
	$('.submit_link .tabs li').bind('click',function(){
		$('.submit_link .tabs li').removeClass('active');
		$(this).addClass('active');
	});
	default_data(1);
});
</script>