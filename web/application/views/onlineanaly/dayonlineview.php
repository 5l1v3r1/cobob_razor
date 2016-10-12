<style>
table th {text-indent: 0;}
.echarts {height: 260px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_meiRiZaiXianWanJia_yao')?></h3>
		</header>
		<div class="echarts">
			<iframe src="<?php echo site_url() ?>/onlineanaly/dayonline/charts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>	
		</div>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th align="center"><?php echo  lang('g_date')?></th>
							<th align="center">acu</th>
							<th align="center">pcu</th>
							<th align="center"><?php echo  lang('t_ccuBiLv_yao')?></th>
							<th align="center"><?php echo  lang('t_xiangQing_yao')?></th>
						</tr>
					</thead> 
					<tbody id="content">
						<?php 
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['startdate_sk']."</td>";
								echo "<td align='center'>".$value['acu']."</td>";
								echo "<td align='center'>".$value['pcu']."</td>";
								echo "<td align='center'>".round($value['ccu'],2)."</td>";
								echo "<td align='center'><a href='javascript:;' class='infoDetail' alt='".$value['startdate_sk']."'>".lang('t_chaKan_yao')."</a></td>";
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
<script>
$(document).ready(function(){
	$('a.infoDetail').live('click',function(){
		$('#userdetail-frame').remove();
		if($(this).text() == '查看'){
			$(this).text('关闭');
			var date = $(this).attr('alt');
			$(this).parents('tr').after("<tr id='userdetail-frame'><td colspan='13' style='height:500px; padding:10px; background:#ddd;'><iframe src='<?php echo site_url() ?>/onlineanaly/dayonline/showdetail?date="+date+"'  frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe></td></tr>");
		}
		else{
			$(this).text('查看');
		}
		$('a.infoDetail').not($(this)).text('查看');
	});
});
</script>