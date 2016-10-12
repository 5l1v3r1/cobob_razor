<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 360px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<!-- 付费间隔  -->
			<h3 class="h3_fontstyle"><?php echo  lang('t_firstpay_analysis_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<!-- 首付周期 -->
					<li class="active"><a><?php echo  lang('t_firstPayTime_yao')?></a></li>
					<!-- 首付等级 -->
					<li><a><?php echo  lang('t_firstPayTimeLevel_yao')?></a></li>
					<!-- 首付金额 -->
					<li><a><?php echo  lang('t_firstPayTimeAcount_yao')?></a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show-1">
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('t_firstPayTime_yao')?></th>
									<th align="center" width="33%"><?php echo  lang('t_payUserNum_yao')?></th>
									<th align="center" width="33%"><?php echo  lang('t_baiFenBi_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($firstpayTime as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['firstpayname']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "<td align='center'>".round((floatval($value['payusersrate'])*100),2)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="show-2">
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('t_firstPayTimeLevel_yao')?></th>
									<th align="center" width="33%"><?php echo  lang('t_payUserNum_yao')?></th>
									<th align="center" width="33%"><?php echo  lang('t_baiFenBi_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($firstpayLevel as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['firstpaylevel']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "<td align='center'>".round((floatval($value['payusersrate'])*100),2)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="show-3">
				<div class="echarts"></div>
				<div class="excel">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center" width="33%"><?php echo  lang('t_firstPayTimeAcount_yao')?></th>
									<th align="center" width="33%"><?php echo  lang('t_payUserNum_yao')?></th>
									<th align="center" width="33%"><?php echo  lang('t_baiFenBi_yao')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($firstpayAmount as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['firstpayamount']."</td>";
										echo "<td align='center'>".$value['payusers']."</td>";
										echo "<td align='center'>".round((floatval($value['payusersrate'])*100),2)."%</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
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
	$arr[0] = "<iframe src='<?php echo site_url() ?>/payanaly/firstpayanaly/echarts?type=time' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[1] = "<iframe src='<?php echo site_url() ?>/payanaly/firstpayanaly/echarts?type=level' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$arr[2] = "<iframe src='<?php echo site_url() ?>/payanaly/firstpayanaly/echarts?type=amount' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
	$('.echarts').eq(0).html($arr[0]);
});
</script>
