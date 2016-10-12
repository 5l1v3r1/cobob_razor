<style>
	#container {height: 240px; width: 100%;}
</style>
<div id="container"></div>
<script>
var type = '<?php echo $type ?>';
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($result) ?>;
	console.log(jsonData)
	var jsonArr = new Array();//总数组
	var channel_name = new Array();
	var dauuser = new Array();
	var payuser = new Array();
	var payamount = new Array();
	var arpu = new Array();
	var arppu = new Array();
	var payrate = new Array();

	for(var i = 0,r = jsonData.length;i < r;i++){
		channel_name[i] = jsonData[i]['channel_name'];
		dauuser[i] = jsonData[i]['dauuser'];
		payuser[i] = jsonData[i]['payuser'];
		payamount[i] = jsonData[i]['payamount'];
		arpu[i] = jsonData[i]['arpu'];
		arppu[i] = jsonData[i]['arppu'];
		payrate[i] = jsonData[i]['payrate'];
	}
	jsonArr.push(channel_name,dauuser,payuser,payamount,arpu,arppu,payrate);
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
	// 为echarts对象加载数据 
	myChart.hideLoading();
	default_data(type);
	function default_data(num){
		if(num == 0){
			option = {
				tooltip: {
					trigger: 'axis'
				},
				legend: {
					data:['付费率','ARPU','ARPPU']
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
						axisLabel: {
							formatter: '{value} %'
						},
						splitLine: {
				            show: false
				        }
					},
					{
						type: 'value',
						axisLabel: {
							formatter: '{value}'
						},
						splitLine: {
				            show: false
				        }
					}
				],
				series: [
					{
						name:'付费率',
						type:'bar',
						data:jsonArr[6],
						tooltip : {
			                trigger: 'item',
			                formatter: "付费率: {c} %"
			            }
					},
					{
						name:'ARPU',
						type:'line',
						yAxisIndex: 1,
						data:jsonArr[4]
					},
					{
						name:'ARPPU',
						type:'line',
						yAxisIndex: 1,
						data:jsonArr[5]
					}
				]
			};
		}
		else if(num == 1){
			var virtualmoney_outputway = new Array();
			var virtualmoney_report = "";
			var jsonarray = null;
			for(var i = 0,r = jsonData.length;i < r;i++){
				var arr = {
							 "value" : jsonData[i]['payamount'],
							 "name" : jsonData[i]['channel_name']
							}
				if(virtualmoney_report == ''){
					virtualmoney_report = "[{'name':'"+jsonData[i]['channel_name']+"','value':'"+jsonData[i]['payamount']+"'}]";
					jsonarray = eval('('+virtualmoney_report+')')
				}
				else{
					jsonarray.push(arr);
				}
			}
			option = {
				title : {
					text: '渠道付费比重',
					x:'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{a} <br/>{b} : {c} ({d}%)"
				},
				legend: {
					orient: 'vertical',
					right : 'right',
					data: jsonArr[0]
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
		}
		myChart.setOption(option);
	}
});
</script>