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
			<!-- 流失统计  -->
			<h3 class="h3_fontstyle"><?php echo  lang('t_losecount_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<!-- 所有用户 -->
					<li class="active" id="alluser"><a><?php echo  lang('tag_all_user')?></a></li>
					<!-- 付费用户 -->
					<li id="payuser"><a><?php echo  lang('t_payUser_yao')?></a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show-1">
				<div class="echarts top"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_7RiLiuShiLv_yao')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_14RiLiuShiLv_yao')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_30RiLiuShiLv_yao')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_xiangQing_yao')?></th>
									<th align="center"><?php echo  lang('t_7RiHuiLiuShu_yao')?></th>
									<th align="center"><?php echo  lang('t_14RiHuiLiuShu_yao')?></th>
									<th align="center"><?php echo  lang('t_30RiHuiLiuShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($alluser as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date_sk']."</td>";
										echo "<td align='center' bgcolor='#e0e0e0'>".(floatval($value['sevenleaverate'])*100)."%</td>";
										echo "<td align='center' bgcolor='#e0e0e0'>".(floatval($value['fourteenleaverate'])*100)."%</td>";
										echo "<td align='center' bgcolor='#e0e0e0'>".(floatval($value['thirtyleaverate'])*100)."%</td>";
										echo "<td align='center' bgcolor='#e0e0e0'><a href='javascript:;' id='".$value['date_sk']."' class='userInfoDetail'>".lang('v_rpt_err_view')."</a></td>";
										echo "<td align='center'>".(floatval($value['sevenreturnrate'])*100)."%</td>";
										echo "<td align='center'>".(floatval($value['fourteenreturnrate'])*100)."%</td>";
										echo "<td align='center'>".(floatval($value['thirtyreturnrate'])*100)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
			</div>
			<div class="show-2">
				<div class="echarts top"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0">
							<thead> 
								<tr>
									<th align="center"><?php echo  lang('g_date')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_7RiLiuShiLv_yao')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_14RiLiuShiLv_yao')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_30RiLiuShiLv_yao')?></th>
									<th align="center" bgcolor="#e0e0e0"><?php echo  lang('t_xiangQing_yao')?></th>
									<th align="center"><?php echo  lang('t_7RiHuiLiuShu_yao')?></th>
									<th align="center"><?php echo  lang('t_14RiHuiLiuShu_yao')?></th>
									<th align="center"><?php echo  lang('t_30RiHuiLiuShu_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($payuser as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['date_sk']."</td>";
										echo "<td align='center' bgcolor='#e0e0e0'>".(floatval($value['sevenleaverate'])*100)."%</td>";
										echo "<td align='center' bgcolor='#e0e0e0'>".(floatval($value['fourteenleaverate'])*100)."%</td>";
										echo "<td align='center' bgcolor='#e0e0e0'>".(floatval($value['thirtyleaverate'])*100)."%</td>";
										echo "<td align='center' bgcolor='#e0e0e0'><a href='javascript:;' id='".$value['date_sk']."' class='userInfoDetail'>".lang('v_rpt_err_view')."</a></td>";
										echo "<td align='center'>".(floatval($value['sevenreturnrate'])*100)."%</td>";
										echo "<td align='center'>".(floatval($value['fourteenreturnrate'])*100)."%</td>";
										echo "<td align='center'>".(floatval($value['thirtyreturnrate'])*100)."%</td>";
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
	$('a.userInfoDetail').live('click',function(){
		$('#userdetail-frame').remove();
		if($(this).text() == '查看'){
			$(this).text('关闭');
			var id = $(this).attr('id');
			var tag = $('.submit_link li.active').attr('id');
			var usertag = (tag == 'alluser')?'alluser':'payuser';
			$(this).parents('tr').after("<tr id='userdetail-frame'><td colspan='13' style='height:400px; padding:10px; background:#e0e0e0;'><iframe src='<?php echo site_url() ?>/leaveanaly/leavecount/showDetail?date="+id+"&usertag="+usertag+"'  frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe></td></tr>");
		}
		else{
			$(this).text('查看');
		}
		$('a.userInfoDetail').not($(this)).text('查看');
	});
	$('#main .submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		$('#container > div').hide();
		$('#container > div').eq(current).show();
		$('.echarts iframe').remove();
		$('.echarts.top').eq(current).html($arr[current]);
	});
	var $arr = new Array();
	$arr[0] = "<iframe src='<?php echo site_url() ?>/leaveanaly/leavecount/charts?type=all_user' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[1] = "<iframe src='<?php echo site_url() ?>/leaveanaly/leavecount/charts?type=pay_user' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$('.echarts.top').eq(0).html($arr[0]);
});
</script>
