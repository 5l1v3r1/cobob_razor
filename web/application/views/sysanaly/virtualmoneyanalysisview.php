<style>
table th {text-indent: 0;}
.echarts {height: 400px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_vitualcurrency_analysis_yao')?></h3>
		</header>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th align="center">虚拟币</th>
							<th align="center">产出+</th>
							<th align="center">消耗-</th>
							<th align="center">产出角色</th>
							<th align="center">消耗角色</th>
							<th align="center">详情</th>
						</tr>
					</thead> 
					<tbody id="content">
						<?php 
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['virtualmoney']."</td>";
								echo "<td align='center'>".$value['virtualmoney_output']."</td>";
								echo "<td align='center'>".$value['virtualmoney_consume']."</td>";
								echo "<td align='center'>".$value['virtualmoney_outputuser']."</td>";
								echo "<td align='center'>".$value['virtualmoney_consumeuser']."</td>";
								echo "<td align='center'><a name='".$value['virtualmoney']."' class='view' href='javascript:;'>查看</a></td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</article>
</section>
<script>
//显示图标
$('a.view').bind('click',function(){
	$('#add-iframe').remove();
	var Name = $(this).attr('name');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="11" style="height:500px;"><iframe src="<?php echo site_url() ?>/sysanaly/virtualmoneyanalysis/charts?Name='+Name+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
</script>