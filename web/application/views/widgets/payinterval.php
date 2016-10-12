<style>
	.column {height: 500px; overflow: auto;}
	table td {background: #eee;}
	#user-info-detail {padding: 10px; color: #fff; background: #333; height: 25px; line-height: 25px; text-align: center;}
	#user-info-detail span {margin-right: 20px;}
	#container {height: 260px; width: 100%; background: #fff!important;}
</style>
<div id="container"></div>
<script>
var type = "<?php echo $charts ?>";
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
	var json;
	if(type == '1'){
		json = <?php echo json_encode($firstpay) ?>;
	}
	else if(type == '2'){
		json = <?php echo json_encode($secondpay) ?>;
	}
	else if(type == '3'){
		json = <?php echo json_encode($thirdpay) ?>;
	}
	var firstpaytime = new Array();
	var payusers = new Array();
	for(var i = 0,r = json.length;i < r;i++){
		firstpaytime.push(json[i]['firstpaytime']);
		payusers.push(json[i]['payusers']);
	}
	all_arr = new Array(firstpaytime,payusers);
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
		legend: {
			data:['付费人数']
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
		series : [
			{
				name:'付费人数',
				type:'line',
				data:all_arr[1]
			}
		]
	};
	myChart.setOption(option);
},500);
</script>