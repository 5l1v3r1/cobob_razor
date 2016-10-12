<style>
table.tablesorter {margin: 0; width: 100%; border: 1px solid #ddd;}
table.tablesorter td {border: 1px solid #ddd;}
table.tablesorter th {text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background: #eee;}
.red {color: red;}
.green {color: green;}
</style>
<?php 
function calcution($text1,$text2){
	if(isset($text1) && isset($text2)){
		$a = intval($text1);
		$b = intval($text2);
		$res = ($b==0)?0:(($a-$b)/$b);
		if($res < 0){
			return "<span class='red'>↓ ".(round($res*100,2))."%</span>";
		}
		else if($res > 0){
			return "<span class='green'>↑ ".(abs(round($res*100,2)))."%</span>";
		}
		else{
			return "<span>".(round($res*100,2))."%</span>";
		}
	}
}
?>
<section id="main" class="column">
	<div style="height:340px;">
		<iframe src="<?php echo site_url() ?>/seeall/realtimeinfo/charts"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>		
	</div>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved">
				<?php echo lang('t_shiShiGaiKuang_yao')?>
				<span class="grey">(注:此统计项不满足渠道、区服、时间筛选要求）</span>
			</h3>
		</header>
		<div class="excel">
			<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
			<div>
				<table width="100%" border="0">
					<tr>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">新增设备<!-- （含重复） --></th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['deviceactivations_0'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['deviceactivations_1'] ?></td>
									<td><?php echo calcution($result['deviceactivations_0'],$result['deviceactivations_1']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['deviceactivations_7'] ?></td>
									<td><?php echo calcution($result['deviceactivations_0'],$result['deviceactivations_7']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['deviceactivations_30'] ?></td>
									<td><?php echo calcution($result['deviceactivations_0'],$result['deviceactivations_30']); ?></td>
								</tr>
							</table>
						</td>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">新增注册</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['newusers_0'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['newusers_1'] ?></td>
									<td><?php echo calcution($result['newusers_0'],$result['newusers_1']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['newusers_7'] ?></td>
									<td><?php echo calcution($result['newusers_0'],$result['newusers_7']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['newusers_30'] ?></td>
									<td><?php echo calcution($result['newusers_0'],$result['newusers_30']); ?></td>
								</tr>
							</table>
						</td>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">新增用户</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['newroles_0'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['newroles_1'] ?></td>
									<td><?php echo calcution($result['newroles_0'],$result['newroles_1']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['newroles_7'] ?></td>
									<td><?php echo calcution($result['newroles_0'],$result['newroles_7']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['newroles_30'] ?></td>
									<td><?php echo calcution($result['newroles_0'],$result['newroles_30']); ?></td>
								</tr>
							</table>
						</td>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">活跃用户</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['dauusers_0'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['dauusers_1'] ?></td>
									<td><?php echo calcution($result['dauusers_0'],$result['dauusers_1']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['dauusers_7'] ?></td>
									<td><?php echo calcution($result['dauusers_0'],$result['dauusers_7']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['dauusers_30'] ?></td>
									<td><?php echo calcution($result['dauusers_0'],$result['dauusers_30']); ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">付费人数</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['payusers_0_user'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['payusers_1_user'] ?></td>
									<td><?php echo calcution($result['payusers_0_user'],$result['payusers_1_user']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['payusers_7_user'] ?></td>
									<td><?php echo calcution($result['payusers_0_user'],$result['payusers_7_user']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['payusers_30_user'] ?></td>
									<td><?php echo calcution($result['payusers_0_user'],$result['payusers_30_user']); ?></td>
								</tr>
							</table>
						</td>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">付费金额</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['payusers_0_amount'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['payusers_1_amount'] ?></td>
									<td><?php echo calcution($result['payusers_0_amount'],$result['payusers_1_amount']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['payusers_7_amount'] ?></td>
									<td><?php echo calcution($result['payusers_0_amount'],$result['payusers_7_amount']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['payusers_30_amount'] ?></td>
									<td><?php echo calcution($result['payusers_0_amount'],$result['payusers_30_amount']); ?></td>
								</tr>
							</table>
						</td>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">付费次数</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['payusers_0_count'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['payusers_1_count'] ?></td>
									<td><?php echo calcution($result['payusers_0_count'],$result['payusers_1_count']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['payusers_7_count'] ?></td>
									<td><?php echo calcution($result['payusers_0_count'],$result['payusers_7_count']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['payusers_30_count'] ?></td>
									<td><?php echo calcution($result['payusers_0_count'],$result['payusers_30_count']); ?></td>
								</tr>
							</table>
						</td>
						<td width="25%">
							<table class="tablesorter" cellspacing="0"> 
								<tr>
									<th colspan="3">最高在线</th>
								</tr>
								<tr>
									<th colspan="3"><?php echo $result['maxonlineusers_0'] ?></th>
								</tr>
								<tr>
									<td>昨日：</td>
									<td><?php echo $result['maxonlineusers_1'] ?></td>
									<td><?php echo calcution($result['maxonlineusers_0'],$result['maxonlineusers_1']); ?></td>
								</tr>
								<tr>
									<td>7日前：</td>
									<td><?php echo $result['maxonlineusers_7'] ?></td>
									<td><?php echo calcution($result['maxonlineusers_0'],$result['maxonlineusers_7']); ?></td>
								</tr>
								<tr>
									<td>30日前：</td>
									<td><?php echo $result['maxonlineusers_30'] ?></td>
									<td><?php echo calcution($result['maxonlineusers_0'],$result['maxonlineusers_30']); ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</article>
</section>
<script>
var date = "<?php echo date('Y-m-d') ?>";
// $('.select_option').html('当天时期:'+date);
$('.select_option').html('今日');
$('.select_option').css({'text-align':'center','line-height':'21px'});
</script>