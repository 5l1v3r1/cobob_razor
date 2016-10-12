<style>
table th {text-indent: 0;}
.echarts {height: 400px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_startgap_analysis_yao')?></h3>
		</header>
		<div class="echarts">
			<iframe src="<?php echo site_url() ?>/onlineanaly/borderintervaltime/charts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>	
		</div>
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
					<th align="center">登录间隔</th>
					<th align="center">人数</th>
				</tr>
			</thead> 
			<tbody id="content">
				<?php 
					foreach ($result as $value) {
						echo "<tr>";
						echo "<td align='center'>".$value['logininterval']."</td>";
						echo "<td align='center'>".$value['rolecount']."</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table> 
	</article>
</section>