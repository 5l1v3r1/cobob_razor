<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo lang('l_cobubRazor')
			?></title>
		<style>
			* {
				margin: 0;
				padding: 0;
			}
		</style>
		<link rel="icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
		<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
		<link rel="Bookmark" href="<?php echo base_url()?>favicon.ico"/>
		<link rel="stylesheet"
		href="<?php echo base_url();?>assets/css/jquery.select.css"
		type="text/css" media="screen" />
		<script src="<?php echo base_url();?>assets/js/jquery.select.js"
		type="text/javascript"></script>
		<link rel="stylesheet"
		href="<?php echo base_url();?>assets/css/<?php  $style = get_cookie('style');
			if ($style == "") {echo "layout";
			} else {echo get_cookie('style');
			}
		?>.css"
		type="text/css" media="screen" />
		<link rel="stylesheet"
		href="<?php echo base_url();?>/assets/css/helplayout.css"
		type="text/css" media="screen" />
		<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/bootstrap-tagmanager.css" type="text/css"
	media="screen" />
		<link rel="stylesheet"
		href="<?php echo base_url();?>assets/css/tag/jquery-ui-1.10.3.custom.css" type="text/css"
		media="screen" />
		<link rel="stylesheet"
		href="<?php echo base_url();?>assets/css/<?php  $style = get_cookie('style');
			if ($style == "") {echo "layout";
			} else {echo get_cookie('style');
			}
		?>pagination.css"
		type="text/css" media="screen" />
		<script src="<?php echo base_url();?>assets/js/json/json2.js"
		type="text/javascript"></script>	 
		 <script src="<?php echo base_url();?>assets/js/tag/jquery-1.9.1.js"
	type="text/javascript"></script>

<script
	src="<?php echo base_url();?>assets/js/tag/jquery-ui-1.10.3.custom.js"
	type="text/javascript"></script>
	
	<script
	src="<?php echo base_url();?>assets/js/jquery-1.9-pack.js"
	type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/jquery.validate.js"
		type="text/javascript"></script> 
		
		<script src="<?php echo base_url();?>assets/js/hideshow.js"
		type="text/javascript"></script>
		<script
		src="<?php echo base_url();?>assets/js/jquery.tablesorter.min.js"
		type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/jquery.pagination.js"
		type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/jquery.blockUI.js"
		type="text/javascript"></script>
		<script type="text/javascript"
		src="<?php echo base_url();?>assets/js/jquery.equalHeight.js"></script>
		<script src="<?php echo base_url();?>assets/js/estimate.js"
		type="text/javascript"></script>
		<!--
		<script src="<?php echo base_url();?>assets/js/charts/highcharts.js"
		type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/charts/highcharts-more.js"
		type="text/javascript"></script>
		<script
		src="<?php echo base_url();?>assets/js/charts/modules/exporting.js"
		type="text/javascript"></script>
		-->
		<!-- easydialog -->
		<link rel="stylesheet"
		href="<?php echo base_url();?>assets/css/easydialog.css" type="text/css"
		media="screen" />
		<script	src="<?php echo base_url();?>assets/js/easydialog/easydialog.js"
		type="text/javascript"></script>
		<script	src="<?php echo base_url();?>assets/js/easydialog/easydialog.min.js"
		type="text/javascript"></script>
		<!-- easydialog -->
		<script src="<?php echo base_url();?>assets/js/jquery.uploadify.v2.1.4.min.js"
		type="text/javascript"></script>
		<script src="<?php echo base_url();?>assets/js/swfobject.js"
		type="text/javascript"></script>
		<link href="<?php echo base_url();?>assets/css/uploadify.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>assets/css/default.css" rel="stylesheet" type="text/css" />
		<script	src="<?php echo base_url();?>assets/js/bootstrap-tagmanager.js"
type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function() {

				//When page loads...
				$(".tab_content").hide();
				//Hide all content
				$("ul.tabs li:first").addClass("active").show();
				//Activate first tab
				$(".tab_content:first").show();
				//Show first tab content

				//On Click Event
				$("ul.tabs li").click(function() {

					$("ul.tabs li").removeClass("active");
					//Remove any "active" class
					$(this).addClass("active");
					//Add "active" class to selected tab
					$(".tab_content").hide();
					//Hide all tab content

					var activeTab = $(this).find("a").attr("href");
					//Find the href attribute value to identify the active tab + content
					$(activeTab).fadeIn();
					//Fade in the active ID content
					return false;
				});
			});

		</script>
		<script type="text/javascript">
			$(function() {
				$('.column').equalHeight();
			});

		</script>
	</head>
	<body id="body">
		<header id="header">
			<hgroup>
				<!-- 左上角Cobub Razor商标 -->
				<h1 class="site_title"><a href="<?php echo base_url();?>"> <img class="logo" src="<?php echo base_url();?>assets/images/razorlogo.png" style="border:0"/> <span style=""><?php echo lang('g_cobubRazor')
					?></span> </a></h1>
				<!-- 控制台 | 个人资料 | 修改密码 | 退出 -->
				<h3 class="section_title" id="main_control"><?php if(isset($username)):?>
				<?php  echo anchor('/', lang('v_console'));?> | <?php  echo anchor('/profile/modify/', lang('m_profile'));?> | <?php  echo anchor('/auth/change_password/', lang('m_changePassword'));?> | <?php  echo anchor('/auth/logout/', lang('m_logout'));?>
				<?php  else:?>
				<?php  echo anchor('/auth/login/', lang('l_login'));?>
				<?php  endif;?></h3>
			</hgroup>
		</header>

		<?php
