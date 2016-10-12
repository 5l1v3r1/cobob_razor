<style>
	#container {height: 400px; width: 100%;}
</style>
<section class="column">
	<div id="container"></div>
</section>
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
var logininterval = new Array();
var rolecount = new Array();
for(var i = 0,r = json.length;i < r;i++){
	logininterval.push(json[i]['logininterval']);
	rolecount.push(json[i]['rolecount']);
}
all_arr = new Array(logininterval,rolecount);
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	option = {
	    tooltip : {
	        trigger: 'axis'
	    },
	    legend: {
	        data:['人数']
	    },
	    xAxis : [
	        {
	            type : 'value',
	            boundaryGap : [0, 0.01]
	        }
	    ],
	    yAxis : [
	        {
	            type : 'category',
	            data : all_arr[0]
	        }
	    ],
	    series : [
	        {
	            name:'人数',
	            type:'bar',
	            data:all_arr[1]
	        }
	    ]
	};
	myChart.setOption(option);
},500);
</script>