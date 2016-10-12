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
	var levelleaverate = new Array();//等级流失率
	var avgupdatetime = new Array();//平均升级时长
	console.log(jsonData);
	for(var i = 0,r = jsonData.length;i < r;i++){
		rolelevel[i] = jsonData[i]['rolelevel'];
		levelleaverate[i] = (parseFloat(jsonData[i]['levelleaverate'])*100).toFixed(0);
		avgupdatetime[i] = jsonData[i]['avgupdatetime'];
	}
	jsonArr.push(rolelevel,levelleaverate,avgupdatetime);
	console.log(jsonArr);
	option = {
	    tooltip : {
	        trigger: 'axis'
	    },
	    calculable : true,
	    legend: {
	        data:['等级流失率','平均升级时长']
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
	            splitLine: {
		            show: false
		        }
	        },
	        {
	            type : 'value',
	            name : '',
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
	            name:'等级流失率',
	            smooth: true,
	            type:'line',
	            data:jsonArr[1],
	            yAxisIndex: 1,
	            tooltip : {
	                trigger: 'item',
	                formatter: "{a}:{c} %"
	            }
	        },
	        {
	            name:'平均升级时长',
	            smooth: true,
	            type:'bar',
	            data:jsonArr[2]
	        }
	    ]
	};
	myChart.setOption(option);
},500);
</script>