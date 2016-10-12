<section id="main" class="column" >
	<h4 class="alert_success" id='msg' style="display:none;"></h4>
	<!-- 今日版本数据 -->
   <article class="module width_full">
			<header><h3 class="tabs_involved"><?php echo  lang('v_rpt_ve_tillYesterday')?></h3>				
			</header>
			<table class="tablesorter" cellspacing="0">
			<thead> 
				<tr> 
					<!-- 应用版本 -->
				    <th><?php echo  lang('v_rpt_ve_appVersion')?></th> 	
				    <!-- 累计用户百分比	-->
    				<th><?php echo  lang('t_accumulatedUsersP')?></th>   
    				<!-- 新增用户 -->
    				<th><?php echo  lang('t_newUsers')?></th>
    				<!-- 升级用户 -->
    				<th><?php echo  lang('t_upgradeUsers')?></th> 	
    				<!-- 活跃用户百分比 -->
    				<th><?php echo  lang('t_activeUserP')?></th>
    				<!-- 启动次数 -->
    				<th><?php echo  lang('t_sessions')?></th>
				</tr> 
			</thead>
			<tbody> 
			<?php if(isset($versionList)&&count($versionList)>0):
			$allusers = 0;
			$activeusers = 0;
			   for ($i=0;$i<count($versionList);$i++)
			   {
			   	$row = $versionList[$i];
			   	$allusers+=$row['total'];
			   	$activeusers+=$row['active'];
			   	
			   }
			 	for($i=0;$i<count($versionList);$i++)
			 	{
			 		$row = $versionList[$i];
			 ?>
				<tr> 
    				<td><?php echo ($row['version']==null)?lang('t_unknow'):$row['version'];?></td> 
    				<td><?php echo $row['total']."(".percent($row["total"], $allusers).")";?></td> 
    				<td><?php echo $row["new"];?></td> 
    				<td><?php echo $row["update"];?></td>
    				<td><?php echo $row['active']."(".percent($row["active"], $activeusers).")";?></td>
    				<td><?php echo $row["start"];?></td>
				</tr> 
			<?php } endif;?>											
			</tbody> 			
			</table>
	</article>

	<!-- 版本变化趋势 -->
	<div style="height:400px;">
		<iframe src="<?php echo site_url() ?>/report/version/addversionviewreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
	</div>

	<!-- 版本分布对比 -->
	<article class="module width_full">
				<header><h3 class="tabs_involved"><?php echo  lang('v_rpt_ve_comparison')?></h3>
	   			<div class="submit_link" >
						 <select onchange=selectStyletop(value) id='selectstyletop'>
						 	<!-- TOP5版本 -->
							<option value = TOP5><?php echo  lang('v_rpt_ve_top5')?></option>
							<!-- TOP10版本 -->
							<option value=TOP10><?php echo  lang('v_rpt_ve_top10')?></option>
							<!-- 全部 -->
							<option value=all><?php echo  lang('g_all')?></option>
						</select>
					</div></header>
				
			<!-- 请选择时间！ -->
			<div align="center" id='selectTime1'><?php echo  lang('v_rpt_ve_selectTime')?>
			            <input type="text" id="dpFrom1" > <?php echo  lang('v_rpt_ve_to')?>
						<input type="text" id="dpTo1">  <?php echo  lang('v_rpt_ve_vs')?>  
						<input type="text" id="dpFrom2"> <?php echo  lang('v_rpt_ve_to')?>
						<input type="text" id="dpTo2">
			<input type="button" id='btn' value="<?php echo  lang('g_update')?>" class="alt_btn" onclick="styleTimeButtonClicked()"></div>
							
		 <hr style="height:15px;border:none">
		  
				<table class="tablesorter" cellspacing="0" style="height:100px"> 
				<thead> 
					<tr> 
						<!-- 应用版本 -->
					    <th><?php echo  lang('v_rpt_ve_appVersion')?></th> 	
					    <!-- 新增用户比例1 -->
	    				<th><div id="userper"><?php echo  lang('t_newUsersP')?></div><span id="newuserfromto1"></span></th>
	    				<!-- 新增用户比例2 -->     				     				
	    				<th><div id="userper1"><?php echo  lang('t_newUsersP')?></div><span id="newuserfromto2"></span></th>
					</tr> 
				</thead>
				<tbody id="versinlist"> 
														
				</tbody> 						
				</table>
			<footer>		
			  <ul class="tabs3">					
				<li><a id="111" mt="newUser1" href="javascript:onContrastTabClicked('NewUser')" onclick='changenew()'><?php echo  lang('t_newUsers')?></a></li>
				<li><a id="222" mt="activeUser1" href="javascript:onContrastTabClicked('ActiveUser')" onclick='changeactive()'><?php echo  lang('t_activeUsers')?></a></li>
		      </ul>
		 </footer>	
	</article>	
