<style>
	.column {height: 500px; overflow: auto;}
	table td {background: #eee;}
	#user-info-detail {padding: 10px; color: #fff; background: #333; height: 25px; line-height: 25px; text-align: center;}
	#user-info-detail span {margin-right: 20px;}
	#container {height: 260px; width: 100%; background: #fff!important;}
</style>
<div id="container"></div>
<script>
var type = "<?php echo $charts ?>";
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
	if(type == 'all_user'){
		var json = <?php echo json_encode($alluser) ?>;
		var date_sk = new Array();
		var fourteenleaverate = new Array();
		var fourteenreturnrate = new Array();
		var sevenleaverate = new Array();
		var sevenreturnrate = new Array();
		var thirtyleaverate = new Array();
		var thirtyreturnrate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			date_sk.push(json[i]['date_sk']);
			fourteenleaverate.push(parseFloat(json[i]['fourteenleaverate'])*100);
			fourteenreturnrate.push(parseFloat(json[i]['fourteenreturnrate'])*100);
			sevenleaverate.push(parseFloat(json[i]['sevenleaverate'])*100);
			sevenreturnrate.push(parseFloat(json[i]['sevenreturnrate'])*100);
			thirtyleaverate.push(parseFloat(json[i]['thirtyleaverate'])*100);
			thirtyreturnrate.push(parseFloat(json[i]['thirtyreturnrate'])*100);
		}
		all_arr = new Array(date_sk.reverse(),fourteenleaverate.reverse(),fourteenreturnrate.reverse(),sevenleaverate.reverse(),sevenreturnrate.reverse(),thirtyleaverate.reverse(),thirtyreturnrate.reverse());
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
			calculable : true,
			legend: {
				data:['7日流失率','14日流失率','30日流失率','7日回流率','14日回流率','30日回流率']
			},
			dataZoom: [
				{
					show: true,
					start: 0,
					end: 25,
					handleSize: 8
				}
			],
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
						formatter: '{value} %'
					},
					splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:'7日流失率',
					smooth: true,
					type:'line',
					data:all_arr[1]
				},
				{
					name:'14日流失率',
					smooth: true,
					type:'line',
					data:all_arr[3]
				},
				{
					name:'30日流失率',
					smooth: true,
					type:'line',
					data:all_arr[5]
				},
				{
					name:'7日回流率',
					smooth: true,
					type:'line',
					data:all_arr[2]
				},
				{
					name:'14日回流率',
					smooth: true,
					type:'line',
					data:all_arr[4]
				},
				{
					name:'30日回流率',
					smooth: true,
					type:'line',
					data:all_arr[6]
				}
			]
		};
	}
	else{
		var json = <?php echo json_encode($payuser) ?>;
		var date_sk = new Array();
		var fourteenleaverate = new Array();
		var fourteenreturnrate = new Array();
		var sevenleaverate = new Array();
		var sevenreturnrate = new Array();
		var thirtyleaverate = new Array();
		var thirtyreturnrate = new Array();
		for(var i = 0,r = json.length;i < r;i++){
			date_sk.push(json[i]['date_sk']);
			fourteenleaverate.push(parseFloat(json[i]['fourteenleaverate'])*100);
			fourteenreturnrate.push(parseFloat(json[i]['fourteenreturnrate'])*100);
			sevenleaverate.push(parseFloat(json[i]['sevenleaverate'])*100);
			sevenreturnrate.push(parseFloat(json[i]['sevenreturnrate'])*100);
			thirtyleaverate.push(parseFloat(json[i]['thirtyleaverate'])*100);
			thirtyreturnrate.push(parseFloat(json[i]['thirtyreturnrate'])*100);
		}
		all_arr = new Array(date_sk.reverse(),fourteenleaverate.reverse(),fourteenreturnrate.reverse(),sevenleaverate.reverse(),sevenreturnrate.reverse(),thirtyleaverate.reverse(),thirtyreturnrate.reverse());
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
			calculable : true,
			legend: {
				data:['7日流失率','14日流失率','30日流失率','7日回流率','14日回流率','30日回流率']
			},
			dataZoom: [
				{
					show: true,
					start: 0,
					end: 25,
					handleSize: 8
				}
			],
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
						formatter: '{value} %'
					},
					splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:'7日流失率',
					smooth: true,
					type:'line',
					data:all_arr[1]
				},
				{
					name:'14日流失率',
					smooth: true,
					type:'line',
					data:all_arr[3]
				},
				{
					name:'30日流失率',
					smooth: true,
					type:'line',
					data:all_arr[5]
				},
				{
					name:'7日回流率',
					smooth: true,
					type:'line',
					data:all_arr[2]
				},
				{
					name:'14日回流率',
					smooth: true,
					type:'line',
					data:all_arr[4]
				},
				{
					name:'30日回流率',
					smooth: true,
					type:'line',
					data:all_arr[6]
				}
			]
		};
	}
	myChart.setOption(option);
},500);
</script>