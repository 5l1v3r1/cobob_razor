<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title></title>
<link rel="icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
<link rel="Bookmark" href="<?php echo base_url()?>favicon.ico"/>
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/jquery.select.css"
	type="text/css" media="screen" />
<script src="<?php echo base_url();?>assets/js/jquery.select.js"
	type="text/javascript"></script>	

<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/english_layout.css" type="text/css" media="screen" />-->
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/layout.css"
	type="text/css" media="screen" />
<link rel="stylesheet"
	href="<?php echo base_url();?>/assets/css/helplayout.css"
	type="text/css" media="screen" />
<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/jquery-ui.css" type="text/css"
	media="screen" />
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/layoutpagination.css"
	type="text/css" media="screen" />
<script src="<?php echo base_url();?>assets/js/json/json2.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/tag/jquery-1.9.1.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/tag/jquery-ui-1.10.3.custom.js"
	type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/tag/jquery-ui-1.10.3.custom.min.js"
	type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-1.9-pack.js"
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

<script src="<?php echo base_url();?>assets/js/charts/highcharts.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/charts/echarts-all.js"
	type="text/javascript" charset="UTF-8"></script>
<script
	src="<?php echo base_url();?>assets/js/charts/modules/exporting.js"
	type="text/javascript"></script>
	</head>
<script>
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
</script>
