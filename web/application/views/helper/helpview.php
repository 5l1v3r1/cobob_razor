<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle">术语和定义 <a href="<?php echo site_url() ;?>/help/edit">(编辑)</a></h3>
		</header>
		<div id="container" class="module_content">
			<table class="tablesorter" cellspacing="0">
				<thead>
					<tr>
						<th width="5%">ID</th>
						<th width="5%">序号</th>
						<th width="10%">术语定义分类</th>
						<th width="10%">术语定义</th>
						<th width="10%">缩写/公式</th>
						<th width="10%">统计周期</th>
						<th width="50%">术语解释</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($result as $value) {
							echo "<tr>";
							echo "<td>".$value['id']."</td>";
							echo "<td>".$value['helpitemid']."</td>";
							echo "<td>".$value['helptype']."</td>";
							echo "<td>".$value['helpitem']."</td>";
							echo "<td>".$value['equation']."</td>";
							echo "<td>".$value['statisticalcycle']."</td>";
							echo "<td>".$value['explanationofnouns']."</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</article>
</section>