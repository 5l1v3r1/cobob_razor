<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li {cursor: pointer; padding: 2px 10px; color: #999;}
.submit_link .tabs li.active {color: #333;}
#container table td,#container table th {padding: 10px 15px;}
.tablesorter thead tr {text-indent: 0;}
#container table tr:nth-child(2n){background: #eee;}
#ope a {float: left; margin: 5px 0 5px 15px; height: 25px; line-height: 25px;}
#form {float: left; height: 25px; line-height: 25px; margin: 5px 0 5px 15px;}
#File {border: 1px solid #ddd;}
#caseViolette {
	background-color : #333;
	height : 100px;
	width : 200px;
	padding-top : 10px;
	position : fixed;
	margin-left: -100px;
	top: 45%;
	left: 50%;
	border-radius: 25px;
	opacity: 0.8;
	display: none;
}
#caseViolette #load {
  color : #fff;
  font-family : calibri;
  text-align : center;
  margin-top : 65px;
}
/****DEBUT CERCLE****/
#cercle {
  height : 50px;
  width : 50px;
  position : absolute;
  top : 15px;
  left : 75px;
  border-radius : 50%;
  background : linear-gradient(#fff,#333);
  animation : turnCercle 2s infinite;
  -webkit-animation : turnCercle 5s infinite;
  animation-timing-function : ease-in-out;
  -webkit-animation-timing-function : ease-in-out;
}

@-webkit-keyframes turnCercle {
  0% {-webkit-transform : rotate(0deg);}
  100% {-webkit-transform : rotate(10080deg);}
}

@keyframes turnCercle {
  0% {transform : rotate(0deg);}
  100% {transform : rotate(10080deg);}
}

#cercleCache {
  height : 40px;
  width : 40px;
  position : absolute;
  border-radius : 50%;
  background-color : #333;
  z-index : 5;
  margin: 5px;
}
/****FIN CERCLE****/

/****DEBUT POINT****/
#point {
  height : 2px;
  width : 2px;
  position : relative;
  top : -52px;
  left : 96px;
  border-radius : 50%;
  background-color : #fff;
  animation : point 1.5s infinite;
  -webkit-animation : point 1.5s infinite;
  animation-timing-function : linear;
  -webkit-animation-timing-function : linear;
}

@-webkit-keyframes point {
  0% {left : 96px; opacity : 0;}
  5% {left : 96px; opacity : 1;}
  15% {left : 96px; opacity : 0;}
  30% {left : 99px; opacity : 0;}
  45% {left : 99px; opacity : 1;}
  60% {left : 99px; opacity : 0;}
  75% {left : 102px; opacity : 0;}
  90% {left : 102px; opacity : 1;}
  100% {left : 102px; opacity : 0;}
}

