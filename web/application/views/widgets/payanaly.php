<style>
	#container {height: 320px; width: 100%;}
	#arpu_day {float: left; width: 50%; height: 100%;}
	#arpu_month {float: left; width: 50%; height: 100%;}
</style>
<div id="container"></div>
<div id="arpu_arppu">
	<div id="arpu_day"></div>
	<div id="arpu_month"></div>
</div>
<script>
var type = '<?php echo $echarts?>';
var date = '<?php echo $date?>';
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
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
	myChart.hideLoading();
	if(type == 'paymoney'){
		var jsonData = <?php echo json_encode($paymoney) ?>;
		var jsonArr = new Array();//总数组
		var payfield = new Array();
		var payusers = new Array();
		var $text = '';
		if(date == 'day'){
			$text = '日付费人数';
		}
		else if(date == 'week'){
			$text = '周付费人数';
		}
		else if(date == 'month'){
			$text = '月付费人数';
		}
		for(var i = 0,r = jsonData.length;i < r;i++){
			payfield[i] = jsonData[i]['payfield'];
			payusers[i] = jsonData[i]['payusers'];
		}
		jsonArr.push(payfield,payusers);
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:[$text]
			},
			calculable : true,
			xAxis : [
				{
					type : 'value',
					boundaryGap : [0, 0.01],
					splitLine: {
			            show: false
			        }
				}
			],
			yAxis : [
				{
					type : 'category',
					data : jsonArr[0],
					splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:$text,
					type:'bar',
					data:jsonArr[1]
				}
			]
		};
		myChart.setOption(option);
	}
	else if(type == 'paycount'){
		var jsonData = <?php echo json_encode($paycount) ?>;
		var jsonArr = new Array();//总数组
		var paycount = new Array();
		var payusers = new Array();
		var $text = '';
		if(date == 'day'){
			$text = '日付费人数';
		}
		else if(date == 'week'){
			$text = '周付费人数';
		}
		else if(date == 'month'){
			$text = '月付费人数';
		}
		for(var i = 0,r = jsonData.length;i < r;i++){
			paycount[i] = jsonData[i]['paycount'];
			payusers[i] = jsonData[i]['payusers'];
		}
		jsonArr.push(paycount,payusers);
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:[$text]
			},
			calculable : true,
			xAxis : [
				{
					type : 'value',
					boundaryGap : [0, 0.01],
					splitLine: {
			            show: false
			        }
				}
			],
			yAxis : [
				{
					type : 'category',
					data : jsonArr[0],
					splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:$text,
					type:'bar',
					data:jsonArr[1]
				}
			]
		};
		myChart.setOption(option);
	}
	else if(type == 'arpu_arppu'){
		$('#arpu_arppu > div').css('height','340px');
		$('#container').remove();
		//arpu day
		var jsonData = <?php echo json_encode($payARPUday) ?>;
		var jsonArr = new Array();//总数组
		var startdate_sk = new Array();
		var dayarpu = new Array();
		var dayarppu = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			startdate_sk[i] = jsonData[i]['startdate_sk'];
			dayarpu[i] = (parseFloat(jsonData[i]['dayarpu'])*100).toFixed(2);
			dayarppu[i] = (parseFloat(jsonData[i]['dayarppu'])*100).toFixed(2);
		}
		jsonArr.push(startdate_sk.reverse(),dayarpu.reverse(),dayarppu.reverse());
		var myChart = echarts.init(document.getElementById('arpu_day'));
		var option = {}
		myChart.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		myChart.hideLoading();
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
				data:[
					"<?php echo  lang('t_riARPU_yao')?>",
					"<?php echo  lang('t_riARPPU_yao')?>"
				]
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
			            formatter: '{value} %'
			        },
			        splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:"<?php echo  lang('t_riARPU_yao')?>",
					type:'line',
					smooth:true,
            		itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:jsonArr[1]
				},
				{
					name:"<?php echo  lang('t_riARPPU_yao')?>",
					type:'line',
					smooth:true,
            		itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:jsonArr[2]
				}
			]
		};
		myChart.setOption(option);
		//arpu month
		var $jsonData = <?php echo json_encode($PayARPUmonth) ?>;
		console.log($jsonData);
		var $jsonArr = new Array();//总数组
		var $startdate_sk = new Array();
		var montharpu = new Array();
		var montharppu = new Array();
		for(var i = 0,r = $jsonData.length;i < r;i++){
			$startdate_sk[i] = $jsonData[i]['startdate_sk'];
			montharpu[i] = (parseFloat($jsonData[i]['montharpu'])*100).toFixed(2);
			montharppu[i] = (parseFloat($jsonData[i]['montharppu'])*100).toFixed(2);
		}
		$jsonArr.push($startdate_sk.reverse(),montharpu.reverse(),montharppu.reverse());
		var $myChart = echarts.init(document.getElementById('arpu_month'));
		var $option = {}
		$myChart.showLoading({
			animation:false,
			text : '数据加载中 ...',
			textStyle : {fontSize : 20},
			effect : 'whirling'
		});
		$myChart.hideLoading();
		$option = {
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
				data:[
					"<?php echo  lang('t_yueARPU_yao')?>",
					"<?php echo  lang('t_yueARPPU_yao')?>"
				]
			},
			calculable : true,
			xAxis : [
				{
					type : 'category',
            		boundaryGap : false,
					data : $jsonArr[0],
					splitLine: {
			            show: false
			        }
				}
			],
			yAxis : [
				{
					type : 'value',
					axisLabel: {
			            formatter: '{value} %'
			        },
			        splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:"<?php echo  lang('t_yueARPU_yao')?>",
					type:'line',
					smooth:true,
            		itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:$jsonArr[1]
				},
				{
					name:"<?php echo  lang('t_yueARPPU_yao')?>",
					type:'line',
					smooth:true,
            		itemStyle: {normal: {areaStyle: {type: 'default'}}},
					data:$jsonArr[2]
				}
			]
		};
		$myChart.setOption($option);
	}
});
</script>