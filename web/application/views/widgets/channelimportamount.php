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
	var deviceactivation = new Array();
	var newdevice = new Array();
	var newregister = new Array();
	var newuser = new Array();
	var newuserrate = new Array();
	var registerrate = new Array();

	for(var i = 0,r = jsonData.length;i < r;i++){
		channel_name[i] = jsonData[i]['channel_name'];
		deviceactivation[i] = jsonData[i]['deviceactivation'];
		newdevice[i] = jsonData[i]['newdevice'];
		newregister[i] = jsonData[i]['newregister'];
		newuser[i] = jsonData[i]['newuser'];
		newuserrate[i] = jsonData[i]['newuserrate'];
		registerrate[i] = jsonData[i]['registerrate'];
	}
	jsonArr.push(channel_name,deviceactivation,newdevice,newregister,newuser,newuserrate,registerrate);
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
					data:['设备激活','新增注册','新增用户','注册转化率','创角率']
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
							formatter: '{value}'
						},
						splitLine: {
				            show: false
				        }
					},
					{
						type: 'value',
						axisLabel: {
							formatter: '{value} %'
						},
						splitLine: {
				            show: false
				        }
					}
				],
				series: [
					{
						name:'设备激活',
						type:'bar',
						data:jsonArr[1]
					},
					{
						name:'新增注册',
						type:'bar',
						data:jsonArr[3]
					},
					{
						name:'新增用户',
						type:'bar',
						data:jsonArr[4]
					},
					{
						name:'注册转化率',
						type:'line',
						yAxisIndex: 1,
						data:jsonArr[6],
						tooltip : {
			                trigger: 'item',
			                formatter: "注册转化率: {c} %"
			            }
					},
					{
						name:'创角率',
						type:'line',
						yAxisIndex: 1,
						data:jsonArr[5],
						tooltip : {
			                trigger: 'item',
			                formatter: "创角率: {c} %"
			            }
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
							 "value" : jsonData[i]['newregister'],
							 "name" : jsonData[i]['channel_name']
							}
				if(virtualmoney_report == ''){
					virtualmoney_report = "[{'name':'"+jsonData[i]['channel_name']+"','value':'"+jsonData[i]['newregister']+"'}]";
					jsonarray = eval('('+virtualmoney_report+')')
				}
				else{
					jsonarray.push(arr);
				}
			}
			option = {
				title : {
					text: '渠道注册占比',
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