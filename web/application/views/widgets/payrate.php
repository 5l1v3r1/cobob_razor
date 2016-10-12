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
var type = '<?php echo $echarts?>';
clearTimeout(loadingTicket);
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	if(type == 'day'){
		var jsonData = <?php echo json_encode($daydata) ?>;
		var jsonArr = new Array();//总数组
		var date = new Array();
		var daypayrate = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			date[i] = jsonData[i]['date'];
			daypayrate[i] = (parseFloat(jsonData[i]['daypayrate'])*100).toFixed(0);
		}
		jsonArr.push(date.reverse(),daypayrate.reverse());
		option = {
			tooltip : {
				trigger: 'axis',
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
			legend: {
				data:['日付费率']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0],
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
					name:'日付费率',
					type:'line',
					stack: '总量',
					data:jsonArr[1]
				}
			]
		};
	}
	else if(type == 'week'){
		var jsonData = <?php echo json_encode($weekdata) ?>;
		var jsonArr = new Array();//总数组
		var date = new Array();
		var weekpayrate = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			date[i] = jsonData[i]['startdate_sk'];
			weekpayrate[i] = (parseFloat(jsonData[i]['weekpayrate'])*100).toFixed(0);
		}
		jsonArr.push(date.reverse(),weekpayrate.reverse());
		console.log(jsonArr);
		option = {
			tooltip : {
				trigger: 'axis',
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
			legend: {
				data:['周付费率']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0],
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
					name:'周付费率',
					type:'line',
					stack: '总量',
					data:jsonArr[1]
				}
			]
		};
	}
	else if(type == 'month'){
		var jsonData = <?php echo json_encode($monthdata) ?>;
		var jsonArr = new Array();//总数组
		var date = new Array();
		var monthpayrate = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			date[i] = jsonData[i]['startdate_sk'];
			monthpayrate[i] = (parseFloat(jsonData[i]['monthpayrate'])*100).toFixed(0);
		}
		jsonArr.push(date.reverse(),monthpayrate.reverse());
		console.log(jsonArr);
		option = {
			tooltip : {
				trigger: 'axis',
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
			legend: {
				data:['月付费率']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0],
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
					name:'月付费率',
					type:'line',
					stack: '总量',
					data:jsonArr[1]
				}
			]
		};
	}
	else if(type == 'total'){
		var jsonData = <?php echo json_encode($daydata) ?>;
		var jsonArr = new Array();//总数组
		var date = new Array();
		var totalpayrate = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			date[i] = jsonData[i]['date'];
			totalpayrate[i] = (parseFloat(jsonData[i]['totalpayrate'])*100).toFixed(0);
		}
		jsonArr.push(date.reverse(),totalpayrate.reverse());
		option = {
			tooltip : {
				trigger: 'axis',
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
			legend: {
				data:['累计付费率']
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : jsonArr[0],
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
					name:'累计付费率',
					type:'line',
					stack: '总量',
					data:jsonArr[1]
				}
			]
		};
	}
	myChart.setOption(option);
},500);
</script>