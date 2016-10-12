<section id="main" class="column">
	<!-- 新增用户-图表 -->
	<div style="height:340px;">
	<iframe src="<?php echo site_url() ?>/useranalysis/newusers/addnewusersreport"  frameborder="0" scrolling="no" style="width:100%;height:100%;"></iframe>		
	</div>


	<!-- 新增用户-数据列表	 -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('t_datalist_yao')?></h3>
				<!-- 导出CSV -->
				<span class="relative r">
					<a href="<?php echo site_url(); ?>/useranalysis/newusers/exportdetaildata" class="bottun4 hover" >
						<font><?php echo  lang('g_exportToCSV');?></font>
					</a>
				</span>					
		</header>


		
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
					<!-- 日期 -->
					<th><?php echo  lang('g_date')?></th>
					<!-- 设备激活 -->
					<th><?php echo  lang('t_deviceActivation_yao')?></th> 
					<!-- 新增用户 -->
					<th><?php echo  lang('t_newuser_analysis_yao')?></th> 
					<!-- 新增设备 -->
					<th><?php echo  lang('t_newDevice_yao')?></th> 
					<!-- 转化率 -->
					<th><?php echo  lang('t_userConversion_yao')?></th>
				</tr> 
			</thead> 
			<tbody id="content">
	    		<?php $num = count($newUsersData);?>
			</tbody>
		</table> 
		<footer>
		<!-- 分页 -->
		<div id="pagination"  class="submit_link">
		</div>
		</footer>
	</article>

</section>

<script>
//When page loads...
$(document).ready(function() {	
    initPagination();
	pageselectCallback(0,null);	
	addreportwidgets();		
});

function deletereport(deletename)
{	
 $('#'+deletename).remove();
  var data={ 
	       reportname:deletename,
	       type:1
	     };
   jQuery.ajax({
				type :  "post",
				url  :  "<?php echo site_url()?>/report/dashboard/deleteshowreport",	
				data :  data,			
				success : function(msg) {	
					 changesectionheight(-1);     					
				},
				error : function(XmlHttpRequest, textStatus, errorThrown) {
					alert("<?php echo lang('t_error') ?>");
				}
			});
}
</script>
<script type="text/javascript">
function addreportwidgets()
{
	var reportinfo=eval(<?php if(isset($addreport)){$report=$addreport->result();echo "'".json_encode($report)."'";}?>);
    if(reportinfo!=eval())
	{  
    	changesectionheight(reportinfo.length);
		var realtype;
		var reporthtml="";
		var divclass;
		var src;
		for(i=0;i<reportinfo.length;i++)
		{		
			src="<?php echo site_url() ?>"+reportinfo[i].src;	
			reporthtml = reporthtml+ "<iframe id='"+reportinfo[i].reportname+"' src='"+src+"/del/'   frameborder='0' scrolling='no'style='width:100%;height:"+reportinfo[i].height+"px;margin: 10px 3% 0 0.3%;'>";
			reporthtml = reporthtml+"</iframe>";		
		}
		$('#addreportregion').html(reporthtml);
    }	
    else
    {
    	changesectionheight(0);
    }
}
</script>
<script type="text/javascript">
var newusersdata = eval(<?php echo "'".json_encode($newUsersData)."'"?>);
function pageselectCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";	
	for(i=0;i<pagenum && (index+i)<newusersdata.length ;i++)
	{ 
		var userconversion ;
 		if(newusersdata[i+index].deviceactivations==0)
     	{
 			userconversion = 0;
 		}
 		else
     	{
 			userconversion =(newusersdata[i+index].newusers/newusersdata[i+index].deviceactivations)*100;
     	}
		msg = msg+"<tr><td>";
		msg = msg+ newusersdata[i+index].date;
		msg = msg+"</td><td>";
		msg = msg+ newusersdata[i+index].deviceactivations;
		msg = msg+"</td><td>";
		msg = msg+ newusersdata[i+index].newusers;
		msg = msg+"</td><td>";
		msg = msg+ newusersdata[i+index].newdevices;
		msg = msg+"</td><td>";
		msg = msg+ (userconversion).toFixed(2)+"%</td>";
		msg = msg+"</tr>";
	}
	$('#content').html(msg);
   // document.getElementById('content').innerHTML = msg;				
   return false;
 }

           
/** 
* Callback function for the AJAX content loader.
 */
function initPagination() {
  var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
  // Create pagination element
  $("#pagination").pagination(num_entries, {
     num_edge_entries: 2,
     prev_text: '<?php echo  lang('g_previousPage')?>',
     next_text: '<?php echo  lang('g_nextPage')?>',           
     num_display_entries: 4,
     callback: pageselectCallback,
     items_per_page:1               
           });
             }
      
