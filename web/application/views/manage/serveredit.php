<section id="main" class="column">
	<h4 class="alert_info" id='msg' style="display: none"></h4>

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_man_pr_editServer') ?></h3>
		</header>
		<div class="tab_container">
			<div class="module_content">
				<fieldset>
					<label><?php echo lang('v_man_au_serverName') ?></label>
					<input type="text" id='server_name'
						value="<?php if(isset($edit)) echo $edit['server_name'] ;?>">
				</fieldset>
				<fieldset>
					<label>区服ID</label>
					<input type="text" id='server_id'
						name="<?php if(isset($edit)) echo $edit['id'] ;?>"
						value="<?php if(isset($edit)) echo $edit['server_id'] ;?>">
				</fieldset>
				<fieldset>
					<label><?php echo lang('v_man_au_productName') ?></label>
					<select id='product_name'>
						<option><?php echo lang('v_man_au_chooseProductName') ?></option>
						<?php
							for($i=0;$i<count($product_list);$i++){
								if($edit['product_id'] == $product_list[$i]['id']){
									echo '<option value="'.$product_list[$i]['id'].'" selected="selected">'.$product_list[$i]['name'].'</option>';
								}
								else{
									echo '<option value="'.$product_list[$i]['id'].'">'.$product_list[$i]['name'].'</option>';
								}
							}
						?>
					</select>
				</fieldset>
				<fieldset>
					<label>区服容量</label>
					<input type="text" id='server_contain'
						value="<?php if(isset($edit)) echo $edit['server_capacity'] ;?>"
						onkeyup="this.value=this.value.replace(/\D/g,'')"
						onafterpaste="this.value=this.value.replace(/\D/g,'')" />
				</fieldset>
				<input
					<?php if(isset($guest_roleid) && $guest_roleid==2):echo 'disabled="disabled"'; endif;?>
					type="button" value="<?php echo lang('v_man_pr_changeServer') ?>"
					class="alt_btn"
					onClick="editserver('<?php if(isset($edit)) echo $edit['server_id'] ; ?>')">
			</div>
			<!-- end of #tab1 -->
			<!-- end of .tab_container -->
	
	</article>
	<!-- end of content manager article -->
	<div class="clear"></div>
	<div class="spacer"></div>
</section>

<script type="text/javascript">
function editserver(server_id)
{
	var id = $('#server_id').attr('name');
	var server_name = trim(document.getElementById('server_name').value);
	var server_id = trim(document.getElementById('server_id').value);
	var product_name = trim(document.getElementById('product_name').value);
	var server_contain = trim(document.getElementById('server_contain').value);
	if(server_name=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_enterServerN') ?></font>';
		document.getElementById('msg').style.display="block";
		return;
	}
	else if(server_id == ''){
		document.getElementById('msg').innerHTML = '<font color=red>区服ID不能为空</font>';
		document.getElementById('msg').style.display="block";
		return;
	}
	else if(product_name == ''){
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_enterProductN') ?></font>';
		document.getElementById('msg').style.display="block";
		return;
	}
	else if(server_contain == ''){
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_enterProductN') ?></font>';
		document.getElementById('msg').style.display="block";
		return;
	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < server_name.length; i++) {
		var str = server_name.substr(i, 1);
		if(pattern.test(str)||str.indexOf('\\')>=0){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('t_error') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
		}
	}
	var data = {
			id: id,
			server_id : server_id,
			server_name :server_name,
			product_name :product_name,
			server_contain :server_contain
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/manage/server/modifyserver",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_pr_existServerS') ?></font>";    
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_modifyServerS') ?>";    
						document.getElementById('msg').style.display="block";}                 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('t_error') ?>");
					},
					beforeSend : function() {
						/*document.getElementById('msg').innerHTML = '<?php echo lang('v_man_pr_modifyChannel') ?>';
						document.getElementById('msg').style.display="block";*/

					},
					complete : function() {
					}
				});
}
function trim(str){
	return  (str.replace(/(^\s*)|(\s*$)/g,''));
 }
</script>

