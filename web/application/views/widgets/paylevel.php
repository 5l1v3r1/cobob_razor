<style>
	#container {height: 260px; width: 100%;}
</style>
<div id="container"></div>
<script>
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
	var jsonData = <?php echo json_encode($result) ?>;
	var jsonArr = new Array();//总数组
	var rolelevel = new Array();
	var payamount = new Array();//付费金额
	var paycount = new Array();//付费次数
	for(var i = 0,r = jsonData.length;i < r;i++){
		rolelevel[i] = jsonData[i]['rolelevel'];
		payamount[i] = jsonData[i]['payamount'];
		paycount[i] = jsonData[i]['paycount'];
	}
	jsonArr.push(rolelevel,payamount,paycount);
	console.log(jsonArr);
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
		legend: {
			data:['付费金额','付费次数']
		},
		xAxis : [
			{
				type : 'category',
				data : jsonArr[0],
				splitLine : {show : false}
			}
		],
		yAxis : [
			{
				type : 'value',
				name : '金额',
				axisLabel : {
					formatter: '{value}'
				},
				splitLine: {
		            show: false
		        }
			},
			{
				type : 'value',
				name : '次数',
				axisLabel : {
					formatter: '{value}'
				},
				splitLine : {show : false}
			}
		],
		series : [

			{
				name:'付费金额',
				type:'bar',
				data:jsonArr[1]
			},
			{
				name:'付费次数',
				type:'line',
				yAxisIndex: 1,
				data:jsonArr[2]
			}
		]
	};
	myChart.setOption(option);
},500);
</script>