</script>
<!-- easydialog -->
<script type="text/javascript">

//add  widgets
 function addwidgetsreport()
  {	
	easyDialog.open({
		container : {
			header : '<?php echo  lang('w_addreport'); ?>',
			content :'<iframe id="widgetslist"  src="<?php echo site_url(); ?>/report/dashboard/loadwidgetslist" frameborder="0" scrolling="no" style="height:400px;"></iframe>',
			yesFn :addreportwidget ,
			noFn : true
		}, 
		fixed : false
	});
}

 var addreportwidget = function(){	
		var obj ;
		var reportvalue;
		 if (document.all)
		 {    //IE
			 obj = document.frames["widgetslist"].document;
	     }
		 else
		 {
			 //Firefox    
			 obj = document.getElementById("widgetslist").contentDocument;
	     }	  
		var item = obj.getElementsByName("reportname"); 
		var canadd=obj.getElementById("overnum").innerHTML; 
		if(canadd=="")
		{	
			//get checkbox checked num
			 var checknum=0
			 for (var i = 0; i < item.length; i++)  
			 {	       		                  	        
			    if(item[i].checked==true)				    		    	  
			    { 		    	  	 
				    checknum++			   		   
				}			   	   	     
			 }			
			 changesectionheight(checknum);
			 //deal with add or delete reports
			var reportgroup = new Array();  
		    for (var i = 0; i < item.length; i++)  
		    {
		    	var str = item[i].value;  
		    	var report=str.split("/");
		        var reportcontroller=report[0];
		        var reportname=report[1];			  	    
		        var height= report[2];		       		                  	        
		        if(item[i].checked==true && document.getElementById(reportname)==null)				    		    	  
		    	{ 		    	  	 
			    	var data={
				  		  	     reportname:reportname,
				  		  	     controller:reportcontroller,
					  		  	 height    :height,
					  		  	 type      :1
					  		  	 
				  		  	    }; 	  
						jQuery.ajax({
										type :  "post",
										url  :  "<?php echo site_url()?>/report/dashboard/addshowreport",	
										data :  data,			
										success : function(msg) {
										if(msg)
										{											
											document.getElementById("addreportregion").innerHTML+=msg;																								
										}		 
										},
										error : function(XmlHttpRequest, textStatus, errorThrown) {
											alert("<?php echo lang('t_error') ?>");
										}
									});					   		   
				 }	
		    	
		    	if(document.getElementById(reportname)!=null&&item[i].checked==false)
				 {   		    		      
							var div = document.getElementById(reportname);
						    var parent = div.parentElement;
						    parent.removeChild(div);    		    	
						    	  var data={
						  		  	     reportname:reportname,
						  		  	     type:1
						  		  	    };
								jQuery.ajax({
												type :  "post",
												url  :  "<?php echo site_url()?>/report/dashboard/deleteshowreport",	
												data :  data,			
												success : function(msg) {						       						        		
												},
												error : function(XmlHttpRequest, textStatus, errorThrown) {
													alert("<?php echo lang('t_error') ?>");
												}
											}); 
				}			    
						
			   	   	     
		    }	       
		  
		}
		else
		{
			return false;
		}
 };	    


</script>
<!-- easydialog --> 
<!-- adjust the height -->
<script type="text/javascript">
function changesectionheight(reportnum)
{
	if(reportnum!=0&&reportnum!=-1)
	{
		if(reportnum<=2)
		{
			var realheight=1300+550*reportnum;				
			document.getElementById("main").style.height =''+realheight+'px';	
			document.getElementById("sidebar").style.height =''+realheight+'px';
		}
		else
		{
			var realheight=1300+500*reportnum;				
			document.getElementById("main").style.height =''+realheight+'px';
			document.getElementById("sidebar").style.height =''+realheight+'px';
		}
							
	}
   else if(reportnum==-1)
   {
	   var cstr=document.getElementById("main").style.height;	  
	   var clength=cstr.length;	  
	   var cheight=cstr.substring(0, clength-2);	   
	   var realheight=parseInt(cheight)+500*reportnum; 
	   document.getElementById("main").style.height =''+realheight+'px';	
	   document.getElementById("sidebar").style.height =''+realheight+'px'; 
   }	
	else
	{
		document.getElementById("main").style.height ="1300px" ;
		document.getElementById("sidebar").style.height ="1300px" ;			
	}	
}
</script>
<!-- adjust the height -->