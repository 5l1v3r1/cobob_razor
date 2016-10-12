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
		useractiverateArr[i] = jsonData[i]['useractiverate'];
	}
	jsonArr.push(dateArr.reverse(),daydauArr.reverse(),weekdauArr.reverse(),monthdauArr.reverse(),newuserArr.reverse(),payuserArr.reverse(),notpayuserArr.reverse(),useractiverateArr.reverse());
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
		dataZoom: [
            {
                type: 'slider',
                show: true,
                start: 0,
                end: 25,
                handleSize: 8
            },
			{
		        type: 'inside',
		        start: 94,
		        end: 100
		    }
        ],
        grid: {
            top: '12%',
            left: '5%',
            right: '5%',
            containLabel: true
        },
		xAxis : [
			{
				type : 'category',
				boundaryGap : false,
				data : [],
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
		option.yAxis = [
			{
				type : 'value',
				axisLabel: {
					show: true,
					interval: 'auto',
					formatter: '{value}'
				},
				splitLine: {
		            show: false
		        }
			}
		];
		option.xAxis[0]['data'] = jsonArr[0];
		if(num == 1){
			option.series = [
				{
					name:"<?php echo  lang('m_xinZengYongHu_yao')?>",
					smooth: true,
					type:'line',
					data: jsonArr[4]
				},
				{
					name:"<?php echo  lang('t_payUser_yao')?>",
					smooth: true,
					type:'line',
					data: jsonArr[5]
				},
				{
					name:"<?php echo  lang('t_notPayUser_yao')?>",
					smooth: true,
					type:'line',
					data: jsonArr[6]
				},
				{
					name:"DAU",
					smooth: true,
					type:'line',
					data: jsonArr[1]
				}
			]
			option.legend = {
				data:[
					"<?php echo  lang('m_xinZengYongHu_yao')?>",
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
					smooth: true,
					type:'line',
					data: jsonArr[1]
				},
				{
					name:"WAU",
					smooth: true,
					type:'line',
					data: jsonArr[2]
				},
				{
					name:"MAU",
					smooth: true,
					type:'line',
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
					smooth: true,
					type:'line',
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
						formatter: '{value}'
					},
					splitLine: {
			            show: false
			        }
				}
			]
		}
		myChart.setOption(option);
	}
});
</script>