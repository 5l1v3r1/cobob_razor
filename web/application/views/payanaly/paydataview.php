<section id="main" class="column">
	<div style="height:340px;">
		<iframe src="<?php echo site_url() ?>/payanaly/paydata/paydataCharts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>		
	</div>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_datalist_yao')?></h3>
		</header>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<!-- 日期 -->
							<th style="border-bottom:1px solid #999;"></th> 
							<!-- 活跃用户 -->
							<th colspan="3" style="background:#dfdfdf; border-bottom:1px solid #999;" align="center"><?php echo  lang('t_huoYueWanJia_yao')?></th> 
							<!-- 首次付费 -->
							<th colspan="3" style="background:#eee; border-bottom:1px solid #999;" align="center"><?php echo  lang('t_shouCiFuFeiWanJia_yao')?></th> 
							<!-- 首日付费 -->
							<th colspan="3" style="background:#dfdfdf; border-bottom:1px solid #999;" align="center"><?php echo  lang('t_shouRiFuFeiWanJia_yao')?></th> 
						</tr> 
						<tr>
							<!-- 日期 -->
							<td><?php echo  lang('g_date')?></td>
							<!-- 付费金额 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiJinE_yao')?></td>
							<!-- 付费人数 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiRenShu_yao')?></td>
							<!-- 付费次数 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></td>
							<!-- 付费金额 -->
							<td style="background:#eee;" align="center"><?php echo  lang('t_fuFeiJinE_yao')?></td>
							<!-- 付费人数 -->
							<td style="background:#eee;" align="center"><?php echo  lang('t_fuFeiRenShu_yao')?></td>
							<!-- 付费次数 -->
							<td style="background:#eee;" align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></td>
							<!-- 付费金额 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiJinE_yao')?></td>
							<!-- 付费人数 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiRenShu_yao')?></td>
							<!-- 付费次数 -->
							<td style="background:#dfdfdf;" align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></td>
						</tr>
					</thead> 
					<tbody id="content">
						 <?php
						 	foreach ($result as $key) {
						 		echo "<tr>";
							 	echo "<td>".$key['date']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['daupayamount']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['daupayuser']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['daupaycount']."</td>";
							 	echo "<td style='background:#eee;' align='center'>".$key['firstpayamount']."</td>";
							 	echo "<td style='background:#eee;' align='center'>".$key['firstpayuser']."</td>";
							 	echo "<td style='background:#eee;' align='center'>".$key['firstpaycount']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['firstdaypayamount']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['firstdaypayuser']."</td>";
							 	echo "<td style='background:#dfdfdf;' align='center'>".$key['firstdaypaycount']."</td>";
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
