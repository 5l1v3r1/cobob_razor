<style>
.container {padding: 20px;}
.container span {margin-right: 20px;}
</style>
<section id="main" class="column">
	<h4 class="alert_info" id='msg' style="display:none;"></h4> 
	<article class="module width_full">
	<header><h3 class="tabs_involved">账号解锁</h3></header>
	<div class="container">
		<span>是否要清除所有锁定用户？</span>
		<span><input type="button" value="清除" onclick="toClear();"></span>
	</div>
	</article>
</section>
<script>
	function toClear()
	{
		var url = "<?php echo site_url()?>/user/clearLockedUser";
		$.get(url,function(res){
			if(res == '1'){
				alert('清除成功');
			}
		});
	}
</script>