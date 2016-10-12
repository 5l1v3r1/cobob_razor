<style>
.echarts {height: 260px;}
</style>
<section id="main" class="column">
	<!-- <div style="height:340px;">
		<iframe src="<?php echo site_url() ?>/payanaly/paydata/paydataCharts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>
	</div> -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_datalist_yao')?></h3>
				<!-- 导出CSV -->
				<!-- <span class="relative r">
					<a href="<?php echo site_url(); ?>/useranalysis/dauusers/exportdetaildata" class="bottun4 hover" >
						<font><?php echo  lang('g_exportToCSV');?></font>
					</a>
				</span>	 -->
		</header>
		<div class="echarts">
			<iframe src='<?php echo site_url() ?>/payanaly/newpay/charts' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>
		</div>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<!-- 日期 -->
							<th style="border-bottom:1px solid #999;"></th> 
							<!-- 首日 -->
							<th colspan="2" style="background:#dfdfdf; border-bottom:1px solid #999;" align="center"><?php echo  lang('t_firstDay_yao')?></th> 
							<!-- 首周 -->
							<th colspan="2" style="background:#eee; border-bottom:1px solid #999;" align="center"><?php echo  lang('t_firstWeek_yao')?></th> 
							<!-- 首月 -->
							<th colspan="2" style="background:#dfdfdf; border-bottom:1px solid #999;" align="center"><?php echo  lang('t_firstMonth_yao')?></th> 
						</tr>
						<tr>
							<!-- 日期 -->
							<td><?php echo  lang('g_date')?></td>
							<!-- 付费人数 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiRenShu_yao')?></td>
							<!-- 付费率 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiLv_yao')?></td>
							<!-- 付费人数 -->
							<td style="background:#eee;" align="center"><?php echo  lang('t_fuFeiRenShu_yao')?></td>
							<!-- 付费率 -->
							<td style="background:#eee;" align="center"><?php echo  lang('t_fuFeiLv_yao')?></td>
							<!-- 付费人数 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiRenShu_yao')?></td>
							<!-- 付费率 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiLv_yao')?></td>
						</tr>
					</thead> 
					<tbody id="content">
						 <?php
						 	foreach ($result as $key) {
								echo "<tr>";
							 	echo "<td>".$key['date']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['firstdaypayuser']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".round((floatval($key['firstdaypaycount'])*100),2)."%</td>";
							 	echo "<td style='background:#eee;' align='center'>".$key['firstweekpayuser']."</td>";
							 	echo "<td style='background:#eee;' align='center'>".round((floatval($key['firstweekpaycount'])*100),2)."%</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['firstmonthpayuser']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".round((floatval($key['firstmonthpaycount'])*100),2)."%</td>";
							 	echo "</tr>";
						 	}
						 ?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
		</div>
	</article>
</section>
