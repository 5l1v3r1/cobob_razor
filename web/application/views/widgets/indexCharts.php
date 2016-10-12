<style>
	#container {height: 342px; width: 100%;}
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
var type = <?php echo json_encode($type) ?>;
var _Date = new Array();//日期
var Deviceactivations = new Array();//设备激活
var Registeruser = new Array();//新增注册
var Newuser = new Array();//新增用户
var Dauuser = new Array();//活跃用户
var Payamount = new Array();//付费金额
var Payuser = new Array();//付费人数
for(var i = 0;i < json.length;i++){
	_Date.push(json[i]['Date']);
	Deviceactivations.push(json[i]['Deviceactivations']);
	Registeruser.push(json[i]['Registeruser']);
	Newuser.push(json[i]['Newuser']);
	Dauuser.push(json[i]['Dauuser']);
	Payamount.push(json[i]['Payamount']);
	Payuser.push(json[i]['Payuser']);
}
all_arr = new Array(_Date,Deviceactivations,Registeruser,Newuser,Dauuser,Payamount,Payuser);

var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	if(type == 0){
		option = {
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:['设备激活','新增注册','新增用户','活跃用户']
			},
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : all_arr[0],
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
			],
			series : [
				{
					name:'设备激活',
					type:'line',
					smooth:true,
					data:all_arr[1]
				},
				{
					name:'新增注册',
					type:'line',
					smooth:true,
					data:all_arr[2]
				},
				{
					name:'新增用户',
					type:'line',
					smooth:true,
					data:all_arr[3]
				},
				{
					name:'活跃用户',
					type:'line',
					smooth:true,
					data:all_arr[4]
				}
			]
		};
	}
	else if(type == 1){
		option = {
			tooltip : {
				trigger: 'axis'
			},
			calculable : true,
			legend: {
				data:['付费金额','付费人数']
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
					name : '付费人数',
					axisLabel : {
						formatter: '{value} 人'
					},
					splitLine: {
			            show: false
			        }
				},
				{
					type : 'value',
					name : '付费金额',
					axisLabel : {
						formatter: '{value} 元'
					},
					splitLine: {
			            show: false
			        }
				}
			],
			series : [
				{
					name:'付费金额',
					type:'line',
					yAxisIndex: 1,
					data:all_arr[5]
				},
				{
					name:'付费人数',
					type:'bar',
					data:all_arr[6]
				}
			]
		};
	}
	myChart.setOption(option);
},500);
</script>