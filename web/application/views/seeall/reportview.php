<style>
#container > div {display: none; overflow: hidden; width: 100%;}
#container > div:first-child {display: block;}
.submit_link .tabs li a {cursor: pointer;}
#container table td {padding: 15px 0;}
.tablesorter thead tr {text-indent: 0;}
.echarts {height: 260px;}
.w9 {width: 9%;}
.date,.tag {width: 99.5%;}
#container li {height: 30px;}
.i-date {height: 30px; line-height: 30px; border-radius: 5px; border: 1px solid #ddd; background: #666;}
.i-date li {float: left; width: 9%; height: 30px; line-height: 30px!important; text-align: center; color: #fff; box-shadow: -1px 0px 0px #ddd;}
.i-date li.first {margin-left: 9%;}
.tag {overflow: hidden;}
.tag li {float: left; width: 9%; height: 30px; line-height: 30px!important; text-align: center; font-size: 12px; font-weight: bold; color: #77BACE;}
.tag li.first {margin-left: 18%;}
.sub-t {width: 100%; height: 30px; line-height: 30px; background: #E0E0E3;}
.sub-t span {float: left; margin-left: 20px; color: #333; font-weight: bold;}
.data-con .line {width: 9%; float: left;}
.data-con .line li {text-align: center; height: 30px; line-height: 30px!important; border-top: 1px solid #ddd;}
.data-con {overflow: hidden;}
.green {color: green;}
.red {color: red;}
.compare {background: #eee;}
.week .data-con .line,.month .data-con .line,.week .tag li,.month .tag li,.week .i-date li,.month .i-date li {width: 10%;}
.week .tag li.first,.month .tag li.first {margin-left: 20%;}
.week .i-date li.first,.month .i-date li.first {margin-left: 10%;}
.week .i-date li {height: 40px!important; line-height: 10px!important; padding-top: 10px;}
.week .i-date {height: 50px;}
</style>
<?php 

 ?>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('t_baoBiao_yao')?></h3>
			<div class="submit_link">
				<ul class="tabs" style="position: relative; top: -5px;">
					<li class="active"><a>日报</a></li>
					<li><a>周报</a></li>
					<li><a>月报</a></li>
				</ul>
			</div>
		</header>
		<div id="container" class="module_content">
			<div class="tab">
				<div class="tag">
					<ul>
						<li class="first">环比昨日</li>
						<li>同比上周</li>
					</ul>
				</div>
				<div class="i-date">
					<ul>
						<li class="first"><?php echo $day_result[0]['countdate']; ?></li>
						<li><?php echo $day_result[1]['countdate']; ?></li>
						<li><?php echo $day_result[7]['countdate']; ?></li>
						<?php 
							for($i=7;$i>0;$i--){
								echo "<li>".$day_result[$i]['countdate']."</li>";
							}
						?>
					</ul>
				</div>
				<div class="sub-t">
					<span>基本数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>设备激活</li>
							<li>新增设备</li>
							<li>新增注册</li>
							<li>新增用户</li>
							<li>注册转化率(%)</li>
							<li>活跃用户</li>
							<li>ACU</li>
							<li>PCU</li>
							<li>游戏次数</li>
							<li>平均日游戏时长(分)</li>
							<li>平均日游戏次数</li>
							<li>设备次日留存</li>
							<li>设备3日留存</li>
							<li>设备7日留存</li>
							<li>新增用户次日留存</li>
							<li>新增用户3日留存</li>
							<li>新增用户7日留存</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $day_result[0]['day_Deviceactivations']; ?></li>
							<li><?php  echo $day_result[0]['day_Newdevices']; ?></li>
							<li><?php  echo $day_result[0]['day_Newusers']; ?></li>
							<li><?php  echo $day_result[0]['day_Newuser']; ?></li>
							<li><?php  echo $day_result[0]['day_userconversion']; ?></li>
							<li><?php  echo $day_result[0]['day_Dauuser']; ?></li>
							<li><?php  echo sprintf("%.2f",$day_result[0]['day_Acu']); ?></li>
							<li><?php  echo $day_result[0]['day_Pcu']; ?></li>
							<li><?php  echo $day_result[0]['day_Gamecount']; ?></li>
							<li><?php  echo sprintf("%.2f",$day_result[0]['day_avggametime']); ?></li>
							<li><?php  echo sprintf("%.2f",$day_result[0]['day_avggamecount']); ?></li>
							<li><?php  echo floatval($day_result[0]['day_1day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[0]['day_3day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[0]['day_7day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[0]['day_1day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[0]['day_3day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[0]['day_7day_newuserremainrate'])*100; ?>%</li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($day_result[0]['day_Deviceactivations'],$day_result[1]['day_Deviceactivations']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Newdevices'],$day_result[1]['day_Newdevices']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Newusers'],$day_result[1]['day_Newusers']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Newuser'],$day_result[1]['day_Newuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_userconversion'],$day_result[1]['day_userconversion']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Dauuser'],$day_result[1]['day_Dauuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Acu'],$day_result[1]['day_Acu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Pcu'],$day_result[1]['day_Pcu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Gamecount'],$day_result[1]['day_Gamecount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_avggametime'],$day_result[1]['day_avggametime']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_avggamecount'],$day_result[1]['day_avggamecount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_1day_deviceremainrate'],$day_result[1]['day_1day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_3day_deviceremainrate'],$day_result[1]['day_3day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_7day_deviceremainrate'],$day_result[1]['day_7day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_1day_newuserremainrate'],$day_result[1]['day_1day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_3day_newuserremainrate'],$day_result[1]['day_3day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_7day_newuserremainrate'],$day_result[1]['day_7day_newuserremainrate']); ?>%</li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($day_result[0]['day_Deviceactivations'],$day_lastWeek_date_result['day_Deviceactivations']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Newdevices'],$day_lastWeek_date_result['day_Newdevices']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Newusers'],$day_lastWeek_date_result['day_Newusers']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Newuser'],$day_lastWeek_date_result['day_Newuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_userconversion'],$day_lastWeek_date_result['day_userconversion']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Dauuser'],$day_lastWeek_date_result['day_Dauuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Acu'],$day_lastWeek_date_result['day_Acu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Pcu'],$day_lastWeek_date_result['day_Pcu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Gamecount'],$day_lastWeek_date_result['day_Gamecount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_avggametime'],$day_lastWeek_date_result['day_avggametime']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_avggamecount'],$day_lastWeek_date_result['day_avggamecount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_1day_deviceremainrate'],$day_lastWeek_date_result['day_1day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_3day_deviceremainrate'],$day_lastWeek_date_result['day_3day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_7day_deviceremainrate'],$day_lastWeek_date_result['day_7day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_1day_newuserremainrate'],$day_lastWeek_date_result['day_1day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_3day_newuserremainrate'],$day_lastWeek_date_result['day_3day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_7day_newuserremainrate'],$day_lastWeek_date_result['day_7day_newuserremainrate']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $day_result[$i]['day_Deviceactivations']; ?></li>
							<li><?php  echo $day_result[$i]['day_Newdevices']; ?></li>
							<li><?php  echo $day_result[$i]['day_Newusers']; ?></li>
							<li><?php  echo $day_result[$i]['day_Newuser']; ?></li>
							<li><?php  echo $day_result[$i]['day_userconversion']; ?></li>
							<li><?php  echo $day_result[$i]['day_Dauuser']; ?></li>
							<li><?php  echo sprintf("%.2f",$day_result[$i]['day_Acu']); ?></li>
							<li><?php  echo $day_result[$i]['day_Pcu']; ?></li>
							<li><?php  echo $day_result[$i]['day_Gamecount']; ?></li>
							<li><?php  echo sprintf("%.2f",$day_result[$i]['day_avggametime']); ?></li>
							<li><?php  echo sprintf("%.2f",$day_result[$i]['day_avggamecount']); ?></li>
							<li><?php  echo floatval($day_result[$i]['day_1day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[$i]['day_3day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[$i]['day_7day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[$i]['day_1day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[$i]['day_3day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($day_result[$i]['day_7day_newuserremainrate'])*100; ?>%</li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
				<div class="sub-t">
					<span>付费数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>付费金额(¥)</li>
							<li>新增付费用户数量</li>
							<li>付费用户数量</li>
							<li>日付费率(%)</li>
							<li>新增ARPU</li>
							<li>活跃ARPU</li>
							<li>新增ARPPU</li>
							<li>活跃ARPPU</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $day_result[0]['day_Payamount']; ?></li>
							<li><?php  echo $day_result[0]['day_NewPayuser']; ?></li>
							<li><?php  echo $day_result[0]['day_Payuser']; ?></li>
							<li><?php  echo $day_result[0]['day_daypayrate']; ?></li>
							<li><?php  echo $day_result[0]['day_newuserarpu']; ?></li>
							<li><?php  echo $day_result[0]['day_dauuserarpu']; ?></li>
							<li><?php  echo $day_result[0]['day_newuserarppu']; ?></li>
							<li><?php  echo $day_result[0]['day_dauuserarppu']; ?></li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($day_result[0]['day_Payamount'],$day_result[1]['day_Payamount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_NewPayuser'],$day_result[1]['day_NewPayuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Payuser'],$day_result[1]['day_Payuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_daypayrate'],$day_result[1]['day_daypayrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_newuserarpu'],$day_result[1]['day_newuserarpu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_dauuserarpu'],$day_result[1]['day_dauuserarpu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_newuserarppu'],$day_result[1]['day_newuserarppu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_dauuserarppu'],$day_result[1]['day_dauuserarppu']); ?>%</li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($day_result[0]['day_Payamount'],$day_lastWeek_date_result['day_Payamount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_NewPayuser'],$day_lastWeek_date_result['day_NewPayuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_Payuser'],$day_lastWeek_date_result['day_Payuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_daypayrate'],$day_lastWeek_date_result['day_daypayrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_newuserarpu'],$day_lastWeek_date_result['day_newuserarpu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_dauuserarpu'],$day_lastWeek_date_result['day_dauuserarpu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_newuserarppu'],$day_lastWeek_date_result['day_newuserarppu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_dauuserarppu'],$day_lastWeek_date_result['day_dauuserarppu']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $day_result[$i]['day_Payamount']; ?></li>
							<li><?php  echo $day_result[$i]['day_NewPayuser']; ?></li>
							<li><?php  echo $day_result[$i]['day_Payuser']; ?></li>
							<li><?php  echo $day_result[$i]['day_daypayrate']; ?></li>
							<li><?php  echo $day_result[$i]['day_newuserarpu']; ?></li>
							<li><?php  echo $day_result[$i]['day_dauuserarpu']; ?></li>
							<li><?php  echo $day_result[$i]['day_newuserarppu']; ?></li>
							<li><?php  echo $day_result[$i]['day_dauuserarppu']; ?></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
				<div class="sub-t">
					<span>累计数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>累计激活设备</li>
							<li>累计新增注册</li>
							<li>累计新增用户</li>
							<li>累计付费用户</li>
							<li>总体付费率(%)</li>
							<li>累计收入金额(¥)</li>
							<li>累计ARPU</li>
							<li>累计ARPPU</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $day_result[0]['day_total_Deviceactivations']; ?></li>
							<li><?php  echo $day_result[0]['day_total_Newusers']; ?></li>
							<li><?php  echo $day_result[0]['day_total_Newuser']; ?></li>
							<li><?php  echo $day_result[0]['day_total_Payuser']; ?></li>
							<li><?php  echo $day_result[0]['day_total_payrate']; ?></li>
							<li><?php  echo $day_result[0]['day_total_Payamount']; ?></li>
							<li><?php  echo $day_result[0]['day_total_arpu']; ?></li>
							<li><?php  echo $day_result[0]['day_total_arppu']; ?></li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($day_result[0]['day_total_Deviceactivations'],$day_result[1]['day_total_Deviceactivations']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Newusers'],$day_result[1]['day_total_Newusers']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Newuser'],$day_result[1]['day_total_Newuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Payuser'],$day_result[1]['day_total_Payuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_payrate'],$day_result[1]['day_total_payrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Payamount'],$day_result[1]['day_total_Payamount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_arpu'],$day_result[1]['day_total_arpu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_arppu'],$day_result[1]['day_total_arppu']); ?>%</li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($day_result[0]['day_total_Deviceactivations'],$day_lastWeek_date_result['day_total_Deviceactivations']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Newusers'],$day_lastWeek_date_result['day_total_Newusers']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Newuser'],$day_lastWeek_date_result['day_total_Newuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Payuser'],$day_lastWeek_date_result['day_total_Payuser']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_payrate'],$day_lastWeek_date_result['day_total_payrate']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_Payamount'],$day_lastWeek_date_result['day_total_Payamount']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_arpu'],$day_lastWeek_date_result['day_total_arpu']); ?>%</li>
							<li><?php echo compare($day_result[0]['day_total_arppu'],$day_lastWeek_date_result['day_total_arppu']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $day_result[$i]['day_total_Deviceactivations']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_Newusers']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_Newuser']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_Payuser']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_payrate']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_Payamount']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_arpu']; ?></li>
							<li><?php  echo $day_result[$i]['day_total_arppu']; ?></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
			</div>
			<div class="tab week">
				<div class="tag">
					<ul>
						<li class="first">同比上周</li>
					</ul>
				</div>
				<div class="i-date">
					<ul>
						<li class="first"><?php echo $week_result[0]['week_firstday'] ?><br/>~<br/><?php echo $week_result[0]['week_lastday']?></li>
						<li><?php echo $week_result[1]['week_firstday'] ?><br/>~<br/><?php echo $week_result[1]['week_lastday'] ?></li>
						<?php 
							for($i=7;$i>0;$i--){
								echo "<li>".$week_result[$i]['week_firstday']."<br/>~<br/>".$week_result[$i]['week_lastday']."</li>";
							}
						?>
					</ul>
				</div>
				<div class="sub-t">
					<span>基本数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>设备激活</li>
							<li>新增设备</li>
							<li>新增注册</li>
							<li>新增用户</li>
							<li>注册转化率(%)</li>
							<li>活跃用户</li>
							<li>ACU</li>
							<li>PCU</li>
							<li>游戏次数</li>
							<li>平均日游戏时长(分)</li>
							<li>平均日游戏次数</li>
							<li>设备次日留存</li>
							<li>设备3日留存</li>
							<li>设备7日留存</li>
							<li>新增用户次日留存</li>
							<li>新增用户3日留存</li>
							<li>新增用户7日留存</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $week_result[0]['day_Deviceactivations']; ?></li>
							<li><?php  echo $week_result[0]['day_Newdevices']; ?></li>
							<li><?php  echo $week_result[0]['day_Newusers']; ?></li>
							<li><?php  echo $week_result[0]['day_Newuser']; ?></li>
							<li><?php  echo $week_result[0]['day_userconversion']; ?></li>
							<li><?php  echo $week_result[0]['day_Dauuser']; ?></li>
							<li><?php  echo sprintf("%.2f",$week_result[0]['day_Acu']); ?></li>
							<li><?php  echo $week_result[0]['day_Pcu']; ?></li>
							<li><?php  echo $week_result[0]['day_Gamecount']; ?></li>
							<li><?php  echo sprintf("%.2f",$week_result[0]['day_avggametime']); ?></li>
							<li><?php  echo sprintf("%.2f",$week_result[0]['day_avggamecount']); ?></li>
							<li><?php  echo floatval($week_result[0]['day_1day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[0]['day_3day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[0]['day_7day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[0]['day_1day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[0]['day_3day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[0]['day_7day_newuserremainrate'])*100; ?>%</li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($week_result[0]['day_Deviceactivations'],$week_lastWeek_date_result['day_Deviceactivations']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Newdevices'],$week_lastWeek_date_result['day_Newdevices']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Newusers'],$week_lastWeek_date_result['day_Newusers']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Newuser'],$week_lastWeek_date_result['day_Newuser']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_userconversion'],$week_lastWeek_date_result['day_userconversion']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Dauuser'],$week_lastWeek_date_result['day_Dauuser']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Acu'],$week_lastWeek_date_result['day_Acu']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Pcu'],$week_lastWeek_date_result['day_Pcu']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Gamecount'],$week_lastWeek_date_result['day_Gamecount']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_avggametime'],$week_lastWeek_date_result['day_avggametime']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_avggamecount'],$week_lastWeek_date_result['day_avggamecount']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_1day_deviceremainrate'],$week_lastWeek_date_result['day_1day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_3day_deviceremainrate'],$week_lastWeek_date_result['day_3day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_7day_deviceremainrate'],$week_lastWeek_date_result['day_7day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_1day_newuserremainrate'],$week_lastWeek_date_result['day_1day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_3day_newuserremainrate'],$week_lastWeek_date_result['day_3day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_7day_newuserremainrate'],$week_lastWeek_date_result['day_7day_newuserremainrate']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $week_result[$i]['day_Deviceactivations']; ?></li>
							<li><?php  echo $week_result[$i]['day_Newdevices']; ?></li>
							<li><?php  echo $week_result[$i]['day_Newusers']; ?></li>
							<li><?php  echo $week_result[$i]['day_Newuser']; ?></li>
							<li><?php  echo $week_result[$i]['day_userconversion']; ?></li>
							<li><?php  echo $week_result[$i]['day_Dauuser']; ?></li>
							<li><?php  echo sprintf("%.2f",$week_result[$i]['day_Acu']); ?></li>
							<li><?php  echo $week_result[$i]['day_Pcu']; ?></li>
							<li><?php  echo $week_result[$i]['day_Gamecount']; ?></li>
							<li><?php  echo sprintf("%.2f",$week_result[$i]['day_avggametime']); ?></li>
							<li><?php  echo sprintf("%.2f",$week_result[$i]['day_avggamecount']); ?></li>
							<li><?php  echo floatval($week_result[$i]['day_1day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[$i]['day_3day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[$i]['day_7day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[$i]['day_1day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[$i]['day_3day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($week_result[$i]['day_7day_newuserremainrate'])*100; ?>%</li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
				<div class="sub-t">
					<span>付费数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>付费金额(¥)</li>
							<li>新增付费用户数量</li>
							<li>付费用户数量</li>
							<li>日付费率(%)</li>
							<li>新增ARPU</li>
							<li>活跃ARPU</li>
							<li>新增ARPPU</li>
							<li>活跃ARPPU</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $week_result[0]['day_Payamount']; ?></li>
							<li><?php  echo $week_result[0]['day_NewPayuser']; ?></li>
							<li><?php  echo $week_result[0]['day_Payuser']; ?></li>
							<li><?php  echo $week_result[0]['day_daypayrate']; ?></li>
							<li><?php  echo $week_result[0]['day_newuserarpu']; ?></li>
							<li><?php  echo $week_result[0]['day_dauuserarpu']; ?></li>
							<li><?php  echo $week_result[0]['day_newuserarppu']; ?></li>
							<li><?php  echo $week_result[0]['day_dauuserarppu']; ?></li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($week_result[0]['day_Payamount'],$week_result[1]['day_Payamount']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_NewPayuser'],$week_result[1]['day_NewPayuser']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_Payuser'],$week_result[1]['day_Payuser']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_daypayrate'],$week_result[1]['day_daypayrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_newuserarpu'],$week_result[1]['day_newuserarpu']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_dauuserarpu'],$week_result[1]['day_dauuserarpu']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_newuserarppu'],$week_result[1]['day_newuserarppu']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_dauuserarppu'],$week_result[1]['day_dauuserarppu']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $week_result[$i]['day_Payamount']; ?></li>
							<li><?php  echo $week_result[$i]['day_NewPayuser']; ?></li>
							<li><?php  echo $week_result[$i]['day_Payuser']; ?></li>
							<li><?php  echo $week_result[$i]['day_daypayrate']; ?></li>
							<li><?php  echo $week_result[$i]['day_newuserarpu']; ?></li>
							<li><?php  echo $week_result[$i]['day_dauuserarpu']; ?></li>
							<li><?php  echo $week_result[$i]['day_newuserarppu']; ?></li>
							<li><?php  echo $week_result[$i]['day_dauuserarppu']; ?></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
				<div class="sub-t">
					<span>累计数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>累计激活设备</li>
							<li>累计新增注册</li>
							<li>累计新增用户</li>
							<li>累计付费用户</li>
							<li>总体付费率(%)</li>
							<li>累计收入金额(¥)</li>
							<li>累计ARPU</li>
							<li>累计ARPPU</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $week_result[0]['day_total_Deviceactivations']; ?></li>
							<li><?php  echo $week_result[0]['day_total_Newusers']; ?></li>
							<li><?php  echo $week_result[0]['day_total_Newuser']; ?></li>
							<li><?php  echo $week_result[0]['day_total_Payuser']; ?></li>
							<li><?php  echo $week_result[0]['day_total_payrate']; ?></li>
							<li><?php  echo $week_result[0]['day_total_Payamount']; ?></li>
							<li><?php  echo $week_result[0]['day_total_arpu']; ?></li>
							<li><?php  echo $week_result[0]['day_total_arppu']; ?></li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($week_result[0]['day_total_Deviceactivations'],$week_result[1]['day_total_Deviceactivations']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_Newusers'],$week_result[1]['day_total_Newusers']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_Newuser'],$week_result[1]['day_total_Newuser']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_Payuser'],$week_result[1]['day_total_Payuser']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_payrate'],$week_result[1]['day_total_payrate']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_Payamount'],$week_result[1]['day_total_Payamount']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_arpu'],$week_result[1]['day_total_arpu']); ?>%</li>
							<li><?php echo compare($week_result[0]['day_total_arppu'],$week_result[1]['day_total_arppu']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $week_result[$i]['day_total_Deviceactivations']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_Newusers']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_Newuser']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_Payuser']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_payrate']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_Payamount']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_arpu']; ?></li>
							<li><?php  echo $week_result[$i]['day_total_arppu']; ?></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
			</div>
			<div class="tab month">
				<div class="tag">
					<ul>
						<li class="first">同比上月</li>
					</ul>
				</div>
				<div class="i-date">
					<ul>
						<li class="first"><?php echo date('Y-m',strtotime($month_result[0]['month_date_firstday'])) ?></li>
						<li><?php echo date('Y-m',strtotime($month_result[1]['month_date_firstday'])); ?></li>
						<?php 
							for($i=7;$i>0;$i--){
								echo "<li>".date('Y-m',strtotime($month_result[$i]['month_date_firstday']))."</li>";
							}
						?>
					</ul>
				</div>
				<div class="sub-t">
					<span>基本数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>设备激活</li>
							<li>新增设备</li>
							<li>新增注册</li>
							<li>新增用户</li>
							<li>注册转化率(%)</li>
							<li>活跃用户</li>
							<li>ACU</li>
							<li>PCU</li>
							<li>游戏次数</li>
							<li>平均日游戏时长(分)</li>
							<li>平均日游戏次数</li>
							<li>设备次日留存</li>
							<li>设备3日留存</li>
							<li>设备7日留存</li>
							<li>新增用户次日留存</li>
							<li>新增用户3日留存</li>
							<li>新增用户7日留存</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $month_result[0]['day_Deviceactivations']; ?></li>
							<li><?php  echo $month_result[0]['day_Newdevices']; ?></li>
							<li><?php  echo $month_result[0]['day_Newusers']; ?></li>
							<li><?php  echo $month_result[0]['day_Newuser']; ?></li>
							<li><?php  echo $month_result[0]['day_userconversion']; ?></li>
							<li><?php  echo $month_result[0]['day_Dauuser']; ?></li>
							<li><?php  echo sprintf("%.2f",$month_result[0]['day_Acu']); ?></li>
							<li><?php  echo $month_result[0]['day_Pcu']; ?></li>
							<li><?php  echo $month_result[0]['day_Gamecount']; ?></li>
							<li><?php  echo sprintf("%.2f",$month_result[0]['day_avggametime']); ?></li>
							<li><?php  echo sprintf("%.2f",$month_result[0]['day_avggamecount']); ?></li>
							<li><?php  echo floatval($month_result[0]['day_1day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[0]['day_3day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[0]['day_7day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[0]['day_1day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[0]['day_3day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[0]['day_7day_newuserremainrate'])*100; ?>%</li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($month_result[0]['day_Deviceactivations'],$month_lastMonth_date_result['day_Deviceactivations']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Newdevices'],$month_lastMonth_date_result['day_Newdevices']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Newusers'],$month_lastMonth_date_result['day_Newusers']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Newuser'],$month_lastMonth_date_result['day_Newuser']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_userconversion'],$month_lastMonth_date_result['day_userconversion']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Dauuser'],$month_lastMonth_date_result['day_Dauuser']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Acu'],$month_lastMonth_date_result['day_Acu']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Pcu'],$month_lastMonth_date_result['day_Pcu']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Gamecount'],$month_lastMonth_date_result['day_Gamecount']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_avggametime'],$month_lastMonth_date_result['day_avggametime']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_avggamecount'],$month_lastMonth_date_result['day_avggamecount']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_1day_deviceremainrate'],$month_lastMonth_date_result['day_1day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_3day_deviceremainrate'],$month_lastMonth_date_result['day_3day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_7day_deviceremainrate'],$month_lastMonth_date_result['day_7day_deviceremainrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_1day_newuserremainrate'],$month_lastMonth_date_result['day_1day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_3day_newuserremainrate'],$month_lastMonth_date_result['day_3day_newuserremainrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_7day_newuserremainrate'],$month_lastMonth_date_result['day_7day_newuserremainrate']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $month_result[$i]['day_Deviceactivations']; ?></li>
							<li><?php  echo $month_result[$i]['day_Newdevices']; ?></li>
							<li><?php  echo $month_result[$i]['day_Newusers']; ?></li>
							<li><?php  echo $month_result[$i]['day_Newuser']; ?></li>
							<li><?php  echo $month_result[$i]['day_userconversion']; ?></li>
							<li><?php  echo $month_result[$i]['day_Dauuser']; ?></li>
							<li><?php  echo sprintf("%.2f",$month_result[$i]['day_Acu']); ?></li>
							<li><?php  echo $month_result[$i]['day_Pcu']; ?></li>
							<li><?php  echo $month_result[$i]['day_Gamecount']; ?></li>
							<li><?php  echo sprintf("%.2f",$month_result[$i]['day_avggametime']); ?></li>
							<li><?php  echo sprintf("%.2f",$month_result[$i]['day_avggamecount']); ?></li>
							<li><?php  echo floatval($month_result[$i]['day_1day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[$i]['day_3day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[$i]['day_7day_deviceremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[$i]['day_1day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[$i]['day_3day_newuserremainrate'])*100; ?>%</li>
							<li><?php  echo floatval($month_result[$i]['day_7day_newuserremainrate'])*100; ?>%</li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
				<div class="sub-t">
					<span>付费数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>付费金额(¥)</li>
							<li>新增付费用户数量</li>
							<li>付费用户数量</li>
							<li>日付费率(%)</li>
							<li>新增ARPU</li>
							<li>活跃ARPU</li>
							<li>新增ARPPU</li>
							<li>活跃ARPPU</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $month_result[0]['day_Payamount']; ?></li>
							<li><?php  echo $month_result[0]['day_NewPayuser']; ?></li>
							<li><?php  echo $month_result[0]['day_Payuser']; ?></li>
							<li><?php  echo $month_result[0]['day_daypayrate']; ?></li>
							<li><?php  echo $month_result[0]['day_newuserarpu']; ?></li>
							<li><?php  echo $month_result[0]['day_dauuserarpu']; ?></li>
							<li><?php  echo $month_result[0]['day_newuserarppu']; ?></li>
							<li><?php  echo $month_result[0]['day_dauuserarppu']; ?></li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($month_result[0]['day_Payamount'],$month_result[1]['day_Payamount']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_NewPayuser'],$month_result[1]['day_NewPayuser']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_Payuser'],$month_result[1]['day_Payuser']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_daypayrate'],$month_result[1]['day_daypayrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_newuserarpu'],$month_result[1]['day_newuserarpu']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_dauuserarpu'],$month_result[1]['day_dauuserarpu']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_newuserarppu'],$month_result[1]['day_newuserarppu']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_dauuserarppu'],$month_result[1]['day_dauuserarppu']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $month_result[$i]['day_Payamount']; ?></li>
							<li><?php  echo $month_result[$i]['day_NewPayuser']; ?></li>
							<li><?php  echo $month_result[$i]['day_Payuser']; ?></li>
							<li><?php  echo $month_result[$i]['day_daypayrate']; ?></li>
							<li><?php  echo $month_result[$i]['day_newuserarpu']; ?></li>
							<li><?php  echo $month_result[$i]['day_dauuserarpu']; ?></li>
							<li><?php  echo $month_result[$i]['day_newuserarppu']; ?></li>
							<li><?php  echo $month_result[$i]['day_dauuserarppu']; ?></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
				<div class="sub-t">
					<span>累计数据</span>
				</div>
				<div class="data-con">
					<div class="line">
						<ul>
							<li>累计激活设备</li>
							<li>累计新增注册</li>
							<li>累计新增用户</li>
							<li>累计付费用户</li>
							<li>总体付费率(%)</li>
							<li>累计收入金额(¥)</li>
							<li>累计ARPU</li>
							<li>累计ARPPU</li>
						</ul>
					</div>
					<div class="line">
						<ul>
							<li><?php  echo $month_result[0]['day_total_Deviceactivations']; ?></li>
							<li><?php  echo $month_result[0]['day_total_Newusers']; ?></li>
							<li><?php  echo $month_result[0]['day_total_Newuser']; ?></li>
							<li><?php  echo $month_result[0]['day_total_Payuser']; ?></li>
							<li><?php  echo $month_result[0]['day_total_payrate']; ?></li>
							<li><?php  echo $month_result[0]['day_total_Payamount']; ?></li>
							<li><?php  echo $month_result[0]['day_total_arpu']; ?></li>
							<li><?php  echo $month_result[0]['day_total_arppu']; ?></li>
						</ul>
					</div>
					<div class="line compare">
						<ul>
							<li><?php echo compare($month_result[0]['day_total_Deviceactivations'],$month_result[1]['day_total_Deviceactivations']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_Newusers'],$month_result[1]['day_total_Newusers']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_Newuser'],$month_result[1]['day_total_Newuser']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_Payuser'],$month_result[1]['day_total_Payuser']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_payrate'],$month_result[1]['day_total_payrate']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_Payamount'],$month_result[1]['day_total_Payamount']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_arpu'],$month_result[1]['day_total_arpu']); ?>%</li>
							<li><?php echo compare($month_result[0]['day_total_arppu'],$month_result[1]['day_total_arppu']); ?>%</li>
						</ul>
					</div>
					<?php
						for($i=7;$i>0;$i--){
					?>
					<div class="line">
						<ul>
							<li><?php  echo $month_result[$i]['day_total_Deviceactivations']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_Newusers']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_Newuser']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_Payuser']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_payrate']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_Payamount']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_arpu']; ?></li>
							<li><?php  echo $month_result[$i]['day_total_arppu']; ?></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
	</article>
</section>
<script>
$(document).ready(function(){
	$('#main .submit_link .tabs li').bind('click',function(){
		var current = $(this).index();
		$('#container > div.tab').hide();
		$('#container > div.tab').eq(current).show();
	});
	$('div.compare li').each(function(){
		var text = $(this).text();
		var num = parseFloat(text.replace('%',''));
		if(num > 0){
			$(this).addClass('green');
			$(this).text('↑ ' + text);
		}
		else if(num < 0){
			$(this).addClass('red');
			$(this).text('↓ ' + text);
		}
	});
});
</script>
