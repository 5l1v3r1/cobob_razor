<style>
	#container {height: 350px; width: 100%;}
</style>
<div id="container"></div>
<script>
var type = '<?php echo $echarts?>';
// 基于准备好的dom，初始化echarts图表
var jsonData = <?php echo json_encode($result) ?>;
var jsonArr = new Array();//总数组
var dateArr = new Array();
var mainArr = new Array();//新增用户 | 设备留存 | 留存详情
var Arr1 = new Array();
var Arr2 = new Array();
var Arr3 = new Array();
var Arr4 = new Array();
var Arr5 = new Array();
var Arr6 = new Array();
var Arr7 = new Array();
var Arr14 = new Array();
var Arr30 = new Array();
for(var i = 0,r = jsonData.length;i < r;i++){
	switch(type){
		case 'userremain':
			dateArr[i] = jsonData[i]['date'];
			mainArr[i] = jsonData[i]['usercount'];
			break;
		case 'deviceremain':
			dateArr[i] = jsonData[i]['date'];
			mainArr[i] = jsonData[i]['devicecount'];
			break;
		case 'maindetail':
			dateArr[i] = jsonData[i]['startdate_sk'];
			mainArr[i] = jsonData[i]['count'];
			break;
	}
	Arr1[i] = (parseFloat(jsonData[i]['day1'])*100).toFixed(0);
	Arr2[i] = (parseFloat(jsonData[i]['day2'])*100).toFixed(0);
	Arr3[i] = (parseFloat(jsonData[i]['day3'])*100).toFixed(0);
	Arr4[i] = (parseFloat(jsonData[i]['day4'])*100).toFixed(0);
	Arr5[i] = (parseFloat(jsonData[i]['day5'])*100).toFixed(0);
	Arr6[i] = (parseFloat(jsonData[i]['day6'])*100).toFixed(0);
	Arr7[i] = (parseFloat(jsonData[i]['day7'])*100).toFixed(0);
	Arr14[i] = (parseFloat(jsonData[i]['day14'])*100).toFixed(0);
	Arr30[i] = (parseFloat(jsonData[i]['day30'])*100).toFixed(0);
}
jsonArr.push(dateArr.reverse(),mainArr.reverse(),Arr1.reverse(),Arr2.reverse(),Arr3.reverse(),Arr4.reverse(),Arr5.reverse(),Arr6.reverse(),Arr7.reverse(),Arr14.reverse(),Arr30.reverse());

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
option = {
	tooltip : {
		trigger: 'axis',
		formatter: function (params,ticket,callback) {
            var res = params[0].name;
            for (var i = 0, l = params.length; i < l; i++) {
                res += '<br/>' + params[i].seriesName + ' : ' + params[i].value + '%';
            }
            setTimeout(function (){
                // 仅为了模拟异步回调
                callback(ticket, res);
            }, 100)
            return 'loading';
        }
	},
	dataZoom: [
		{
			type: 'slider',
			show: true,
			start: 0,
			end: 25
		}
	],
	grid: {
        top: '12%',
        left: '5%',
        right: '5%',
        containLabel: true
    },
	xAxis : [
		{
			type : 'category',
			boundaryGap : false,
			data : [],
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
	]
};
// 为echarts对象加载数据 
myChart.hideLoading();
switch(type){
	case 'userremain':
		default_data(1,"<?php echo  lang('m_rpt_userRetention')?>");
		break;
	case 'deviceremain':
		default_data(1,"<?php echo  lang('t_deviceRetention_yao')?>");
		break;
	case 'maindetail':
		default_data(1,"<?php echo  lang('t_liucunxiangqing_yao')?>");
		break;
}
function default_data(num,name){
	option.yAxis = [];
	option.xAxis[0]['data'] = jsonArr[0];
	if(num == 1){
		option.series = [
			{
				name:"次日",
				smooth: true,
				type:'line',
				tooltip : {
			        formatter: "{c} %"
			    },
				data: jsonArr[2]
			},
			{
				name:"2日",
				smooth: true,
				type:'line',
				data: jsonArr[3]
			},
			{
				name:"3日",
				smooth: true,
				type:'line',
				data: jsonArr[4]
			},
			{
				name:"4日",
				smooth: true,
				type:'line',
				data: jsonArr[5]
			},
			{
				name:"5日",
				smooth: true,
				type:'line',
				data: jsonArr[6]
			},
			{
				name:"6日",
				smooth: true,
				type:'line',
				data: jsonArr[7]
			},
			{
				name:"7日",
				smooth: true,
				type:'line',
				data: jsonArr[8]
			},
			{
				name:"14日",
				smooth: true,
				type:'line',
				data: jsonArr[9]
			},
			{
				name:"30日",
				smooth: true,
				type:'line',
				data: jsonArr[10]
			}
		]
		option.legend = {
			data:[
				"次日",
				"2日",
				"3日",
				"4日",
				"5日",
				"6日",
				"7日",
				"14日",
				"30日"
			]
		}
		option.yAxis = [
			{
				type : 'value',
				axisLabel: {
					show: true,
					interval: 'auto',
					formatter: '{value} %'
				},
				splitLine: {
		            show: false
		        }
			}
		]
	}
	myChart.setOption(option);
}
function parentHTMLcommunication(data,type){
	$data = data;
	if(type == 1){
		var filter_jsonData = $data;
		var filter_jsonDatajsonArr = new Array();//总数组
		var filter_dateArr = new Array();
		var filter_mainArr = new Array();//新增用户 | 设备留存 | 留存详情
		var filter_Arr1 = new Array();
		var filter_Arr2 = new Array();
		var filter_Arr3 = new Array();
		var filter_Arr4 = new Array();
		var filter_Arr5 = new Array();
		var filter_Arr6 = new Array();
		var filter_Arr7 = new Array();
		var filter_Arr14 = new Array();
		var filter_Arr30 = new Array();
		for(var i = 0,r = filter_jsonData.length;i < r;i++){
			filter_dateArr[i] = filter_jsonData[i]['startdate_sk'];
			filter_mainArr[i] = filter_jsonData[i]['count'];
			filter_Arr1[i] = (parseFloat(filter_jsonData[i]['day1'])*100).toFixed(0);
			filter_Arr2[i] = (parseFloat(filter_jsonData[i]['day2'])*100).toFixed(0);
			filter_Arr3[i] = (parseFloat(filter_jsonData[i]['day3'])*100).toFixed(0);
			filter_Arr4[i] = (parseFloat(filter_jsonData[i]['day4'])*100).toFixed(0);
			filter_Arr5[i] = (parseFloat(filter_jsonData[i]['day5'])*100).toFixed(0);
			filter_Arr6[i] = (parseFloat(filter_jsonData[i]['day6'])*100).toFixed(0);
			filter_Arr7[i] = (parseFloat(filter_jsonData[i]['day7'])*100).toFixed(0);
			filter_Arr14[i] = (parseFloat(filter_jsonData[i]['day14'])*100).toFixed(0);
			filter_Arr30[i] = (parseFloat(filter_jsonData[i]['day30'])*100).toFixed(0);
		}
		filter_jsonDatajsonArr.push(filter_dateArr.reverse(),filter_mainArr.reverse(),filter_Arr1.reverse(),filter_Arr2.reverse(),filter_Arr3.reverse(),filter_Arr4.reverse(),filter_Arr5.reverse(),filter_Arr6.reverse(),filter_Arr7.reverse(),filter_Arr14.reverse(),filter_Arr30.reverse());
		if (myChart){
			myChart = null;
		}
		var myChart = echarts.init(document.getElementById('container'));
		option.yAxis = [];
		option.xAxis[0]['data'] = filter_jsonDatajsonArr[0];
		option.series = [
			{
				name:"次日",
				smooth: true,
				type:'line',
				tooltip:{
					formatter:function(params,ticket,callback){
						var res = params[0].name;
						for (var i = 0, l = params.length; i < l; i++) {
							res += '<br / >' + params[i].seriesName + ' : ' + params[i].value + '%';
						}
						setTimeout(function (){
							callback(ticket, res);
						}, 10)
						return 'loading';
					}
				},
				data: filter_jsonDatajsonArr[2]
			},
			{
				name:"2日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[3]
			},
			{
				name:"3日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[4]
			},
			{
				name:"4日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[5]
			},
			{
				name:"5日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[6]
			},
			{
				name:"6日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[7]
			},
			{
				name:"7日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[8]
			},
			{
				name:"14日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[9]
			},
			{
				name:"30日",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[10]
			}
		]
		option.legend = {
			data:[
				"次日",
				"2日",
				"3日",
				"4日",
				"5日",
				"6日",
				"7日",
				"14日",
				"30日"
			]
		}
		option.yAxis = [
			{
				type : 'value',
				axisLabel: {
					  show: true,
					  interval: 'auto',
					  formatter: '{value} %'
					},
				splitLine: {
		            show: false
		        }
			}
		]
		myChart.setOption(option);
	}
	else if(type == 2){
		var filter_jsonData = $data;
		var filter_jsonDatajsonArr = new Array();//总数组
		var filter_dateArr = new Array();
		var filter_mainArr = new Array();//新增用户 | 设备留存 | 留存详情
		var filter_Arr1 = new Array();
		var filter_Arr2 = new Array();
		var filter_Arr3 = new Array();
		var filter_Arr4 = new Array();
		var filter_Arr5 = new Array();
		var filter_Arr6 = new Array();
		var filter_Arr7 = new Array();
		var filter_Arr8 = new Array();
		var filter_Arr9 = new Array();
		for(var i = 0,r = filter_jsonData.length;i < r;i++){
			filter_dateArr[i] = filter_jsonData[i]['startdate_sk'];
			filter_mainArr[i] = filter_jsonData[i]['count'];
			filter_Arr1[i] = (parseFloat(filter_jsonData[i]['week1'])*100).toFixed(0);
			filter_Arr2[i] = (parseFloat(filter_jsonData[i]['week2'])*100).toFixed(0);
			filter_Arr3[i] = (parseFloat(filter_jsonData[i]['week3'])*100).toFixed(0);
			filter_Arr4[i] = (parseFloat(filter_jsonData[i]['week4'])*100).toFixed(0);
			filter_Arr5[i] = (parseFloat(filter_jsonData[i]['week5'])*100).toFixed(0);
			filter_Arr6[i] = (parseFloat(filter_jsonData[i]['week6'])*100).toFixed(0);
			filter_Arr7[i] = (parseFloat(filter_jsonData[i]['week7'])*100).toFixed(0);
			filter_Arr8[i] = (parseFloat(filter_jsonData[i]['week14'])*100).toFixed(0);
			filter_Arr9[i] = (parseFloat(filter_jsonData[i]['week30'])*100).toFixed(0);
		}
		filter_jsonDatajsonArr.push(filter_dateArr.reverse(),filter_mainArr.reverse(),filter_Arr1.reverse(),filter_Arr2.reverse(),filter_Arr3.reverse(),filter_Arr4.reverse(),filter_Arr5.reverse(),filter_Arr6.reverse(),filter_Arr7.reverse(),filter_Arr8.reverse(),filter_Arr9.reverse());
		if (myChart){
			myChart = null;
		}
		var myChart = echarts.init(document.getElementById('container'));
		option.yAxis = [];
		option.xAxis[0]['data'] = filter_jsonDatajsonArr[0];
		option.series = [
			{
				name:"次周",
				smooth: true,
				type:'line',
				tooltip:{
					formatter:function(params,ticket,callback){
						var res = params[0].name;
						for (var i = 0, l = params.length; i < l; i++) {
							res += '<br / >' + params[i].seriesName + ' : ' + params[i].value + '%';
						}
						setTimeout(function (){
							callback(ticket, res);
						}, 10)
						return 'loading';
					}
				},
				data: filter_jsonDatajsonArr[2]
			},
			{
				name:"2周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[3]
			},
			{
				name:"3周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[4]
			},
			{
				name:"4周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[5]
			},
			{
				name:"5周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[6]
			},
			{
				name:"6周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[7]
			},
			{
				name:"7周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[8]
			},
			{
				name:"14周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[9]
			},
			{
				name:"30周",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[10]
			}
		]
		option.legend = {
			data:[
				"次周",
				"2周",
				"3周",
				"4周",
				"5周",
				"6周",
				"7周",
				"14周",
				"30周"
			]
		}
		option.yAxis = [
			{
				type : 'value',
				axisLabel: {
					show: true,
					interval: 'auto',
					formatter: '{value} %'
				},
				splitLine: {
		            show: false
		        }
			}
		]
		myChart.setOption(option);
	}
	else if(type == 3){
		var filter_jsonData = $data;
		console.log(filter_jsonData);
		var filter_jsonDatajsonArr = new Array();//总数组
		var filter_dateArr = new Array();
		var filter_mainArr = new Array();//新增用户 | 设备留存 | 留存详情
		var filter_Arr1 = new Array();
		var filter_Arr2 = new Array();
		var filter_Arr3 = new Array();
		var filter_Arr4 = new Array();
		var filter_Arr5 = new Array();
		var filter_Arr6 = new Array();
		var filter_Arr7 = new Array();
		var filter_Arr8 = new Array();
		var filter_Arr9 = new Array();
		for(var i = 0,r = filter_jsonData.length;i < r;i++){
			filter_dateArr[i] = filter_jsonData[i]['startdate_sk'];
			filter_mainArr[i] = filter_jsonData[i]['count'];
			filter_Arr1[i] = (parseFloat(filter_jsonData[i]['month1'])*100).toFixed(0);
			filter_Arr2[i] = (parseFloat(filter_jsonData[i]['month2'])*100).toFixed(0);
			filter_Arr3[i] = (parseFloat(filter_jsonData[i]['month3'])*100).toFixed(0);
			filter_Arr4[i] = (parseFloat(filter_jsonData[i]['month4'])*100).toFixed(0);
			filter_Arr5[i] = (parseFloat(filter_jsonData[i]['month5'])*100).toFixed(0);
			filter_Arr6[i] = (parseFloat(filter_jsonData[i]['month6'])*100).toFixed(0);
			filter_Arr7[i] = (parseFloat(filter_jsonData[i]['month7'])*100).toFixed(0);
			filter_Arr8[i] = (parseFloat(filter_jsonData[i]['month14'])*100).toFixed(0);
			filter_Arr9[i] = (parseFloat(filter_jsonData[i]['month30'])*100).toFixed(0);
		}
		filter_jsonDatajsonArr.push(filter_dateArr.reverse(),filter_mainArr.reverse(),filter_Arr1.reverse(),filter_Arr2.reverse(),filter_Arr3.reverse(),filter_Arr4.reverse(),filter_Arr5.reverse(),filter_Arr6.reverse(),filter_Arr7.reverse(),filter_Arr8.reverse(),filter_Arr9.reverse());
		if (myChart){
			myChart = null;
		}
		var myChart = echarts.init(document.getElementById('container'));
		option.yAxis = [];
		option.xAxis[0]['data'] = filter_jsonDatajsonArr[0];
		option.series = [
			{
				name:"次月",
				smooth: true,
				type:'line',
				tooltip:{
					formatter:function(params,ticket,callback){
						var res = params[0].name;
						for (var i = 0, l = params.length; i < l; i++) {
							res += '<br / >' + params[i].seriesName + ' : ' + params[i].value + '%';
						}
						setTimeout(function (){
							callback(ticket, res);
						}, 10)
						return 'loading';
					}
				},
				data: filter_jsonDatajsonArr[2]
			},
			{
				name:"2月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[3]
			},
			{
				name:"3月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[4]
			},
			{
				name:"4月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[5]
			},
			{
				name:"5月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[6]
			},
			{
				name:"6月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[7]
			},
			{
				name:"7月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[8]
			},
			{
				name:"14月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[9]
			},
			{
				name:"30月",
				smooth: true,
				type:'line',
				data: filter_jsonDatajsonArr[10]
			}
		]
		option.legend = {
			data:[
				"次月",
				"2月",
				"3月",
				"4月",
				"5月",
				"6月",
				"7月",
				"14月",
				"30月"
			]
		}
		option.yAxis = [
			{
				type : 'value',
				axisLabel: {
					show: true,
					interval: 'auto',
					formatter: '{value} %'
				},
				splitLine: {
		            show: false
		        }
			}
		]
		myChart.setOption(option);
	}
}
</script>