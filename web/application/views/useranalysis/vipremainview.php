<style>
table.tablesorter td {padding: 15px 0;}
table.tablesorter thead tr {text-indent: 0;}
#filter {background: #eee; margin: 5px; overflow: hidden;}
#filter span {padding: 10px 0px 10px 15px; float: left; height: 30px; line-height: 30px;}
.show {display: block!important;}
#filter select {padding: 5px;}
#filter span.f {margin-left: -10px;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('m_vipLiuCun_yao')?></h3>
		</header>
		<div id="filter">
			<span><b>筛选：</b>VIP等级</span>
			<span>
				<select id="filter-select">
					<!-- <option value="-1">全部</option> -->
					<?php 
						foreach ($level as $value) {
							echo "<option value='".$value['viplevel']."'>VIP ".$value['viplevel']."</option>";
						}
					?>
				</select>
			</span>
			<span class="f"><a href="#" class="search-btn bottun4 hover"><font>确定</font></a></span>
		</div>
		<div id="echarts-container" style="height:250px;">
			
		</div>
		<div class="tab_container">
			<div class="tab_content">
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead>
								<tr>
									<th align="center">新增日期</th>
									<th align="center">用户数</th>
									<th align="center">次日</th>
									<th align="center">2日</th>
									<th align="center">3日</th>
									<th align="center">4日</th>
									<th align="center">5日</th>
									<th align="center">6日</th>
									<th align="center">7日</th>
									<th align="center">14日</th>
									<th align="center">30日</th>
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
								</tr>
								<?php
									foreach ($result as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['startdate_sk']."</td>";
										echo "<td align='center'>".$value['usercount']."</td>";
										echo "<td align='center'>".$value['day1']."%</td>";
										echo "<td align='center'>".$value['day2']."%</td>";
										echo "<td align='center'>".$value['day3']."%</td>";
										echo "<td align='center'>".$value['day4']."%</td>";
										echo "<td align='center'>".$value['day5']."%</td>";
										echo "<td align='center'>".$value['day6']."%</td>";
										echo "<td align='center'>".$value['day7']."%</td>";
										echo "<td align='center'>".$value['day14']."%</td>";
										echo "<td align='center'>".$value['day30']."%</td>";
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
function exchange(){
	var usercount=0,day1=0,day2=0,day3=0,day4=0,day5=0,day6=0,day7=0,day14=0,day30=0,n=0;
	var avgArr = new Array();
	$('.tablesorter tbody tr').not('.avg').each(function(){
		n++;
		var key = $(this);
		usercount += parseFloat(key.find('td').eq(1).text());
		day1 += parseFloat(key.find('td').eq(2).text().replace('%',''));
		day2 += parseFloat(key.find('td').eq(3).text().replace('%',''));
		day3 += parseFloat(key.find('td').eq(4).text().replace('%',''));
		day4 += parseFloat(key.find('td').eq(5).text().replace('%',''));
		day5 += parseFloat(key.find('td').eq(6).text().replace('%',''));
		day6 += parseFloat(key.find('td').eq(7).text().replace('%',''));
		day7 += parseFloat(key.find('td').eq(8).text().replace('%',''));
		day14 += parseFloat(key.find('td').eq(9).text().replace('%',''));
		day30 += parseFloat(key.find('td').eq(10).text().replace('%',''));
	});
	$('tr.avg td').eq(1).text((usercount/n).toFixed(2));
	$('tr.avg td').eq(2).text(day1/n+'%');
	$('tr.avg td').eq(3).text(day2/n+'%');
	$('tr.avg td').eq(4).text(day3/n+'%');
	$('tr.avg td').eq(5).text(day4/n+'%');
	$('tr.avg td').eq(6).text(day5/n+'%');
	$('tr.avg td').eq(7).text(day6/n+'%');
	$('tr.avg td').eq(8).text(day7/n+'%');
	$('tr.avg td').eq(9).text(day14/n+'%');
	$('tr.avg td').eq(10).text(day30/n+'%');

	avgArr.push(day1/n);
	avgArr.push(day2/n);
	avgArr.push(day3/n);
	avgArr.push(day4/n);
	avgArr.push(day5/n);
	avgArr.push(day6/n);
	avgArr.push(day7/n);
	avgArr.push(day14/n);
	avgArr.push(day30/n);

	var frame = '<iframe src="<?php echo site_url() ?>/useranalysis/vipremain/charts?avg='+avgArr+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>';
	$('#echarts-container').html(frame);
}
exchange();
$('.search-btn').bind('click',function(){
	var value = $('#filter-select').val();
	$.get("<?php echo site_url() ?>/useranalysis/vipremain/filter?value="+value,function(res){
		var data = JSON.parse(res);
		console.log(data);
		var html = '';
		for(var i=0,r=data.length;i<r;i++){
			html += "<tr>";
			html += "<td align='center'>"+data[i]['startdate_sk']+"</td>";
			html += "<td align='center'>"+data[i]['usercount']+"</td>";
			html += "<td align='center'>"+data[i]['day1']+"%</td>";
			html += "<td align='center'>"+data[i]['day2']+"%</td>";
			html += "<td align='center'>"+data[i]['day3']+"%</td>";
			html += "<td align='center'>"+data[i]['day4']+"%</td>";
			html += "<td align='center'>"+data[i]['day5']+"%</td>";
			html += "<td align='center'>"+data[i]['day6']+"%</td>";
			html += "<td align='center'>"+data[i]['day7']+"%</td>";
			html += "<td align='center'>"+data[i]['day14']+"%</td>";
			html += "<td align='center'>"+data[i]['day30']+"%</td>";
			html += "</tr>";
		}
		$('.tablesorter tbody tr').not('.avg').remove();
		$('.tablesorter tbody tr.avg').after(html);
		exchange();
	});
});
</script>