<?php
$language = $this->config->item('language');
?>
<style>
#index-all-data {border: none; background: none;}
#index-all-data p.overview_count {font-size: 20px;}
.grey {color: #999;}
</style>
<section id="main" class="column" style='height:1500px;'>
			<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>
		<?php endif;?>	
			<article class="module width_3_quarter">
			<header><h3><?php echo  lang('v_overallAnalytics')?></h3>
				<ul class="tabs2">
				<li><a href="javascript:;">用户数据</a></li>
				<li><a href="javascript:;">付费数据</a></li>
			</ul>
			</header>
				<div class="module_content">
				<article>
					<div id="container" class="module_content" style="height:342px"></div>
				</article>
				<div class="clear"></div>
			</div>
		</article><!-- end of stats article -->
		<article class="module width_quarter">
			<header><h3>汇总</h3></header>
			<article class="stats_overview width_full" id="index-all-data">
				<div class="overview_today" >
					<p class="overview_day">今日</p>
					<p style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['today_Deviceactivations'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">设备激活</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['today_Registeruser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">新增注册</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['today_Newuser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">新增用户</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['today_Dauuser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">活跃用户</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['today_Payuser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">付费人数</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['today_Payamount'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">付费金额</p>
				</div>
				<div class="overview_previous">
					<p class="overview_day">累计</p>
					<p style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['total_Deviceactivations'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">设备激活</p>
					<p style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['total_Registeruser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">新增注册</p>
					<p style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['total_Newuser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">新增用户</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['total_Dauuser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">活跃用户</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['total_Payuser'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">付费人数</p>
					<p  style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo $gatherall['total_Payamount'];?></p>
					<p style="height:6px;"><br /></p>
					<p class="overview_type">付费金额</p>
				</div>
			</article>
		</article>
			<div class="clear"></div>
		<div class="spacer"></div>
			<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('m_youXi_newApp')?>
		 <?php echo anchor('/manage/product/create/',lang('m_tianJiaYouXi_newApp')); ?></h3>
		
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
					<th></th>
					<th>游戏编号</th>
					<th>游戏名称</th>
					<th></th>
					<th>设备激活</th>
					<th>新增注册</th> 
					<th>新增用户</th> 
					<th>活跃用户</th>
					<th>付费人数</th>
					<th>付费金额</th>
					<th>ARPU</th>
					<th>ARPPU</th>
					<th>付费率</th>
					<th><?php echo  lang('g_actions')?></th>
				</tr> 
			</thead> 
			<tbody> 
			
			 <?php if(isset($myGame)):
				for($i=0;$i<count($myGame);$i++)
			 {
			 ?>
				<tr>
					<td rowspan="2"><input type="checkbox" name="pid" value="<?php echo $myGame[$i]['id'];?>"/></td>
					<td rowspan="2"><?php echo $myGame[$i]['id'] ?></td>
					<td rowspan="2"><?php echo $myGame[$i]['name'];?></td> 
					<td class="grey">今日</td>
					<td><?php echo $myGame[$i]['today_Deviceactivations'] ?></td>
					<td><?php echo $myGame[$i]['today_Registeruser'] ?></td> 
					<td><?php echo $myGame[$i]['today_Newuser'] ?></td> 
					<td><?php echo $myGame[$i]['today_Dauuser'] ?></td>
					<td><?php echo $myGame[$i]['today_Payuser'] ?></td>
					<td><?php echo $myGame[$i]['today_Payamount'] ?></td>
					<td><?php echo $myGame[$i]['today_Payarpu'] ?></td>
					<td><?php echo $myGame[$i]['today_Payarrpu'] ?></td>
					<td><?php echo (floatval($myGame[$i]['today_Payrate'])*100) ?>%</td>
					<td rowspan="2">
					<?php echo anchor('/report/productbasic/view/'.$myGame[$i]['id'],lang('v_viewReport'));?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if(isset($guest_roleid) && $guest_roleid != 3):?>

					<?php else: ?>
						<a  href="javascript:if(confirm('<?php echo  lang('v_deleteAppPrompt')?>'))location='<?php echo site_url();?>/manage/product/delete/<?php echo $myGame[$i]['id'] ;?>'">
						<?php echo lang('g_delete')?></a>
					<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td class="grey">累计</td>
					<td><?php echo $myGame[$i]['total_Deviceactivations'] ?></td>
					<td><?php echo $myGame[$i]['total_Registeruser'] ?></td> 
					<td><?php echo $myGame[$i]['total_Newuser'] ?></td> 
					<td><?php echo $myGame[$i]['total_Dauuser'] ?></td>
					<td><?php echo $myGame[$i]['total_Payuser'] ?></td>
					<td><?php echo $myGame[$i]['total_Payamount'] ?></td>
					<td><?php echo $myGame[$i]['total_Payarpu'] ?></td>
					<td><?php echo $myGame[$i]['total_Payarrpu'] ?></td>
					<td><?php echo (floatval($myGame[$i]['total_Payrate'])*100) ?>%</td>
				</tr>
			<?php } endif;?>
			</tbody> 
			</table>
			<!-- <div style="margin:5px">
				<input type="submit" value="<?php echo lang('c_compare_product')?>" name="compareButton" class="alt_btn" id="submit" onclick="compareProduct();"/>
				<span style="padding:10px;vertical-align: middle;"><?php echo lang('c_compare2two4')?></span>
			</div> -->
			</div>
			<!-- end of #tab1 -->
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->	
		<div class="clear"></div>
		<div class="spacer"></div>		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
<script type="text/javascript">
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

function addFrame(type){
	$('#indexCharts').remove();
	var frame = '<iframe id="indexCharts" src="<?php echo site_url() ?>/report/console/charts?type='+type+'"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>';
	$('#container').html(frame);
}
addFrame(0);
//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	var current = $(this).index();
	addFrame(current);
	//$(".tab_content").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("ct"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});

//compare product
$(function(){
	if($('input[name=pid]').length<1){
		$("input[name=compareButton]").attr({disabled:"disabled"});
	}
});
function compareProduct(){
	var pids=new Array();
	$('input[name=pid]').each(function(index,item){
		if($(this).attr('checked')=='checked'){
			pids.push($(this).val());
			}
		});
	
	if(pids.length>4||pids.length<=1){alert('<?php echo lang('c_compare2two4')?>');return;}
	$('input[name=compareButton]').ajaxStart(function(){$(this).attr({disabled:'disabled'});});
	$.ajax({
		type:'post',
		url:'<?php echo site_url()?>/compare/compareProduct',
		data:{'pids':pids},
		dataType:'json',
		success:function(data,status){
			if('ok'==data){
				location.href='<?php echo site_url()?>/compare/compareProduct/compareConsole';
				return;
				}
			$('input[name=compareButton]').removeAttr('disabled');
			}
		});
}
$('.choose_channel_version').remove();
</script>


	