if(! isset ($versioninform)&& isset ($versionvalue))
{
if(isset($username)):
		?>
		<!-- Cobub Razor版本更新提示 -->
		<div id="newversioninform"   style="text-align:center;background-color:#E6DB55;font-size: 12px;padding:8px 0;" >
			<?php  echo lang('l_versioninform') . $versionvalue . lang('l_vinformtogo');?><a href="<?php  echo $version;?>" target="_blank"><?php  echo $version;?></a>
			<?php  echo lang('l_vinformupdate');?>
			<a href="javascript:void(o)" onclick="closeinform()" style="float:right;margin-right:2%;" ><img src="<?php  echo base_url();?>/assets/images/erroryell.png" /></a>
		</div>
		<?php  endif;
			}
		?>
		<!-- end of header bar -->
		<?php if(isset($login) && $login):
		?>
		
		<section id="secondary_bar">
			<!-- 用户名(个人资料) -->
			<div class="user">
				<p>
					<?php
					if (isset($username)) { echo $username;
					}
					?>
					(<?php echo anchor('/profile/modify',lang('m_profile'))
					?>)
				</p>
				<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
			</div>

			<div class="breadcrumbs_container">
				<article class="breadcrumbs">
					<!-- 控制台 -->
					<a href="<?php echo base_url();?>"><?php echo lang('v_console')
					?></a>
					<?php if(isset($product)):
					?>
					<div class="breadcrumb_divider"></div>
					<?php  echo anchor('/report/productbasic/view/', $product -> name);?>
					<?php  endif;?>
					<?php if(isset($pageTitle)&&$pageTitle!=""):
					?>
					<div class="breadcrumb_divider"></div>
					<a class="current"><?php  echo $pageTitle;?></a>
					<?php  endif;?>

					<?php if(isset($viewname)&& $viewname!="")
					{
					?>
					<div class="breadcrumb_divider"></div>
					<a class="current"><?php echo $viewname;?></a>
					<?php  }?>
				</article>
			</div>
			<!-- Section for user date section selector -->
			<!-- 渠道版本筛选 -->
			<?php if(isset($showFilter)&&$showFilter==true):
			?>
			<div class="choose_channel_version">
				<div class="filter">
					<div class="fl">
						<p>筛选</p>
						<div class="filter-icon fr"></div>
					</div>
					<div id="add-con">
						<div class="comm-b">
							<div class="top-tt">
								<div class="offset">
									<font class="fl">渠道:</font>
									<a href="javascript:void(0);" class="button-off"></a>
								</div>
								<div class="no-choose">不筛选</div>
								<div class="status"></div>
							</div>
							<div class="choose-con">
								<label class="ope">
									<span class="fl"><input type="checkbox" /></span>
									<span class="fl">全选</span>
								</label>
								<div class="con" id="f_channels">
									<?php 
										for($i = 0;$i < count($filter['channels']);$i++){
											echo "<a href='javascript:;' class='fl'>".$filter['channels'][$i]."</a>";
										}
									?>
								</div>
							</div>
						</div>
						<div class="comm-b">
							<div class="top-tt">
								<div class="offset">
									<font class="fl">版本:</font>
									<a href="javascript:void(0);" class="button-off"></a>
								</div>
								<div class="no-choose">不筛选</div>
								<div class="status"></div>
							</div>
							<div class="choose-con">
								<label class="ope">
									<span class="fl"><input type="checkbox" /></span>
									<span class="fl">全选</span>
								</label>
								<div class="con" id="f_version">
									<?php 
										for($i = 0;$i < count($filter['version']);$i++){
											echo "<a href='javascript:;' class='fl'>".$filter['version'][$i]."</a>";
										}
									?>
								</div>
							</div>
						</div>
						<div class="comm-b">
							<div class="top-tt">
								<div class="offset">
									<font class="fl">区服:</font>
									<a href="javascript:void(0);" class="button-off"></a>
								</div>
								<div class="no-choose">不筛选</div>
								<div class="status"></div>
							</div>
							<div class="choose-con">
								<label class="ope">
									<span class="fl"><input type="checkbox" /></span>
									<span class="fl">全选</span>
								</label>
								<div class="con" id="f_server">
									<?php 
										for($i = 0;$i < count($filter['server']);$i++){
											echo "<a href='javascript:;' class='fl'>".$filter['server'][$i]."</a>";
										}
									?>
								</div>
							</div>
						</div>
						<div class="confirm"><span class="fr"><a href="javascript:userDetailInfoChanged(this);" class="bottun4 hover" style="float:none;"><font>确定</font></a></span></div>
					</div>
				</div>
			</div>
			<script>
				$(document).ready(function(){
					$('.choose_channel_version .comm-b').each(function(){
						if($(this).find('div.con a').length == 0){
							$(this).find('.choose-con').remove();
						}
					});
					$('.choose_channel_version .filter > div.fl').bind('click',function(e){
						$('#add-con').toggle('normal');
						e.stopPropagation();
					});
					$('.choose_channel_version .offset > a').bind('click',function(){
						var key = $(this);
						if(key.attr('class') == 'button-off'){
							key.attr('class','button-on');
							key.parents('.top-tt').find('.status').show();
							key.parents('.top-tt').find('.no-choose').hide();
							key.parents('.top-tt').next().show();
						}
						else{
							key.attr('class','button-off');
							key.parents('.top-tt').find('.status').hide();
							key.parents('.top-tt').find('.no-choose').show();
							key.parents('.top-tt').next().hide();
						}
					});
					$('.choose_channel_version .comm-b .con a').bind('click',function(){
						// ($(this).hasClass('select'))?$(this).removeClass('select'):$(this).addClass('select');
						// var all_num = $(this).parent().find('a').length;
						// var select_num = $(this).parent().find('a.select').length;
						// if(all_num == select_num){
						// 	$(this).parent().prev().find('input').attr('checked',true);
						// }
						// else{
						// 	$(this).parent().prev().find('input').attr('checked',false);
						// }
						$(this).parent().find('a').removeClass('select');
						$(this).addClass('select')
					});
					$('.choose_channel_version .comm-b .con').each(function(){
						var all_num = $(this).find('a').length;
						var select_num = $(this).find('a.select').length;
						if(all_num == select_num){
							$(this).prev().find('input').attr('checked',true);
						}
						else{
							$(this).prev().find('input').attr('checked',false);
						}
					});
					$('.choose_channel_version .comm-b .ope input').change(function(){
						if($(this).attr('checked') != 'checked'){
							$(this).parents('.choose-con').find('a').removeClass('select');
						}
						else{
							$(this).parents('.choose-con').find('a').addClass('select');
						}
					});
				});
			</script>
			<?php  endif;?>
			<!-- 日期选择 -->
			<?php
			$fromTime = $this -> session -> userdata("fromTime");
			if (isset($fromTime) && $fromTime != null && $fromTime != "") {
			} else {
				$fromTime = date("Y-m-d", strtotime("-6 day"));
			}

			$toTime = $this -> session -> userdata("toTime");
			if (isset($toTime) && $toTime != null && $toTime != "") {

			} else {
				$toTime = date("Y-m-d", time());
			}
			?>
			<?php if(isset($showDate)&&$showDate==true):
			?>
			<div class="select_option fr"
			style="z-index:5555;position: absolute; right: 30px; margin-top: 3px">
				<div class="select_arrow fr"></div>
				<div id="selected_value" style="font-size: 12px;"
				class="selected_value fr">
					<?php  echo $fromTime;?>~<?php  echo $toTime;?>
				</div>
				<div class="clear"></div>
				<div id="select_list_body" style="display: none;"
				class="select_list_body">
					<ul>
						<li><a class="" id=""
							href="javascript:timePhaseChanged('0day')"> <?php echo  lang('g_today')?></a>
						</li>
						<li><a class="" id=""
							href="javascript:timePhaseChanged('1day')"> <?php echo  lang('g_yesterday')?></a>
						</li>
						<li>
							<a class="" id=""
							href="javascript:timePhaseChanged('7day')"> <?php echo  lang('g_lastweek')
							?></a>
						</li>
						<li>
							<a class="" id=""
							href="javascript:timePhaseChanged('1month');"> <?php echo  lang('g_lastmonth')
							?></a>
						</li>
						<!-- <li>
							<a class=""
							href="javascript:timePhaseChanged('3month');"> <?php echo  lang('g_last3months')
							?></a>
						</li> -->
						<li>
							<a class=""
							href="javascript:timePhaseChanged('all');"> <?php echo  lang('g_alltime')
							?></a>
						</li>
						<li class="date_picker noClick">
							<a style=""><?php echo  lang('g_anytime')
							?></a>
						</li>
						<li style="padding: 0; display: none;"
						class="date_picker_box noClick">
							<div style="width: 100%; padding-left: 20px;" class="selbox">
								<span><?php echo  lang('g_from')
									?></span>
								<input
								type="text" name="dpMainFrom" id="dpMainFrom" value=""
								class="datainp first_date date">
								<br>
								<span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  lang('v_rpt_ve_to')
									?></span>
								<input type="text" name="dpMainTo" id="dpMainTo" value=""
								class="datainp last_date date">
							</div>
							<div class="" style="">
								<input id="any" type="button" onclick="onAnyTimePhaseUpdate()"
								value="&nbsp;<?php echo  lang('g_search')?>&nbsp;"
								class="any" style="margin: 5px 60px 0 50px;">
							</div>
						</li>
					</ul>
				</div>
			</div>

			<?php  endif;?>
		</section>
		<!-- end of secondary bar -->
		<aside id="sidebar" class="column" >
			<?php if(!isset($product)):
			?>
			<!-- 管理 -->
			<h3><?php echo lang('m_manage');
			?></h3>
			<ul class="toggle">
				<!-- 我的应用 -->
				 <li class="icn_system">
					<?php  echo anchor('/report/console', lang('t_youXiZongLan_yao'));?>
				</li>
				<?php if(isset($admin) && !(isset($product))):
				?>
				<!-- 新建应用 -->
				<li class="icn_add_apps">
					<?php  echo anchor('/manage/product/create', lang('t_tianJiaYouXi_yao'));?>
				</li>
				<!-- 渠道管理 -->
				<li class="icn_app_channel">
					<?php  echo anchor('/manage/channel/', lang('m_channelManagement'));?>
				</li>
				<!-- 区服管理 -->
				<li class="icn_app_server">
					<?php  echo anchor('/manage/server/', lang('m_serverManagement'));?>
				</li>
				<?php  endif;?>
			</ul>
			<?php  endif;?>
			<?php if(isset($product)):
			?>
			<form class="quick_search">
				<?php if(isset($productList)):
				?>
				<select style="width: 90%;" id='select_head'
				onchange='changeProduct(value)'>
					<?php foreach($productList as $row){
					?>
					<option <?php
						if ($product -> id == $row['id'])
							echo 'selected';
					?> value="<?php echo $row['id'];?>"><?php  echo $row['name'];?></option>
					<?php  }?>
				</select>
				<?php  endif;?>
			</form>
			<hr />
			<?php
				for($i=0;$i<$classifyNum;$i++){
					echo "<h3>".$nav[$i][0][0]->classifname."</h3>";
					echo "<ul class='toggle'>";
					for($r=0;$r<count($nav[$i][0]);$r++){
						$isDisplay = false;
						foreach ($catalog as $value) {
							if($value['source'] == $nav[$i][0][$r]->id){
								if($value['read'] == '1'){
									$isDisplay = true;
								}
							}
						}
						if($isDisplay){
							echo "<li class='".$nav[$i][0][$r]->item_type."' id='".$nav[$i][0][$r]->id."'>";
						}
						else{
							echo "<li class='".$nav[$i][0][$r]->item_type." hide' id='".$nav[$i][0][$r]->id."'>";
						}
						echo "<a class='colorMediumBlue bold spanHover' href='".site_url().$nav[$i][0][$r]->anchor."'>".$nav[$i][0][$r]->description."</a>";
						echo "</li>";

					}
					echo "</ul>";
				}
			?>
			<?php endif; ?>

			<?php if(isset($admin)&& !(isset($product))): ?>
			<h3><?php echo lang('m_userPermission')	?></h3>
			<ul class="toggle">
				<li class="icn_mangageuser">
					<?php echo anchor('/user/', lang('m_userManagement'));
					?>
				</li>
				<li class="icn_managerole">
					<?php echo anchor('/user/rolemanage/', '权限组管理');
					?>
				</li>
				<li class="icn_manaresource"><?php echo anchor('/user/resourcemanage/', lang('m_resourceManagement'));?></li>	   
				<li class="icn_new_user">
					<?php echo anchor('/user/newUser/', lang('t_xinZengZhangHao_yao'));
					?>
				</li>
				<li class="icn_security">
					<?php echo anchor('/user/unlocked/', '账号解锁');
					?>
				</li>
			</ul>

			<?php endif; ?>
			<?php
			$arr= $this->pluginm->run("getPluginInfo","");
				for($i=0;$i<count($arr);$i++){
					$identifier= $arr[$i]['identifier']; 
					$pluginstatus=$this->plugin->getPluginStatusByIdentifier($identifier);
					$uid = $this->common->getUserId();
					$isactive = $this->plugin->getUserActive($uid);
					if($pluginstatus==1&& $isactive){
						// print_r( $arr[$i]['menus']);
						if(!(isset($product))){
							 $flag = false;
							$men = $arr[$i]['menus'];
							 $menus="<hr/><h3>".$men['title']."</h3><ul class='toggle'>";
							for( $j=0;$j<count($men['menus']);$j++){
								// while(list($key,$val)= each($men['menus'][$i])){
								if($men['menus'][$j]['level1']){
									$flag = true;
									 $menus = $menus. "<li class='icn_my_application'><a href='".site_url () .$men['menus'][$j]['link']."'class='colorMediumBlue bold spanHover'>".$men['menus'][$j]['name']."</a></li>";
								}
							}
							$menus = $menus."</ul>";
							if($flag){
								 echo $menus;
							}
						}else{

							 $flag = false;
							 $men = $arr[$i]['menus'];
							 $menus="<hr/><h3>".$men['title']."</h3><ul class='toggle'>";
							for( $j=0;$j<count($men['menus']);$j++){
								if($men['menus'][$j]['level2']){
									$flag = true;
									 $menus = $menus. "<li class='icn_my_application'><a href='".site_url () .$men['menus'][$j]['link']."'class='colorMediumBlue bold spanHover'>".$men['menus'][$j]['name']."</a></li>";
								}
							}
							$menus = $menus."</ul>";
							if($flag){
								 echo $menus;
							}
						}
					}	
				}
			?>
			<ul style="margin-top:5%">
				<hr/>
				<br/>
				<li class="icn_term_define">
					<a href="<?php echo site_url() ;?>/help"> <?php echo lang('m_termsAndD'); ?></a>
				</li>		
				<li class="icn_openAPIManual">
					<a href="http://git.oschina.net/wonderful60/razor_introduce" target="_blank"><?php echo lang('m_explainD'); ?></a>
				</li>	
			</ul>
		</aside>
		
		<?php endif;
		?>

		<script type="text/javascript">
