<style>
table th {text-indent: 0;}
.echarts {height: 400px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_function_analysis_yao')?></h3>
		</header>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th align="center">功能</th>
							<th align="center">使用人数</th>
							<th align="center">使用次数</th>
							<th align="center">使用率</th>
							<th align="center">付费货币产出</th>
							<th align="center">占比1</th>
							<th align="center">付费货币消耗</th>
							<th align="center">占比2</th>
							<th align="center">主货币产出</th>
							<th align="center">占比3</th>
							<th align="center">主货币消耗</th>
							<th align="center">占比4</th>
							<th align="center">详情</th>
						</tr>
					</thead> 
					<tbody id="content">
						<?php 
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['function']."</td>";
								echo "<td align='center'>".$value['function_useuser']."</td>";
								echo "<td align='center'>".$value['function_usecount']."</td>";
								echo "<td align='center'>".round((floatval($value['function_userate'])*100),2)."%</td>";
								echo "<td align='center'>".$value['function_goldoutput']."</td>";
								echo "<td align='center'></td>";
								echo "<td align='center'>".$value['function_goldconsume']."</td>";
								echo "<td align='center'></td>";
								echo "<td align='center'>".$value['function_sliveroutput']."</td>";
								echo "<td align='center'></td>";
								echo "<td align='center'>".$value['function_sliverconsume']."</td>";
								echo "<td align='center'></td>";
								echo "<td align='center'><a name='".$value['function']."' class='view' href='javascript:;'>查看</a></td>";
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
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="13" style="height:500px;"><iframe src="<?php echo site_url() ?>/sysanaly/functionanalysis/charts?Name='+Name+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
});
var payGain = 0,payConsum = 0,mainGain = 0,mainConsum = 0;
$('.tablesorter tbody tr').each(function(){
	payGain += parseInt($(this).find('td').eq(4).text());
	payConsum += parseInt($(this).find('td').eq(6).text());
	mainGain += parseInt($(this).find('td').eq(8).text());
	mainConsum += parseInt($(this).find('td').eq(10).text());
});
$('.tablesorter tbody tr').each(function(){
	var _payGain = $(this).find('td').eq(4).text();
	var $_payGain = (_payGain/payGain*100).toFixed(2);
	if($_payGain == 'NaN'){
		$_payGain = 0;
	}
	$(this).find('td').eq(5).text($_payGain+'%');

	var _payConsum = $(this).find('td').eq(6).text();
	var $_payConsum = (_payConsum/payConsum*100).toFixed(2);
	if($_payConsum == 'NaN'){
		$_payConsum = 0;
	}
	$(this).find('td').eq(7).text($_payConsum+'%');

	var _mainGain = $(this).find('td').eq(8).text();
	var $_mainGain = (_mainGain/mainGain*100).toFixed(2);
	if($_mainGain == 'NaN'){
		$_mainGain = 0;
	}
	$(this).find('td').eq(9).text($_mainGain+'%');

	var _mainConsum = $(this).find('td').eq(10).text();
	var $_mainConsum = (_mainConsum/mainConsum*100).toFixed(2);
	if($_mainConsum == 'NaN'){
		$_mainConsum = 0;
	}
	$(this).find('td').eq(11).text($_mainConsum+'%');
});

</script>