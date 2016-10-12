<style>
	#container {height: 300px; width: 100%;}
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
	var jsonData = <?php echo json_encode($errorcodeanaly) ?>;
	console.log(jsonData);
	var jsonArr = new Array();//总数组
	var errorname = new Array();//行为名
	var useravgcount = new Array();//人均次数
	var erroruser = new Array();//人数
	var errorcount = new Array();//次数
	for(var i = 0,r = jsonData.length;i < r;i++){
		errorname[i] = jsonData[i]['errorname'];
		useravgcount[i] = jsonData[i]['useravgcount'];
		erroruser[i] = jsonData[i]['erroruser'];
		errorcount[i] = jsonData[i]['errorcount'];
	}
	jsonArr.push(errorname,useravgcount,erroruser,errorcount);
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
		legend: {
			data:['人均次数','人数','次数']
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
				name : '人数次数',
				axisLabel : {
					formatter: '{value} 人'
				},
				splitLine: {
		            show: false
		        }
			},
			{
				type : 'value',
				name : '人均次数',
				axisLabel : {
					formatter: '{value} 人'
				},
				splitLine: {
		            show: false
		        }
			}
		],
		series : [
			{
				name:'人均次数',
				type:'bar',
				data:jsonArr[1]
			},
			{
				name:'人数',
				type:'line',
				data:jsonArr[2]
			},
			{
				name:'次数',
				type:'line',
				yAxisIndex: 1,
				data:jsonArr[3]
			}
		]
	};
	myChart.setOption(option);
},500);
</script>