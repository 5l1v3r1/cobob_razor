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
			<h3 class="h3_fontstyle"><?php echo  lang('m_xinZengChuangJue_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>创角率</a></li>
					<li><a>滚服用户创角</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="echarts">
				<iframe src='<?php echo site_url() ?>/useranalysis/newcreaterole/echarts?type=0' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>
			</div>
			<div class="excel">
				<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
				<div>
					<table class="tablesorter" cellspacing="0">
						<thead> 
							<tr>
								<th align="center">日期</th>
								<th align="center">新增注册</th>
								<th align="center">新增用户</th>
								<th align="center">创角率</th>
								<th align="center">新增创角</th>
								<th align="center">滚服用户创角</th>
								<th align="center">滚服用户占比</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($result as $value) {
									echo "<tr>";
									echo "<td align='center'>".$value['startdate_sk']."</td>";
									echo "<td align='center'>".$value['newregister']."</td>";
									echo "<td align='center'>".$value['newuser']."</td>";
									echo "<td align='center'>".$value['newcreaterolerate']."%</td>";
									echo "<td align='center'>".$value['newcreaterole']."</td>";
									echo "<td align='center'>".$value['mixcreaterole']."</td>";
									echo "<td align='center'>".$value['mixcreaterolerate']."%</td>";
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
		var frame = "<iframe src='<?php echo site_url() ?>/useranalysis/newcreaterole/echarts?type="+current+"' frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe>";
		$('.echarts').html(frame);
	});
});
</script>
