<section id="main" class="column" style='height: 1500px;'>
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('m_serverManagement') ?></h3>
			<ul class="tabs">
				<li><a href="#tab1"><?php echo lang('v_rpt_mk_serverList') ?></a></li>
				<li><a href="#tab2"><?php echo lang('v_man_pr_addServer') ?></a></li>
			</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
				<table class="tablesorter" cellspacing="0">
					<thead>
					<?php if ($num == 0) { ?>
						<h4 class="alert_warning"><?php echo lang('v_man_pr_noCustomS') ?></h4>
						<div class="clear"></div>
						<div class="spacer"></div>
					<?php } else { ?>
						<tr>
							<th><?php echo lang('v_man_ch_serverID') ?></th>
							<th><?php echo lang('v_man_pr_name_n') ?></th>
							<th><?php echo lang('v_rpt_mk_serverName') ?></th>
							<th><?php echo lang('v_rpt_mk_contain') ?></th>
							<th><?php echo lang('g_actions') ?></th>
						</tr>
					</thead> 
					<?php } ?>
					<tbody> 
					<?php if (isset($server)) :
						$product_id = array();
						foreach ($server as $key) {
							$product_id[] = $key['product_id'];
						}
						array_multisort($product_id,SORT_ASC,$server);
						foreach ($server as $rel) {	?>
						<tr>
							<td><?php echo $rel['server_id'];?></td>
							<td>
								<?php
									for($i=0;$i<count($product_name);$i++){
										if($product_name[$i]['id'] == $rel['product_id']){
											$pn = $product_name[$i]['name'];
										}
									}
									echo $pn;
								 ?>
							</td>
							<td><?php echo $rel['server_name'];?></td>
							<td><?php echo $rel['server_capacity'];?></td>
							<td><a
								href="<?php echo site_url();?>/manage/server/editserver/<?php echo $rel['server_id']; ?>">
									<img src="<?php echo base_url();?>assets/images/icn_edit.png"
									title=<?php echo lang('v_element_edit')?> style="border: 0px" />
								</a>
								<?php if(isset($guest_roleid) && $guest_roleid==2): ?>
								<a> <img
									src="<?php echo base_url();?>assets/images/icn_trash.png"
									title=<?php echo lang('v_element_trash')?> style="border: 0px" /></a>
				
								<?php else: ?>    
								<a
								href="javascript:if(confirm('<?php echo lang('v_man_pr_deleteNoteS') ?>'))location='<?php echo site_url();?>/manage/server/deleteserver/<?php echo $rel['server_id']; ?>'">
									<img src="<?php echo base_url();?>assets/images/icn_trash.png"
									title=<?php echo lang('v_element_trash')?> style="border: 0px" />
								</a>
								<?php endif; ?>
							</td>
						</tr> 
					<?php } endif;?>
					</tbody>
				</table>
			</div>
			<!-- end of #tab1 -->

			<div id="tab2" class="tab_content">
				<div class="module_content">
					<fieldset>
						<label>区服ID</label> 
						<input type="text" id='server_id' maxlength="12"
						onkeyup="value=value.replace(/[^\d]/g,'')"
						onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
					</fieldset>
					<fieldset>
						<label><?php echo lang('v_man_au_serverName') ?></label> 
						<input type="text" id='server_name'>
					</fieldset>
					<fieldset>
						<label><?php echo lang('v_man_au_productName') ?></label> 
						<select id='product_name'>
							<option><?php echo lang('v_man_au_chooseProductName') ?></option>
							<?php 
								for($i=0;$i<count($product_list);$i++){
									echo '<option value="'.$product_list[$i]['id'].'">'.$product_list[$i]['name'].'</option>';
								}
							?>
						</select>
					</fieldset>
					<fieldset>
						<label><?php echo lang('v_rpt_mk_contain') ?></label> 
						<input type="text" id='server_contain'
						onkeyup="value=value.replace(/[^\d]/g,'')"
						onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
					</fieldset>
					<input
						<?php if(isset($guest_roleid) && $guest_roleid==2):echo 'disabled="disabled"'; endif;?>
						id="addserverButton" type="button"
						value="<?php echo lang('v_man_pr_addServer') ?>" class="alt_btn"
						onClick='addserver()'>
				</div>
				<!-- end of post new article -->
			</div>
			<!-- end of #tab2 -->
		</div>
		<!-- end of .tab_container -->
	</article>
	<!-- end of content manager article -->
		<div class="clear"></div>
	<div class="spacer"></div>
</section>

<script>
$(".tab_content1").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content1:first").show(); //Show first tab content

$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(".tab_content1").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">
//add custom channel
function addserver() {
	server_id = trim(document.getElementById('server_id').value);
	server_name = trim(document.getElementById('server_name').value);
	product_name = trim(document.getElementById('product_name').value);
	server_contain = trim(document.getElementById('server_contain').value);
	if(server_name == '')
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
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_rpt_mk_contain') ?></font>';
		document.getElementById('msg').style.display="block";
		return;
	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < server_name.length; i++) {
		var str = server_name.substr(i, 1);
		if(pattern.test(str)||str.indexOf('\\')>=0){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_rpt_mk_serverNameE') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
		}
	}
	
	document.getElementById('addserverButton').disabled=true;
	var data = {
			server_name :server_name,
			server_id :server_id,
			product_name :product_name,
			server_contain :server_contain
		};
		jQuery.ajax({
			type : "post",
			url : "<?php echo site_url()?>/manage/server/addserver",
			data : data,
			success : function(msg) {
				if(!msg){
					document.getElementById('addserverButton').disabled=false;
					document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_pr_existServerS') ?></font>";    
					document.getElementById('msg').style.display="block";
				}else{
				document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_addServerS') ?>";                                         
				document.getElementById('msg').style.display="block";
				window.location="<?php echo site_url()?>/manage/server";    }
			},
			error : function(XmlHttpRequest, textStatus, errorThrown) {
				alert("<?php echo lang('t_error') ?>");
				document.getElementById('addserverButton').disabled=false;
			},
			beforeSend : function() {
				/*document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_modifyChannel') ?>";
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




