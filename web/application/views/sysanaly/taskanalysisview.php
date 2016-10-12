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
			<h3 class="tabs_involved"><?php echo  lang('t_task_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>新手任务</a></li>
					<li><a>主线任务</a></li>
					<li><a>支线任务</a></li>
					<li><a>一般任务</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show-1">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">任务</th>
									<th align="center">步骤数</th>
									<th align="center">任务激活次数</th>
									<th align="center">任务完成次数</th>
									<th align="center">任务完成率</th>
									<th align="center">任务激活人数</th>
									<th align="center">任务停留人数</th>
									<th align="center">任务停留率</th>
									<th align="center">任务通过人数</th>
									<th align="center">任务通过率</th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result1 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['task']."</td>";//任务
										echo "<td align='center'>".$value['stepcount']."</td>";//步骤数
										echo "<td align='center'>".$value['taskactivecount']."</td>";//任务激活次数
										echo "<td align='center'>".$value['taskdonecount']."</td>";//任务完成次数
										echo "<td align='center'>".round(((floatval($value['taskdonecount']))/(floatval($value['taskactivecount']))*100),2)."%</td>";//任务完成率
										echo "<td align='center'>".$value['taskactiveuser']."</td>";//任务激活人数
										echo "<td align='center'>".$value['taskstayuser']."</td>";//任务停留人数
										echo "<td align='center'>".round(((floatval($value['taskstayuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务停留率
										echo "<td align='center'>".$value['taskpassuser']."</td>";//任务通过人数
										echo "<td align='center'>".round(((floatval($value['taskpassuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务通过率
										echo "<td align='center'><a name='".$value['task']."' title='newuser' class='view' href='javascript:;'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div class="show-2">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">任务</th>
									<th align="center">步骤数</th>
									<th align="center">任务激活次数</th>
									<th align="center">任务完成次数</th>
									<th align="center">任务完成率</th>
									<th align="center">任务激活人数</th>
									<th align="center">任务停留人数</th>
									<th align="center">任务停留率</th>
									<th align="center">任务通过人数</th>
									<th align="center">任务通过率</th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result2 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['task']."</td>";//任务
										echo "<td align='center'>".$value['stepcount']."</td>";//步骤数
										echo "<td align='center'>".$value['taskactivecount']."</td>";//任务激活次数
										echo "<td align='center'>".$value['taskdonecount']."</td>";//任务完成次数
										echo "<td align='center'>".round(((floatval($value['taskdonecount']))/(floatval($value['taskactivecount']))*100),2)."%</td>";//任务完成率
										echo "<td align='center'>".$value['taskactiveuser']."</td>";//任务激活人数
										echo "<td align='center'>".$value['taskstayuser']."</td>";//任务停留人数
										echo "<td align='center'>".round(((floatval($value['taskstayuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务停留率
										echo "<td align='center'>".$value['taskpassuser']."</td>";//任务通过人数
										echo "<td align='center'>".round(((floatval($value['taskpassuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务通过率
										echo "<td align='center'><a name='".$value['task']."' title='main' class='view' href='javascript:;'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div class="show-3">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">任务</th>
									<th align="center">步骤数</th>
									<th align="center">任务激活次数</th>
									<th align="center">任务完成次数</th>
									<th align="center">任务完成率</th>
									<th align="center">任务激活人数</th>
									<th align="center">任务停留人数</th>
									<th align="center">任务停留率</th>
									<th align="center">任务通过人数</th>
									<th align="center">任务通过率</th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result3 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['task']."</td>";//任务
										echo "<td align='center'>".$value['stepcount']."</td>";//步骤数
										echo "<td align='center'>".$value['taskactivecount']."</td>";//任务激活次数
										echo "<td align='center'>".$value['taskdonecount']."</td>";//任务完成次数
										echo "<td align='center'>".round(((floatval($value['taskdonecount']))/(floatval($value['taskactivecount']))*100),2)."%</td>";//任务完成率
										echo "<td align='center'>".$value['taskactiveuser']."</td>";//任务激活人数
										echo "<td align='center'>".$value['taskstayuser']."</td>";//任务停留人数
										echo "<td align='center'>".round(((floatval($value['taskstayuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务停留率
										echo "<td align='center'>".$value['taskpassuser']."</td>";//任务通过人数
										echo "<td align='center'>".round(((floatval($value['taskpassuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务通过率
										echo "<td align='center'><a name='".$value['task']."' title='branch' class='view' href='javascript:;'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div class="show-4">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">任务</th>
									<th align="center">步骤数</th>
									<th align="center">任务激活次数</th>
									<th align="center">任务完成次数</th>
									<th align="center">任务完成率</th>
									<th align="center">任务激活人数</th>
									<th align="center">任务停留人数</th>
									<th align="center">任务停留率</th>
									<th align="center">任务通过人数</th>
									<th align="center">任务通过率</th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result4 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['task']."</td>";//任务
										echo "<td align='center'>".$value['stepcount']."</td>";//步骤数
										echo "<td align='center'>".$value['taskactivecount']."</td>";//任务激活次数
										echo "<td align='center'>".$value['taskdonecount']."</td>";//任务完成次数
										echo "<td align='center'>".round(((floatval($value['taskdonecount']))/(floatval($value['taskactivecount']))*100),2)."%</td>";//任务完成率
										echo "<td align='center'>".$value['taskactiveuser']."</td>";//任务激活人数
										echo "<td align='center'>".$value['taskstayuser']."</td>";//任务停留人数
										echo "<td align='center'>".round(((floatval($value['taskstayuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务停留率
										echo "<td align='center'>".$value['taskpassuser']."</td>";//任务通过人数
										echo "<td align='center'>".round(((floatval($value['taskpassuser']))/(floatval($value['taskactiveuser']))*100),2)."%</td>";//任务通过率
										echo "<td align='center'><a name='".$value['task']."' title='general' class='view' href='javascript:;'>查看</a></td>";
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
$('.submit_link .tabs li').bind('click',function(){
	var current = $(this).index();
	$('#container > div').hide();
	$('#container > div').eq(current).show();
})
//显示图标
$('a.view').bind('click',function(){
	$('#add-iframe').remove();
	var taskName = $(this).attr('name');
	var tasktype = $(this).attr('title');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="11" style="height:500px;"><iframe src="<?php echo site_url() ?>/sysanaly/taskanalysis/charts?taskName='+taskName+'&tasktype='+tasktype+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
</script>