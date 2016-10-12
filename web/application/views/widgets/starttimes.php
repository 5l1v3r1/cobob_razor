<style>
	#container {height: 260px; width: 100%; background: #fff!important;}
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
var json = <?php echo json_encode($result) ?>;
var startdate_sk = new Array();
var starttimes = new Array();
var logintimes = new Array();
for(var i = 0,r = json.length;i < r;i++){
	startdate_sk.push(json[i]['startdate_sk']);
	starttimes.push(json[i]['starttimes']);
	logintimes.push(json[i]['logintimes']);
}
all_arr = new Array(startdate_sk.reverse(),starttimes.reverse(),logintimes.reverse());
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	option = {
	    tooltip : {
	        trigger: 'axis'
	    },
	    calculable : true,
	    legend: {
	        data:['启动次数','登录次数']
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
	        }
	    ],
	    series : [
	        {
	            name:'启动次数',
	            smooth: true,
	            type:'line',
	            data:all_arr[1]
	        },
	         {
	            name:'登录次数',
	            smooth: true,
	            type:'line',
	            data:all_arr[2]
	        }
	    ]
	};
	myChart.setOption(option);
},500);
</script>