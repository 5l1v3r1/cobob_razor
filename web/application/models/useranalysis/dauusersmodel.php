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
 * Dauusersmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Dauusersmodel extends CI_Model {

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
		  $this->load->model("useranalysis/newcreaterolemodel", 'newcreaterole');
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
	 function getDetailUsersDataByDay($fromTime, $toTime, $channel, $server, $version) {
		  $list = array();
		  $query = $this->getDetailDauUsersData($fromTime, $toTime, $channel, $server, $version);
		  $dauUsersRow = $query->first_row();
		  for ($i = 0; $i < $query->num_rows(); $i++) {
				$fRow = array();
				$fRow["date"] = $dauUsersRow->date_sk;
				$fRow['newuser'] = $dauUsersRow->newuser;
				$fRow['payuser'] = $dauUsersRow->payuser;
				$fRow['notpayuser'] = $dauUsersRow->notpayuser;
				$fRow['daydau'] = $dauUsersRow->daydau;
				$fRow['weekdau'] = $dauUsersRow->weekdau;
				$fRow['monthdau'] = $dauUsersRow->monthdau;
				$fRow['useractiverate'] = $dauUsersRow->useractiverate;
				$dauUsersRow = $query->next_row();
				array_push($list, $fRow);
		  }
		  return $list;
	 }

	 /**
	  * GetDetailDauUsersData function
	  * get detailed data
	  *
	  * @param string $fromTime from time
	  * @param string $toTime   to time
	  * @param string $channel channel
	  * @param string $server server
	  * @param string $version version
	  *
	  * @return query query
	  */
	 function getDetailDauUsersData($fromTime, $toTime, $channel, $version, $server) {
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		$sql = "SELECT
			IFNULL(date_sk, 0) date_sk,
			IFNULL(newuser, 0) newuser,
			IFNULL(payuser, 0) payuser,
			IFNULL(notpayuser, 0) notpayuser,
			IFNULL(daydau, 0) daydau,
			IFNULL(weekdau, 0) weekdau,
			IFNULL(monthdau, 0) monthdau,
			IFNULL(useractiverate, 0) useractiverate
			FROM
				" . $dwdb->dbprefix('sum_basic_dauusers') . "
			WHERE
			date_sk >= '" . $fromTime . "'
			AND channel_name in('" . $channel_list . "')
			AND version_name in('" . $version_list . "')
			AND server_name in('" . $server_list . "')
			AND product_id = '" . $productId . "'
			Group By date_sk DESC";
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
	  * GetNewuser function
	  * get new users
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int newuser
	  */
	 function getNewuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					 FROM
								razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								#AND c.chId = '$channelid'
								AND c.srvId = '$serverid'
					 AND c.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					 FROM
								razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								#AND c.chId = '$channelid'
								AND c.srvId = '$serverid'
					 #AND c.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					 FROM
								razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								#AND c.chId = '$channelid'
								#AND c.srvId = '$serverid'
					 AND c.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					 FROM
								razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								#AND c.chId = '$channelid'
								#AND c.srvId = '$serverid'
					 #AND c.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					 FROM
								razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								AND c.chId = '$channelid'
								AND c.srvId = '$serverid'
					 AND c.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					 FROM
								razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								AND c.chId = '$channelid'
								AND c.srvId = '$serverid'
					 #AND c.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(c.roleId) newuser
					FROM
								razor_createrole c
					WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								AND c.chId = '$channelid'
								#AND c.srvId = '$serverid'
				AND c.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(c.roleId) newuser
					 FROM
							razor_createrole c
					 WHERE
								c.create_role_date >= '$fromdate'
								AND c.create_role_date <= '$todate'
								AND c.appId='$appid'
								AND c.chId = '$channelid'
								#AND c.srvId = '$serverid'
					 #AND c.version = '$versionname';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->newuser;
		  }
	 }
    
	 /**
	  * GetPayuser function
	  * get pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int payuser
	  */
	 function getPayuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
						count(distinct p.roleId) payuser
				FROM
						" . $this->db->dbprefix('pay') . " p
				WHERE
						p.pay_date >= '$fromdate'
						AND p.pay_date <= '$todate'
						AND p.appId='$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
				AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->payuser;
		  }
	 }

 	 /**
	  * GetDaupayuser function
	  * get pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int payuser
	  */
	 function getDaupayuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 #AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 #AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 #AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
						count(distinct p.roleId) payuser
				FROM
						" . $this->db->dbprefix('login') . " p
				WHERE
						p.login_date >= '$fromdate'
						AND p.login_date <= '$todate'
						AND p.appId='$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
				AND p.version = '$versionname'
				AND p.roleVip<>0;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(distinct p.roleId) payuser
					 FROM
								" . $this->db->dbprefix('login') . " p
					 WHERE
								p.login_date >= '$fromdate'
								AND p.login_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 #AND p.version = '$versionname'
					 AND p.roleVip<>0;";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->payuser;
		  }
	 }


 	 /**
	  * getPaycount function
	  * get pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int payuser
	  */
	 function getPaycount($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								#AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
						count(p.roleId) paycount
				FROM
						" . $this->db->dbprefix('pay') . " p
				WHERE
						p.pay_date >= '$fromdate'
						AND p.pay_date <= '$todate'
						AND p.appId='$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
				AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
								count(p.roleId) paycount
					 FROM
								" . $this->db->dbprefix('pay') . " p
					 WHERE
								p.pay_date >= '$fromdate'
								AND p.pay_date <= '$todate'
								AND p.appId='$appid'
								AND p.chId = '$channelid'
								#AND p.srvId = '$serverid'
					 #AND p.version = '$versionname';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->paycount;
		  }
	 }
	 
	 /**
	  * GetDauuser function
	  * get dau user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int dauuser
	  */
	 function getDauuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "
						  SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
			  $sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
									 COUNT(DISTINCT l.roleId) dauuser
						  FROM
									 razor_login l
						  WHERE
							  l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->dauuser;
		  }
	 }


 	 /**
	  * getGamecountandtime function
	  * get dau user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int dauuser
	  */
	 function getGamecountandtime($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  #AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
			  $sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  AND l.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
									IFNULL(SUM(case WHEN l.type='login' THEN 1 else 0 end),0) gamecount,
								 	IFNULL(
												SUM(
													CASE
													WHEN l.type = 'logout' THEN
														l.offlineSettleTime / 60
													ELSE
														0
													END
												),
												0
											) onlinetime
						  FROM
									 razor_login l
						  WHERE
							l.login_date >= '$fromdate'
						  AND l.login_date <= '$todate'
						  AND l.appId = '$appid'
						  AND l.chId = '$channelid'
						  #AND l.srvId = '$serverid'
						  #AND l.version = '$versionname';";
		  }
	  	$query = $this->db->query($sql);
	    if ($query != null && $query -> num_rows() > 0) {
	        return $query -> row_array();
	    }
	 }

	 /**
	  * Sum_basic_dauusers function
	  * count dau users
	  * 
	  * 
	  */
	 function sum_basic_dauusers($countdate) {
	 	  $sevendaysago=date('Y-m-d', strtotime("-7 day", strtotime($countdate)));
	 	  $thirtydaysago=date('Y-m-d', strtotime("-30 day", strtotime($countdate)));
	 	  $dwdb = $this->load->database('dw', true);

		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
				$data = array(
					 'product_id' => $paramsRow_psv->appId,
					 'channel_name' => 'all',
					 'version_name' => $paramsRow_psv->version,
					 'server_name' => $server_name,
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
				$data = array(
					 'product_id' => $paramsRow_ps->appId,
					 'channel_name' => 'all',
					 'version_name' => 'all',
					 'server_name' => $server_name,
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$data = array(
					 'product_id' => $paramsRow_pv->appId,
					 'channel_name' => 'all',
					 'version_name' => $paramsRow_pv->version,
					 'server_name' => 'all',
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$data = array(
					 'product_id' => $paramsRow_p->appId,
					 'channel_name' => 'all',
					 'version_name' => 'all',
					 'server_name' => 'all',
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
				$data = array(
					 'product_id' => $paramsRow_pcsv->appId,
					 'channel_name' => $channel_name,
					 'version_name' => $paramsRow_pcsv->version,
					 'server_name' => $server_name,
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
				$data = array(
					 'product_id' => $paramsRow_pcs->appId,
					 'channel_name' => $channel_name,
					 'version_name' => 'all',
					 'server_name' => $server_name,
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
				$data = array(
					 'product_id' => $paramsRow_pcv->appId,
					 'channel_name' => $channel_name,
					 'version_name' => $paramsRow_pcv->version,
					 'server_name' => 'all',
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
				$newuser = $this->newcreaterole->getNewuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$payuser = $this->getDaupayuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$daydau = $this->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$weekdau = $this->getDauuser($sevendaysago,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$monthdau = $this->getDauuser($thirtydaysago,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
				$data = array(
					 'product_id' => $paramsRow_pc->appId,
					 'channel_name' => $channel_name,
					 'version_name' => 'all',
					 'server_name' => 'all',
					 'date_sk' => $countdate,
					 'newuser' => $newuser,
					 'payuser' => $payuser,
					 'notpayuser' => $daydau-$payuser,
					 'daydau' => $daydau,
					 'weekdau' => $weekdau,
					 'monthdau' => $monthdau,
					 'useractiverate' => ($monthdau==0)?0:($daydau/$monthdau)
				);
				$dwdb->insert_or_update('razor_sum_basic_dauusers', $data);
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }

}
