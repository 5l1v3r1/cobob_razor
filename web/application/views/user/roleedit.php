<section id="main" class="column">
	<h4 class="alert_info" id="msg" style="display:none;"></h4> 
	<article class="module width_full">
		<header><h3 class="tabs_involved">角色编辑</h3></header>
		<div class="tab_container">
			<div class="module_content">
				<fieldset>
					<label>权限组</label>
					<input type="text" id='name' maxlength="20" value='<?php if(isset($info)) echo $info[0]->name?>'>
				</fieldset>
				<fieldset>
					<label>权限组描述</label>
					<input type="text" id='description' maxlength="30"  value='<?php if(isset($info)) echo  $info[0]->description?>'>
				</fieldset>
				<input type="button" value="<?php echo  lang('g_update')?>" class="alt_btn" onClick='modifyInfo(<?php if(isset($id))  echo $id?>)'>
			</div>
		</div>
	</article>
</section>
<script>
function trim(str){
	return str.replace(/(^\s*)|(\s*$)/g, "");
}
function modifyInfo(params)
{
	var name = $('#name').val();
	var description = $('#description').val();
	var url = "<?php echo site_url()?>/user/updateRoleInfo";
	if(trim(name) == ''){
		alert('权限组不能为空');
	}
	else{
		$.post(url,{id:params,name:name,description:description},function(res){
			if(res == '1'){
				alert('更新成功');
				window.history.go(-1);
			}
		});
	}
}
</script>