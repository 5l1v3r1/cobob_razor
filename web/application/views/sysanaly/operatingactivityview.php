<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
#container table #add-iframe td {padding: 10px; background: #eee;}
#filter {background: #eee;}
#filter span {padding: 10px 0px 10px 15px; float: left; height: 30px; line-height: 30px;}
.show {display: block!important;}
#filter select {padding: 5px;}
#filter span.f {margin-left: -10px;}
</style>
<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('m_yunYingHuoDong_yao')?></h3>
		</header>
		<div id="container" class="module_content">
			<div id="filter">
				<span><b>筛选：</b>活动名</span>
				<span>
					<select id="filter-select">
						<option>全部</option>
						<?php 
							foreach ($select as $value) {
								echo "<option>".$value['activity_name']."</option>";
							}
						?>
					</select>
				</span>
				<span class="f"><a href="#" class="search-btn bottun4 hover"><font>确定</font></a></span>
			</div>
			<div class="show">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr>
							<th align="center">活动期号</th>
							<th align="center">活动名</th>
							<th align="center">开始时间</th>
							<th align="center">结束时间</th>
							<th align="center">有效用户数</th>
							<th align="center">参与人数</th>
							<th align="center">参与率</th>
							<th align="center">付费货币消耗</th>
							<th align="center">人均消耗</th>
							<th align="center"><?php echo lang('t_xiangQing_yao') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($result as $value) {
								echo "<tr>";
								echo "<td align='center'>".$value['activity_issue']."</td>";
								echo "<td align='center'>".$value['activity_name']."</td>";
								echo "<td align='center'>".$value['startdate']."</td>";
								echo "<td align='center'>".$value['enddate']."</td>";
								echo "<td align='center'>".$value['validuser']."</td>";
								echo "<td align='center'>".$value['joinuser']."</td>";
								echo "<td align='center'>".$value['joinrate']."%</td>";
								echo "<td align='center'>".$value['coinconsume']."</td>";
								echo "<td align='center'>".$value['useravgconsume']."</td>";
								echo "<td align='center'><a title=".$value['activity_issue']." class='view' href='javascript:;'>查看</a></td>";
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
$('a.view').live('click',function(){
	$('#add-iframe').remove();
	var activity_issue = $(this).attr('title');
	$(this).parents('tr').after('<tr id="add-iframe"><td colspan="11" style="height:600px;"><iframe src="<?php echo site_url() ?>/sysanaly/operatingactivity/charts?activity_issue='+activity_issue+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe></td></tr>');
})
$(document).ready(function(){
	$('.search-btn').bind('click',function(){
		var name = $('#filter-select option:selected').text();
		$.get("<?php echo site_url() ?>/sysanaly/operatingactivity/filter?name="+name,function(res){
			var data = JSON.parse(res);
			$('table.tablesorter tbody tr').remove();
			var html = "";
			for(var i=0;i<data.length;i++){
				html += "<tr>";
				html += "<td align='center'>"+data[i]['activity_issue']+"</td>";
				html += "<td align='center'>"+data[i]['activity_name']+"</td>";
				html += "<td align='center'>"+data[i]['startdate']+"</td>";
				html += "<td align='center'>"+data[i]['enddate']+"</td>";
				html += "<td align='center'>"+data[i]['validuser']+"</td>";
				html += "<td align='center'>"+data[i]['joinuser']+"</td>";
				html += "<td align='center'>"+data[i]['joinrate']+"%</td>";
				html += "<td align='center'>"+data[i]['coinconsume']+"</td>";
				html += "<td align='center'>"+data[i]['useravgconsume']+"</td>";
				html += "<td align='center'><a title="+data[i]['activity_issue']+" class='view' href='javascript:;'>查看</a></td>";
				html += "</tr>";
			}
			$('table.tablesorter tbody').html(html);
		});
	});
});
</script>