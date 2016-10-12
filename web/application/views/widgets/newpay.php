<style>
	#container {height: 240px; width: 100%;}
</style>
<div id="container"></div>
<script>
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($result) ?>;
	var jsonArr = new Array();//总数组
	var date = new Array();
	var firstdaypaycount = new Array();
	var firstdaypayuser = new Array();
	var firstweekpaycount = new Array();
	var firstweekpayuser = new Array();
	var firstmonthpaycount = new Array();
	var firstmonthpayuser = new Array();

	for(var i = 0,r = jsonData.length;i < r;i++){
		date[i] = jsonData[i]['date'];
		firstdaypaycount[i] = (parseFloat(jsonData[i]['firstdaypaycount'])*100).toFixed(0);
		firstdaypayuser[i] = jsonData[i]['firstdaypayuser'];
		firstweekpaycount[i] = (parseFloat(jsonData[i]['firstweekpaycount'])*100).toFixed(0);
		firstweekpayuser[i] = jsonData[i]['firstweekpayuser'];
		firstmonthpaycount[i] = (parseFloat(jsonData[i]['firstmonthpaycount'])*100).toFixed(0);
		firstmonthpayuser[i] = jsonData[i]['firstmonthpayuser'];
	}
	jsonArr.push(date.reverse(),firstdaypaycount.reverse(),firstdaypayuser.reverse(),firstweekpaycount.reverse(),firstweekpayuser.reverse(),firstmonthpaycount.reverse(),firstmonthpayuser.reverse());
	console.log(jsonArr);
	if (myChart){
		myChart = null;
	}
	var myChart = echarts.init(document.getElementById('container'));
	var option = {};
	myChart.showLoading({
		animation:false,
		text : '数据加载中 ...',
		textStyle : {fontSize : 20},
		effect : 'whirling'
	});
	// 为echarts对象加载数据 
	clearTimeout(loadingTicket);
	var loadingTicket = setTimeout(function (){
		option = {
			tooltip : {
				trigger: 'axis',
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
			legend: {
				data:['首日付费率','首周付费率','首月付费率']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0],
					splitLine: {
			            show: false
			        }
				}
			],
			yAxis : [
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
			],
			series : [
				{
					name:'首日付费率',
					type:'line',
					smooth:true,
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:jsonArr[1],
				},
				{
					name:'首周付费率',
					type:'line',
					smooth:true,
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:jsonArr[3]
				},
				{
					name:'首月付费率',
					type:'line',
					smooth:true,
					itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:jsonArr[5]
				}
			]
		};
		myChart.setOption(option);
		myChart.hideLoading();
	},500);
});
</script>