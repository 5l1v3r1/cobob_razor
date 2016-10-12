<style>
	#container {height: 240px; width: 100%;}
</style>
<div id="container"></div>
<script>
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($result) ?>;
	if (myChart){
		myChart = null;
	}
	$data = new Array(jsonData[0],jsonData[1],jsonData[2],jsonData[3],jsonData[4],jsonData[5],jsonData[6],jsonData[7],jsonData[8]);
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
			legend: {
				data:['AVG']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : ['次日','2日','3日','4日','5日','6日','7日','14日','30日'],
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
					name:'AVG',
					type:'line',
					smooth:true,
					data:$data,
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
				}
			]
		};
		myChart.setOption(option);
		myChart.hideLoading();
	},500);
});
</script>