<style>
	#container {height: 300px; width: 100%;}
</style>
<div id="container"></div>
<script>
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($result) ?>;
	if (myChart){
		myChart = null;
	}
	$data = jsonData.split(',')
	console.log($data);
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
				trigger: 'axis'
			},
			legend: {
				data:['AVG']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : ['1日','2日','3日','4日','5日','6日','7日','14日','30日','60日','90日'],
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
					name:'AVG',
					type:'line',
					smooth:true,
					data:$data
				}
			]
		};
		myChart.setOption(option);
		myChart.hideLoading();
	},500);
});
</script>