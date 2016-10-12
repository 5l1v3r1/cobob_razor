<style>
	#container {height: 300px; width: 100%;}
</style>
<div id="container"></div>
<?php var_dump($name); ?>
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
var json = <?php echo json_encode($result); ?>;
var name = <?php echo json_encode($name); ?>;
$name = name.split(',');
var step = new Array();
for(var i=1;i<$name.length;i++){
	step.push($name[i]);
}

var date = new Array();
for(var i=0;i<json.length;i++){
	date.push(json[i][0]);
}
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	option = {
		tooltip: {
			trigger: 'axis',
			formatter: function (params,ticket,callback) {
	            var res = params[0].name;
	            console.log(params)
	            for (var i = 0, l = params.length; i < l; i++) {
	            	var v;
	            	if(params[i].value == 'NaN'){
	            		v = 0;
	            	}
	            	else{
	            		v = params[i].value;
	            	}
	                res += '<br/>' + params[i].seriesName + ' : ' + v + '%';
	            }
	            setTimeout(function (){
	                callback(ticket, res);
	            }, 100)
	            return 'loading';
	        }
		},
		legend: {
			data:date
		},
		xAxis: {
			type: 'category',
			boundaryGap: false,
			data: step,
			splitLine: {
	            show: false
	        }
		},
		yAxis: {
			type: 'value',
			axisLabel: {
	            formatter: '{value} %'
	        },
	        splitLine: {
	            show: false
	        }
		},
		series: []
	};
	for(var i=0;i<json.length;i++){
		var handel = json[i][1];
		var base = json[i][1][0];
		$result = new Array();
		for(var r=1;r<handel.length;r++){
			var v = (handel[r]/base*100).toFixed(2);
			if(!isFinite(v)){
				v = 0;
			}
			$result.push(v);
		}
		option.series[i] = {
			name:json[i][0],
			type:'line',
			smooth: true,
			data:$result
		}
	}
	myChart.setOption(option);
},500);
</script>