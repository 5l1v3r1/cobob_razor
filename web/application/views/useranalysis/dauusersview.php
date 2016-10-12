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
			<h3 class="h3_fontstyle"><?php echo  lang('t_dauUser_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<!-- 日活跃用户数 -->
					<li class="active" id="dau"><a>DAU</a></li>
					<!-- 日/周/月 跃用户数 -->
					<li id="d_w_mau"><a>D,W,MAU</a></li>
					<!-- 用户活跃率 -->
					<li id="dau_mau"><a>DAU/MAU</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div>
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center"><?php echo  lang('m_xinZengYongHu_yao')?></th>
									<th align="center"><?php echo  lang('t_fuFeiYongHu_yao')?></th>
									<th align="center"><?php echo  lang('t_notPayUser_yao')?></th>
									<th align="center">DAU</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($dauUsersData as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date']."</td>";
										echo "<td align='center'>".$value['newuser']."</td>";
										echo "<td align='center'>".$value['payuser']."</td>";
										echo "<td align='center'>".$value['notpayuser']."</td>";
										echo "<td align='center'>".$value['daydau']."</td>";
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
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center">DAU</th>
									<th align="center">WAU</th>
									<th align="center">MAU</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($dauUsersData as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date']."</td>";
										echo "<td align='center'>".$value['daydau']."</td>";
										echo "<td align='center'>".$value['weekdau']."</td>";
										echo "<td align='center'>".$value['monthdau']."</td>";
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
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center">DAU/MAU</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($dauUsersData as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date']."</td>";
										echo "<td align='center'>".round($value['useractiverate'],2)."</td>";
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
	$('#main .submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		$('#container > div').hide();
		$('#container > div').eq(current).show();
		$('.echarts iframe').remove();
		$('.echarts').eq(current).html($arr[current]);
	});
	var $arr = new Array();
	$arr[0] = "<iframe src='<?php echo site_url() ?>/useranalysis/dauusers/adddauusersreport?type=dau' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[1] = "<iframe src='<?php echo site_url() ?>/useranalysis/dauusers/adddauusersreport?type=d_w_mau' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[2] = "<iframe src='<?php echo site_url() ?>/useranalysis/dauusers/adddauusersreport?type=dau_mau' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$('.echarts').eq(0).html($arr[0]);
});
</script>
