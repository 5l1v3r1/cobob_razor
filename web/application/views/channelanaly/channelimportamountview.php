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
			<h3 class="h3_fontstyle"><?php echo  lang('m_quDaoDaoLiang_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>渠道导量</a></li>
					<li><a>渠道比重</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="echarts">
				<iframe src='<?php echo site_url() ?>/channelanaly/channelimportamount/echarts?type=0' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>
			</div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead> 
							<tr>
								<th align="center">渠道</th>
								<th align="center">新增设备</th>
								<th align="center">设备激活</th>
								<th align="center">新增注册</th>
								<th align="center">注册转化率</th>
								<th align="center">新增用户</th>
								<th align="center">创角率</th>
								<th align="center">渠道注册占比</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['channel_name']."</td>";
									echo "<td align='center'>".$value['newdevice']."</td>";
									echo "<td align='center'>".$value['deviceactivation']."</td>";
									echo "<td align='center'>".$value['newregister']."</td>";
									echo "<td align='center'>".$value['registerrate']."%</td>";
									echo "<td align='center'>".$value['newuser']."</td>";
									echo "<td align='center'>".$value['newuserrate']."%</td>";
									echo "<td align='center'></td>";
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
		var frame = "<iframe src='<?php echo site_url() ?>/channelanaly/channelimportamount/echarts?type="+current+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$('.echarts').html(frame);
	});
	var all_newregister = 0;
	$('.tablesorter tbody tr').each(function(){
		var num = parseInt($(this).find('td').eq(3).text());
		all_newregister += num;
	});
	$('.tablesorter tbody tr').each(function(){
		var num = $(this).find('td').eq(3).text();
		$(this).find('td').eq(7).text((parseInt(num)/all_newregister*100).toFixed(2)+'%');
	});
});
</script>
