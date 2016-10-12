<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
#container table #add-iframe td {padding: 10px; background: #eee;}
.echarts {width: 100%; height: 300px;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('m_cuoWuMaFenXi_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>触发详情</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show">
				<div class="echarts">
					<iframe src="<?php echo site_url() ?>/exceptionanaly/errorcodeanaly/charts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>
				</div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">错误码</th>
									<th align="center">触发人数</th>
									<th align="center">触发次数</th>
									<th align="center">触发率</th>
									<th align="center">人均次数</th>
									<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($errorcodeanaly as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['errorname']."</td>";
										echo "<td align='center'>".$value['erroruser']."</td>";
										echo "<td align='center'>".$value['errorcount']."</td>";
										echo "<td align='center'>".round((floatval($value['errorcount'])/floatval($value['erroruser'])*100),2)."%</td>";
										echo "<td align='center'>".$value['useravgcount']."</td>";
										echo "<td align='center'><a id='".$value['errorid']."' class='view' href='javascript:;'>查看</a></td>";
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
$('.submit_link .tabs li').bind('click',function(){
	var current = $(this).index();
	$('#container > div').hide();
	$('#container > div').eq(current).show();
})
//显示图标
$('a.view').bind('click',function(){
	$('#add-iframe').remove();
	var id = $(this).attr('id');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="11" style="height:600px;"><iframe src="<?php echo site_url() ?>/exceptionanaly/errorcodeanaly/chartsDetail?id='+id+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
</script>