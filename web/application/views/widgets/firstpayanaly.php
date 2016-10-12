<style>
	#container {height: 360px; width: 100%;}
</style>
<div id="container"></div>
<script>
var type = '<?php echo $echarts?>';
$(document).ready(function(){
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
	// 为echarts对象加载数据 
	if(type == 'time'){
		// 基于准备好的dom，初始化echarts图表
		var jsonData = <?php echo json_encode($firstpayTime) ?>;
		var jsonArr = new Array();//总数组
		var firstpaynameArr = new Array();
		var payusersArr = new Array();
		var payusersrateArr = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			firstpaynameArr[i] = jsonData[i]['firstpayname'];
			payusersArr[i] = jsonData[i]['payusers'];
			payusersrateArr[i] = (parseFloat(jsonData[i]['payusersrate'])*100);
		}
		jsonArr.push(firstpaynameArr,payusersArr,payusersrateArr);
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:["<?php echo  lang('t_payUserNum_yao')?>"]
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
					name:"<?php echo  lang('t_payUserNum_yao')?>",
					type:'bar',
					data:jsonArr[1],
					splitLine: {
			            show: false
			        }
				}
			]
		};
	}
	else if(type == 'level'){
		// 基于准备好的dom，初始化echarts图表
		var jsonData = <?php echo json_encode($firstpayLevel) ?>;
		var jsonArr = new Array();//总数组
		var firstpaylevel = new Array();
		var payusers = new Array();
		var payusersrate = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			firstpaylevel[i] = jsonData[i]['firstpaylevel'];
			payusers[i] = jsonData[i]['payusers'];
			payusersrate[i] = (parseFloat(jsonData[i]['payusersrate'])*100);
		}
		jsonArr.push(firstpaylevel,payusers,payusersrate);
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:["<?php echo  lang('t_payUserNum_yao')?>"]
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
					name:"<?php echo  lang('t_payUserNum_yao')?>",
					type:'bar',
					data:jsonArr[1]
				}
			]
		};
	}
	else if(type == 'amount'){
		var jsonData = <?php echo json_encode($firstpayAmount) ?>;
		console.log(jsonData);
		var jsonArr = new Array();//总数组
		var firstpayamount = new Array();
		var payusers = new Array();
		var payusersrate = new Array();
		for(var i = 0,r = jsonData.length;i < r;i++){
			firstpayamount[i] = jsonData[i]['firstpayamount'];
			payusers[i] = jsonData[i]['payusers'];
			payusersrate[i] = (parseFloat(jsonData[i]['payusersrate'])*100);
		}
		jsonArr.push(firstpayamount,payusers,payusersrate);
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:["<?php echo  lang('t_payUserNum_yao')?>"]
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
					name:"<?php echo  lang('t_payUserNum_yao')?>",
					type:'bar',
					data:jsonArr[1]
				}
			]
		};
	}
	myChart.setOption(option);
	myChart.hideLoading();
});
</script>