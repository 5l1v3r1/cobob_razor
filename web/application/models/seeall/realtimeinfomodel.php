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
 * Realtimeinfomodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Realtimeinfomodel extends CI_Model {

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
       	  $this->load->model('onlineanaly/dayonlinemodel', 'dayonline');
       	  $this->load->model('payanaly/paydatamodel', 'paydata');
	 }
     
	function getRealtimeinfoByDay($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->RealtimeinfoByDay($fromTime,$toTime,$channel,$server,$version);
		$LevelalyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['count_date'] = $LevelalyRow->count_date;
			$fRow['count_time'] = $LevelalyRow->count_time;
			$fRow['onlineusers'] = $LevelalyRow->onlineusers;
			$fRow['newusers'] = $LevelalyRow->newusers;
			$fRow['dauusers'] = $LevelalyRow->dauusers;
			$fRow['payamount'] = $LevelalyRow->payamount;
			$fRow['insertdate'] = $LevelalyRow->insertdate;
			$LevelalyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function RealtimeinfoByDay($fromTime,$toTime,$channel,$server,$version)
	{	
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(count_date, 0) count_date,
					IFNULL(count_time, 0) count_time,
					IFNULL(onlineusers, 0) onlineusers,
					IFNULL(newusers, 0) newusers,
					IFNULL(dauusers, 0) dauusers,
					IFNULL(payamount, 0) payamount,
					IFNULL(insertdate, 0) insertdate
				FROM
					".$dwdb->dbprefix('sum_basic_realtimeroleinfo')." 
				WHERE
				count_date = '".date('y-m-d',time())."'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "');";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getRealtimeinfoByYestoday($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->RealtimeinfoByYestoday($fromTime,$toTime,$channel,$server,$version);
		$LevelalyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['count_date'] = $LevelalyRow->count_date;
			$fRow['count_time'] = date('H:i:00',strtotime($LevelalyRow->count_time));
			$fRow['onlineusers'] = $LevelalyRow->onlineusers;
			$fRow['newusers'] = $LevelalyRow->newusers;
			$fRow['dauusers'] = $LevelalyRow->dauusers;
			$fRow['payamount'] = $LevelalyRow->payamount;
			$fRow['insertdate'] = $LevelalyRow->insertdate;
			$LevelalyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function RealtimeinfoByYestoday($fromTime,$toTime,$channel,$server,$version)
	{	
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(count_date, 0) count_date,
					IFNULL(count_time, 0) count_time,
					IFNULL(onlineusers, 0) onlineusers,
					IFNULL(newusers, 0) newusers,
					IFNULL(dauusers, 0) dauusers,
					IFNULL(payamount, 0) payamount,
					IFNULL(insertdate, 0) insertdate
				FROM
					".$dwdb->dbprefix('sum_basic_realtimeroleinfo')." 
				WHERE
				count_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "');";
		$query = $dwdb->query($sql);
		return $query;
	}
	function unescape($str) {
		$ret = '';
		$len = strlen($str);
		for ($i = 0; $i < $len; $i ++) {
			if ($str[$i] == '%' && $str[$i + 1] == 'u') {
				$val = hexdec(substr($str, $i + 2, 4));
				if ($val < 0x7f)
					$ret .= chr($val);
				else
				if ($val < 0x800)
					$ret .= chr(0xc0 | ($val >> 6)) .
							chr(0x80 | ($val & 0x3f));
				else
					$ret .= chr(0xe0 | ($val >> 12)) .
							chr(0x80 | (($val >> 6) & 0x3f)) .
							chr(0x80 | ($val & 0x3f));
				$i += 5;
			} else
			if ($str[$i] == '%') {
				$ret .= urldecode(substr($str, $i, 3));
				$i += 2;
			} else
				$ret .= $str[$i];
		}
		return $ret;
	}
     /**
	  * Count_onlineuser_byperfivemin function
	  * get new roles
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int newuser
	  */
	 function count_onlineuser_byperfivemin($appid,$time) {
		  $sql = "SELECT
                                IFNULL(SUM(r.onlineusers), 0) onlineuser
                        FROM
                                razor_realtimeonlineusers r
                        WHERE
                                TIMESTAMPDIFF(MINUTE, r.count_time, '$time') <= 5 AND r.count_time<SUBDATE('$time',INTERVAL 2 MINUTE)
                        AND r.appId = '$appid';";
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->onlineuser;
		  }
	 }

     /**
	  * Count_onlineuser_byperfivemin2 function
	  * get new roles
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int newuser
	  */
    function count_onlineuser_byperfivemin2($time,$appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					#AND r.chId = '$channelid'
					AND r.srvId = '$serverid'
					AND r.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					#AND r.chId = '$channelid'
					AND r.srvId = '$serverid'
					#AND r.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					#AND r.chId = '$channelid'
					#AND r.srvId = '$serverid'
					AND r.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					#AND r.chId = '$channelid'
					#AND r.srvId = '$serverid'
					#AND r.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					AND r.chId = '$channelid'
					AND r.srvId = '$serverid'
					AND r.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					AND r.chId = '$channelid'
					AND r.srvId = '$serverid'
					#AND r.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					AND r.chId = '$channelid'
					#AND r.srvId = '$serverid'
					AND r.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						IFNULL(SUM(r.onlineusers), 0) onlineuser
					FROM
						razor_realtimeonlineusers r
					WHERE
						TIMESTAMPDIFF(
							MINUTE,
							r.count_time,
							'$time'
						) <= 5
					AND r.count_time < SUBDATE(
						'$time',
						INTERVAL 2 MINUTE
					)
					AND r.appId = '$appid'
					AND r.chId = '$channelid'
					#AND r.srvId = '$serverid'
					#AND r.version = '$versionname';";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->onlineuser;
        }
    }
         
   /**
	  * Count_createrole_byperfivemin function
	  * get new roles
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int newuser
	  */
	 function count_createrole_byperfivemin($appid,$time) {
		  $sql = "SELECT
                                COUNT(1) newuser
                        FROM
                                razor_createrole c
                        WHERE
                                TIMESTAMPDIFF(
                                        MINUTE,
                                        c.create_role_time,
                                        '$time'
                                ) <= 5
                        AND c.appId = '$appid';";
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->newuser;
		  }
	 }
         
         
         /**
	  * Count_dauuser_byperfivemin function
	  * get new roles
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int newuser
	  */
	 function count_dauuser_byperfivemin($appid,$time) {
		  $sql = "SELECT
                                COUNT(DISTINCT l.roleId) dauuser
                        FROM
                                razor_login l
                        WHERE
                                TIMESTAMPDIFF(MINUTE, l.login_time, '$time') <= 5
                        AND l.appId = '$appid';";
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->dauuser;
		  }
	 }
         
         
         /**
	  * Count_payamount_byperfivemin function
	  * get new roles
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int newuser
	  */
	 function count_payamount_byperfivemin($appid,$time) {
		  $sql = "SELECT
                                IFNULL(SUM(p.pay_amount),0) amount
                        FROM
                                razor_pay p
                        WHERE
                                TIMESTAMPDIFF(MINUTE, p.pay_time, '$time') <= 5
                        AND p.appId = '$appid';";
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->amount;
		  }
	 }
	 


	 /**
	  * Overview function
	  *
	  * @param $appId product id
	  * 
	  * @return $data overview
	  */
	 function overview($appId) {
	 	$today=date('Y-m-d');
	 	$onedaysago=date('Y-m-d', strtotime("-1 day", strtotime($today)));
	 	$sevendaysago=date('Y-m-d', strtotime("-7 day", strtotime($today)));
 	    $thirtydaysago=date('Y-m-d', strtotime("-30 day", strtotime($today)));

 	    //按照姚胜保2016.05.09优化需求将设备激活修改为新增设备
		$deviceactivations_0 = $this->newusers->getNewdevices($today,$today, $appId, 'all', 'all');
		$deviceactivations_1 = $this->newusers->getNewdevices($onedaysago,$onedaysago, $appId, 'all', 'all');
		$deviceactivations_7 = $this->newusers->getNewdevices($sevendaysago,$sevendaysago, $appId, 'all', 'all');
		$deviceactivations_30 = $this->newusers->getNewdevices($thirtydaysago,$thirtydaysago, $appId, 'all', 'all');

		$newusers_0 = $this->newusers->getNewusers($today,$today, $appId, 'all', 'all');
		$newusers_1 = $this->newusers->getNewusers($onedaysago,$onedaysago, $appId, 'all', 'all');
		$newusers_7 = $this->newusers->getNewusers($sevendaysago,$sevendaysago, $appId, 'all', 'all');
		$newusers_30 = $this->newusers->getNewusers($thirtydaysago,$thirtydaysago, $appId, 'all', 'all');

		$newroles_0 = $this->dauusers->getNewuser($today,$today, $appId,'all','all','all');
		$newroles_1 = $this->dauusers->getNewuser($onedaysago,$onedaysago, $appId,'all','all','all');
		$newroles_7 = $this->dauusers->getNewuser($sevendaysago,$sevendaysago, $appId,'all','all','all');
		$newroles_30 = $this->dauusers->getNewuser($thirtydaysago,$thirtydaysago, $appId,'all','all','all');

		$dauusers_0 = $this->dauusers->getDauuser($today,$today, $appId,'all','all','all');
		$dauusers_1 = $this->dauusers->getDauuser($onedaysago,$onedaysago, $appId,'all','all','all');
		$dauusers_7 = $this->dauusers->getDauuser($sevendaysago,$sevendaysago, $appId,'all','all','all');
		$dauusers_30 = $this->dauusers->getDauuser($thirtydaysago,$thirtydaysago, $appId,'all','all','all');

		$payusers_0 = $this->dauusers->getPayuser($today,$today, $appId,'all','all','all');
		$payusers_1 = $this->dauusers->getPayuser($onedaysago,$onedaysago, $appId,'all','all','all');
		$payusers_7 = $this->dauusers->getPayuser($sevendaysago,$sevendaysago, $appId,'all','all','all');
		$payusers_30 = $this->dauusers->getPayuser($thirtydaysago,$thirtydaysago, $appId,'all','all','all');

		$payamounts_0 = $this->paydata->getPayamount($today,$today, $appId,'all','all','all');
		$payamounts_1 = $this->paydata->getPayamount($onedaysago,$onedaysago, $appId,'all','all','all');
		$payamounts_7 = $this->paydata->getPayamount($sevendaysago,$sevendaysago, $appId,'all','all','all');
		$payamounts_30 = $this->paydata->getPayamount($thirtydaysago,$thirtydaysago, $appId,'all','all','all');

		$paycount_0 = $this->dauusers->getPaycount($today,$today, $appId,'all','all','all');
		$paycount_1 = $this->dauusers->getPaycount($onedaysago,$onedaysago, $appId,'all','all','all');
		$paycount_7 = $this->dauusers->getPaycount($sevendaysago,$sevendaysago, $appId,'all','all','all');
		$paycount_30 = $this->dauusers->getPaycount($thirtydaysago,$thirtydaysago, $appId,'all','all','all');

		$maxonlineusers_0 = $this->dayonline->getPcu($today,$today, $appId, 'all','all','all');
		$maxonlineusers_1 = $this->dayonline->getPcu($onedaysago,$onedaysago, $appId, 'all','all','all');
		$maxonlineusers_7 = $this->dayonline->getPcu($sevendaysago,$sevendaysago, $appId, 'all','all','all');
		$maxonlineusers_30 = $this->dayonline->getPcu($thirtydaysago,$thirtydaysago, $appId, 'all','all','all');

		//0-今天 1-昨天 7-七天前 30-三十天前
		// deviceactivations-设备激活
		// newusers-新增注册
		// newroles-新增用户
		// dauusers-活跃用户
		// payusers_xxx_user-付费人数
		// payusers_xxx_amount-付费金额
		// payusers_xxx_user-付费次数
		// maxonlineusers-最高在线
		$data = array(
			 'deviceactivations_0' => $deviceactivations_0,
			 'deviceactivations_1' => $deviceactivations_1,
			 'deviceactivations_7' => $deviceactivations_7,
			 'deviceactivations_30' => $deviceactivations_30,
			 'newusers_0' => $newusers_0,
			 'newusers_1' => $newusers_1,
			 'newusers_7' => $newusers_7,
			 'newusers_30' => $newusers_30,
			 'newroles_0' => $newroles_0,
			 'newroles_1' => $newroles_1,
			 'newroles_7' => $newroles_7,
			 'newroles_30' => $newroles_30,
			 'dauusers_0' => $dauusers_0,
			 'dauusers_1' => $dauusers_1,
			 'dauusers_7' => $dauusers_7,
			 'dauusers_30' => $dauusers_30,
			 'payusers_0_user' => $payusers_0,
			 'payusers_0_amount' => $payamounts_0,
			 'payusers_0_count' => $paycount_0,
			 'payusers_1_user' => $payusers_1,
			 'payusers_1_amount' => $payamounts_1,
			 'payusers_1_count' => $paycount_1,
			 'payusers_7_user' => $payusers_7,
			 'payusers_7_amount' => $payamounts_7,
			 'payusers_7_count' => $paycount_7,
			 'payusers_30_user' => $payusers_30,
			 'payusers_30_amount' => $payamounts_30,
			 'payusers_30_count' => $paycount_30,
			 'maxonlineusers_0' => $maxonlineusers_0,
			 'maxonlineusers_1' => $maxonlineusers_1,
			 'maxonlineusers_7' => $maxonlineusers_7,
			 'maxonlineusers_30' => $maxonlineusers_30
		);
		return $data;
	  }
          
          
     /**
	  * Sum_razor_realtimeroleinfo function
	  * count dau users
	  * 
	  * 
	  */
	 function sum_razor_realtimeroleinfo() {
          $nowdate=date("Y-m-d");
          $nowtime=date('Y-m-d H:i:s', time());

		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
                $onlineusers = $this->count_onlineuser_byperfivemin($paramsRow_p->appId,$nowtime);
                $newusers = $this->count_createrole_byperfivemin($paramsRow_p->appId,$nowtime);
                $dauusers = $this->count_dauuser_byperfivemin($paramsRow_p->appId,$nowtime);
                $payamount = $this->count_payamount_byperfivemin($paramsRow_p->appId,$nowtime);
                                
				$data = array(
                     'count_date' =>$nowdate ,
					 'appId' => $paramsRow_p->appId,
					 'count_time' =>$nowtime ,
                     'onlineusers' => $onlineusers,
					 'newusers' => $newusers,
					 'dauusers' => $dauusers,
					 'payamount' => $payamount
				);
				$this -> db -> insert('razor_realtimeroleinfo', $data);
				$paramsRow_p = $params_p->next_row();
		  }
		  
	 }

 	 /**
	  * sum_basic_realtimeroleinfo function
	  * count dau users
	  * 
	  * 
	  */
	 function sum_basic_realtimeroleinfo() {
          $countdate=date("Y-m-d");
          $countime=date('Y-m-d H:i:s', time());
	 	  $dwdb = $this->load->database('dw', true);

		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
				$data = array(
					 'product_id' => $paramsRow_psv->appId,
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'version_name' => $paramsRow_psv->version,
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
				$data = array(
					 'product_id' => $paramsRow_ps->appId,
					 'channel_name' => 'all',
					 'server_name' => $server_name,
 					 'version_name' => 'all',
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$data = array(
					 'product_id' => $paramsRow_pv->appId,
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'version_name' => $paramsRow_pv->version,
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_p->appId, 'all', 'all', 'all');
				$data = array(
					 'product_id' => $paramsRow_p->appId,
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'version_name' => 'all',
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
				$data = array(
					 'product_id' => $paramsRow_pcsv->appId,
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'version_name' => $paramsRow_pcsv->version,
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
				$data = array(
					 'product_id' => $paramsRow_pcs->appId,
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'version_name' => 'all',
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
				$data = array(
					 'product_id' => $paramsRow_pcv->appId,
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'version_name' => $paramsRow_pcv->version,
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$payamount = $this->paydata->getPayamount($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$daydau = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$onlineusers = $this->count_onlineuser_byperfivemin2($countime,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
				$data = array(
					 'product_id' => $paramsRow_pc->appId,
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'version_name' => 'all',
					 'count_date' => $countdate,
					 'count_time' => $countime,
					 'onlineusers' => $onlineusers,
					 'newusers' => $newuser,
					 'dauusers' => $daydau,
					 'payamount' => $payamount
				);
				$dwdb->insert_or_update('razor_sum_basic_realtimeroleinfo', $data);
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }

}
