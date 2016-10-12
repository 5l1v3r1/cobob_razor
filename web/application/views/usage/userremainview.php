<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td,#container table th {padding: 15px 0;}
#container table th {background: #ddd; padding: 10px 0;}
.tablesorter thead tr {text-indent: 0;}
#container .show-3 .tablesorter {margin-top: 20px;}
#container .filter {padding: 5px 15px; display: block; height: 25px; background: #ddd;}
#container .filter select {padding: 2px 10px;}
#container .filter span,.filter label,.filter a {float: left; height: 25px; line-height: 25px; margin: 0 15px 0 0;}
.echarts {height: 350px; margin-top: 20px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('m_rpt_userRetention')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a><?php echo  lang('m_rpt_userRetention')?></a></li>
					<li><a><?php echo  lang('t_deviceRetention_yao')?></a></li>
					<li><a><?php echo  lang('t_liucunxiangqing_yao')?></a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show-1">
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<tr>
								<th><?php echo  lang('g_date')?></th>
								<th><?php echo  lang('m_xinZengYongHu_yao')?></th>
								<th><?php echo  lang('t_ciRi_yao')?></th>
								<th><?php echo  lang('t_2Day_yao')?></th>
								<th><?php echo  lang('t_3Day_yao')?></th>
								<th><?php echo  lang('t_4Day_yao')?></th>
								<th><?php echo  lang('t_5Day_yao')?></th>
								<th><?php echo  lang('t_6Day_yao')?></th>
								<th><?php echo  lang('t_7Day_yao')?></th>
								<th><?php echo  lang('t_14Day_yao')?></th>
								<th><?php echo  lang('t_30Day_yao')?></th>
							</tr>
							<?php 
								foreach ($userremain as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['date']."</td>";
									echo "<td align='center'>".$value['usercount']."</td>";
									echo "<td align='center'>".(floatval($value['day1'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day2'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day3'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day4'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day5'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day6'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day7'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day14'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day30'])*100)."%</td>";
									echo "</tr>";
								}
							 ?>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div class="show-2">
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<tr>
								<th><?php echo  lang('g_date')?></th>
								<th><?php echo  lang('t_newDevice_yao')?></th>
								<th><?php echo  lang('t_ciRi_yao')?></th>
								<th><?php echo  lang('t_2Day_yao')?></th>
								<th><?php echo  lang('t_3Day_yao')?></th>
								<th><?php echo  lang('t_4Day_yao')?></th>
								<th><?php echo  lang('t_5Day_yao')?></th>
								<th><?php echo  lang('t_6Day_yao')?></th>
								<th><?php echo  lang('t_7Day_yao')?></th>
								<th><?php echo  lang('t_14Day_yao')?></th>
								<th><?php echo  lang('t_30Day_yao')?></th>
							</tr>
							<?php 
								foreach ($deviceremain as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['date']."</td>";
									echo "<td align='center'>".$value['devicecount']."</td>";
									echo "<td align='center'>".(floatval($value['day1'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day2'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day3'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day4'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day5'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day6'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day7'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day14'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day30'])*100)."%</td>";
									echo "</tr>";
								}
							 ?>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div class="show-3">
				<div class="filter">
					<span><?php echo  lang('t_filter_yao')?></span>
					<label>
						<select>
							<option value="1"><?php echo  lang('t_day_yao')?></option>
							<option value="2"><?php echo  lang('t_week_yao')?></option>
							<option value="3"><?php echo  lang('t_month_yao')?></option>
						</select>
					</label>
					<label>
						<select class="ss-c">
							<option value="newuser"><?php echo  lang('m_xinZengYongHu_yao')?></option>
							<option value="payuser"><?php echo  lang('t_fuFeiYongHu_yao')?></option>
							<option value="dauuser"><?php echo  lang('t_huoYueYongHu_yao')?></option>
						</select>
					</label>
					<label>
						<select>
							<option value="1"><?php echo  lang('t_zhiShao1Ci_yao')?></option>
							<option value="2"><?php echo  lang('t_zhiShao2Ci_yao')?></option>
							<option value="3"><?php echo  lang('t_zhiShao3Ci_yao')?></option>
						</select>
					</label>
					<a class="bottun4 hover filter-btn" href="javascript:;"><font><?php echo  lang('t_queDing_yao')?></font></a>
				</div>
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter info-detail" cellspacing="0">
							<thead>
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center" class="s-c"><?php echo  lang('m_xinZengYongHu_yao')?></th>
									<th align="center"><?php echo  lang('t_ciRi_yao')?></th>
									<th align="center"><?php echo  lang('t_2Day_yao')?></th>
									<th align="center"><?php echo  lang('t_3Day_yao')?></th>
									<th align="center"><?php echo  lang('t_4Day_yao')?></th>
									<th align="center"><?php echo  lang('t_5Day_yao')?></th>
									<th align="center"><?php echo  lang('t_6Day_yao')?></th>
									<th align="center"><?php echo  lang('t_7Day_yao')?></th>
									<th align="center"><?php echo  lang('t_14Day_yao')?></th>
									<th align="center"><?php echo  lang('t_30Day_yao')?></th>
								</tr>
							</thead>
							<tbody>
							<?php 
								foreach ($remaindetail as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['startdate_sk']."</td>";
									echo "<td align='center'>".$value['count']."</td>";
									echo "<td align='center'>".(floatval($value['day1'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day2'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day3'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day4'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day5'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day6'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day7'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day14'])*100)."%</td>";
									echo "<td align='center'>".(floatval($value['day30'])*100)."%</td>";
									echo "</tr>";
								}
							 ?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
		</div>
	</article>
</section>
<script>

$(document).ready(function(){
	$('.ss-c').change(function(){
		$('.s-c').text($(this).find('option:selected').text());
	});
	var $arr = new Array();
	$arr[0] = "<iframe src='<?php echo site_url() ?>/report/userremain/echarts?type=userremain' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[1] = "<iframe src='<?php echo site_url() ?>/report/userremain/echarts?type=deviceremain' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[2] = "<iframe src='<?php echo site_url() ?>/report/userremain/echarts?type=maindetail' frameborder='0' scrolling='no' name='myFrame' style='width:100%;height:100%;'></iframe>";
	$('.echarts').eq(0).html($arr[0]);
	$('.submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		$('#container > div').hide();
		$('#container > div').eq(current).show();
		$('.echarts iframe').remove();
		$('.echarts').eq(current).html($arr[current]);
	});
	$('#container .filter a.filter-btn').bind('click',function(){
		$('table.info-detail tbody tr').remove();
		function changeText(type){
			var obj = $('table.info-detail tr:first th:eq(1)');
			if(type == 'newuser'){
				obj.text('<?php echo lang('m_xinZengYongHu_yao')?>');

			}
			else if(type == 'payuser'){
				obj.text('<?php echo lang('t_fuFeiYongHu_yao')?>');
			}
			else if(type == 'dauuser'){
				obj.text('<?php echo lang('t_huoYueYongHu_yao')?>');
			}
		}
		var value1 = $('#container .filter select').eq(0).val(),
			value2 = $('#container .filter select').eq(1).val(),
			value3 = $('#container .filter select').eq(2).val();
		changeText(value2);
		$.get('<?php echo site_url()?>/report/userremain/detailFilter?datetype='+value1+'&type='+value2+'&logintimes='+value3,function(res){
			var data = JSON.parse(res);
			console.log(data);
			myFrame.window.parentHTMLcommunication(data,value1);
			var html = '';
			var thead = '';
			if(value1 == 1){
				thead = "<tr>\
							<th align='center'><?php echo  lang('g_date')?></th>\
							<th align='center'></th>\
							<th align='center'><?php echo  lang('t_ciRi_yao')?></th>\
							<th align='center'><?php echo  lang('t_2Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_3Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_4Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_5Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_6Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_7Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_14Day_yao')?></th>\
							<th align='center'><?php echo  lang('t_30Day_yao')?></th>\
						</tr>"
				for(var i = 0,count = data.length;i < count;i++){
					html += "<tr>\
						<td align='center'>"+data[i]['startdate_sk']+"</td>\
						<td align='center'>"+data[i]['count']+"</td>\
						<td align='center'>"+(parseFloat(data[i]['day1'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day2'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day3'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day4'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day5'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day6'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day7'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day14'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['day30'])*100).toFixed(2)+"%</td>\
					</tr>"
				};
			}
			else if(value1 == 2){
				thead = "<tr>\
							<th align='center'><?php echo  lang('g_date')?></th>\
							<th align='center'></th>\
							<th align='center'><?php echo  lang('t_ciZhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_2Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_3Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_4Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_5Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_6Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_7Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_14Zhou_yao')?></th>\
							<th align='center'><?php echo  lang('t_30Zhou_yao')?></th>\
						</tr>"
				for(var i = 0,count = data.length;i < count;i++){
					html += "<tr>\
						<td align='center'>"+data[i]['startdate_sk']+"</td>\
						<td align='center'>"+data[i]['count']+"</td>\
						<td align='center'>"+(parseFloat(data[i]['week1'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week2'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week3'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week4'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week5'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week6'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week7'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week14'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['week30'])*100).toFixed(2)+"%</td>\
					</tr>"
				};
			}
			else if(value1 == 3){
				thead = "<tr>\
					<th align='center'><?php echo  lang('g_date')?></th>\
					<th align='center'></th>\
					<th align='center'><?php echo  lang('t_ciYue_yao')?></th>\
					<th align='center'><?php echo  lang('t_2Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_3Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_4Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_5Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_6Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_7Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_14Yue_yao')?></th>\
					<th align='center'><?php echo  lang('t_30Yue_yao')?></th>\
				</tr>"
				for(var i = 0,count = data.length;i < count;i++){
					html += "<tr>\
						<td align='center'>"+data[i]['startdate_sk']+"</td>\
						<td align='center'>"+data[i]['count']+"</td>\
						<td align='center'>"+(parseFloat(data[i]['month1'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month2'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month3'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month4'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month5'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month6'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month7'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month14'])*100).toFixed(2)+"%</td>\
						<td align='center'>"+(parseFloat(data[i]['month30'])*100).toFixed(2)+"%</td>\
					</tr>"
				};
			}
			$('table.info-detail tbody').html(html);
			$('table.info-detail thead').html(thead);
			changeText(value2);
		});
	});
});
</script>
