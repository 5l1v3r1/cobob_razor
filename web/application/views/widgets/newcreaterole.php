<style>
	#container {height: 240px; width: 100%;}
</style>
<div id="container"></div>
<script>
var type = '<?php echo $type?>';
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($result) ?>;
	var jsonArr = new Array();//总数组
	var startdate_sk = new Array();
	var newregister = new Array();
	var newuser = new Array();
	var newcreaterole = new Array();
	var newcreaterolerate = new Array();
	var mixcreaterole = new Array();
	var mixcreaterolerate = new Array();

	for(var i = 0,r = jsonData.length;i < r;i++){
		startdate_sk[i] = jsonData[i]['startdate_sk'];
		newregister[i] = jsonData[i]['newregister'];
		newuser[i] = jsonData[i]['newuser'];
		newcreaterole[i] = jsonData[i]['newcreaterole'];
		newcreaterolerate[i] = jsonData[i]['newcreaterolerate'];
		mixcreaterole[i] = jsonData[i]['mixcreaterole'];
		mixcreaterolerate[i] = jsonData[i]['mixcreaterolerate'];
	}
	jsonArr.push(startdate_sk.reverse(),newregister.reverse(),newuser.reverse(),newcreaterole.reverse(),newcreaterolerate.reverse(),mixcreaterole.reverse(),mixcreaterolerate.reverse());
	if (myChart){
		myChart = null;
	}
	console.log(jsonArr);
	var myChart = echarts.init(document.getElementById('container'));
	var option = {}
	myChart.showLoading({
		animation:false,
		text : '数据加载中 ...',
		textStyle : {fontSize : 20},
		effect : 'whirling'
	});
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
        grid: {
            top: '15%',
            left: '5%',
            right: '5%',
            bottom: '3%',
            containLabel: true
        },
		xAxis : [
			{
				type : 'category',
				data : jsonArr[0],
				splitLine: {
		            show: false
		        }
			}
		]
	};
	// 为echarts对象加载数据 
	myChart.hideLoading();
	default_data(type);
	function default_data(num){
		option.yAxis = [
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
		];
		if(num == 0){
			option.series = [
				{
					name:"创角率",
					type:'bar',
					data: jsonArr[4],
					tooltip : {
		                trigger: 'item',
		                formatter: "创角率: {c} %"
		            }
				},
				{
					name:"新增注册",
					smooth: true,
					type:'line',
					data: jsonArr[1],
					yAxisIndex: 1
				},
				{
					name:"新增用户",
					smooth: true,
					type:'line',
					data: jsonArr[2],
					yAxisIndex: 1
				}
			]
			option.legend = {
				data:[
					"创角率",
					"新增注册",
					"新增用户"
				]
			}
		}
		else if(num == 1){
			option.series = [
				{
					name:"滚服用户占比",
					type:'bar',
					data: jsonArr[6],
					tooltip : {
		                trigger: 'item',
		                formatter: "滚服用户占比: {c} %"
		            }
				},
				{
					name:"滚服用户创角",
					smooth: true,
					type:'line',
					data: jsonArr[5]
				},
				{
					name:"新增用户",
					smooth: true,
					type:'line',
					data: jsonArr[2]
				}
			]
			option.legend = {
				data:[
					"滚服用户占比",
					"滚服用户创角",
					"新增用户"
				]
			}
		}
		myChart.setOption(option);
	}
});
</script>