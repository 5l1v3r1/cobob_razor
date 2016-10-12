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
 * Userltvmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Userltvmodel extends CI_Model {

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
			 $this->load->model("useranalysis/dauusersmodel", 'dauusers');
			 $this->load->model("payanaly/paydatamodel", 'paydata');
			 $this->load->model('common');
			 
	  }

	  /**
		* GetDetailDauUsersDataByDay function
		* get detailed data
		*
		* @param string $fromTime from time
		* @param string $toTime   to time
		* @param string $channel channel
		* @param string $server server
		* @param string $version version
		*
		* @return query list
		*/
	  function getUserltvData($fromTime, $toTime, $channel, $server, $version) {
			 $list = array();
			 $query = $this->UserltvData($fromTime, $toTime, $channel, $server, $version);
			 $dauUsersRow = $query->first_row();
			 for ($i = 0; $i < $query->num_rows(); $i++) {
					 $fRow = array();
					 $fRow["startdate_sk"] = $dauUsersRow->startdate_sk;
					 $fRow['newuser'] = $dauUsersRow->newuser;
					 $fRow['day1'] = $dauUsersRow->day1;
					 $fRow['day2'] = $dauUsersRow->day2;
					 $fRow['day3'] = $dauUsersRow->day3;
					 $fRow['day4'] = $dauUsersRow->day4;
					 $fRow['day5'] = $dauUsersRow->day5;
					 $fRow['day6'] = $dauUsersRow->day6;
					 $fRow['day7'] = $dauUsersRow->day7;
					 $fRow['day14'] = $dauUsersRow->day14;
					 $fRow['day30'] = $dauUsersRow->day30;
					 $fRow['day60'] = $dauUsersRow->day60;
					 $fRow['day90'] = $dauUsersRow->day90;
					 $dauUsersRow = $query->next_row();
					 array_push($list, $fRow);
			 }
			 return $list;
	  }

	  function UserltvData($fromTime, $toTime, $channel, $version, $server) {
		  $currentProduct = $this->common->getCurrentProduct();
		  $productId = $currentProduct->id;
		  $dwdb = $this->load->database('dw', true);
		  ($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		  ($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		  ($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		  $sql = "SELECT
				IFNULL(startdate_sk, 0) startdate_sk,
				IFNULL(newuser, 0) newuser,
				IFNULL(day1, 0) day1,
				IFNULL(day2, 0) day2,
				IFNULL(day3, 0) day3,
				IFNULL(day4, 0) day4,
				IFNULL(day5, 0) day5,
				IFNULL(day6, 0) day6,
				IFNULL(day7, 0) day7,
				IFNULL(day14, 0) day14,
				IFNULL(day30, 0) day30,
				IFNULL(day60, 0) day60,
				IFNULL(day90, 0) day90
				FROM
					 " . $dwdb->dbprefix('sum_basic_userltv') . "
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND product_id = '" . $productId . "'
				Order By startdate_sk DESC";
		  $query = $dwdb->query($sql);
		  return $query;
	  }

	  function getTimefieldData($fromTime, $toTime, $channel, $server, $version, $date) {
			 $list = array();
			 $query = $this->TimefieldData($fromTime, $toTime, $channel, $server, $version, $date);
			 $dauUsersRow = $query->first_row();
			 for ($i = 0; $i < $query->num_rows(); $i++) {
					 $fRow = array();
					 $fRow["startdate_sk"] = $dauUsersRow->startdate_sk;
					 $fRow['newuser'] = $dauUsersRow->newuser;
					 $fRow['timefield'] = $dauUsersRow->timefield;
					 $fRow['date_sk'] = $dauUsersRow->date_sk;
					 $fRow['payuser'] = $dauUsersRow->payuser;
					 $fRow['payamount'] = $dauUsersRow->payamount;
					 $fRow['ltv'] = $dauUsersRow->ltv;
					 $dauUsersRow = $query->next_row();
					 array_push($list, $fRow);
			 }
			 return $list;
	  }

	  function TimefieldData($fromTime, $toTime, $channel, $server, $version, $date) {
		  $currentProduct = $this->common->getCurrentProduct();
		  $productId = $currentProduct->id;
		  $dwdb = $this->load->database('dw', true);
		  ($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		  ($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		  ($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		  $sql = "SELECT
				IFNULL(startdate_sk, 0) startdate_sk,
				IFNULL(newuser, 0) newuser,
				IFNULL(timefield, 0) timefield,
				IFNULL(date_sk, 0) date_sk,
				IFNULL(payuser, 0) payuser,
				IFNULL(payamount, 0) payamount,
				IFNULL(ltv, 0) ltv
				FROM
					 " . $dwdb->dbprefix('sum_basic_userltv_timefield') . "
				WHERE
				startdate_sk = '" . $date . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND product_id = '" . $productId . "'
				Order By rid ASC";
		  $query = $dwdb->query($sql);
		  return $query;
	  }

	  /**
		* unescape 
		* 
		* @param Array $str
		* 
		* @return Array $ret
		*/
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
		* getNewvipuser function
		* get new vip user
		* 
		* @param Date $date
		* @param String $appid
		* @param String $channelid
		* @param String $serverid
		* @param String $versionname
		* 
		* @return Int new vip user
		*/
	  function getUserltv($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days) {
			 if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							#AND ctr.chId = '$channelid'
							AND ctr.srvId = '$serverid'
							AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							#AND ctr.chId = '$channelid'
							AND ctr.srvId = '$serverid'
							#AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							#AND ctr.chId = '$channelid'
							#AND ctr.srvId = '$serverid'
							AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							#AND ctr.chId = '$channelid'
							#AND ctr.srvId = '$serverid'
							#AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							AND ctr.chId = '$channelid'
							AND ctr.srvId = '$serverid'
							AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							AND ctr.chId = '$channelid'
							AND ctr.srvId = '$serverid'
							#AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							AND ctr.chId = '$channelid'
							#AND ctr.srvId = '$serverid'
							AND ctr.version = '$versionname'
						);";
			 } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
					 $sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_totalamount
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$fromdate'
						AND p.pay_date <= DATE_ADD('$todate', INTERVAL $days DAY)
				AND p.pay_date<>CURDATE()
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId IN (
							SELECT DISTINCT
								ctr.roleId
							FROM
								razor_createrole ctr
							WHERE
								ctr.create_role_date = '$fromdate'
							AND ctr.appId = '$appid'
							AND ctr.chId = '$channelid'
							#AND ctr.srvId = '$serverid'
							#AND ctr.version = '$versionname'
						);";
			 }
			 $query = $this->db->query($sql);
			 $row = $query->first_row();
			 if ($query->num_rows > 0) {
					 return $row->pay_totalamount;
			 }
	  }

		/**
		* sum_basic_userltv function
		* count user ltv
		* 
		* 
		*/
	  function sum_basic_userltv($countdate) {
			 $dwdb = $this->load->database('dw', true);

			 $params_psv = $this->product->getProductServerVersionOffChannel();
			 $paramsRow_psv = $params_psv->first_row();
			 for ($i = 0; $i < $params_psv->num_rows(); $i++) {
					 //get servername by serverid
					 $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_psv->appId,
							'version_name' => $paramsRow_psv->version,
							'channel_name' => 'all',
							'server_name' => $server_name,
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$d);
					$data_userltv_timefield = array(
								'startdate_sk' => $countdate,
								'enddate_sk' => $countdate,
								'product_id' => $paramsRow_psv->appId,
								'version_name' => $paramsRow_psv->version,
								'channel_name' => 'all',
								'server_name' => $server_name,
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_psv = $params_psv->next_row();
			 }
			 $params_ps = $this->product->getProductServerOffChannelVersion();
			 $paramsRow_ps = $params_ps->first_row();
			 for ($i = 0; $i < $params_ps->num_rows(); $i++) {
					 //get servername by serverid
					 $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_ps->appId,
							'version_name' => 'all',
							'channel_name' => 'all',
							'server_name' => $server_name,
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_ps->appId,
								 'version_name' => 'all',
								 'channel_name' => 'all',
								 'server_name' => $server_name,
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_ps = $params_ps->next_row();
			 }
			 $params_pv = $this->product->getProductVersionOffChannelServer();
			 $paramsRow_pv = $params_pv->first_row();
			 for ($i = 0; $i < $params_pv->num_rows(); $i++) {
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_pv->appId,
							'version_name' => $paramsRow_pv->version,
							'channel_name' => 'all',
							'server_name' => 'all',
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_pv->appId,
								 'version_name' => $paramsRow_pv->version,
								 'channel_name' => 'all',
								 'server_name' => 'all',
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_pv = $params_pv->next_row();
			 }
			 $params_p = $this->product->getProductOffChannelServerVersion();
			 $paramsRow_p = $params_p->first_row();
			 for ($i = 0; $i < $params_p->num_rows(); $i++) {
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_p->appId,
							'version_name' => 'all',
							'channel_name' => 'all',
							'server_name' => 'all',
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate, $paramsRow_p->appId, 'all', 'all', 'all');
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate, $paramsRow_p->appId, 'all', 'all', 'all');
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_p->appId,
								 'version_name' => 'all',
								 'channel_name' => 'all',
								 'server_name' => 'all',
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_p = $params_p->next_row();
			 }
			 $params_pcsv = $this->product->getProductChannelServerVersion();
			 $paramsRow_pcsv = $params_pcsv->first_row();
			 for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
					 //get channelname by channelid
					 $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
					 //get servername by serverid
					 $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_pcsv->appId,
							'version_name' => $paramsRow_pcsv->version,
							'channel_name' => $channel_name,
							'server_name' => $server_name,
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_pcsv->appId,
								 'version_name' => $paramsRow_pcsv->version,
								 'channel_name' => $channel_name,
								 'server_name' => $server_name,
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_pcsv = $params_pcsv->next_row();
			 }
			 $params_pcs = $this->product->getProductChannelServerOffVersion();
			 $paramsRow_pcs = $params_pcs->first_row();
			 for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
					 //get channelname by channelid
					 $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
					 //get servername by serverid
					 $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_pcs->appId,
							'version_name' => 'all',
							'channel_name' => $channel_name,
							'server_name' => $server_name,
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_pcs->appId,
								 'version_name' => 'all',
								 'channel_name' => $channel_name,
								 'server_name' => $server_name,
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_pcs = $params_pcs->next_row();
			 }
			 $params_pcv = $this->product->getProductChannelVersionOffServer();
			 $paramsRow_pcv = $params_pcv->first_row();
			 for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
					 //get channelname by channelid
					 $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_pcv->appId,
							'version_name' => $paramsRow_pcv->version,
							'channel_name' => $channel_name,
							'server_name' => 'all',
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_pcv->appId,
								 'version_name' => $paramsRow_pcv->version,
								 'channel_name' => $channel_name,
								 'server_name' => 'all',
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_pcv = $params_pcv->next_row();
			 }
			 $params_pc = $this->product-> getProductChannelOffServerVersion();
			 $paramsRow_pc = $params_pc->first_row();
			 for ($i = 0; $i < $params_pc->num_rows(); $i++) {
					 //get channelname by channelid
					 $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
					 // razor_sum_basic_userltv
					 $Newuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
					 $Userltv1=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1);
					 $Userltv2=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2);
					 $Userltv3=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',3);
					 $Userltv4=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4);
					 $Userltv5=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',5);
					 $Userltv6=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',6);
					 $Userltv7=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7);
					 $Userltv14=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14);
					 $Userltv30=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30);
					 $Userltv60=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',60);
					 $Userltv90=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',90);
				$data_userltv = array(
							'startdate_sk' => $countdate,
							'enddate_sk' => $countdate,
							'product_id' => $paramsRow_pc->appId,
							'version_name' => 'all',
							'channel_name' => $channel_name,
							'server_name' => 'all',
							'newuser' => $Newuser,
							'day1' => ($Newuser==0)?0:($Userltv1/$Newuser),
							'day2' => ($Newuser==0)?0:($Userltv2/$Newuser),
							'day3' => ($Newuser==0)?0:($Userltv3/$Newuser),
							'day4' => ($Newuser==0)?0:($Userltv4/$Newuser),
							'day5' => ($Newuser==0)?0:($Userltv5/$Newuser),
							'day6' => ($Newuser==0)?0:($Userltv6/$Newuser),
							'day7' => ($Newuser==0)?0:($Userltv7/$Newuser),
							'day14' => ($Newuser==0)?0:($Userltv14/$Newuser),
							'day30' => ($Newuser==0)?0:($Userltv30/$Newuser),
							'day60' => ($Newuser==0)?0:($Userltv60/$Newuser),
							'day90' => ($Newuser==0)?0:($Userltv90/$Newuser)
					 );
					 $dwdb->insert_or_update('razor_sum_basic_userltv', $data_userltv);
					 // razor_sum_basic_userltv_timefield
					 for ($d=0; $d <91 ; $d++) { 
						$Dateadddate=$this->common->getDateadddate($countdate,$d);
						 $Payuser=$this->dauusers->getPayuser($Dateadddate,$Dateadddate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
						  $Payamount=$this->paydata->getPayamount($Dateadddate,$Dateadddate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
						 $Userltv=$this->getUserltv($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$d);
					$data_userltv_timefield = array(
								 'startdate_sk' => $countdate,
								 'enddate_sk' => $countdate,
								 'product_id' => $paramsRow_pc->appId,
								 'version_name' => 'all',
								 'channel_name' => $channel_name,
								 'server_name' => 'all',
								'newuser' => $Newuser,
								'timefield' => $d.'日',
								'date_sk' => $Dateadddate,
								'payuser' => $Payuser,
								'payamount' => $Payamount,
								'ltv' => ($Newuser==0)?0:($Userltv/$Newuser)
						 );
						 $dwdb->insert_or_update('razor_sum_basic_userltv_timefield', $data_userltv_timefield);
					 }
					 $paramsRow_pc = $params_pc->next_row();
			 }
	  }

}
