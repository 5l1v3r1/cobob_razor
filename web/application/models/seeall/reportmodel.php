<?php

/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Reportmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Reportmodel extends CI_Model {

    /**
     * Construct load
     * Construct function
     * 
     * @return void
     */
    function __construct() {
        parent::__construct();
        $this->load->model("product/productmodel", 'product');
        $this->load->model("channelmodel", 'channel');
        $this->load->model("servermodel", 'server');
        $this->load->model('useranalysis/newusersmodel', 'newusers');
        $this->load->model('useranalysis/dauusersmodel', 'dauusers');
        $this->load->model('payanaly/paydatamodel', 'paydata');
		$this->load->model('payanaly/newpaymodel', 'newpay');       
        $this->load->model('onlineanaly/dayonlinemodel', 'dayonline');
        $this->load->model('useranalysis/remaincountmodel', 'remaincount');   
        $this->load->model('common');
    }	 	 

	 /**
	  * report_day function
	  *
	  * @param $appId product id
	  * 
	  * @return array report day
	  */
	 function report_day($num,$appid) {
	 		//获得统计日期
		 	$countdate=date("Y-m-d",strtotime("-$num day"));
		 
			// 设备激活 
			$day_Deviceactivations = $this->newusers->getDeviceactivations($countdate,$countdate, $appid, 'all', 'all');
			// 新增设备 
			$day_Newdevices = $this->newusers->getNewdevices($countdate,$countdate, $appid, 'all', 'all');
			// 新增注册设备
			$day_Newregisterdevice = $this->newusers->getNewregisterdevice($countdate,$countdate, $appid, 'all', 'all');
			// 新增用户
			$day_Newusers = $this->newusers->getNewusers($countdate,$countdate, $appid, 'all', 'all');
			// 新增注册
			$day_Newuser = $this->dauusers->getNewuser($countdate,$countdate, $appid, 'all', 'all', 'all');
			// 用户转化率（％） = 新增注册设备/新增设备
			$day_userconversion = ($day_Newdevices==0)?0:($day_Newregisterdevice/$day_Newdevices);
			// 活跃用户 
			$day_Dauuser = $this->dauusers->getDauuser($countdate,$countdate, $appid, 'all', 'all', 'all');
			// ACU 
			$day_Acu = $this->dayonline->getAcu($countdate,$countdate, $appid, 'all', 'all', 'all');
			// PCU 
			$day_Pcu = $this->dayonline->getPcu($countdate,$countdate, $appid, 'all', 'all', 'all');
			// 游戏次数
			$Gamecountandtime = $this->dauusers->getGamecountandtime($countdate,$countdate, $appid, 'all', 'all', 'all');
			$day_Gamecount = $Gamecountandtime['gamecount'];
			// 平均日游戏时长（分） 
			$day_avggametime = ($day_Dauuser==0)?0:($Gamecountandtime['onlinetime']/$day_Dauuser);
			// 平均日游戏次数 
			$day_avggamecount = ($day_Dauuser==0)?0:($Gamecountandtime['gamecount']/$day_Dauuser);
			// 设备次日留存 
			$day_1day_deviceremainrate = $this->remaincount->getRemainByNewddevice($countdate, $appid, 'all', 'all', 1);
			// 设备3日留存
			$day_3day_deviceremainrate = $this->remaincount->getRemainByNewddevice($countdate, $appid, 'all', 'all', 3); 
			// 设备7日留存 
			$day_7day_deviceremainrate = $this->remaincount->getRemainByNewddevice($countdate, $appid, 'all', 'all', 7);
			// 新增用户次日留存
			$day_1day_newuserremainrate = $this->remaincount->getRemainByNewuser($countdate, $appid, 'all', 'all', 'all', 1) ;
			// 新增用户3日留存 
			$day_3day_newuserremainrate = $this->remaincount->getRemainByNewuser($countdate, $appid, 'all', 'all', 'all', 3);
			// 新增用户7日留存 
			$day_7day_newuserremainrate = $this->remaincount->getRemainByNewuser($countdate, $appid, 'all', 'all', 'all', 7);

			// 付费金额
			$day_Payamount = $day_= $this->paydata->getPayamount($countdate,$countdate,$appid, 'all', 'all', 'all');
			// 新增付费用户
			$day_NewPayuser = $this->newpay->getNewPayuser($countdate, $appid, 'all', 'all', 'all',0);
			// 付费用户
			$day_Payuser = $this->dauusers->getPayuser($countdate,$countdate, $appid, 'all', 'all', 'all');
			// 日付费率（％）
			$day_daypayrate = ($day_Dauuser==0)?0:($day_Payuser/$day_Dauuser);
			// 新增ARPU
			$Payamountbynewuser= $this->paydata->getPayamountbynewuser($countdate,$countdate,$appid, 'all', 'all', 'all');
			$day_newuserarpu = ($day_Newuser==0)?0:($Payamountbynewuser/$day_Newuser);
			// 活跃ARPU
			$Payamountbydauuser= $this->paydata->getPayamountbydauuser($countdate,$countdate,$appid, 'all', 'all', 'all');
			$day_dauuserarpu = ($day_Dauuser==0)?0:($Payamountbydauuser/$day_Dauuser);
			// 新增ARPPU
			$day_newuserarppu = ($day_NewPayuser==0)?0:($Payamountbynewuser/$day_NewPayuser);
			// 活跃ARPPU
			$day_dauuserarppu = ($day_Payuser==0)?0:($Payamountbydauuser/$day_Payuser);

			// 累计激活设备
			$day_total_Deviceactivations = $this->newusers->getDeviceactivations('1970-01-01',$countdate, $appid, 'all', 'all');
			// 累计新增注册
			$day_total_Newusers = $this->newusers->getNewusers('1970-01-01',$countdate, $appid, 'all', 'all');
			// 累计新增用户
			$day_total_Newuser = $this->dauusers->getNewuser('1970-01-01',$countdate, $appid, 'all', 'all', 'all');
			// 累计付费用户
			$day_total_Payuser = $this->dauusers->getPayuser('1970-01-01',$countdate, $appid, 'all', 'all', 'all');
			$total_Dauuser = $this->dauusers->getDauuser('1970-01-01',$countdate, $appid, 'all', 'all', 'all');
			// 总体付费率（％）
			$day_total_payrate = ($total_Dauuser==0)?0:($day_total_Payuser/$total_Dauuser);
			// 累计付费金额
			$day_total_Payamount = $this->paydata->getPayamount('1970-01-01',$countdate,$appid, 'all', 'all', 'all');
			// 累计ARPU
			$day_total_arpu = ($total_Dauuser==0)?0:($day_total_Payamount/$total_Dauuser);
			// 累计ARPPU
			$day_total_arppu = ($day_total_Payuser==0)?0:($day_total_Payamount/$day_total_Payuser);

			$data = array(
				 'countdate' => $countdate,
				 'day_Deviceactivations' => $day_Deviceactivations,
				 'day_Newdevices' => $day_Newdevices,
				 'day_Newusers' => $day_Newusers,
				 'day_Newuser' => $day_Newuser,
				 'day_userconversion' => (float)$day_userconversion,
				 'day_Dauuser' => $day_Dauuser,
				 'day_Acu' => $day_Acu,
				 'day_Pcu' => $day_Pcu,
				 'day_Gamecount' => $day_Gamecount,
				 'day_avggametime' => $day_avggametime,
				 'day_avggamecount' => $day_avggamecount,
				 'day_1day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_1day_deviceremainrate/$day_Newdevices),4),
				 'day_3day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_3day_deviceremainrate/$day_Newdevices),4),
				 'day_7day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_7day_deviceremainrate/$day_Newdevices),4),
				 'day_1day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_1day_newuserremainrate/$day_Newuser),4),
				 'day_3day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_3day_newuserremainrate/$day_Newuser),4),
				 'day_7day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_7day_newuserremainrate/$day_Newuser),4),
				 'day_Payamount' => $day_Payamount,
				 'day_NewPayuser' => $day_NewPayuser,
				 'day_Payuser' => $day_Payuser,
				 'day_daypayrate' => (float)$day_daypayrate,
				 'day_newuserarpu' => (float)$day_newuserarpu,
				 'day_dauuserarpu' => (float)$day_dauuserarpu,
				 'day_newuserarppu' => (float)$day_newuserarppu,
				 'day_dauuserarppu' => (float)$day_dauuserarppu,
				 'day_total_Deviceactivations' => $day_total_Deviceactivations,
				 'day_total_Newusers' => $day_total_Newusers,
				 'day_total_Newuser' => $day_total_Newuser,
				 'day_total_Payuser' => $day_total_Payuser,
				 'day_total_payrate' => (float)$day_total_payrate,
				 'day_total_Payamount' => $day_total_Payamount,
				 'day_total_arpu' => (float)$day_total_arpu,
				 'day_total_arppu' => (float)$day_total_arppu
			);
			return $data;
	  }
          
      /**
	  * report_week function
	  *
	  * @param $appId product id
	  * 
	  * @return array report week
	  */
	 function report_week($num,$appid) {
	 		//获得某周的第一天
	 		$week_firstday=$this->common->last_monday_1($num,0,false);
	 		//获得某周的最后一天
		 	$week_lastday=$this->common->last_sunday_1($num,0,false);

			// 设备激活 
			$day_Deviceactivations = $this->newusers->getDeviceactivations($week_firstday,$week_lastday, $appid, 'all', 'all');
			// 新增设备 
			$day_Newdevices = $this->newusers->getNewdevices($week_firstday,$week_lastday, $appid, 'all', 'all');
			// 新增注册设备
			$day_Newregisterdevice = $this->newusers->getNewregisterdevice($week_firstday,$week_lastday, $appid, 'all', 'all');
			// 新增用户
			$day_Newusers = $this->newusers->getNewusers($week_firstday,$week_lastday, $appid, 'all', 'all');
			// 新增注册
			$day_Newuser = $this->dauusers->getNewuser($week_firstday,$week_lastday, $appid, 'all', 'all', 'all');
			// 用户转化率（％） = 新增注册设备/新增设备
			$day_userconversion = ($day_Newdevices==0)?0:($day_Newregisterdevice/$day_Newdevices);
			// 活跃用户 
			$day_Dauuser = $this->dauusers->getDauuser($week_firstday,$week_lastday, $appid, 'all', 'all', 'all');
			// ACU 
			$day_Acu = $this->dayonline->getAcu($week_firstday,$week_lastday, $appid, 'all', 'all', 'all');
			// PCU 
			$day_Pcu = $this->dayonline->getPcu($week_firstday,$week_lastday, $appid, 'all', 'all', 'all');
			// 游戏次数
			$Gamecountandtime = $this->dauusers->getGamecountandtime($week_firstday,$week_lastday, $appid, 'all', 'all', 'all');
			$day_Gamecount = $Gamecountandtime['gamecount'];
			// 平均日游戏时长（分） 
			$day_avggametime = ($day_Dauuser==0)?0:($Gamecountandtime['onlinetime']/$day_Dauuser);
			// 平均日游戏次数 
			$day_avggamecount = ($day_Dauuser==0)?0:($Gamecountandtime['gamecount']/$day_Dauuser);
			// 设备次日留存 
			$day_1day_deviceremainrate = $this->remaincount->getRemainByNewddevice($week_firstday, $appid, 'all', 'all', 1);
			// 设备3日留存
			$day_3day_deviceremainrate = $this->remaincount->getRemainByNewddevice($week_firstday, $appid, 'all', 'all', 3); 
			// 设备7日留存 
			$day_7day_deviceremainrate = $this->remaincount->getRemainByNewddevice($week_firstday, $appid, 'all', 'all', 7);
			// 新增用户次日留存
			$day_1day_newuserremainrate = $this->remaincount->getRemainByNewuser($week_firstday, $appid, 'all', 'all', 'all', 1) ;
			// 新增用户3日留存 
			$day_3day_newuserremainrate = $this->remaincount->getRemainByNewuser($week_firstday, $appid, 'all', 'all', 'all', 3);
			// 新增用户7日留存 
			$day_7day_newuserremainrate = $this->remaincount->getRemainByNewuser($week_firstday, $appid, 'all', 'all', 'all', 7);

			// 付费金额
			$day_Payamount = $day_= $this->paydata->getPayamount($week_firstday,$week_lastday,$appid, 'all', 'all', 'all');
			// 新增付费用户
			$day_NewPayuser = $this->newpay->getNewPayuser($week_firstday, $appid, 'all', 'all', 'all',0);
			// 付费用户
			$day_Payuser = $this->dauusers->getPayuser($week_firstday,$week_lastday, $appid, 'all', 'all', 'all');
			// 日付费率（％）
			$day_daypayrate = ($day_Dauuser==0)?0:($day_Payuser/$day_Dauuser);
			// 新增ARPU
			$Payamountbynewuser= $this->paydata->getPayamountbynewuser($week_firstday,$week_lastday,$appid, 'all', 'all', 'all');
			$day_newuserarpu = ($day_Newuser==0)?0:($Payamountbynewuser/$day_Newuser);
			// 活跃ARPU
			$Payamountbydauuser= $this->paydata->getPayamountbydauuser($week_firstday,$week_lastday,$appid, 'all', 'all', 'all');
			$day_dauuserarpu = ($day_Dauuser==0)?0:($Payamountbydauuser/$day_Dauuser);
			// 新增ARPPU
			$day_newuserarppu = ($day_NewPayuser==0)?0:($Payamountbynewuser/$day_NewPayuser);
			// 活跃ARPPU
			$day_dauuserarppu = ($day_Payuser==0)?0:($Payamountbydauuser/$day_Payuser);

			// 累计激活设备
			$day_total_Deviceactivations = $this->newusers->getDeviceactivations('1970-01-01',$week_lastday, $appid, 'all', 'all');
			// 累计新增注册
			$day_total_Newusers = $this->newusers->getNewusers('1970-01-01',$week_lastday, $appid, 'all', 'all');
			// 累计新增用户
			$day_total_Newuser = $this->dauusers->getNewuser('1970-01-01',$week_lastday, $appid, 'all', 'all', 'all');
			// 累计付费用户
			$day_total_Payuser = $this->dauusers->getPayuser('1970-01-01',$week_lastday, $appid, 'all', 'all', 'all');
			$total_Dauuser = $this->dauusers->getDauuser('1970-01-01',$week_lastday, $appid, 'all', 'all', 'all');
			// 总体付费率（％）
			$day_total_payrate = ($total_Dauuser==0)?0:($day_total_Payuser/$total_Dauuser);
			// 累计付费金额
			$day_total_Payamount = $this->paydata->getPayamount('1970-01-01',$week_lastday,$appid, 'all', 'all', 'all');
			// 累计ARPU
			$day_total_arpu = ($total_Dauuser==0)?0:($day_total_Payamount/$total_Dauuser);
			// 累计ARPPU
			$day_total_arppu = ($day_total_Payuser==0)?0:($day_total_Payamount/$day_total_Payuser);


			$data = array(
				 'week_firstday' => $week_firstday,
				 'week_lastday' => $week_lastday,
				 'day_Deviceactivations' => $day_Deviceactivations,
				 'day_Newdevices' => $day_Newdevices,
				 'day_Newusers' => $day_Newusers,
				 'day_Newuser' => $day_Newuser,
				 'day_userconversion' => (float)$day_userconversion,
				 'day_Dauuser' => $day_Dauuser,
				 'day_Acu' => $day_Acu,
				 'day_Pcu' => $day_Pcu,
				 'day_Gamecount' => $day_Gamecount,
				 'day_avggametime' => $day_avggametime,
				 'day_avggamecount' => $day_avggamecount,
				 'day_1day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_1day_deviceremainrate/$day_Newdevices),4),
				 'day_3day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_3day_deviceremainrate/$day_Newdevices),4),
				 'day_7day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_7day_deviceremainrate/$day_Newdevices),4),
				 'day_1day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_1day_newuserremainrate/$day_Newuser),4),
				 'day_3day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_3day_newuserremainrate/$day_Newuser),4),
				 'day_7day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_7day_newuserremainrate/$day_Newuser),4),
				 'day_Payamount' => $day_Payamount,
				 'day_NewPayuser' => $day_NewPayuser,
				 'day_Payuser' => $day_Payuser,
				 'day_daypayrate' => (float)$day_daypayrate,
				 'day_newuserarpu' => (float)$day_newuserarpu,
				 'day_dauuserarpu' => (float)$day_dauuserarpu,
				 'day_newuserarppu' => (float)$day_newuserarppu,
				 'day_dauuserarppu' => (float)$day_dauuserarppu,
				 'day_total_Deviceactivations' => (float)$day_total_Deviceactivations,
				 'day_total_Newusers' => $day_total_Newusers,
				 'day_total_Newuser' => $day_total_Newuser,
				 'day_total_Payuser' => $day_total_Payuser,
				 'day_total_payrate' => (float)$day_total_payrate,
				 'day_total_Payamount' => $day_total_Payamount,
				 'day_total_arpu' => (float)$day_total_arpu,
				 'day_total_arppu' => (float)$day_total_arppu
			);
			return $data;
	  }


  	 /**
	  * report_month function
	  *
	  * @param $appId product id
	  * 
	  * @return array report_month
	  */
	 function report_month($num,$appid) {
		 	//获得某月的第一天和最后一天
	 		$month_date_firstday=$this->common->lastmonth_firstday_1($num);
	 		$month_date_lastday=$this->common->lastmonth_lastday_1($num);

			// 设备激活 
			$day_Deviceactivations = $this->newusers->getDeviceactivations($month_date_firstday,$month_date_lastday, $appid, 'all', 'all');
			// 新增设备 
			$day_Newdevices = $this->newusers->getNewdevices($month_date_firstday,$month_date_lastday, $appid, 'all', 'all');
			// 新增注册设备
			$day_Newregisterdevice = $this->newusers->getNewregisterdevice($month_date_firstday,$month_date_lastday, $appid, 'all', 'all');
			// 新增用户
			$day_Newusers = $this->newusers->getNewusers($month_date_firstday,$month_date_lastday, $appid, 'all', 'all');
			// 新增注册
			$day_Newuser = $this->dauusers->getNewuser($month_date_firstday,$month_date_lastday, $appid, 'all', 'all', 'all');
			// 用户转化率（％） = 新增注册设备/新增设备
			$day_userconversion = ($day_Newdevices==0)?0:($day_Newregisterdevice/$day_Newdevices);
			// 活跃用户 
			$day_Dauuser = $this->dauusers->getDauuser($month_date_firstday,$month_date_lastday, $appid, 'all', 'all', 'all');
			// ACU 
			$day_Acu = $this->dayonline->getAcu($month_date_firstday,$month_date_lastday, $appid, 'all', 'all', 'all');
			// PCU 
			$day_Pcu = $this->dayonline->getPcu($month_date_firstday,$month_date_lastday, $appid, 'all', 'all', 'all');
			// 游戏次数
			$Gamecountandtime = $this->dauusers->getGamecountandtime($month_date_firstday,$month_date_lastday, $appid, 'all', 'all', 'all');
			$day_Gamecount = $Gamecountandtime['gamecount'];
			// 平均日游戏时长（分） 
			$day_avggametime = ($day_Dauuser==0)?0:($Gamecountandtime['onlinetime']/$day_Dauuser);
			// 平均日游戏次数 
			$day_avggamecount = ($day_Dauuser==0)?0:($Gamecountandtime['gamecount']/$day_Dauuser);
			// 设备次日留存 
			$day_1day_deviceremainrate = $this->remaincount->getRemainByNewddevice($month_date_firstday, $appid, 'all', 'all', 1);
			// 设备3日留存
			$day_3day_deviceremainrate = $this->remaincount->getRemainByNewddevice($month_date_firstday, $appid, 'all', 'all', 3); 
			// 设备7日留存 
			$day_7day_deviceremainrate = $this->remaincount->getRemainByNewddevice($month_date_firstday, $appid, 'all', 'all', 7);
			// 新增用户次日留存
			$day_1day_newuserremainrate = $this->remaincount->getRemainByNewuser($month_date_firstday, $appid, 'all', 'all', 'all', 1) ;
			// 新增用户3日留存 
			$day_3day_newuserremainrate = $this->remaincount->getRemainByNewuser($month_date_firstday, $appid, 'all', 'all', 'all', 3);
			// 新增用户7日留存 
			$day_7day_newuserremainrate = $this->remaincount->getRemainByNewuser($month_date_firstday, $appid, 'all', 'all', 'all', 7);

			// 付费金额
			$day_Payamount = $day_= $this->paydata->getPayamount($month_date_firstday,$month_date_lastday,$appid, 'all', 'all', 'all');
			// 新增付费用户
			$day_NewPayuser = $this->newpay->getNewPayuser($month_date_firstday, $appid, 'all', 'all', 'all',0);
			// 付费用户
			$day_Payuser = $this->dauusers->getPayuser($month_date_firstday,$month_date_lastday, $appid, 'all', 'all', 'all');
			// 日付费率（％）
			$day_daypayrate = ($day_Dauuser==0)?0:($day_Payuser/$day_Dauuser);
			// 新增ARPU
			$Payamountbynewuser= $this->paydata->getPayamountbynewuser($month_date_firstday,$month_date_lastday,$appid, 'all', 'all', 'all');
			$day_newuserarpu = ($day_Newuser==0)?0:($Payamountbynewuser/$day_Newuser);
			// 活跃ARPU
			$Payamountbydauuser= $this->paydata->getPayamountbydauuser($month_date_firstday,$month_date_lastday,$appid, 'all', 'all', 'all');
			$day_dauuserarpu = ($day_Dauuser==0)?0:($Payamountbydauuser/$day_Dauuser);
			// 新增ARPPU
			$day_newuserarppu = ($day_NewPayuser==0)?0:($Payamountbynewuser/$day_NewPayuser);
			// 活跃ARPPU
			$day_dauuserarppu = ($day_Payuser==0)?0:($Payamountbydauuser/$day_Payuser);

			// 累计激活设备
			$day_total_Deviceactivations = $this->newusers->getDeviceactivations('1970-01-01',$month_date_lastday, $appid, 'all', 'all');
			// 累计新增注册
			$day_total_Newusers = $this->newusers->getNewusers('1970-01-01',$month_date_lastday, $appid, 'all', 'all');
			// 累计新增用户
			$day_total_Newuser = $this->dauusers->getNewuser('1970-01-01',$month_date_lastday, $appid, 'all', 'all', 'all');
			// 累计付费用户
			$day_total_Payuser = $this->dauusers->getPayuser('1970-01-01',$month_date_lastday, $appid, 'all', 'all', 'all');
			$total_Dauuser = $this->dauusers->getDauuser('1970-01-01',$month_date_lastday, $appid, 'all', 'all', 'all');
			// 总体付费率（％）
			$day_total_payrate = ($total_Dauuser==0)?0:($day_total_Payuser/$total_Dauuser);
			// 累计付费金额
			$day_total_Payamount = $this->paydata->getPayamount('1970-01-01',$month_date_lastday,$appid, 'all', 'all', 'all');
			// 累计ARPU
			$day_total_arpu = ($total_Dauuser==0)?0:($day_total_Payamount/$total_Dauuser);
			// 累计ARPPU
			$day_total_arppu = ($day_total_Payuser==0)?0:($day_total_Payamount/$day_total_Payuser);


			$data = array(
				 'month_date_firstday' => $month_date_firstday,
				 'month_date_lastday' => $month_date_lastday,
				 'day_Deviceactivations' => $day_Deviceactivations,
				 'day_Newdevices' => $day_Newdevices,
				 'day_Newusers' => $day_Newusers,
				 'day_Newuser' => $day_Newuser,
				 'day_userconversion' => (float)$day_userconversion,
				 'day_Dauuser' => $day_Dauuser,
				 'day_Acu' => $day_Acu,
				 'day_Pcu' => $day_Pcu,
				 'day_Gamecount' => $day_Gamecount,
				 'day_avggametime' => $day_avggametime,
				 'day_avggamecount' => $day_avggamecount,
				 'day_1day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_1day_deviceremainrate/$day_Newdevices),4),
				 'day_3day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_3day_deviceremainrate/$day_Newdevices),4),
				 'day_7day_deviceremainrate' => ($day_Newdevices==0)?0:round(($day_7day_deviceremainrate/$day_Newdevices),4),
				 'day_1day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_1day_newuserremainrate/$day_Newuser),4),
				 'day_3day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_3day_newuserremainrate/$day_Newuser),4),
				 'day_7day_newuserremainrate' => ($day_Newuser==0)?0:round(($day_7day_newuserremainrate/$day_Newuser),4),
				 'day_Payamount' => $day_Payamount,
				 'day_NewPayuser' => $day_NewPayuser,
				 'day_Payuser' => $day_Payuser,
				 'day_daypayrate' => (float)$day_daypayrate,
				 'day_newuserarpu' => (float)$day_newuserarpu,
				 'day_dauuserarpu' => (float)$day_dauuserarpu,
				 'day_newuserarppu' => (float)$day_newuserarppu,
				 'day_dauuserarppu' => (float)$day_dauuserarppu,
				 'day_total_Deviceactivations' => $day_total_Deviceactivations,
				 'day_total_Newusers' => $day_total_Newusers,
				 'day_total_Newuser' => $day_total_Newuser,
				 'day_total_Payuser' => $day_total_Payuser,
				 'day_total_payrate' => (float)$day_total_payrate,
				 'day_total_Payamount' => $day_total_Payamount,
				 'day_total_arpu' => (float)$day_total_arpu,
				 'day_total_arppu' => (float)$day_total_arppu
			);
			return $data;
		}
}
