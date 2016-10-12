<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
#container table #add-iframe td {padding: 10px; background: #eee;}
.echart {width: 100%; height: 300px;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('m_yongHuLTV_yao')?></h3>
		</header>
		<div id="container" class="module_content">
			<div class="show">
				<div class="echart"></div>
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr>
							<th align="center">日期</th>
							<th align="center">新增用户</th>
							<th align="center">1日</th>
							<th align="center">2日</th>
							<th align="center">3日</th>
							<th align="center">4日</th>
							<th align="center">5日</th>
							<th align="center">6日</th>
							<th align="center">7日</th>
							<th align="center">14日</th>
							<th align="center">30日</th>
							<th align="center">60日</th>
							<th align="center">90日</th>
							<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
						</tr>
					</thead>
					<tbody>
						<tr class="avg">
							<td align="center">AVG</td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
							<td align="center"></td>
						</tr>
						<?php
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['startdate_sk']."</td>";
								echo "<td align='center'>".$value['newuser']."</td>";
								echo "<td align='center'>".$value['day1']."</td>";
								echo "<td align='center'>".$value['day2']."</td>";
								echo "<td align='center'>".$value['day3']."</td>";
								echo "<td align='center'>".$value['day4']."</td>";
								echo "<td align='center'>".$value['day5']."</td>";
								echo "<td align='center'>".$value['day6']."</td>";
								echo "<td align='center'>".$value['day7']."</td>";
								echo "<td align='center'>".$value['day14']."</td>";
								echo "<td align='center'>".$value['day30']."</td>";
								echo "<td align='center'>".$value['day60']."</td>";
								echo "<td align='center'>".$value['day90']."</td>";
								echo "<td align='center'><a name='".$value['startdate_sk']."' class='view' href='javascript:;'>查看</a></td>";
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
	var date = $(this).attr('name');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="14" style="height:500px;"><iframe src="<?php echo site_url() ?>/channelanaly/userltv/usereventdetail?date='+date+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
});
$(document).ready(function(){
	var newuser = 0,
		day1 = 0,
		day2 = 0,
		day3 = 0,
		day4 = 0,
		day5 = 0,
		day6 = 0,
		day7 = 0,
		day14 = 0,
		day30 = 0,
		day60 = 0,
		day90 = 0;
	var all_num = $('.tablesorter tbody tr').not('.avg').length;
	$('.tablesorter tbody tr').not('.avg').each(function(){
		newuser += parseFloat($(this).find('td').eq(1).text());
		day1 += parseFloat($(this).find('td').eq(2).text());
		day2 += parseFloat($(this).find('td').eq(3).text());
		day3 += parseFloat($(this).find('td').eq(4).text());
		day4 += parseFloat($(this).find('td').eq(5).text());
		day5 += parseFloat($(this).find('td').eq(6).text());
		day6 += parseFloat($(this).find('td').eq(7).text());
		day7 += parseFloat($(this).find('td').eq(8).text());
		day14 += parseFloat($(this).find('td').eq(9).text());
		day30 += parseFloat($(this).find('td').eq(10).text());
		day60 += parseFloat($(this).find('td').eq(11).text());
		day90 += parseFloat($(this).find('td').eq(12).text());
	});
	var all_arr = new Array(
			parseFloat(newuser/all_num).toFixed(2),
			parseFloat(day1/all_num).toFixed(2),
			parseFloat(day2/all_num).toFixed(2),
			parseFloat(day3/all_num).toFixed(2),
			parseFloat(day4/all_num).toFixed(2),
			parseFloat(day5/all_num).toFixed(2),
			parseFloat(day6/all_num).toFixed(2),
			parseFloat(day7/all_num).toFixed(2),
			parseFloat(day14/all_num).toFixed(2),
			parseFloat(day30/all_num).toFixed(2),
			parseFloat(day60/all_num).toFixed(2),
			parseFloat(day90/all_num).toFixed(2)
		);

	$('.avg td').eq(1).text(all_arr[0]);
	$('.avg td').eq(2).text(all_arr[1]);
	$('.avg td').eq(3).text(all_arr[2]);
	$('.avg td').eq(4).text(all_arr[3]);
	$('.avg td').eq(5).text(all_arr[4]);
	$('.avg td').eq(6).text(all_arr[5]);
	$('.avg td').eq(7).text(all_arr[6]);
	$('.avg td').eq(8).text(all_arr[7]);
	$('.avg td').eq(9).text(all_arr[8]);
	$('.avg td').eq(10).text(all_arr[9]);
	$('.avg td').eq(11).text(all_arr[10]);
	$('.avg td').eq(12).text(all_arr[11]);
	var frame = '<iframe src="<?php echo site_url() ?>/channelanaly/userltv/charts?params='+all_arr.splice(1,11)+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>';
	$('.echart').html(frame);
	$('.avg td').each(function(){
		if($(this).text() == 'NaN'){
			$(this).text('-');
		}
	});
});
</script>