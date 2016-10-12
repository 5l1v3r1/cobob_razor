<style>
input.text,textarea {padding: 5px 10px; width: 80%;}
.submit_link a {cursor: pointer;}
.hide {display: none;}
input.uid {width: 60px;}
#search-btn {margin-left: 15px; height: 30px; line-height: 30px;}
.grey {margin-left: 10px; color: #999; line-height: 30px;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle">术语和定义编辑</h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>添加</a></li>
					<li><a>删除</a></li>
					<li><a>编辑</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="show">
				<table class="tablesorter" id="add-info" cellspacing="0">
					<tbody>
						<tr>
							<td width="80" align="right">序号</td>
							<td>
								<input type="text" class="text uid" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
								<span class="grey">PS:条目顺序，序号小的排前面</span>
							</td>
						</tr>
						<tr>
							<td align="right">术语定义分类</td>
							<td><input type="text" class="text classify" /></td>
						</tr>
						<tr>
							<td align="right">术语定义</td>
							<td><input type="text" class="text rules" /></td>
						</tr>
						<tr>
							<td align="right">缩写/公式</td>
							<td><input type="text" class="text formula" /></td>
						</tr>
						<tr>
							<td align="right">统计周期</td>
							<td><input type="text" class="text calculation" /></td>
						</tr>
						<tr>
							<td align="right">术语解释</td>
							<td><textarea rows="10" class="explain"></textarea></td>
						</tr>
						<tr>
							<td align="right"></td>
							<td><input type="submit" id="add-btn" value="添加" /></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="show hide">
				<table class="tablesorter" id="delete-info" cellspacing="0">
					<tbody>
						<tr>
							<td width="130" align="right">输入需删除的ID</td>
							<td><input type="text" class="text uid" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" /></td>
						</tr>
						<tr>
							<td align="right"></td>
							<td><input type="submit" id="delete-btn" value="删除" /></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="show hide">
				<table class="tablesorter" id="search-info" cellspacing="0">
					<tbody>
						<tr>
							<td width="130" align="right">输入需编辑的ID</td>
							<td>
								<input type="text" class="text uid" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
								<input type="submit" id="search-btn" value="查询" />
							</td>
						</tr>
					</tbody>
				</table>
				<table class="tablesorter hide" id="edit-info" cellspacing="0">
					<tbody>
						<tr>
							<td width="80" align="right">序号</td>
							<td>
								<input type="text" class="text uid" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
								<span class="grey">PS:条目顺序，序号小的排前面</span>
							</td>
						</tr>
						<tr>
							<td align="right">术语定义分类</td>
							<td><input type="text" class="text classify" /></td>
						</tr>
						<tr>
							<td align="right">术语定义</td>
							<td><input type="text" class="text rules" /></td>
						</tr>
						<tr>
							<td align="right">缩写/公式</td>
							<td><input type="text" class="text formula" /></td>
						</tr>
						<tr>
							<td align="right">统计周期</td>
							<td><input type="text" class="text calculation" /></td>
						</tr>
						<tr>
							<td align="right">术语解释</td>
							<td><textarea rows="10" class="explain"></textarea></td>
						</tr>
						<tr>
							<td align="right"></td>
							<td><input type="submit" id="edit-btn" value="编辑保存" /></td>
						</tr>
					</tbody>
				</table>
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
	});
	var locationUrl = "<?php echo site_url() ;?>/help/edit";
	var type = locationUrl.charAt(locationUrl.length-1,1);
	$('div.show').hide();
	$('.submit_link .tabs li').removeClass('active');
	if(type == 1){
		$('div.show').eq(0).show();
		$('.submit_link .tabs li').eq(0).addClass('active');
	}
	else if(type == 2){
		$('div.show').eq(1).show();
		$('.submit_link .tabs li').eq(1).addClass('active');
	}
	else if(type == 3){
		$('div.show').eq(2).show();
		$('.submit_link .tabs li').eq(2).addClass('active');
	}
	else{
		$('div.show').eq(0).show();
		$('.submit_link .tabs li').eq(0).addClass('active');
	}
	$('#add-btn').bind('click',function(){
		var uid = $('#add-info .uid').val();
		var classify = $('#add-info .classify').val();
		var rules = $('#add-info .rules').val();
		var formula = $('#add-info .formula').val();
		var calculation = $('#add-info .calculation').val();
		var explain = $('#add-info .explain').val();
		$.post('<?php echo site_url() ;?>/help/add',
			{
				uid:uid,
				classify:classify,
				rules:rules,
				formula:formula,
				calculation:calculation,
				explain:explain
			},
			function(res){
				var data = JSON.parse(res);
				if(data == 1){
					alert('添加成功');
					window.location.href = locationUrl + '?type=1';
				}
			}
		);
	});
	$('#delete-btn').bind('click',function(){
		var uid = $('#delete-info .uid').val();
		$.post('<?php echo site_url() ;?>/help/delete',
			{
				uid:uid
			},
			function(res){
				var data = JSON.parse(res);
				if(data == 1){
					alert('删除成功');
					window.location.href = locationUrl + '?type=2';
				}
			}
		);
	});
	$('#search-btn').bind('click',function(){
		var uid = $('#search-info .uid').val();
		$.post('<?php echo site_url() ;?>/help/search',
			{
				uid:uid
			},
			function(res){
				var data = JSON.parse(res);
				if(data.length > 0){
					$('#edit-info').fadeIn();
					$('#edit-info .uid').val(data[0]['helpitemid']);
					$('#edit-info .classify').val(data[0]['helptype']);
					$('#edit-info .rules').val(data[0]['helpitem']);
					$('#edit-info .formula').val(data[0]['equation']);
					$('#edit-info .calculation').val(data[0]['statisticalcycle']);
					$('#edit-info .explain').val(data[0]['explanationofnouns']);
				}
				else{
					alert('未找到对应ID');
				}
			}
		);
	});
	$('#edit-btn').bind('click',function(){
		var id = $('#search-info .uid').val();
		var uid = $('#edit-info .uid').val();
		var classify = $('#edit-info .classify').val();
		var rules = $('#edit-info .rules').val();
		var formula = $('#edit-info .formula').val();
		var calculation = $('#edit-info .calculation').val();
		var explain = $('#edit-info .explain').val();
		$.post('<?php echo site_url() ;?>/help/editInfo',
			{	
				id:id,
				uid:uid,
				classify:classify,
				rules:rules,
				formula:formula,
				calculation:calculation,
				explain:explain
			},
			function(res){
				var data = JSON.parse(res);
				if(data == 1){
					alert('保存成功');
					window.location.href = locationUrl + '?type=3';
				}
			}
		);
	});
});
</script>