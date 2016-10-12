<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */
?>
<style>
article.module.width_full {margin: 0; width: 100%; border: 0;}
#mainlevelalycontainer {height: 250px;}
</style>
<article class="module width_full">
	<div id="mainlevelalycontainer"></div>
</article>
<script type="text/javascript">
// 基于准备好的dom，初始化echarts图表
var myChart = echarts.init(document.getElementById('mainlevelalycontainer'));
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
clearTimeout(loadingTicket);
var loadingTicket = setTimeout(function (){
	myChart.hideLoading();
	default_data();
},500);
function default_data(){
	myChart.clear();
	option.xAxis[0]['data'] = [0];
	var json = <?php echo json_encode($result) ?>;

	var levelfield_arr = new Array();//等级段
	var dauusers_arr = new Array();//活跃用户
	var gamecount_arr = new Array();//游戏次数
	var newusers_arr = new Array();//新增用户数

	for(var i = 0;i < json.length;i++){
		levelfield_arr.push(json[i]['levelfield']);
		dauusers_arr.push(json[i]['dauusers']);
		gamecount_arr.push(json[i]['gamecount']);
		newusers_arr.push(json[i]['newusers']);
	}
	all_arr = new Array(levelfield_arr,dauusers_arr,gamecount_arr,newusers_arr);
	option.xAxis[0]['data'] = all_arr[0];
	console.log(all_arr);
	option.series = [
		{
			name:"活跃角色",
			smooth: true,
			type:'line',
			data:all_arr[1]
		},
		{
			name:"<?php echo lang('t_youXiCiShu_yao')?>",
			smooth: true,
			type:'line',
			data:all_arr[2]
		},
		{
			name:"<?php echo lang('t_xinZengYongHuShu_yao')?>",
			smooth: true,
			type:'line',
			data:all_arr[3]
		}
	];
	option.legend = {
		data:[
			"活跃角色",
			"<?php echo lang('t_youXiCiShu_yao')?>",
			"<?php echo lang('t_xinZengYongHuShu_yao')?>"
		]
	};
	myChart.setOption(option);
}
</script>

