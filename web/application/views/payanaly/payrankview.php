<style>
table.tablesorter th,table.tablesorter td {text-indent: 0;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_payrank_analysis_yao')?></h3>
		</header>
		<table class="tablesorter" cellspacing="0">
			<thead> 
				<tr>
					<th align="center"><?php echo  lang('t_paiMing_yao')?></th>
					<th align="center"><?php echo  lang('t_zhangHao_yao')?></th>
					<th align="center"><?php echo  lang('t_dengJi_yao')?></th>
					<th align="center"><?php echo  lang('t_vipDengJi_yao')?></th>
					<th align="center"><?php echo  lang('t_zhuCeRiQi_yao')?></th>
					<th align="center"><?php echo  lang('t_shouFuRiQi_yao')?></th>
					<th align="center"><?php echo  lang('t_zongFuFei$_yao')?></th>
					<th align="center"><?php echo  lang('t_fuFeiCiShu_yao')?></th>
					<th align="center"><?php echo  lang('t_zaiXianTianShu_yao')?></th>
					<th align="center"><?php echo  lang('t_youXiShiChang_yao')?></th>
					<th align="center"><?php echo  lang('t_youXiCiShu_yao')?></th>
					<th align="center"><?php echo  lang('t_quShi_yao')?></th>
					<th align="center"><?php echo  lang('t_xiangQing_yao')?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($alluserrank as $value) {
						echo "<tr>";
						echo "<td align='center'>".$value['ranks']."</td>";
						echo "<td align='center'>".$value['account']."</td>";
						echo "<td align='center'>".$value['rolelevel']."</td>";
						echo "<td align='center'>".$value['roleviplevel']."</td>";
						echo "<td align='center'>".$value['registerdate']."</td>";
						echo "<td align='center'>".$value['firstpaydate']."</td>";
						echo "<td align='center'>".$value['totalpayamount']."</td>";
						echo "<td align='center'>".$value['paycount']."</td>";
						echo "<td align='center'>".$value['onlinedays']."</td>";
						echo "<td align='center'>".$value['gametime']."</td>";
						echo "<td align='center'>".$value['gamecount']."</td>";
						echo "<td align='center'>".$value['tag']."</td>";
						echo "<td align='center'><a href='javascript:;' id='user-".$value['roleId']."' class='userInfoDetail'>".lang('t_xiangQing_yao')."</a></td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table> 
	</article>
</section>
<script>
$(document).ready(function(){
	$('a.userInfoDetail').live('click',function(){
		$('#userdetail-frame').remove();
		var id = $(this).attr('id').replace('user-','');
		$(this).parents('tr').after("<tr id='userdetail-frame'><td colspan='13' style='height:500px; padding:10px; background:#ddd;'><iframe src='<?php echo site_url() ?>/payanaly/payrank/userInfoDetail?id="+id+"'  frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe></td></tr>");
	});
});
</script>