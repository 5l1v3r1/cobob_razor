<style>
#container > div {display: none; width: 100%;}
#container > div:first-child {display: block;}
.link .tab {margin: 10px; overflow: hidden;}
.link .tab li {cursor: pointer; padding: 2px 10px; color: #333; float: left; width: 180px; height: 25px; line-height: 25px; border-radius: 3px; background: #eee; margin: 1px;}
.link .tab li.active {color: #fff; background: #666;}
#container table td,#container table th {padding: 10px 15px; white-space: nowrap;}
.tablesorter thead tr {text-indent: 0;}
#container table tr:nth-child(2n){background: #eee;}
.des th {color: #999; font-weight: 300;}
.warning {padding: 10px 15px; background: #ffffcc; color: red;}
#container div.show {overflow: auto;}
</style>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('m_riZhiChaXun_yao')?></h3>
		</header>
		<div class="link">
			<ul class="tab" style="position: relative; top: -5px;">
				<li class="active">设备信息表</li>
				<li>创建角色表</li>
				<li>设备安装表</li>
				<li>登录表</li>
				<li>支付表</li>
				<li>注册表</li>
				<li>实时在线用户数表</li>
				<li>玩家角色等级升级信息记录表</li>
				<li>玩家角色VIP等级升级信息记录表</li>
				<li>经验变化表</li>
				<li>功能统计表</li>
				<li>新手引导表</li>
				<li>道具消耗表</li>
				<li>属性变化表</li>
				<li>道具获得表</li>
				<li>任务表</li>
				<li>关卡表</li>
				<li>新用户进度表</li>
				<li>错误码表</li>
				<li>设备启动表</li>
			</ul>
		</div>
		<div id="container" class="module_content">
			<!-- 设备信息表 -->
			<div class="show" id="clientdata">
				<p class="warning">该表为系统自带表，不支持区服、渠道、版本、日期筛选。</p>
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>serviceversion</th>
							<th>name</th>
							<th>版本号</th>
							<th>应用编号</th>
							<th>手机操作系统编号</th>
							<th>手机操作系统版本</th>
							<th>osaddtional</th>
							<th>语言</th>
							<th>分辨率</th>
							<th>是否是手机</th>
							<th>设备名称</th>
							<th>设备编号</th>
							<th>默认浏览器</th>
							<th>javasupport</th>
							<th>flashversion</th>
							<th>modulename</th>
							<th>imei</th>
							<th>imsi</th>
							<th>salt</th>
							<th>havegps</th>
							<th>havebt</th>
							<th>havewifi</th>
							<th>havegravity</th>
							<th>wifimac</th>
							<th>latitude</th>
							<th>longitude</th>
							<th>时间</th>
							<th>设备IP地址</th>
							<th>应用key值</th>
							<th>service_supplier</th>
							<th>国家</th>
							<th>地区</th>
							<th>城市</th>
							<th>街道</th>
							<th>streetno</th>
							<th>postcode</th>
							<th>network</th>
							<th>isjailbroken</th>
							<th>入库时间</th>
							<th>useridentifier</th>
						</tr>
						<tr>
							<th>id</th>
							<th>serviceversion</th>
							<th>name</th>
							<th>version</th>
							<th>appId</th>
							<th>platform</th>
							<th>osversion</th>
							<th>osaddtional</th>
							<th>language</th>
							<th>resolution</th>
							<th>ismobiledevice</th>
							<th>devicename</th>
							<th>deviceid</th>
							<th>defaultbrowser</th>
							<th>javasupport</th>
							<th>flashversion</th>
							<th>modulename</th>
							<th>imei</th>
							<th>imsi</th>
							<th>salt</th>
							<th>havegps</th>
							<th>havebt</th>
							<th>havewifi</th>
							<th>havegravity</th>
							<th>wifimac</th>
							<th>latitude</th>
							<th>longitude</th>
							<th>date</th>
							<th>clientip</th>
							<th>productkey</th>
							<th>service_supplier</th>
							<th>country</th>
							<th>region</th>
							<th>city</th>
							<th>street</th>
							<th>streetno</th>
							<th>postcode</th>
							<th>network</th>
							<th>isjailbroken</th>
							<th>insertdate</th>
							<th>useridentifier</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($clientdata as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['serviceversion']."</td>";
								echo	"<td>".$value['name']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['platform']."</td>";
								echo	"<td>".$value['osversion']."</td>";
								echo	"<td>".$value['osaddtional']."</td>";
								echo	"<td>".$value['language']."</td>";
								echo	"<td>".$value['resolution']."</td>";
								echo	"<td>".$value['ismobiledevice']."</td>";
								echo	"<td>".$value['devicename']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['defaultbrowser']."</td>";
								echo	"<td>".$value['javasupport']."</td>";
								echo	"<td>".$value['flashversion']."</td>";
								echo	"<td>".$value['modulename']."</td>";
								echo	"<td>".$value['imei']."</td>";
								echo	"<td>".$value['imsi']."</td>";
								echo	"<td>".$value['salt']."</td>";
								echo	"<td>".$value['havegps']."</td>";
								echo	"<td>".$value['havebt']."</td>";
								echo	"<td>".$value['havewifi']."</td>";
								echo	"<td>".$value['havegravity']."</td>";
								echo	"<td>".$value['wifimac']."</td>";
								echo	"<td>".$value['latitude']."</td>";
								echo	"<td>".$value['longitude']."</td>";
								echo	"<td>".$value['date']."</td>";
								echo	"<td>".$value['clientip']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['service_supplier']."</td>";
								echo	"<td>".$value['country']."</td>";
								echo	"<td>".$value['region']."</td>";
								echo	"<td>".$value['city']."</td>";
								echo	"<td>".$value['street']."</td>";
								echo	"<td>".$value['streetno']."</td>";
								echo	"<td>".$value['postcode']."</td>";
								echo	"<td>".$value['network']."</td>";
								echo	"<td>".$value['isjailbroken']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo	"<td>".$value['useridentifier']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 玩家角色信息表 -->
			<div class="show" id="createrole">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>玩家角色创建日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>预留字段一</th>
							<th>预留字段二</th>
							<th>预留字段三</th>
							<th>预留字段四</th>
							<th>预留字段五</th>
							<th>预留字段六</th>
							<th>用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>玩家角色创建时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色性别</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>create_role_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>obligate1</th>
							<th>obligate2</th>
							<th>obligate3</th>
							<th>obligate4</th>
							<th>obligate5</th>
							<th>obligate6</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>create_role_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleSex</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($createrole as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['create_role_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['obligate1']."</td>";
								echo	"<td>".$value['obligate2']."</td>";
								echo	"<td>".$value['obligate3']."</td>";
								echo	"<td>".$value['obligate4']."</td>";
								echo	"<td>".$value['obligate5']."</td>";
								echo	"<td>".$value['obligate6']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['create_role_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleSex']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 安装表 -->
			<div class="show" id="install">
				<p class="warning">该表不支持区服筛选。</p>
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>安装日期</th>
							<th>渠道编号</th>
							<th>appId</th>
							<th>版本号</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>安装时间</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>install_date</th>
							<th>chId</th>
							<th>appId</th>
							<th>version</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>install_time</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($install as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['install_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['install_time']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 登录表 -->
			<div class="show" id="login">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>登录日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>类型(上线、下线)</th>
							<th>下线结算时长</th>
							<th>预留字段一</th>
							<th>预留字段二</th>
							<th>预留字段三</th>
							<th>预留字段四</th>
							<th>用户ID</th>
							<th>玩家角色编号</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>应用key值</th>
							<th>登录时间</th>
							<th>设备编号</th>
							<th>设备IP地址</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>login_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>type</th>
							<th>offlineSettleTime</th>
							<th>obligate1</th>
							<th>obligate2</th>
							<th>obligate3</th>
							<th>obligate4</th>
							<th>userId</th>
							<th>roleId</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>productkey</th>
							<th>login_time</th>
							<th>deviceid</th>
							<th>ip</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($login as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['login_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['type']."</td>";
								echo	"<td>".$value['offlineSettleTime']."</td>";
								echo	"<td>".$value['obligate1']."</td>";
								echo	"<td>".$value['obligate2']."</td>";
								echo	"<td>".$value['obligate3']."</td>";
								echo	"<td>".$value['obligate4']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['login_time']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['ip']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 支付表 -->
			<div class="show" id="pay">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>支付日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>appId</th>
							<th>版本号</th>
							<th>预留字段一</th>
							<th>预留字段二</th>
							<th>预留字段三</th>
							<th>预留字段四</th>
							<th>用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>支付时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>支付单位</th>
							<th>支付类型</th>
							<th>支付金额</th>
							<th>金币数量</th>
							<th>支付订单编号</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>pay_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>obligate1</th>
							<th>obligate2</th>
							<th>obligate3</th>
							<th>obligate4</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>pay_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>pay_unit</th>
							<th>pay_type</th>
							<th>pay_amount</th>
							<th>coinAmount</th>
							<th>orderId</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($pay as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['pay_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['obligate1']."</td>";
								echo	"<td>".$value['obligate2']."</td>";
								echo	"<td>".$value['obligate3']."</td>";
								echo	"<td>".$value['obligate4']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['pay_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['pay_unit']."</td>";
								echo	"<td>".$value['pay_type']."</td>";
								echo	"<td>".$value['pay_amount']."</td>";
								echo	"<td>".$value['coinAmount']."</td>";
								echo	"<td>".$value['orderId']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 注册表 -->
			<div class="show" id="register">
				<p class="warning">该表不支持区服筛选。</p>
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>注册日期</th>
							<th>渠道编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>预留字段一</th>
							<th>预留字段二</th>
							<th>预留字段三</th>
							<th>预留字段四</th>
							<th>用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>注册时间</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>register_date</th>
							<th>chId</th>
							<th>appId</th>
							<th>version</th>
							<th>obligate1</th>
							<th>obligate2</th>
							<th>obligate3</th>
							<th>obligate4</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>register_time</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($register as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['register_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['obligate1']."</td>";
								echo	"<td>".$value['obligate2']."</td>";
								echo	"<td>".$value['obligate3']."</td>";
								echo	"<td>".$value['obligate4']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['register_time']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 实时在线用户数表 -->
			<div class="show" id="realtimeonlineusers">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>统计日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>统计时间</th>
							<th>实时在线用户数</th>
							<th>入库时间</th>
							<th>productkey</th>
						</tr>
						<tr>
							<th>id</th>
							<th>count_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>count_time</th>
							<th>onlineusers</th>
							<th>insertdate</th>
							<th>productkey</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($realtimeonlineusers as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['count_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['count_time']."</td>";
								echo	"<td>".$value['onlineusers']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 玩家角色等级升级信息记录表 -->
			<div class="show" id="levelupgrade">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>玩家等级升级日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>玩家等级升级时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家当前角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>levelupgrade_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>levelupgrade_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($levelupgrade as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['levelupgrade_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['levelupgrade_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 玩家角色VIP等级升级信息记录表 -->
			<div class="show" id="viplevelup">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>玩家VIP等级升级日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>玩家VIP等级升级时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家当前角色等级</th>
							<th>当前玩家角色VIP等级</th>
							<th>升级前玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>viplevelup_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>viplevelup_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>currentRoleVip</th>
							<th>beforeRoleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($viplevelup as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['viplevelup_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['levelupgrade_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['currentRoleVip']."</td>";
								echo	"<td>".$value['beforeRoleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 经验变化表 -->
			<div class="show" id="experiencevariation">
				<table class="tablesorter" cellspacing="0"> 
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>经验变化日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>行为id</th>
							<th>经验数</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>经验变化时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>experiencevariation_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>actionid</th>
							<th>experience</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>experiencevariation_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($experiencevariation as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['experiencevariation_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['actionid']."</td>";
								echo	"<td>".$value['experience']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['experiencevariation_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 功能统计表 -->
			<div class="show" id="functioncount">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>功能统计日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>事件id</th>
							<th>活动期号</th>
							<th>功能ID</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>功能统计时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>functioncount_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>eventid</th>
							<th>issue</th>
							<th>functionid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>functioncount_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($functioncount as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['functioncount_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['eventid']."</td>";
								echo	"<td>".$value['issue']."</td>";
								echo	"<td>".$value['functionid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['functioncount_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 新手引导表 -->
			<div class="show" id="newuserguide">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>新手引导日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>新手引导id</th>
							<th>新手引导步骤id</th>
							<th>玩家用户ID</th>
							<th>markid</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>新手引导时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>newuserguide_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>newuserguide_id</th>
							<th>stepid</th>
							<th>userId</th>
							<th>markid</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>newuserguide_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($newuserguide as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['newuserguide_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['newuserguide_id']."</td>";
								echo	"<td>".$value['stepid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['markid']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['newuserguide_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 道具消耗表 -->
			<div class="show" id="propconsume">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>道具消耗日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>行为id</th>
							<th>道具id</th>
							<th>道具等级</th>
							<th>道具消耗数量</th>
							<th>道具存量</th>
							<th>功能ID</th>
							<th>行为类型ID</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>道具消耗时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>propconsume_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>actionid</th>
							<th>propid</th>
							<th>proplevel</th>
							<th>propconsume_count</th>
							<th>prop_stock</th>
							<th>functionid</th>
							<th>acionttypeid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>propconsume_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($propconsume as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['propconsume_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['actionid']."</td>";
								echo	"<td>".$value['propid']."</td>";
								echo	"<td>".$value['proplevel']."</td>";
								echo	"<td>".$value['propconsume_count']."</td>";
								echo	"<td>".$value['prop_stock']."</td>";
								echo	"<td>".$value['functionid']."</td>";
								echo	"<td>".$value['acionttypeid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['propconsume_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 属性变化表 -->
			<div class="show" id="propertyvariation">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>属性变化日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>行为id</th>
							<th>属性</th>
							<th>属性加减 0 减 1 加</th>
							<th>数量</th>
							<th>当前存量</th>
							<th>功能ID</th>
							<th>行为类型ID</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>属性变化时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>propertyvariation_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>actionid</th>
							<th>property</th>
							<th>property_variation</th>
							<th>count</th>
							<th>stock</th>
							<th>functionid</th>
							<th>acionttypeid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>propertyvariation_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($propertyvariation as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['propertyvariation_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['actionid']."</td>";
								echo	"<td>".$value['property']."</td>";
								echo	"<td>".$value['property_variation']."</td>";
								echo	"<td>".$value['count']."</td>";
								echo	"<td>".$value['stock']."</td>";
								echo	"<td>".$value['functionid']."</td>";
								echo	"<td>".$value['acionttypeid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['propertyvariation_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 道具获得表 -->
			<div class="show" id="propgain">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>道具获得日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>行为id</th>
							<th>道具id</th>
							<th>道具等级</th>
							<th>获得道具数量</th>
							<th>道具存量</th>
							<th>功能ID</th>
							<th>行为类型ID</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>道具获得时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>propgain_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>actionid</th>
							<th>propid</th>
							<th>proplevel</th>
							<th>propgain_count</th>
							<th>prop_stock</th>
							<th>functionid</th>
							<th>acionttypeid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>propgain_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($propgain as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['propgain_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['actionid']."</td>";
								echo	"<td>".$value['propid']."</td>";
								echo	"<td>".$value['proplevel']."</td>";
								echo	"<td>".$value['propgain_count']."</td>";
								echo	"<td>".$value['prop_stock']."</td>";
								echo	"<td>".$value['functionid']."</td>";
								echo	"<td>".$value['acionttypeid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['propgain_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 任务表 -->
			<div class="show" id="task">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>任务日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>任务id</th>
							<th>步骤id</th>
							<th>完成标识(1完成,0未完成)</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>任务时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>task_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>taskid</th>
							<th>stepid</th>
							<th>markid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>task_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($task as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['task_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['taskid']."</td>";
								echo	"<td>".$value['stepid']."</td>";
								echo	"<td>".$value['markid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['task_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 关卡表 -->
			<div class="show" id="tollgate">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>关卡日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>关卡大类id</th>
							<th>关卡id</th>
							<th>关卡</th>
							<th>扫荡标识(1扫荡,0非扫荡)</th>
							<th>是否通关 1-通关 2-未通关</th>
							<th>通关耗时 单位:秒</th>
							<th>是否战斗超时 0-未超时 1-超时</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>关卡时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>tollgate_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>moduleid</th>
							<th>tollgateid</th>
							<th>tollgategrade</th>
							<th>tollgatesweep</th>
							<th>pass</th>
							<th>passtime</th>
							<th>combattimeout</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>tollgate_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($tollgate as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['tollgate_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['moduleid']."</td>";
								echo	"<td>".$value['tollgateid']."</td>";
								echo	"<td>".$value['tollgategrade']."</td>";
								echo	"<td>".$value['tollgatesweep']."</td>";
								echo	"<td>".$value['pass']."</td>";
								echo	"<td>".$value['passtime']."</td>";
								echo	"<td>".$value['combattimeout']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['tollgate_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 新用户进度表 -->
			<div class="show" id="newuserprogress">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>新用户进度日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>新用户进度id</th>
							<th>步骤id。1开始</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>新用户进度时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>newuserprogress_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>newuserprogressid</th>
							<th>stepid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>newuserprogress_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($newuserprogress as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['newuserprogress_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['newuserprogressid']."</td>";
								echo	"<td>".$value['stepid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['newuserprogress_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 错误码表 -->
			<div class="show" id="errorcode">
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>错误码日期</th>
							<th>渠道编号</th>
							<th>合区区服编号</th>
							<th>区服编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>错误码id</th>
							<th>行为id</th>
							<th>功能ID</th>
							<th>玩家用户ID</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>新用户进度时间</th>
							<th>玩家角色编号</th>
							<th>玩家角色名称</th>
							<th>玩家角色等级</th>
							<th>玩家角色VIP等级</th>
							<th>金币存量</th>
							<th>银币存量</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>errorcode_date</th>
							<th>chId</th>
							<th>subSrvId</th>
							<th>srvId</th>
							<th>appId</th>
							<th>version</th>
							<th>errorcodeid</th>
							<th>aciontid</th>
							<th>functionid</th>
							<th>userId</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>errorcode_time</th>
							<th>roleId</th>
							<th>roleName</th>
							<th>roleLevel</th>
							<th>roleVip</th>
							<th>goldCoin</th>
							<th>sliverCoin</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($errorcode as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['errorcode_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['subSrvId']."</td>";
								echo	"<td>".$value['srvId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['errorcodeid']."</td>";
								echo	"<td>".$value['aciontid']."</td>";
								echo	"<td>".$value['functionid']."</td>";
								echo	"<td>".$value['userId']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['errorcode_time']."</td>";
								echo	"<td>".$value['roleId']."</td>";
								echo	"<td>".$value['roleName']."</td>";
								echo	"<td>".$value['roleLevel']."</td>";
								echo	"<td>".$value['roleVip']."</td>";
								echo	"<td>".$value['goldCoin']."</td>";
								echo	"<td>".$value['sliverCoin']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
			<!-- 设备启动表 -->
			<div class="show" id="deviceboot">
				<p class="warning">该表不支持渠道、区服筛选。</p>
				<table class="tablesorter" cellspacing="0">
					<thead>
						<tr class="des">
							<th>编号</th>
							<th>设备启动日期</th>
							<th>渠道编号</th>
							<th>应用编号</th>
							<th>版本号</th>
							<th>应用key值</th>
							<th>设备编号</th>
							<th>设备启动时间</th>
							<th>入库时间</th>
						</tr>
						<tr>
							<th>id</th>
							<th>deviceboot_date</th>
							<th>chId</th>
							<th>appId</th>
							<th>version</th>
							<th>productkey</th>
							<th>deviceid</th>
							<th>deviceboot_time</th>
							<th>insertdate</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($deviceboot as $value) {
								echo "<tr>";
								echo	"<td>".$value['id']."</td>";
								echo	"<td>".$value['deviceboot_date']."</td>";
								echo	"<td>".$value['chId']."</td>";
								echo	"<td>".$value['appId']."</td>";
								echo	"<td>".$value['version']."</td>";
								echo	"<td>".$value['productkey']."</td>";
								echo	"<td>".$value['deviceid']."</td>";
								echo	"<td>".$value['deviceboot_time']."</td>";
								echo	"<td>".$value['insertdate']."</td>";
								echo"</tr>";
							}
						?>
					</tbody>
				</table>
				<footer><div class="submit_link hasPagination"></div></footer>
			</div>
		</div>
	</article>
</section>
<script>
$(document).ready(function(){
	$('.link li').bind('click',function(){
		$('#container > div.show').hide();
		var current = $(this).index();
		$('#container > div.show').eq(current).show();
		$('.link li').removeClass('active');
		$(this).addClass('active');
		sessionStorage.setItem("tabCurrent",current);
	});
	if(sessionStorage.getItem("tabCurrent")){
		$('#container > div.show').hide();
		var current = sessionStorage.getItem("tabCurrent");
		$('#container > div.show').eq(current).show();
		$('.link li').removeClass('active');
		$('.link li').eq(current).addClass('active');
	}
});
</script>