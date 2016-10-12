<section id="main" class="column" style='height:1500px;'>
		
		<h4 class="alert_info" id="msg" style="display:none;"></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('m_roleManagement')?></h3>
		<ul class="tabs">
   			<li><a href="#tab1">权限组列表</a></li>
    		  <li><a href="#tab2">添加权限组</a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
				    <th>权限组ID</th> 
    				<th>权限组</th> 
    				<th>权限组描述</th>     				
    				<th><?php echo  lang('v_user_rolem_permissionM')?></th>
    				<th>操作</th> 
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($rolelist)):
			 	foreach($rolelist->result() as $row)
			 	{
			 ?>
				<tr> 
				    <td><?php echo $row->id;?></td> 
    				<td><?php echo $row->name;?></td> 
    				<td><?php echo $row->description;?></td> 
    				<td><?php echo anchor('/user/roleManageDetail/'.$row->id.'/'.urlencode($row->name),lang('v_user_rolem_mPermission'));?></td>
    				<td><a href="<?php echo site_url()?>/user/roleEdit?id=<?php echo $row->id ?>">编辑</a></td>
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
		  	
			<div id="tab2" class="tab_content">
				<div class="module_content">
						<fieldset>
							<label><?php echo  lang('v_user_rolem_roleName')?></label>
							<input type="text" id='role'>
						</fieldset>
						<fieldset>
							<label><?php echo  lang('v_user_rolem_roleDescription')?></label>
							<input type="text" id='description'>
						</fieldset>
						<input <?php if(isset($guest_roleid) && $guest_roleid==2){echo 'disabled="disabled"'; }?> 
						 id="addRoleBtn" type="button" value="<?php echo  lang('v_user_rolem_addRole')?>" class="alt_btn" onClick='addRole()'>
				</div>
		<!-- end of post new article -->
			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function addRole() {	
	role = trim(document.getElementById('role').value);
	description = trim(document.getElementById('description').value);
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	if(role=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo  lang('v_user_rolem_enterRoleN')?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < role.length; i++) {
		var str= role.substr(i, 1);
		if(pattern.test(str)||str.indexOf('\\')>=0){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_rolem_errorInput') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
		}
	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo  lang('v_user_rolem_enterRoleD')?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	document.getElementById('addRoleBtn').disabled=true;
	var data = {
			role : role,
			description : description
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo base_url()?>index.php?/user/addRole",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo  lang('v_user_rolem_duplicateRole')?></font>";
							document.getElementById('msg').style.display="block";	
							document.getElementById('addRoleBtn').disabled=false;
						}else{
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_rolem_addRoleS')?>";
						document.getElementById('msg').style.display="block";
						window.location="<?php echo site_url()?>/user/rolemanage";}					 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
						document.getElementById('addRoleBtn').disabled=false;
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_rolem_waitAdd')?>';
						document.getElementById('msg').style.display="block";

					},
					complete : function() {
					}
				});
}
function trim(str){
    return  (str.replace(/(^\s*)|(\s*$)/g,''));
 }
</script>
	
