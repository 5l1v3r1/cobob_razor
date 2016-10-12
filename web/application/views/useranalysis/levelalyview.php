<style>
	table.tablesorter td {padding: 15px 0;}
	table.tablesorter thead tr {text-indent: 0;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_level_yao')?></h3>
		</header>
		<div id="echarts-container" style="height:250px;">
			<iframe src="<?php echo site_url() ?>/useranalysis/levelaly/mainlevelalyreport"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>
		</div>
		<div class="tab_container">
			<div class="tab_content">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center"><?php echo lang('t_levelDuan_yao') ?></th>
									<th align="center">活跃角色</th>
									<th align="center"><?php echo lang('t_baiFenBi_yao') ?></th>
									<th align="center"><?php echo lang('t_youXiCiShu_yao') ?></th>
									<th align="center"><?php echo lang('t_baiFenBi_yao') ?></th>
									<th align="center">新增角色数</th>
									<th align="center">百分比</th>
									<th align="center"><?php echo lang('t_pingJunShiJiShiChang_yao') ?></th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['levelfield']."</td>";
										echo "<td align='center'>".$value['dauusers']."</td>";
										echo "<td align='center'>".round((floatval($value['dauusers'])/floatval($SumResult[0]['dauusers'])*100),2)."%</td>";
										echo "<td align='center'>".$value['gamecount']."</td>";
										echo "<td align='center'>".round((floatval($value['gamecount'])/floatval($SumResult[0]['gamecount'])*100),2)."%</td>";
										echo "<td align='center'>".$value['newusers']."</td>";
										echo "<td align='center'>".round((floatval($value['newusers'])/floatval($SumResult[0]['newusers'])*100),2)."%</td>";
										echo "<td align='center'>".$value['avglevelupgrade']."</td>";
										echo "<td align='center'><a id='".$value['levelfield']."' class='view' href='javascript:;'>查看</a></td>";
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
	var levelfield = $(this).attr('id');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="9" style="padding:0; height:500px;"><iframe src="<?php echo site_url() ?>/useranalysis/levelaly/addlevelalyreport?levelfield='+levelfield+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
</script>