<style>
	table.tablesorter td {padding: 15px 0;}
	table.tablesorter thead tr {text-indent: 0;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved">
				<?php echo  lang('t_rongLiangShiTu_yao')?>
				<span class="grey">(注:此统计项不满足区服筛选要求）</span>
			</h3>
		</header>
		<div id="echarts-container" style="height:250px;">
			<iframe src="<?php echo site_url() ?>/onlineanaly/capacityv/charts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>
		</div>
		<div class="tab_container">
			<div class="tab_content">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center"><?php echo lang('g_date') ?></th>
									<th align="center"><?php echo lang('t_zongCPU_yao') ?></th>
									<th align="center"><?php echo lang('t_fuWuQiZongRongLiang_yao') ?></th>
									<th align="center"><?php echo lang('t_zaiXianRongLiangBi_yao') ?></th>
									<th align="center"><?php echo lang('v_rpt_err_view') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['startdate_sk']."</td>";
										echo "<td align='center'>".$value['totalpcu']."</td>";
										echo "<td align='center'>".$value['totalcapacity']."</td>";
										echo "<td align='center'>".(floatval($value['userate'])*100)."%</td>";
										echo "<td align='center'><a id='".$value['startdate_sk']."' class='view' href='javascript:;'>查看</a></td>";
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
	<!-- end of content manager article -->
		<div class="clear"></div>
	<div class="spacer"></div>
</section>
<script>
//显示图标
$('a.view').bind('click',function(){
	$('#add-iframe').remove();
	var date = $(this).attr('id');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="9" style="padding:0; height:500px;"><iframe src="<?php echo site_url() ?>/onlineanaly/capacityv/detailCharts?date='+date+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
</script>