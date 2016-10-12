<style>
	#container {height: 260px; width: 100%;}
</style>
<div id="container"></div>
<script>
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
	var jsonData = <?php echo json_encode($result) ?>;
	var jsonArr = new Array();//总数组
	var startdate_sk = new Array();
	var totalcapacity = new Array();//服务器总容量
	var userate = new Array();//总容量使用率
	var totalpcu = new Array();//总pcu
	for(var i = 0,r = jsonData.length;i < r;i++){
		startdate_sk[i] = jsonData[i]['startdate_sk'];
		totalcapacity[i] = jsonData[i]['totalcapacity'];
		totalpcu[i] = jsonData[i]['totalpcu'];
		userate[i] = (parseFloat(jsonData[i]['userate'])*100).toFixed(0);
	}
	jsonArr.push(startdate_sk,totalpcu,totalcapacity,userate);
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
		legend: {
			data:['总PCU','服务器总容量','在线容量比']
		},
		xAxis : [
			{
				type : 'category',
				data : jsonArr[0],
				splitLine: {
		            show: false
		        }
			}
		],
		yAxis : [
			{
				type : 'value',
				name : '人数',
				axisLabel : {
					formatter: '{value} 人'
				},
				splitLine: {
		            show: false
		        }
			},
			{
				type : 'value',
				name : '百分比',
				axisLabel : {
					formatter: '{value} %'
				},
				splitLine: {
		            show: false
		        }
			}
		],
		series : [

			{
				name:'总PCU',
				type:'bar',
				data:jsonArr[1]
			},
			{
				name:'服务器总容量',
				type:'bar',
				data:jsonArr[2]
			},
			{
				name:'在线容量比',
				type:'line',
				yAxisIndex: 1,
				data:jsonArr[3],
				tooltip : {
	                trigger: 'item',
	                formatter: "{a}:{c} %"
	            }
			}
		]
	};
	myChart.setOption(option);
},500);
</script>