var username="<?php if(isset($username)){echo $username;}else{echo false;} ?>";
var nouse="<?php $isuse=get_cookie('nouse') ;if($isuse=="isfalse"){echo $isuse;}else{echo "istrue";}?>";
$(document).ready(function(){
//init time segment selector
initTimeSelect();
});

$(function() {
$("#dpMainFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
});

$(function() {
$( "#dpMainTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
});

function blockUI()
{
var chart_canvas = $('#body');
var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");

chart_canvas.block({
message: loading_img,
css:{
width:'32px',
border:'none',
background: 'none'
},
overlayCSS:{
backgroundColor: '#FFF',
opacity: 0.8
},
baseZ:997
});
}
/*
	*danny edit
	*add version server filter
*/
function userDetailInfoChanged(obj){
	blockUI();
	var arr = new Array();
	var $channels = '',$version = '',$server = '';
	$('.choose_channel_version .offset a').each(function(){
		if($(this).hasClass('button-on')){
			var name = $(this).parents('.top-tt').next().find('div.con').attr('id');
			switch(name){
				case 'f_channels':
					var channels = new Array();
					$(this).parents('.top-tt').next().find('div.con .select').each(function(){
						channels.push($(this).text());
					});
					arr[0] = channels;
					break;
				case 'f_version':
					var version = new Array();
					$(this).parents('.top-tt').next().find('div.con .select').each(function(){
						version.push($(this).text());
					});
					arr[1] = version;
					break;
				case 'f_server':
					var server = new Array();
					$(this).parents('.top-tt').next().find('div.con .select').each(function(){
						server.push($(this).text());
					});
					arr[2] = server;
					break;			
			}
		}
	});
	if(arr[0]){
		$channels = arr[0].join('-');
	}
	if(arr[1]){
		$version = arr[1].join('-');
	}
	if(arr[2]){
		$server = arr[2].join('-');
	}

	var url = "<?php echo site_url()?>/report/console/svcPhase";
	$.post(url,{
		channels:$channels,
		version:$version,
		server:$server
	},function(res){
		window.location.reload();
	});
	sessionStorage.setItem("f_server",$server);
	sessionStorage.setItem("f_version",$version);
	sessionStorage.setItem("f_channels",$channels);
}
$('#main_control a:last').bind('click',function(){
	sessionStorage.removeItem("f_server");
	sessionStorage.removeItem("f_version");
	sessionStorage.removeItem("f_channels");
})
//获取cookies 初始化状态
var f_server =  unescape(sessionStorage.getItem('f_server')).split('-');
var f_version =  unescape(sessionStorage.getItem('f_version')).split('-');
var f_channels =  unescape(sessionStorage.getItem('f_channels')).split('-');

var header_filter_cookies = new Array();
header_filter_cookies[0] = f_channels;
header_filter_cookies[1] = f_version;
header_filter_cookies[2] = f_server;
if(header_filter_cookies[0] != 'null' || header_filter_cookies[1] != 'null' || header_filter_cookies[2] != 'null'){
	var html = '';
	html += "<div id='choose-filter'>";
	if(header_filter_cookies[0] != '') {html += "<div class='line'><b>渠道:</b> "+header_filter_cookies[0]+"</div>"};
	if(header_filter_cookies[1] != '') {html += "<div class='line'><b>版本:</b> "+header_filter_cookies[1]+"</div>"};
	if(header_filter_cookies[2] != '') {html += "<div class='line'><b>区服:</b> "+header_filter_cookies[2]+"</div>"};
	html += "</div>";
	$(document).ready(function(){
		$('section#main').prepend(html);
		if($('#choose-filter >div').length < 1){
			$('#choose-filter').remove();
		}
	});
}

function default_header_filter_status(el,num){
	if(header_filter_cookies[num].length > 0 && header_filter_cookies[num] != ''){
		$('#'+el).parents('.comm-b').find('.offset a').addClass('button-on').removeClass('button-off');
		$('#'+el).parents('.choose-con').show();
		$('#'+el).parents('.comm-b').find('.no-choose').hide();
		$('#'+el+' a').each(function(){
			var text = $(this).text();
			for(var i = 0,r = header_filter_cookies[num].length;i < r;i++){
				if(text == header_filter_cookies[num][i]){
					$(this).addClass('select');
				}
			}
		});
	}
}
default_header_filter_status('f_channels',0);
default_header_filter_status('f_version',1);
default_header_filter_status('f_server',2);

function timePhaseChanged(phase)
{
blockUI();
var url = "<?php echo site_url()?>/report/console/changeTimePhase/"+phase;
console.log(url);
jQuery.getJSON(url, null, function(data) {
window.location.reload();
});
setCookie("timephase",phase);
}

function onAnyTimePhaseUpdate()
{
blockUI();
var fromTime = document.getElementById('dpMainFrom').value;
var toTime = document.getElementById('dpMainTo').value;
var url = "<?php echo site_url()?>/report/console/changeTimePhase/any/"+fromTime+"/"+toTime;
jQuery.getJSON(url, null, function(data) {
window.location.reload();
});
}

//Change selected product to another
function changeProduct(pid)
{
blockUI();
var url = "<?php echo site_url()?>/manage/product/changeProduct/"+pid;
jQuery.getJSON(url, null, function(data) {
//window.location.href="<?php echo site_url()?>/report/productbasic/view/"+pid;
window.location.href="<?php echo site_url()?>/useranalysis/newusers/index";
});

}

function setcssstyle(cssstyle)
{
setCookie("style",cssstyle);
window.location.reload();
}
function getCookie(name)
{
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg))
return unescape(arr[2]);
else
return null;
}
function setCookie(name,value)
{
var Days = 365; //cookie will remain one year
var exp  = new Date();    //new Date("December 31, 9998");
exp.setTime(exp.getTime() + Days*24*60*60);
document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString();
}
function delCookie(name)
{
var exp = new Date();
exp.setTime(exp.getTime() - 1);
var cval=getCookie(name);
if(cval!=null)
document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
//update version inform
function closeinform()
{
var item= document.getElementById("noinform") ;
var url="<?php echo site_url()?>/report/console/setnewversion";
jQuery.getJSON(url, null, function(data)
{
window.location.reload();

});
document.getElementById("newversioninform").style.display="none";
}
</script>
<script>
//调整面包屑
var location_current = <?php
	if (isset($product) && $product != null) {
		echo '1';
	}
	else{
		echo '0';
	}
 ?>;
if(location_current){
	//产品内页
	var len = $('.breadcrumbs .current').length;
	//左侧栏菜单定位
	function leftMainNavColorInside(){
		var location = window.location.search;
		switch(location){
			//实时概况
			case '?/seeall/realtimeinfo/index':
				$('#sidebar .toggle:eq(0) li:eq(0)').addClass('select');
				break;
			case '?/seeall/report/index':
				$('#sidebar .toggle:eq(0) li:eq(1)').addClass('select');
				break;
			//新增注册
			case '?/useranalysis/newusers/index':
				$('#sidebar .toggle:eq(1) li:eq(0)').addClass('select');
				break;
			case '?/useranalysis/newcreaterole/index':
				$('#sidebar .toggle:eq(1) li:eq(1)').addClass('select');
				break;
			case '?/useranalysis/dauusers/index':
				$('#sidebar .toggle:eq(1) li:eq(2)').addClass('select');
				break;
			case '?/report/userremain/index':
				$('#sidebar .toggle:eq(1) li:eq(3)').addClass('select');
				break;
			case '?/useranalysis/newuserprogress/index':
				$('#sidebar .toggle:eq(1) li:eq(4)').addClass('select');
				break;
			case '?/useranalysis/viprole/index':
				$('#sidebar .toggle:eq(1) li:eq(5)').addClass('select');
				break;
			case '?/useranalysis/vipremain/index':
				$('#sidebar .toggle:eq(1) li:eq(6)').addClass('select');
				break;
			case '?/useranalysis/levelaly/index':
				$('#sidebar .toggle:eq(1) li:eq(7)').addClass('select');
				break;
			//付费数据
			case '?/payanaly/paydata/index':
				$('#sidebar .toggle:eq(2) li:eq(0)').addClass('select');
				break;
			case '?/payanaly/payanalysis/index':
				$('#sidebar .toggle:eq(2) li:eq(1)').addClass('select');
				break;
			case '?/payanaly/paylevel/index':
				$('#sidebar .toggle:eq(2) li:eq(2)').addClass('select');
				break;
			case '?/payanaly/firstpayanaly/index':
				$('#sidebar .toggle:eq(2) li:eq(3)').addClass('select');
				break;
			case '?/payanaly/newpay/index':
				$('#sidebar .toggle:eq(2) li:eq(4)').addClass('select');
				break;
			case '?/payanaly/payrate/index':
				$('#sidebar .toggle:eq(2) li:eq(5)').addClass('select');
				break;
			case '?/payanaly/payrank/index':
				$('#sidebar .toggle:eq(2) li:eq(6)').addClass('select');
				break;
			case '?/payanaly/payinterval/index':
				$('#sidebar .toggle:eq(2) li:eq(7)').addClass('select');
				break;
			//
			case '?/onlineanaly/dayonline/index':
				$('#sidebar .toggle:eq(3) li:eq(0)').addClass('select');
				break;
			case '?/onlineanaly/capacityv/index':
				$('#sidebar .toggle:eq(3) li:eq(1)').addClass('select');
				break;
			case '?/onlineanaly/starttimes/index':
				$('#sidebar .toggle:eq(3) li:eq(2)').addClass('select');
				break;
			case '?/onlineanaly/borderintervaltime/index':
				$('#sidebar .toggle:eq(3) li:eq(3)').addClass('select');
				break;
			case '?/onlineanaly/avgtimeorcount/index':
				$('#sidebar .toggle:eq(3) li:eq(4)').addClass('select');
				break;
			case '?/onlineanaly/frequencytime/index':
				$('#sidebar .toggle:eq(3) li:eq(5)').addClass('select');
				break;
			case '?/leaveanaly/leavecount/index':
				$('#sidebar .toggle:eq(4) li:eq(0)').addClass('select');
				break;
			case '?/leaveanaly/levelleave/index':
				$('#sidebar .toggle:eq(4) li:eq(1)').addClass('select');
				break;
			case '?/sysanaly/taskanalysis/index':
				$('#sidebar .toggle:eq(5) li:eq(0)').addClass('select');
				break;
			case '?/sysanaly/tollgateanalysis/index':
				$('#sidebar .toggle:eq(5) li:eq(1)').addClass('select');
				break;
			case '?/sysanaly/virtualmoneyanalysis/index':
				$('#sidebar .toggle:eq(5) li:eq(2)').addClass('select');
				break;
			case '?/sysanaly/propanaly/index':
				$('#sidebar .toggle:eq(5) li:eq(3)').addClass('select');
				break;
			case '?/sysanaly/functionanalysis/index':
				$('#sidebar .toggle:eq(5) li:eq(4)').addClass('select');
				break;
			case '?/sysanaly/newserveractivity/index':
				$('#sidebar .toggle:eq(5) li:eq(5)').addClass('select');
				break;
			case '?/sysanaly/operatingactivity/index':
				$('#sidebar .toggle:eq(5) li:eq(6)').addClass('select');
				break;
			case '?/channelanaly/userltv/index':
				$('#sidebar .toggle:eq(6) li:eq(0)').addClass('select');
				break;
			case '?/channelanaly/channelimportamount/index':
				$('#sidebar .toggle:eq(6) li:eq(1)').addClass('select');
				break;
			case '?/channelanaly/channelquality/index':
				$('#sidebar .toggle:eq(6) li:eq(2)').addClass('select');
				break;
			case '?/channelanaly/channelincome/index':
				$('#sidebar .toggle:eq(6) li:eq(3)').addClass('select');
				break;
			case '?/exceptionanaly/errorcodeanaly/index':
				$('#sidebar .toggle:eq(7) li:eq(0)').addClass('select');
				break;
			case '?/manage/product/editproduct':
				$('#sidebar .toggle:eq(8) li:eq(0)').addClass('select');
				break;
			case '?/manage/onlineconfig':
				$('#sidebar .toggle:eq(8) li:eq(1)').addClass('select');
				break;
			case '?/manage/event':
				$('#sidebar .toggle:eq(8) li:eq(2)').addClass('select');
				break;
			case '?/manage/alert':
				$('#sidebar .toggle:eq(8) li:eq(3)').addClass('select');
				break;
			case '?/manage/channel/appchannel':
				$('#sidebar .toggle:eq(8) li:eq(4)').addClass('select');
				break;
			case '?/manage/funnels':
				$('#sidebar .toggle:eq(8) li:eq(5)').addClass('select');
				break;
			case '?/manage/pointmark/listmarkeventspage':
				$('#sidebar .toggle:eq(8) li:eq(6)').addClass('select');
				break;
			case '?/manage/mainconfig/index':
				$('#sidebar ul:eq(8) li:eq(7)').addClass('select');
				break;
			case '?/manage/logquery/index':
				$('#sidebar ul:eq(8) li:eq(8)').addClass('select');
				break;
			case '?/help':
				$('#sidebar ul:eq(9) li:eq(0)').addClass('select');
				break;
			case '?/help/edit':
				$('#sidebar ul:eq(9) li:eq(0)').addClass('select');
				break;
		}
		if(location.indexOf('?/sysanaly/propanaly') >= 0){
			$('#sidebar .toggle:eq(5) li:eq(3)').addClass('select');
		}
	};
	leftMainNavColorInside();
}
else{
	//首页
	var len = $('.breadcrumbs .current').length;
	if(len == 1){
		var text = $('.breadcrumbs .current:eq(0)').text();
		var $text = search_current_location(text);
		if($text) $('.breadcrumbs .current:eq(0)').before('<a class="current">'+$text+'</a><div class="breadcrumb_divider"></div>');
	}
	else if(len >= 2){
		var text = $('.breadcrumbs .current:eq(1)').text();
		var $text = search_current_location(text);
		if($text) $('.breadcrumbs .current:eq(0)').text($text);
	}
	//左侧栏菜单定位
	function leftMainNavColorIndex(){
		var location = window.location.search;
		switch(location){
			case '':
				$('#sidebar .toggle:eq(0) li:eq(0)').addClass('select');
				break;
			case '?/report/console':
				$('#sidebar .toggle:eq(0) li:eq(0)').addClass('select');
				break;
			case '?/manage/product/create':
				$('#sidebar .toggle:eq(0) li:eq(1)').addClass('select');
				break;
			case '?/manage/channel':
				$('#sidebar .toggle:eq(0) li:eq(2)').addClass('select');
				break;
			case '?/manage/server':
				$('#sidebar .toggle:eq(0) li:eq(3)').addClass('select');
				break;
			case '?/user':
				$('#sidebar .toggle:eq(1) li:eq(0)').addClass('select');
				break;
			case '?/user/rolemanage':
				$('#sidebar .toggle:eq(1) li:eq(1)').addClass('select');
				break;
			case '?/user/resourcemanage':
				$('#sidebar .toggle:eq(1) li:eq(2)').addClass('select');
				break;
			case '?/user/newUser':
				$('#sidebar .toggle:eq(1) li:eq(3)').addClass('select');
				break;
			case '?/user/unlocked':
				$('#sidebar .toggle:eq(1) li:eq(4)').addClass('select');
				break;
			case '?/help':
				$('#sidebar ul:eq(2) li:eq(0)').addClass('select');
				break;
			case '?/help/edit':
				$('#sidebar ul:eq(2) li:eq(0)').addClass('select');
				break;
		}
		if(location.indexOf('?/manage/channel') >= 0){
			$('#sidebar .toggle:eq(0) li:eq(2)').addClass('select');
		}
		if(location.indexOf('?/manage/server') >= 0){
			$('#sidebar .toggle:eq(0) li:eq(3)').addClass('select');
		}
		if(location.indexOf('?/user/editResource') >= 0){
			$('#sidebar .toggle:eq(1) li:eq(2)').addClass('select');
		}
		if(location.indexOf('?/user/roleEdit') >= 0){
			$('#sidebar .toggle:eq(1) li:eq(1)').addClass('select');
		}
		if(location.indexOf('?/user/assignProducts') >= 0){
			$('#sidebar .toggle:eq(1) li:eq(0)').addClass('select');
		}
	};
	leftMainNavColorIndex();
}

function search_current_location(text){
	var parent;
	$('#sidebar ul.toggle').each(function(){
		$(this).find('a').each(function(){
			if($(this).text() == text){
				parent = $(this).parents('ul').prev().text();
			}
		});
	});
	return parent;
}
//导出excel
$(document).ready(function(){
	var excelUrl = '<?php echo site_url() ?>/importexcel/excel/index';
	$('.import-excel .import').bind('click',function(){
		var table = $(this).parent().next('div').find('table');
		table.attr('border','1');
		var content = $(this).parent().next('div').html();
		table.removeAttr('border');
		$.post(excelUrl,{con:content},function(res){
			window.location.href=excelUrl;
		});
	});
});
//nav
$('#sidebar ul.toggle').each(function(){
	var len = $(this).find('li').length;
	var hideLen = $(this).find('li.hide').length;
	if(len == hideLen){
		$(this).prev().remove();
		$(this).remove();
	}
});
</script>
