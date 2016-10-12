<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 260px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<!-- 付费率  -->
			<h3 class="h3_fontstyle"><?php echo  lang('m_vipYongHu_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>用户分析</a></li>
					<li><a>付费分析</a></li>
					<li><a>流失分析</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead> 
								<tr>
									<th align="center">VIP等级</th>
									<th align="center">新增VIP</th>
									<th align="center">去掉VIP</th>
									<th align="center">当前VIP</th>
									<th align="center">日均游戏次数</th>
									<th align="center">每次游戏时长</th>
									<th align="center">详情</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($useralany as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['viplevel']."</td>";
										echo "<td align='center'>".$value['newvip']."</td>";
										echo "<td align='center'>".$value['outvip']."</td>";
										echo "<td align='center'>".$value['currentvip']."</td>";
										echo "<td align='center'>".round($value['dayavggamecount'],2)."</td>";
										echo "<td align='center'>".$value['pergametime']."</td>";
										echo "<td align='center'><a class='view' title='".$value['viplevel']."' href='javascript:;'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter payalanyData" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center">VIP等级</th>
									<th align="center">付费总额￥</th>
									<th align="center">日均付费次数</th>
									<th align="center">日均付费人数</th>
									<th align="center">人均付费次数</th>
									<th align="center">付费率</th>
									<th align="center">ARPU</th>
									<th align="center">ARPPU</th>
									<th align="center">详细</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($payalanyData as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['viplevel']."</td>";
										echo "<td align='center'>".$value['payamount']."</td>";
										echo "<td align='center'>".round($value['dayavgpaycount'],2)."</td>";
										echo "<td align='center'>".round($value['dayavgpayuser'],2)."</td>";
										echo "<td align='center'>".round($value['useravgpaycount'],2)."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".round($value['arpu'],2)."</td>";
										echo "<td align='center'>".round($value['arppu'],2)."</td>";
										echo "<td align='center'><a class='view' title='".$value['viplevel']."' href='javascript:;'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center">VIP等级</th>
									<th align="center">3日流失</th>
									<th align="center">3日流失率</th>
									<th align="center">7日流失</th>
									<th align="center">7日流失率</th>
									<th align="center">14日流失</th>
									<th align="center">14日流失率</th>
									<th align="center">30日流失</th>
									<th align="center">30日流失率</th>
									<th align="center">详细</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($leavealany as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['viplevel']."</td>";
										echo "<td align='center'>".$value['dayleave3']."</td>";
										echo "<td align='center'>".$value['dayleaverate3']."%</td>";
										echo "<td align='center'>".$value['dayleave7']."</td>";
										echo "<td align='center'>".$value['dayleaverate7']."%</td>";
										echo "<td align='center'>".$value['dayleave14']."</td>";
										echo "<td align='center'>".$value['dayleaverate14']."%</td>";
										echo "<td align='center'>".$value['dayleave30']."</td>";
										echo "<td align='center'>".$value['dayleaverate30']."%</td>";
										echo "<td align='center'><a class='view' title='".$value['viplevel']."' href='javascript:;'>查看</a></td>";
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
</section>
<script>
$(document).ready(function(){
	var current = 0;
	$('#main .submit_link .tabs li').bind('click',function(){
		current = $(this).index();
		$('#container > div').hide();
		$('#container > div').eq(current).show();
	});
	var payrate = 0;
	$('.payalanyData tbody tr').each(function(){
		var num = parseInt($(this).find('td:eq(3)').text());
		payrate += num;
	});
	$('.payalanyData tbody tr').each(function(){
		var num = parseInt($(this).find('td:eq(3)').text());
		var result = (isNaN((num/payrate).toFixed(2)))?0:(num/payrate).toFixed(2);
		$(this).find('td:eq(5)').text(result+'%');
	});
	//显示图标
	$('a.view').bind('click',function(){
		$('#add-iframe').remove();
		var url = '';
		var num = 0;
		switch(current){
			case 0:
				url = 'useralany';
				num = 7;
				break;
			case 1:
				url = 'payalany';
				num = 9;
				break;
			case 2:
				url = 'leavealany';
				num = 10;
				break;
		}
		var viprole = $(this).attr('title');
		$(this).parents('tr').after('<tr id="add-iframe"><td colspan="'+num+'" style="padding:0; height:600px;"><iframe src="<?php echo site_url() ?>/useranalysis/viprole/'+url+'?viprole='+viprole+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
	});
});
</script>