@keyframes point {
  0% {left : 96px; opacity : 0;}
  5% {left : 96px; opacity : 1;}
  15% {left : 96px; opacity : 0;}
  30% {left : 99px; opacity : 0;}
  45% {left : 99px; opacity : 1;}
  60% {left : 99px; opacity : 0;}
  75% {left : 102px; opacity : 0;}
  90% {left : 102px; opacity : 1;}
  100% {left : 102px; opacity : 0;}
}
/****FIN POINT****/
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('m_daoRuZhuPeiZhiBiao_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active">行为表</li>
					<li>行为类型表</li>
					<li>功能表</li>
					<li>开服活动</li>
					<li>运营活动</li>
					<li>道具表</li>
					<li>属性货币表</li>
					<li>关卡表</li>
					<li>新手任务</li>
					<li>主线任务</li>
					<li>支线任务</li>
					<li>一般任务</li>
					<li>错误码</li>
					<li>新用户进度步骤</li>
				</ul>
			</div>
		</header>
		<div id="ope">
			<form id="form" name="form"  method="post" enctype="multipart/form-data" action="<?php echo site_url() ;?>/manage/mainconfig/excel">
				<input id="File" type="file" name="userfile" placeholder="请上传xls文件" />
				<input type="button" class="submit_btn" value="导入主配置表Excel" />
			</form>
			<a href="assets/template/basketball20160427.xls">下载主配置表Excel模板</a>
		</div>
		<div id="container" class="module_content">
			<!-- 行为表 -->
			<div class="show" id="action">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>游戏ID（由razor管理员提供）</th>
							<th>行为ID(编号唯一)</th>
							<th>行为名称</th>
							<th>行为描述</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($action as $value) {
								echo "<tr>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['action_id']."</td>";
								echo	"<td>".$value['action_name']."</td>";
								echo	"<td>".$value['action_desc']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 行为类型表 -->
			<div class="show hide" id="actiontype">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>游戏ID（由razor管理员提供）</th>
							<th>编号(编号唯一)</th>
							<th>名称</th>
							<th>分类</th>
							<th>是否拆分</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($actiontype as $value) {
								echo "<tr>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['actiontype_id']."</td>";
								echo	"<td>".$value['actiontype_name']."</td>";
								echo	"<td>".$value['action_category']."</td>";
								echo	"<td>".$value['split']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 功能表 -->
			<div class="show hide" id="function">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>游戏ID（由razor管理员提供）</th>
							<th>行为编号(编号唯一)</th>
							<th>行为名称</th>
							<th>功能ID</th>
							<th>功能名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($function as $value) {
								echo "<tr>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['action_id']."</td>";
								echo	"<td>".$value['action_name']."</td>";
								echo	"<td>".$value['function_id']."</td>";
								echo	"<td>".$value['function_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 开服活动 -->
			<div class="show hide" id="newserveractivity">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>游戏ID（由razor管理员提供）</th>
							<th>编号(编号唯一)</th>
							<th>名称</th>
							<th>期号</th>
							<th>开始日期</th>
							<th>结束日期</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($newserveractivity as $value) {
								echo "<tr>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['newserveractivity_id']."</td>";
								echo	"<td>".$value['newserveractivity_name']."</td>";
								echo	"<td>".$value['newserveractivity_issue']."</td>";
								echo	"<td>".$value['startdate']."</td>";
								echo	"<td>".$value['enddate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 运营活动 -->
			<div class="show hide" id="operationactivity">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>游戏ID（由razor管理员提供）</th>
							<th>编号(编号唯一)</th>
							<th>名称</th>
							<th>期号</th>
							<th>开始日期</th>
							<th>结束日期</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($operationactivity as $value) {
								echo "<tr>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['operationactivity_id']."</td>";
								echo	"<td>".$value['operationactivity_name']."</td>";
								echo	"<td>".$value['operationactivity_issue']."</td>";
								echo	"<td>".$value['startdate']."</td>";
								echo	"<td>".$value['enddate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 道具表 -->
			<div class="show hide" id="prop">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>编号(编号唯一)</th>
							<th>道具名称</th>
							<th>分类</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($prop as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['prop_id']."</td>";
								echo	"<td>".$value['prop_name']."</td>";
								echo	"<td>".$value['prop_category']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 属性货币表 -->
			<div class="show hide" id="propertymoney">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>编号(编号唯一)</th>
							<th>名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($propertymoney as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['propertymoney_id']."</td>";
								echo	"<td>".$value['propertymoney_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 关卡表 -->
			<div class="show hide" id="tollgate">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>关卡大类id</th>
							<th>关卡大类名称</th>
							<th>小关卡id</th>
							<th>小关卡名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($tollgate as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['tollgate_bigcategory_id']."</td>";
								echo	"<td>".$value['tollgate_bigcategory_name']."</td>";
								echo	"<td>".$value['tollgate_smallcategory_id']."</td>";
								echo	"<td>".$value['tollgate_smallcategory_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 新手任务 -->
			<div class="show hide" id="newusertask">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>任务ID</th>
							<th>任务名称</th>
							<th>步骤</th>
							<th>步骤名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($newusertask as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['task_id']."</td>";
								echo	"<td>".$value['task_name']."</td>";
								echo	"<td>".$value['step_id']."</td>";
								echo	"<td>".$value['step_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 主线任务 -->
			<div class="show hide" id="mainlinetask">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>任务ID</th>
							<th>任务名称</th>
							<th>步骤</th>
							<th>步骤名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($mainlinetask as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['task_id']."</td>";
								echo	"<td>".$value['task_name']."</td>";
								echo	"<td>".$value['step_id']."</td>";
								echo	"<td>".$value['step_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 支线任务 -->
			<div class="show hide" id="branchlinetask">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>任务ID</th>
							<th>任务名称</th>
							<th>步骤</th>
							<th>步骤名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($branchlinetask as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['task_id']."</td>";
								echo	"<td>".$value['task_name']."</td>";
								echo	"<td>".$value['step_id']."</td>";
								echo	"<td>".$value['step_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 一般任务 -->
			<div class="show hide" id="generaltask">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>任务ID</th>
							<th>任务名称</th>
							<th>步骤</th>
							<th>步骤名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($generaltask as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['task_id']."</td>";
								echo	"<td>".$value['task_name']."</td>";
								echo	"<td>".$value['step_id']."</td>";
								echo	"<td>".$value['step_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 错误码 -->
			<div class="show hide" id="errorcode">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>错误码ID</th>
							<th>错误码名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($errorcode as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['errorcode_id']."</td>";
								echo	"<td>".$value['errorcode_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
			<!-- 新用户进度步骤 -->
			<div class="show hide" id="newuserprogress">
				<table class="tablesorter" cellspacing="0"> 
					<thead> 
						<tr>
							<th>编号</th>
							<th>游戏ID（由razor管理员提供）</th>
							<th>步骤ID</th>
							<th>步骤名称</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($newuserprogress as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['app_id']."</td>";
								echo	"<td>".$value['newuserprogress_id']."</td>";
								echo	"<td>".$value['newuserprogress_name']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table> 
			</div>
		</div>
	</article>
</section>
<div id="caseViolette">
	<div id="cercle">
		<div id="cercleCache"></div>
	</div>
	<div id="load">
		<p>正在导入EXCEL，请勿做其他操作</p>
	</div>
	<div id="point"></div>
</div>
<script>
$(document).ready(function(){
	$('.submit_link li').bind('click',function(){
		$('#container > div.show').hide();
		var current = $(this).index();
		$('#container > div.show').eq(current).show();
	});
	$('.submit_btn').bind('click',function(){
		if($('#File').val() == ''){
			alert('请选择一个文件');
		}
		else{
			var r = confirm("当前游戏是'"+game_name+"',确认导入吗?");
			if(r){
				var FileName = $('#File').val();
				var extension = new String (FileName.substring(FileName.lastIndexOf(".")+1,FileName.length));
				if(extension == 'xls'){
					$('#caseViolette').fadeIn();
					$('#form').submit();
				}
				else{
					alert('只允许上传.xls文件,其他类型的文档需要转换');
				}
			}
		}
	});
	var game_name = '';
	$('#select_head option').each(function(){
		if($(this).attr('selected') == 'selected'){
			game_name = $(this).text();
		}
	});
});
</script>