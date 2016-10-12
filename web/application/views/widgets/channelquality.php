<style>
	#container {height: 240px; width: 100%;}
</style>
<div id="container"></div>
<script>
var type = '<?php echo $type?>';
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonArr = <?php echo json_encode($result) ?>;
	var allArr = new Array();//总数组
	var channel_name = new Array();
	var dayavgdau = new Array();
	var daudegree = new Array();
	var day1avgremain = new Array();
	var day3avgremain = new Array();
	var day7avgremain = new Array();
	var day14avgremain = new Array();
	var day30avgremain = new Array();

	for(var i = 0,r = jsonArr.length;i < r;i++){
		channel_name[i] = jsonArr[i]['channel_name'];
		dayavgdau[i] = jsonArr[i]['dayavgdau'];
		daudegree[i] = jsonArr[i]['daudegree'];
		day1avgremain[i] = jsonArr[i]['day1avgremain'];
		day3avgremain[i] = jsonArr[i]['day3avgremain'];
		day7avgremain[i] = jsonArr[i]['day7avgremain'];
		day14avgremain[i] = jsonArr[i]['day14avgremain'];
		day30avgremain[i] = jsonArr[i]['day30avgremain'];
	}
	allArr.push(channel_name,dayavgdau,daudegree,day1avgremain,day3avgremain,day7avgremain,day14avgremain,day30avgremain);
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
					data:['日均活跃','活跃度']
				},
				xAxis: [
					{
						type: 'category',
						data: allArr[0],
						splitLine: {
				            show: false
				        }
					}
				],
				yAxis: [
					{
						type: 'value',
						name: '日均活跃',
						axisLabel: {
							formatter: '{value}'
						},
						splitLine: {
				            show: false
				        }
					},
					{
						type: 'value',
						name: '活跃度',
						splitLine: {
				            show: false
				        }
					}
				],
				series: [
					{
						name:'日均活跃',
						type:'bar',
						data:allArr[1]
					},
					{
						name:'活跃度',
						type:'line',
						yAxisIndex: 1,
						data:allArr[2]
					}
				]
			};
		}
		else if(num == 1){
			option = {
			    tooltip: {
			        trigger: 'axis',
			        formatter: "{c} %"
			    },
			    legend: {
			        data:allArr[0]
			    },
			    grid: {
			        left: '3%',
			        right: '4%',
			        bottom: '3%',
			        containLabel: true
			    },
			    xAxis: {
			        type: 'category',
			        boundaryGap: false,
			        data: ['次日均留存率','3日均留存率','7日均留存率','14日均留存率','30日均留存率'],
			        splitLine: {
			            show: false
			        }
			    },
			    yAxis: {
			        type: 'value',
			        name: '留存率',
			        axisLabel: {
						formatter: '{value} %'
					},
					splitLine: {
			            show: false
			        }
			    }
			};
			
			var jsonarray = [];
			for(var i = 0,r = allArr[0].length;i < r;i++){
				var jsonFormatter = {
					name:allArr[0][i],
					type:'bar',
					smooth: true,
					data:[allArr[3][i],allArr[4][i],allArr[5][i],allArr[6][i],allArr[7][i]]
				};
				jsonarray.push(jsonFormatter);
			}
			option.series = jsonarray;
		}
		myChart.setOption(option);
	}
});
</script>