<style>
table.tablesorter th,table.tablesorter td {text-indent: 0;}
.echarts {height: 260px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_levellose_analysis_yao')?></h3>
		</header>
		<div class="echarts">
			<iframe src='<?php echo site_url() ?>/leaveanaly/levelleave/charts' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>
		</div>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0">
					<thead> 
						<tr>
							<th align="center"><?php echo  lang('t_dengJi_yao')?></th>
							<th align="center"><?php echo  lang('t_levelLossRate_yao')?></th>
							<th align="center"><?php echo  lang('t_pingJunShiJiShiChang_yao')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['rolelevel']."</td>";
								echo "<td align='center'>".(round(floatval($value['levelleaverate']),2)*100)."%</td>";
								echo "<td align='center'>".$value['avgupdatetime']."</td>";
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
