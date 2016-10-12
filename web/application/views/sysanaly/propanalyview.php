<style>
.width_full {overflow: hidden;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 320px;}
.hide {display: none;}
.sub-tabs {overflow: hidden; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;}
.sub-tabs li {float: left; list-style: none; margin-left: 20px; padding: 2px 10px; cursor: pointer; border-radius: 5px;}
.sub-tabs li.active {background: #333; color: #fff;}
.prop_filter {height: 24px; padding: 10px 20px; line-height: 24px; position: absolute; left: 0; top: 0;}
.prop_filter select {padding: 2px; margin-left: 5px;}
.show {position: relative; zoom: 1;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('m_daoJuFenXi_yao')?></h3>
		</header>
		<div id="container" class="module_content">
			<div class="sub-tabs">
				<li class="active">新增用户</li>
				<li>活跃用户</li>
				<li>付费用户</li>
			</div>
			<div class="show">
				<span class="prop_filter">
					道具类型 : 
					<select id="prop_filter">
						<option>全部</option>
						<?php 
							foreach ($propClassify as $value) {
								if(isset($propType) && $propType == $value['prop_type']){
									echo "<option selected>".$value['prop_type']."</option>";
								}
								else{
									echo "<option>".$value['prop_type']."</option>";
								}
								
							}
						?>
					</select>
				</span>
				<div class="excel newuser">
					<p class="import-excel">
						<a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a>
					</p>
					<div>
						<table class="tablesorter newuser" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center">道具名称</th>
									<th align="center">道具类型</th>
									<th align="center">商城购买</th>
									<th align="center">购买占比</th>
									<th align="center">系统赠送</th>
									<th align="center">赠送占比</th>
									<th align="center">功能产出</th>
									<th align="center">功能占比</th>
									<th align="center">活动产出</th>
									<th align="center">活动占比</th>
									<th align="center">总产出</th>
									<th align="center">总消耗</th>
									<th align="center">消耗产出比</th>
									<th align="center">详情</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result1 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['prop_name']."</td>";
										echo "<td align='center'>".$value['prop_type']."</td>";
										echo "<td align='center'>".$value['shopbuy']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['systemdonate']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['functiongaincount']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['activitygaincount']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['totalgaincount']."</td>";
										echo "<td align='center'>".$value['totalconsumecount']."</td>";
										echo "<td align='center'>".$value['totalrate']."</td>";
										echo "<td align='center'><a href='javascript:;' title='".$value['prop_name']."' alt='newuser' class='infoDetail'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
				<div class="excel duauser hide">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter duauser" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center">道具名称</th>
									<th align="center">道具类型</th>
									<th align="center">商城购买</th>
									<th align="center">购买占比</th>
									<th align="center">系统赠送</th>
									<th align="center">赠送占比</th>
									<th align="center">功能产出</th>
									<th align="center">功能占比</th>
									<th align="center">活动产出</th>
									<th align="center">活动占比</th>
									<th align="center">总产出</th>
									<th align="center">总消耗</th>
									<th align="center">消耗产出比</th>
									<th align="center">详情</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result2 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['prop_name']."</td>";
										echo "<td align='center'>".$value['prop_type']."</td>";
										echo "<td align='center'>".$value['shopbuy']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['systemdonate']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['functiongaincount']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['activitygaincount']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['totalgaincount']."</td>";
										echo "<td align='center'>".$value['totalconsumecount']."</td>";
										echo "<td align='center'>".$value['totalrate']."%</td>";
										echo "<td align='center'><a href='javascript:;' title='".$value['prop_name']."' alt='dauuser' class='infoDetail'>查看</a></td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
						<footer><div class="submit_link hasPagination"></div></footer>
					</div>
				</div>
				<div class="excel payuser hide">
					<p class="import-excel"><a class="import bottun4 hover" href="javascript:;"><font>导出EXCEL</font></a></p>
					<div>
						<table class="tablesorter payuser" cellspacing="0"> 
							<thead> 
								<tr>
									<th align="center">道具名称</th>
									<th align="center">道具类型</th>
									<th align="center">商城购买</th>
									<th align="center">购买占比</th>
									<th align="center">系统赠送</th>
									<th align="center">赠送占比</th>
									<th align="center">功能产出</th>
									<th align="center">功能占比</th>
									<th align="center">活动产出</th>
									<th align="center">活动占比</th>
									<th align="center">总产出</th>
									<th align="center">总消耗</th>
									<th align="center">消耗产出比</th>
									<th align="center">详情</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($result3 as $value) {
										echo "<tr>";
										echo "<td align='center'>".$value['prop_name']."</td>";
										echo "<td align='center'>".$value['prop_type']."</td>";
										echo "<td align='center'>".$value['shopbuy']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['systemdonate']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['functiongaincount']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['activitygaincount']."</td>";
										echo "<td align='center'></td>";
										echo "<td align='center'>".$value['totalgaincount']."</td>";
										echo "<td align='center'>".$value['totalconsumecount']."</td>";
										echo "<td align='center'>".$value['totalrate']."%</td>";
										echo "<td align='center'><a href='javascript:;' title='".$value['prop_name']."' alt='payuser' class='infoDetail'>查看</a></td>";
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
	$('.sub-tabs li').bind('click',function(){
		$('.sub-tabs li').removeClass('active');
		$(this).addClass('active');
		var current = $(this).index();
		$('div.excel').hide();
		$('div.excel').eq(current).show();
	});
	function rate(ele){
		var shopbuy = 0,systemdonate = 0,functiongaincount = 0,activitygaincount = 0;
		$(ele).each(function(){
			var shopbuyNum = parseInt($(this).find('td').eq(2).text());
			var systemdonateNum = parseInt($(this).find('td').eq(4).text());
			var functiongaincountNum = parseInt($(this).find('td').eq(6).text());
			var activitygaincountNum = parseInt($(this).find('td').eq(8).text());
			shopbuy += shopbuyNum;
			systemdonate += systemdonateNum;
			functiongaincount += functiongaincountNum;
			activitygaincount += activitygaincountNum;
		});
		$(ele).each(function(){
			var shopbuyNum = parseInt($(this).find('td').eq(2).text());
			var systemdonateNum = parseInt($(this).find('td').eq(4).text());
			var functiongaincountNum = parseInt($(this).find('td').eq(6).text());
			var activitygaincountNum = parseInt($(this).find('td').eq(8).text());
			var a = (shopbuyNum/shopbuy*100).toFixed(2);
			var b = (systemdonateNum/systemdonate*100).toFixed(2);
			var c = (functiongaincountNum/functiongaincount*100).toFixed(2);
			var d = (activitygaincountNum/activitygaincount*100).toFixed(2);
			if(a == 'NaN'){
				a = 0;
			}
			if(b == 'NaN'){
				b = 0;
			}
			if(c == 'NaN'){
				c = 0;
			}
			if(d == 'NaN'){
				d = 0;
			}
			$(this).find('td').eq(3).text(a+'%');
			$(this).find('td').eq(5).text(b+'%');
			$(this).find('td').eq(7).text(c+'%');
			$(this).find('td').eq(9).text(d+'%');
		});
	}
	rate('table.newuser tbody tr');
	rate('table.duauser tbody tr');
	rate('table.payuser tbody tr');
	$('a.infoDetail').live('click',function(){
		$('#userdetail-frame').remove();
		if($(this).text() == '查看'){
			$(this).text('关闭');
			var date = $(this).attr('title');
			var type = $(this).attr('alt');
			$(this).parents('tr').after("<tr id='userdetail-frame'><td colspan='14' style='height:800px; padding:10px; background:#ddd;'><iframe src='<?php echo site_url() ?>/sysanaly/propanaly/charts?date="+date+"&type="+type+"'  frameborder='0' scrolling='no' style='width:100%;height:100%;'></iframe></td></tr>");
		}
		else{
			$(this).text('查看');
		}
		$('a.infoDetail').not($(this)).text('查看');
	});
	$('#prop_filter').change(function(){
		var local = '<?php echo site_url() ?>';
		var val = $(this).find('option:selected').text();
		var current = $('.sub-tabs li.active').index();
		window.location.href = local+'/sysanaly/propanaly/index?propType='+val+'&current='+current;
	});
	var localHref = window.location.href;
	localHref = localHref.split('&');
	var current = localHref.pop();
	current = parseInt(current.replace('current=',''))
	if(!isNaN(current)){
		$('.sub-tabs li').removeClass('active');
		$('.sub-tabs li').eq(current).addClass('active');
		$('div.excel').hide();
		$('div.excel').eq(current).show();
	}
});
</script>
