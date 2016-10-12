<style>
#container > div {overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 260px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('m_quDaoZhiLiang_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>日活跃</a></li>
					<li><a>留存率</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="echarts">
				<iframe src='<?php echo site_url() ?>/channelanaly/channelquality/echarts?type=0' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>
			</div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead> 
							<tr>
								<th align="center">渠道</th>
								<th align="center">日均活跃</th>
								<th align="center">活跃度</th>
								<th align="center">次日均留存率</th>
								<th align="center">3日均留存率</th>
								<th align="center">7日均留存率</th>
								<th align="center">14日均留存率</th>
								<th align="center">30日均留存率</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['channel_name']."</td>";
									echo "<td align='center'>".$value['dayavgdau']."</td>";
									echo "<td align='center'>".$value['daudegree']."</td>";
									echo "<td align='center'>".$value['day1avgremain']."%</td>";
									echo "<td align='center'>".$value['day3avgremain']."%</td>";
									echo "<td align='center'>".$value['day7avgremain']."%</td>";
									echo "<td align='center'>".$value['day14avgremain']."%</td>";
									echo "<td align='center'>".$value['day30avgremain']."%</td>";
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
<script>
$(document).ready(function(){
	$('#main .submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		var frame = "<iframe src='<?php echo site_url() ?>/channelanaly/channelquality/echarts?type="+current+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$('.echarts').html(frame);
	});
});
</script>
