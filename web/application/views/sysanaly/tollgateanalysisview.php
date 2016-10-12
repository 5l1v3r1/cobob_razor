<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
#container table #add-iframe td {padding: 10px; background: #eee;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_systemlevel_analysis_yao')?></h3>
		</header>
		<div id="container" class="module_content">
			<div class="show-1">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">关卡大类</th>
									<th align="center">关卡总数</th>
									<th align="center">攻打次数</th>
									<th align="center">胜利次数</th>
									<th align="center">胜率</th>
									<th align="center">攻打人数</th>
									<th align="center">通过人数</th>
									<th align="center">通关率</th>
									<th align="center">扫荡次数</th>
									<th align="center">扫荡人数</th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['tollgate_bigcategory']."</td>";
										echo "<td align='center'>".$value['tollgate_totalcount']."</td>";
										echo "<td align='center'>".$value['tollgate_attackcount']."</td>";
										echo "<td align='center'>".$value['tollgate_successcount']."</td>";
										echo "<td align='center'>".round((floatval($value['tollgate_successcount'])/floatval($value['tollgate_attackcount'])*100),2)."%</td>";
										echo "<td align='center'>".$value['tollgate_attackuser']."</td>";
										echo "<td align='center'>".$value['tollgate_passuser']."</td>";
										echo "<td align='center'>".round((floatval($value['tollgate_passuser'])/floatval($value['tollgate_attackuser'])*100),2)."%</td>";
										echo "<td align='center'>".$value['tollgate_sweepcount']."</td>";
										echo "<td align='center'>".$value['tollgate_sweepuser']."</td>";
										echo "<td align='center'><a name='".$value['tollgate_bigcategory']."' class='view' href='javascript:;'>查看</a></td>";
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
	<!-- end of content manager article -->
	<div class="clear"></div>
</section>
<script>
//显示图标
$('a.view').bind('click',function(){
	$('#add-iframe').remove();
	var taskName = $(this).attr('name');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="12" style="height:500px;"><iframe src="<?php echo site_url() ?>/sysanaly/tollgateanalysis/charts?taskName='+taskName+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
</script>