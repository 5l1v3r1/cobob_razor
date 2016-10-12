<section id="main" class="column">

<h4 class="alert_info" id='msg' style="display: none;"></h4> 

<article class="module width_full">
<header>
<h3 class="tabs_involved">权限组 : <?php echo $rolename; ?></h3>
</header>
<div class="tab_container">
	<div id="tab1" class="tab_content">
	<table class="tablesorter" cellspacing="0">
		<thead>
			<tr>
				<!-- <th align="center">ID</th> -->
				<!-- <th align="center">分类</th> -->
				<th align="center">模块名</th>
				<th align="center">描述</th>
				<th align="center">访问权限</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				if(isset($guest_roleid) && $guest_roleid == 3){
					foreach ($resources as $value) {
						echo "<tr>";
						// echo "<td align='center'>".$value->id."</td>";
						// echo "<td align='center'>".$value->classifname."</td>";
						echo "<td align='center'>".$value->name."</td>";
						echo "<td align='center'>".$value->description."</td>";
						$isset = false;
						foreach ($permissions as $key) {
							if($key['source'] == $value->id && $key['read'] == '1'){
								$isset = true;
							}
						}
						if($isset == false){
							echo "<td align='center'><input type='checkbox' id=".$value->id." roleid=".$roleid." class='change_jurisdiction'></td>";
						}
						else{
							echo "<td align='center'><input type='checkbox' checked='checked' id=".$value->id." roleid=".$roleid." class='change_jurisdiction'></td>";
						}
						echo "</tr>";
					}
				}
				else{
					echo "<tr><td colspan='3'>您没有权限查看或编辑此内容</td></tr>";
				}
			?>
		</tbody>
	</table>
	</div>
</div>
<!-- end of .tab_container -->
</article>
<!-- end of content manager article -->

<div class="clear"></div>
<div class="spacer"></div>
</section>

<script type="text/javascript">

$('.change_jurisdiction').bind('click',function(){
	var roleid = $(this).attr('roleid');
	var jurisdictionid = $(this).attr('id');
	var data = {};
	if($(this).attr('checked') == 'checked'){
		data = {
			roleid : roleid,
			jurisdictionid : jurisdictionid,
			display : 'on'
		};
	}
	else{
		data = {
			roleid : roleid,
			jurisdictionid : jurisdictionid,
			display : 'off'
		};
	}
	$.ajax({
		type : "post",
		url : "<?php echo site_url()?>/user/jurisdictionInsert",
		data : data,
		success : function(msg) {
			document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_rolem_mofifyS')?>";	
			document.getElementById('msg').style.display="block";
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			alert("<?php echo  lang('t_error')?>");
		},
		beforeSend : function() {
			document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_rolem_waitmodify')?>";
			document.getElementById('msg').style.display="block";
		},
		complete : function() {
		}
	});
});

function check(role, resource) {	
	var capability;	
	if (document.getElementById('check_'+resource).checked == true) {
		capability = 1;
		
	} else {
		capability = 0;		
	}	
	var data = {
			role : role,
			resource : resource,
			capability : capability
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/modifyRoleCapability",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_rolem_mofifyS')?>";	
						document.getElementById('msg').style.display="block";
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_rolem_waitmodify')?>';
						document.getElementById('msg').style.display="block";
					},
					complete : function() {
					}
				});
}
</script>


