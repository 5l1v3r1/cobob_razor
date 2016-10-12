<style>
table.tablesorter td,table.tablesorter th {text-indent: 0; text-align: center;}
</style>
<section id="main" class="column">
	<!-- 新增用户-图表 -->
	<div style="height:340px;">
	<iframe src="<?php echo site_url() ?>/useranalysis/newusers/addnewusersreport"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>		
	</div>
	<!-- 新增用户-数据列表	 -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved">
				<?php echo  lang('t_datalist_yao')?>
				<span class="grey">(注:此统计项不满足区服筛选要求）</span>
			</h3>
				<!-- 导出CSV -->
				<span class="relative r">
					<a href="<?php echo site_url(); ?>/useranalysis/newusers/exportdetaildata" class="bottun4 hover" >
						<font><?php echo  lang('g_exportToCSV');?></font>
					</a>
				</span>					
		</header>
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
					<!-- 日期 -->
					<th><?php echo  lang('g_date')?></th>
					<!-- 设备激活 -->
					<th><?php echo  lang('t_deviceActivation_yao')?></th> 
					<!-- 新增注册 -->
					<th><?php echo  lang('t_newUser')?></th> 
					<!-- 新增设备 -->
					<th><?php echo  lang('t_newDevice_yao')?></th> 
					<!-- 注册转化率 -->
					<th><?php echo  lang('t_registerConversion_yao')?></th>
				</tr> 
			</thead> 
			<tbody id="content">
	    		<?php
	    			foreach ($newUsersData as $value) {
	    				echo "<tr>";
	    				echo "<td>".$value['date']."</td>";
	    				echo "<td>".$value['deviceactivations']."</td>";
	    				echo "<td>".$value['newusers']."</td>";
	    				echo "<td>".$value['newdevices']."</td>";
	    				echo "<td>".round((intval($value['userconversion'])*100),2)."%</td>";
	    				echo "</tr>";
	    			}
	    		?>
			</tbody>
		</table> 
		<footer><div class="submit_link hasPagination"></div></footer>
	</article>

</section>