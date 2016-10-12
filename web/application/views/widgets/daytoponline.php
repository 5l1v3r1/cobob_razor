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
var json = <?php echo json_encode($result) ?>;
var date_arr = new Array();
var acu = new Array();
var pcu = new Array();
var ccu = new Array();
for(var i = 0;i < json.length;i++){
	date_arr.push(json[i]['startdate_sk']);
	acu.push(json[i]['acu']);
	pcu.push(json[i]['pcu']);
	ccu.push((parseFloat(json[i]['ccu'])).toFixed(2));
}
all_arr = new Array(date_arr.reverse(),acu.reverse(),pcu.reverse(),ccu.reverse());
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	option = {
		tooltip : {
			trigger: 'item'
		},
		legend: {
			data:['acu','pcu','ccu比率'],
			itemGap: 5
		},
		xAxis : [
			{
				type : 'category',
				data : all_arr[0],
				splitLine: {
		            show: false
		        }
			}
		],
		yAxis : [
			{
				type : 'value',
				axisLabel : {
					formatter: '{value}'
				},
				splitLine: {
		            show: false
		        }
			},
			{
				type : 'value',
				name : 'ccu比率',
				axisLabel : {
					formatter: '{value}'
				},
				splitLine: {
		            show: false
		        }
			}
		],
		dataZoom: [
			{
				type: 'slider',
				show: true,
				start: 0,
				end: 25
			}
		],
		series : [
			{
				name:'acu',
				type:'bar',
				data:all_arr[1]
			},
			{
				name:'pcu',
				type:'bar',
				data:all_arr[2]
			},
			{
				name:'ccu比率',
				type:'line',
				yAxisIndex: 1,
				data:all_arr[3],
				tooltip : {
	                formatter: "ccu比率:{c}"
	            }
			}
		]
	};
	myChart.setOption(option);
},500);
</script>