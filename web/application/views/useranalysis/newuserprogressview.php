<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 300px;}
.excel {display: block!important;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('m_xinYongHuJinDu_yao')?></h3>
		</header>
		<div id="container" class="module_content">
			<div class="echarts">
				<iframe src="<?php echo site_url() ?>/useranalysis/newuserprogress/charts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>
			</div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0"> 
						<thead> 
							<tr>
								<th align="center"><?php echo  lang('g_date')?></th>
								<?php
									for ($i=0;$i<count($name);$i++) {
										echo "<th align='center'>".$name[$i]['newuserprogress_name']."</th>";
										if($i > 0){
											echo "<th align='center'>".$name[$i]['newuserprogress_name']."占比</th>";
										}
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php
								for($i=0;$i<count($result);$i++){
									$base = $result[$i][1][0];
									echo "<tr>";
									echo "<td align='center'>".$result[$i][0]."</td>";
									for($r=0;$r<count($result[$i][1]);$r++){
										echo "<td align='center'>".$result[$i][1][$r]."</td>";
										if($r!=0){
											echo "<td align='center'>".round((floatval($result[$i][1][$r])/floatval($base)*100),2)."%</td>";
										}
									}
									echo "</tr>";
								}
							?>
						</tbody>
					</table>
					<footer><div class="submit_link hasPagination"></div></footer>
				</div>
			</div>
		</div>
	</article>
</section>

