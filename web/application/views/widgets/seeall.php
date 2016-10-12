<style>
	#container {height: 240px; width: 100%;}
</style>
<div id="container"></div>
<script>
var type = '<?php echo $echarts?>';
$(document).ready(function(){
	// 基于准备好的dom，初始化echarts图表
	var jsonData = <?php echo json_encode($dauUsersData) ?>;
	var jsonArr = new Array();//总数组
	var dateArr = new Array();
	var daydauArr = new Array();
	var weekdauArr = new Array();
	var monthdauArr = new Array();
	var newuserArr = new Array();
	var notpayuserArr = new Array();
	var payuserArr = new Array();
	var useractiverateArr = new Array();

	for(var i = 0,r = jsonData.length;i < r;i++){
		dateArr[i] = jsonData[i]['date'];
		daydauArr[i] = jsonData[i]['daydau'];
		weekdauArr[i] = jsonData[i]['weekdau'];
		monthdauArr[i] = jsonData[i]['monthdau'];
		newuserArr[i] = jsonData[i]['newuser'];
		payuserArr[i] = jsonData[i]['payuser'];
		notpayuserArr[i] = jsonData[i]['notpayuser'];
		useractiverateArr[i] = (parseFloat(jsonData[i]['useractiverate'])*100).toFixed(0);
	}
	jsonArr.push(dateArr,daydauArr,weekdauArr,monthdauArr,newuserArr,payuserArr,notpayuserArr,useractiverateArr);

	console.log(jsonArr);
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
	option = {
		tooltip : {
			trigger: 'axis'
		},
		calculable : true,
		xAxis : [
			{
				type : 'category',
				boundaryGap : false,
				data : []
			}
		],
		yAxis : [
			{
				type : 'value'
			}
		]
	};
	// 为echarts对象加载数据 
	myChart.hideLoading();
	switch(type){
		case 'dau':
			chartsName = 'dau';
			default_data(1);
			break;
		case 'd_w_mau':
			chartsName = 'd_w_mau';
			default_data(2);
			break;
		case 'dau_mau':
			chartsName = 'dau_mau';
			default_data(3);
			break;
	}
	function default_data(num){
		option.yAxis = [];
		option.xAxis[0]['data'] = jsonArr[0];
		if(num == 1){
			option.series = [
				{
					name:"<?php echo  lang('t_xinzengyonghu_yao')?>",
					type:'line',
					stack: '总量',
					data: jsonArr[4]
				},
				{
					name:"<?php echo  lang('t_payUser_yao')?>",
					type:'line',
					stack: '总量',
					data: jsonArr[5]
				},
				{
					name:"<?php echo  lang('t_notPayUser_yao')?>",
					type:'line',
					stack: '总量',
					data: jsonArr[6]
				},
				{
					name:"DAU",
					type:'line',
					stack: '总量',
					data: jsonArr[1]
				}
			]
			option.legend = {
				data:[
					"<?php echo  lang('t_xinzengyonghu_yao')?>",
					"<?php echo  lang('t_payUser_yao')?>",
					"<?php echo  lang('t_notPayUser_yao')?>",
					"DAU"
				]
			}
		}
		else if(num == 2){
			option.series = [
				{
					name:"DAU",
					type:'line',
					stack: '总量',
					data: jsonArr[1]
				},
				{
					name:"WAU",
					type:'line',
					stack: '总量',
					data: jsonArr[2]
				},
				{
					name:"MAU",
					type:'line',
					stack: '总量',
					data: jsonArr[3]
				}
			]
			option.legend = {
				data:[
					"DAU",
					"WAU",
					"MAU"
				]
			}
		}
		else if(num == 3){
			option.series = [
				{
					name:"DAU/MAU",
					type:'line',
					stack: '总量',
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
					data: jsonArr[7]
				}
			]
			option.legend = {
				data:[
					"DAU/MAU"
				]
			}
			option.yAxis = [
				{
					type : 'value',
					axisLabel: {
						  show: true,
						  interval: 'auto',
						  formatter: '{value} %'
						}
				}
			]
		}
		myChart.setOption(option);
	}
});
</script>