<div class="spacer"></div>
		
		
</section>
<script type="text/javascript">
function changenew()
{
	var type =document.getElementById('111').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('g_percent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('g_percent')?>";
}
function changeactive()
{
	var type =document.getElementById('222').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('g_percent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('g_percent')?>";
}
</script>

<script type="text/javascript">
function changeday()
{
	var type =document.getElementById('whichday').innerHTML;
	if(type=="<?php echo  lang('v_rpt_ve_viewToday')?>")
	{
		document.getElementById('day').innerHTML="<?php echo  lang('v_rpt_ve_versionST')?>";
		document.getElementById('whichday').innerHTML="<?php echo  lang('v_rpt_ve_viewYesterday')?>";
	}
	else
	{
		document.getElementById('day').innerHTML="<?php echo  lang('v_rpt_ve_ersionSY')?>";
		document.getElementById('whichday').innerHTML="<?php echo  lang('v_rpt_ve_viewToday')?>"	;	
	}
}
function changeactive()
{
	var type =document.getElementById('222').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('g_percent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('g_percent')?>";
}
</script>
<script type="text/javascript">
var styleName = 'NewUser';
var version='5'
</script>

<script type="text/javascript">
var chartversion = 'default';
var time = '7day';
var fromTime='';
var toTime='';
var jsondata;
var contrast_data;
var titlename='';

//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
function changeStyleName(name)
{
	styleName = name;
	getdata();
}
function selectStyletop(value)
{
    if(value=='TOP5')
    {
        version='5';
        getdata();           
     }
    if(value=='TOP10')
    {
    	version='10';
        getdata();
        
     }
    if(value=='all')
    {
    	version='100';
        getdata();
     }          
}
//On Click Event
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("mt"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">	
	$(function() {
		$("#dpFrom1" ).datepicker();
	});
	$( "#dpFrom1" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo1" ).datepicker();
	});
	$( "#dpTo1" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$("#dpFrom2" ).datepicker();
	});
	$( "#dpFrom2" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo2" ).datepicker();
	});
	$( "#dpTo2" ).datepicker({ dateFormat: "yy-mm-dd" });  
</script>

<script type="text/javascript">
function styleTimeButtonClicked()
{  
	fromTime1 = document.getElementById('dpFrom1').value;
	toTime1 = document.getElementById('dpTo1').value;
	document.getElementById('newuserfromto1').innerHTML = "("+fromTime1 + '-' + toTime1+")";
	fromTime2 = document.getElementById('dpFrom2').value;
	toTime2 = document.getElementById('dpTo2').value;
	document.getElementById('newuserfromto2').innerHTML = "("+fromTime2 + '-' + toTime2+")";
	ft1=new Date(fromTime1);
	tot1=new Date(toTime1);
	ft2=new Date(fromTime2);
	tot2=new Date(toTime2);
	if(ft1>tot1||ft2>tot2){
	alert('<?php echo lang("g_timeError")?>');return;}
	getdata();
}

</script>
<script type="text/javascript">
function getdata()
{
	var myurl = "";
	if(styleName == 'NewUser')
	{	
		myurl="<?php echo site_url()?>/report/version/getVersionContrast/"+fromTime1+"/"+toTime1+"/"+fromTime2+"/"+toTime2+"/"+version;
	}
	else
	{		
	   myurl="<?php echo site_url()?>/report/version/getVersionContrast/"+fromTime1+"/"+toTime1+"/"+fromTime2+"/"+toTime2+"/"+version;
	}
	
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('msg').innerHTML = "<?php echo  lang('v_rpt_ve_competeLoad')?>";	
			document.getElementById('msg').style.display="block";		
			jsonData=eval("("+msg+")");	
			contrast_data = jsonData;
					
			if(document.getElementById("versinlist").value!="")							
			{
				clearSel(document.getElementById("versinlist")); 
			}	
					
			for(j = 0;j<jsonData[1].length;j++)
		    {
			    if(styleName == "NewUser")	 
			    { 
				    document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['newuserpercent']+'</td><td>'+jsonData[1][j]['newuserpercent']+'</td></tr>';
			    }
			    if(styleName == "ActiveUser")
			    {  
				     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['startuserpercent']+'</td><td>'+jsonData[1][j]['startuserpercent']+'</td></tr>';
			    }
		    } 
									
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			document.getElementById('msg').innerHTML = "<?php echo  lang('t_error')?>";
			document.getElementById('msg').style.display="block";
			
		},
		beforeSend : function() {
			document.getElementById('msg').innerHTML = '<?php echo  lang('v_rpt_ve_waitLoad')?>';
			document.getElementById('msg').style.display="block";

		},
		complete : function() {
		}
	});
}
</script>
<script type="text/javascript">

function onContrastTabClicked(styleName)
{
	var jsonData = contrast_data;
	
	if(document.getElementById("versinlist").value!="")							
	{
		clearSel(document.getElementById("versinlist")); 
	}	
			
	for(j = 0;j<jsonData[1].length;j++)
    {
	    if(styleName == "NewUser")	 
	     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['newuserpercent']+'</td><td>'+jsonData[1][j]['newuserpercent']+'</td></tr>';
	    if(styleName == "ActiveUser")
		     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['startuserpercent']+'</td><td>'+jsonData[1][j]['startuserpercent']+'</td></tr>';
	    
    } 
}
</script>
<script type="text/javascript">
function setTBodyInnerHTML(tbody, html) {
	  var temp = tbody.ownerDocument.createElement('div');
	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
	}       
</script>
<script type="text/javascript">
function clearSel(selectname){
    
	  while(selectname.childNodes.length>0){
		  selectname.removeChild(selectname.childNodes[0]);
	  }
}  
</script>
