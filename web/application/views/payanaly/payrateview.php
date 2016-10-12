<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 260px;}
</style>

<section id="main" class="column">
	<article class="module width_full">
		<header>
			<!-- 付费率  -->
			<h3 class="h3_fontstyle"><?php echo  lang('t_payrate_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<!-- 日付费率 -->
					<li class="active"><a><?php echo  lang('t_dayPayRate_yao')?></a></li>
					<!-- 周付费率 -->
					<li><a><?php echo  lang('t_weekPayRate_yao')?></a></li>
					<!-- 月付费率 -->
					<li><a><?php echo  lang('t_monthPayRate_yao')?></a></li>
					<!-- 累计付费率 -->
					<li><a><?php echo  lang('t_accumulatePayRate_yao')?></a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div>
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('g_date')?></th>
									<th align="center" width="33%"><?php echo  lang('t_dayPayRate_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($daydata as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date']."</td>";
										echo "<td align='center'>".round((floatval($value['daypayrate'])*100),2)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div>
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('g_date')?></th>
									<th align="center" width="33%"><?php echo  lang('t_weekPayRate_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($weekdata as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['startdate_sk']." - ".$value['enddate_sk']."</td>";
										echo "<td align='center'>".round((floatval($value['weekpayrate'])*100),2)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div>
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('g_date')?></th>
									<th align="center" width="33%"><?php echo  lang('t_monthPayRate_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($monthdata as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['startdate_sk']."</td>";
										echo "<td align='center'>".round((floatval($value['monthpayrate'])*100),2)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div>
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('g_date')?></th>
									<th align="center" width="33%"><?php echo  lang('t_accumulatePayRate_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($daydata as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date']."</td>";
										echo "<td align='center'>".round((floatval($value['totalpayrate'])*100),2)."%</td>";
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
	$('#main .submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		$('#container > div').hide();
		$('#container > div').eq(current).show();
		$('.echarts iframe').remove();
		$('.echarts').eq(current).html($arr[current]);
	});
	var $arr = new Array();
	$arr[0] = "<iframe src='<?php echo site_url() ?>/payanaly/payrate/charts?type=day' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[1] = "<iframe src='<?php echo site_url() ?>/payanaly/payrate/charts?type=week' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[2] = "<iframe src='<?php echo site_url() ?>/payanaly/payrate/charts?type=month' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[3] = "<iframe src='<?php echo site_url() ?>/payanaly/payrate/charts?type=total' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$('.echarts').eq(0).html($arr[0]);
});
</script>
