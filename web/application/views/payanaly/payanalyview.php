<style>
.width_full {overflow: hidden;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 320px;}
.hide {display: none;}
.sub-tabs {overflow: hidden; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;}
.sub-tabs li {float: left; list-style: none; margin-left: 20px; padding: 2px 10px; cursor: pointer; border-radius: 5px;}
.sub-tabs li.active {background: #333; color: #fff;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<!-- 付费数据  -->
			<h3 class="h3_fontstyle"><?php echo  lang('t_payanalysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<!-- 付费金额 -->
					<li class="active"><a><?php echo  lang('t_fuFeiJinE_yao')?></a></li>
					<!-- 付费次数 -->
					<li><a><?php echo  lang('t_fuFeiCiShu_yao')?></a></li>
					<!-- ARPU/ARPPU -->
					<li><a><?php echo  lang('ARPU/ARPPU')?></a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="sub-tabs">
				<li class="active">每日</li>
				<li>每周</li>
				<li>每月</li>
			</div>
			<div class="show">
				<div class="echarts"></div>
				<div class="excel day">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter day date-table" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('t_fuFeiQuJian_yao')?></th>
									<th align="center"><?php echo  lang('t_riFuFeiRenShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($paymoney_day as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['payfield']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="excel week hide">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter week date-table" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('t_fuFeiQuJian_yao')?></th>
									<th align="center"><?php echo  lang('t_zhouFuFeiRenShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($paymoney_week as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['payfield']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="excel month hide">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter month date-table" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('t_fuFeiQuJian_yao')?></th>
									<th align="center"><?php echo  lang('t_yueFuFeiRenShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($paymoney_month as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['payfield']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="show hide">
				<div class="echarts"></div>
				<div class="excel day">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter day date-table" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></th>
									<th align="center"><?php echo  lang('t_riFuFeiRenShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($paycount_day as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['paycount']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="excel week hide">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter week hide date-table" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></th>
									<th align="center"><?php echo  lang('t_zhouFuFeiRenShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($paycount_week as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['paycount']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="excel month hide">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter month hide date-table" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></th>
									<th align="center"><?php echo  lang('t_yueFuFeiRenShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($paycount_month as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['paycount']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="show hide">
				<div class="echarts"></div>
				<div class="excel month">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0" width="50%" style="float:left;width:50%;"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center"><?php echo  lang('t_riARPU_yao')?></th>
									<th align="center"><?php echo  lang('t_riARPPU_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($payARPUday as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['startdate_sk']."</td>";
										echo "<td align='center'>".round((floatval($value['dayarpu'])),2)."</td>";
										echo "<td align='center'>".round((floatval($value['dayarppu'])),2)."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<table class="tablesorter" cellspacing="0" style="float:left;width:50%; border-left:1px dashed #999;"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center"><?php echo  lang('t_yueARPU_yao')?></th>
									<th align="center"><?php echo  lang('t_yueARPPU_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($PayARPUmonth as $value) {
										echo "<tr>";
										echo "<td align='center'>".date('Y-m',strtotime($value['startdate_sk']))."</td>";
										echo "<td align='center'>".round((floatval($value['montharpu'])),2)."</td>";
										echo "<td align='center'>".round((floatval($value['montharppu'])),2)."</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</article>
</section>
<script>
$(document).ready(function(){
	var date = 'day';
	var topCurrent = 0;
	$('.submit_link .tabs li').bind('click',function(){
		topCurrent = $(this).index();
		$('#container > div.show').hide();
		$('#container > div.show').eq(topCurrent).show();
		$('.echarts iframe').remove();
		data_arr(topCurrent,date);
		if(topCurrent == '2'){
			$('.sub-tabs').hide();
		}
		else{
			$('.sub-tabs').show();
		}
	});
	$('.sub-tabs li').bind('click',function(){
		$('div.excel').hide();
		var current = $(this).index();
		$('.sub-tabs li').removeClass('active');
		$(this).addClass('active');
		switch(current){
			case 0:
				date = 'day';
				$('div.day').show();
				break;
			case 1:
				date = 'week';
				$('div.week').show();
				break;
			case 2:
				date = 'month';
				$('div.month').show();
				break;
		}
		data_arr(topCurrent,date);
	});
	var $arr = new Array();
	function data_arr(current,date){
		$arr[0] = "<iframe src='<?php echo site_url() ?>/payanaly/payanalysis/echarts?type=paymoney&date="+date+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$arr[1] = "<iframe src='<?php echo site_url() ?>/payanaly/payanalysis/echarts?type=paycount&date="+date+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$arr[2] = "<iframe src='<?php echo site_url() ?>/payanaly/payanalysis/echarts?type=arpu_arppu' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$('.echarts').eq(current).html($arr[current]);
	}
	data_arr(topCurrent,date);
});
</script>
