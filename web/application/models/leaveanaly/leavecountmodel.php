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
 * Leavecountmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Leavecountmodel extends CI_Model {

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
			$this->load->model('useranalysis/dauusersmodel', 'dauusers');
			$this->load->model('useranalysis/levelalymodel', 'levelaly');
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
	function getLeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag) {
			$list = array();
			$query = $this->LeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag);
			$dauUsersRow = $query->first_row();
			for ($i = 0; $i < $query->num_rows(); $i++) {
					$fRow = array();
					$fRow['date_sk'] = $dauUsersRow->date_sk;
					$fRow['sevenleaverate'] = $dauUsersRow->sevenleaverate;
					$fRow['fourteenleaverate'] = $dauUsersRow->fourteenleaverate;
					$fRow['thirtyleaverate'] = $dauUsersRow->thirtyleaverate;
					$fRow['sevenreturnrate'] = $dauUsersRow->sevenreturnrate;
					$fRow['fourteenreturnrate'] = $dauUsersRow->fourteenreturnrate;
					$fRow['thirtyreturnrate'] = $dauUsersRow->thirtyreturnrate;
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
	function LeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag) {
			$currentProduct = $this->common->getCurrentProduct();
			$productId = $currentProduct->id;
			$dwdb = $this->load->database('dw', true);
			($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
			($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
			($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
			$sql = "SELECT
				IFNULL(date_sk, 0) date_sk,
				IFNULL(sevenleaverate, 0) sevenleaverate,
				IFNULL(fourteenleaverate, 0) fourteenleaverate,
				IFNULL(thirtyleaverate, 0) thirtyleaverate,
				IFNULL(sevenreturnrate, 0) sevenreturnrate,
				IFNULL(fourteenreturnrate, 0) fourteenreturnrate,
				IFNULL(thirtyreturnrate, 0) thirtyreturnrate
			FROM
				" . $dwdb->dbprefix('sum_basic_leavecount') . " 
			WHERE
			date_sk >= '".$fromTime."'
			AND usertag = '".$usertag."'
			AND product_id = $productId
			AND channel_name in('" . $channel_list . "')
			AND version_name in('" . $version_list . "')
			AND server_name in('" . $server_list . "')
			Order By date_sk DESC";
			$query = $dwdb->query($sql);
			return $query;
	}
	function getLeavecountDetailData($channel, $server,$version,$usertag,$getdate,$type) {
			$list = array();
			$query = $this->LeavecountDetailData($channel, $server,$version,$usertag,$getdate,$type);
			$dauUsersRow = $query->first_row();
			for ($i = 0; $i < $query->num_rows(); $i++) {
					$fRow = array();
					$fRow['date_sk'] = $dauUsersRow->date_sk;
					$fRow['levelfield'] = $dauUsersRow->levelfield;
					$fRow['users'] = $dauUsersRow->users;
					$fRow['leveldistribitionrate'] = $dauUsersRow->leveldistribitionrate;
					$fRow['payamount'] = $dauUsersRow->payamount;
					$fRow['payusers'] = $dauUsersRow->payusers;
					$fRow['userrate'] = $dauUsersRow->userrate;
					$dauUsersRow = $query->next_row();
					array_push($list, $fRow);
			}
			return $list;
	}

	function LeavecountDetailData($channel, $server,$version,$usertag,$getdate,$type) {
			$currentProduct = $this->common->getCurrentProduct();
			$productId = $currentProduct->id;
			$dwdb = $this->load->database('dw', true);
			($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
			($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
			($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
			$sql = "SELECT
				IFNULL(date_sk, 0) date_sk,
				IFNULL(levelfield, 0) levelfield,
				IFNULL(users, 0) users,
				IFNULL(leveldistribitionrate, 0) leveldistribitionrate,
				IFNULL(payamount, 0) payamount,
				IFNULL(payusers, 0) payusers,
				IFNULL(userrate, 0) userrate
			FROM
				" . $dwdb->dbprefix('sum_basic_leavecount_levelaly') . "
			WHERE
			product_id = $productId
			AND channel_name in('" . $channel_list . "')
			AND version_name in('" . $version_list . "')
			AND server_name in('" . $server_list . "')
			AND date_sk = '".$getdate."'
			AND usertag = '".$usertag."'
			AND type = '".$type."'
			Order By levelfield ASC";
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
	  * Getleaveuser function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getleaveuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=7) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) leavecount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date > '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->leavecount;
		  }
	 }

 	 /**
	  * GetUsersByLevel function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getUsersByLevel($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=7,$startLevel=1,$endLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(a.userlevel) userlevel
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date <= '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
										AND c.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date > '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
							WHERE a.userlevel>=$startLevel AND a.userlevel<=$endLevel;";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->userlevel;
		  }
	 }

	 /**
	  * Getleaveuser function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getleaveuserBypay($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=7) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) leavecount
						FROM
							razor_pay p
						WHERE
							p.pay_date < '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->leavecount;
		  }
	 }

	 	 /**
	  * Getleaveuser function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getleavepayuserByLevel($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=7,$startLevel=1,$endLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										#AND p.chId = '$channelid'
										AND p.srvId = '$serverid'
										AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										#AND p.chId = '$channelid'
										AND p.srvId = '$serverid'
										#AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										#AND p.chId = '$channelid'
										#AND p.srvId = '$serverid'
										AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										#AND p.chId = '$channelid'
										#AND p.srvId = '$serverid'
										#AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											#AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										AND p.chId = '$channelid'
										AND p.srvId = '$serverid'
										AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										AND p.chId = '$channelid'
										AND p.srvId = '$serverid'
										#AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										AND p.chId = '$channelid'
										#AND p.srvId = '$serverid'
										AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(a.roleId) roleCount,
							IFNULL(SUM(a.amount),0) payAmount
						FROM
							(
								SELECT
									pr.*, IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											p.roleId,
											IFNULL(SUM(p.pay_amount),0) amount
										FROM
											razor_pay p
										WHERE
											p.pay_date < '$date'
										AND p.appId = '$appid'
										AND p.chId = '$channelid'
										#AND p.srvId = '$serverid'
										#AND p.version = '$versionname'
										AND p.roleId NOT IN (
											SELECT DISTINCT
												lg.roleId
											FROM
												razor_login lg
											WHERE
												lg.login_date >= '$date'
											AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
											AND lg.appId = '$appid'
											AND lg.chId = '$channelid'
											#AND lg.srvId = '$serverid'
											#AND lg.version = '$versionname'
										)
										GROUP BY
											p.roleId
									) pr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date <= DATE_ADD('$date', INTERVAL $days DAY)
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON pr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
	 }


 	 /**
	  * Getreturnuser function
	  * get return user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int returnuser
	  */
	 function getreturnuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=7) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						#AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) returncount
						FROM
							razor_createrole c
						WHERE
							c.create_role_date <= '$date'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						#AND c.srvId = '$serverid'
						#AND c.version = '$versionname'
						AND c.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND c.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->returncount;
		  }
	 }

	  	 /**
	  * Getreturnuser function
	  * get return user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int returnuser
	  */
	 function getreturnuserBypay($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=7) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							#AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							AND lg.version = '$versionname'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(DISTINCT p.roleId) returncount
						FROM
							razor_pay p
						WHERE
							p.pay_date <= '$date'
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname'
						AND p.roleId NOT IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date >= '$date'
							AND lg.login_date <= DATE_ADD('$date', INTERVAL $days DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						)
						AND p.roleId IN (
							SELECT DISTINCT
								lg.roleId
							FROM
								razor_login lg
							WHERE
								lg.login_date = DATE_ADD('$date', INTERVAL $days+1 DAY)
							AND lg.appId = '$appid'
							AND lg.chId = '$channelid'
							#AND lg.srvId = '$serverid'
							#AND lg.version = '$versionname'
						);";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->returncount;
		  }
	 }

 	 /**
	  * Sum_basic_leavecount function
	  * count dau users
	  *
	  *
	  */
	 function sum_basic_leavecount($countdate) {
	 	  $dwdb = $this->load->database('dw', true);

		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
		  		$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
		  		$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30);

				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_psv->appId,
					 'version_name' => $paramsRow_psv->version,
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_psv->appId,
					 'version_name' => $paramsRow_psv->version,
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
		  		$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30);

				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_ps->appId,
					 'version_name' => 'all',
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_ps->appId,
					 'version_name' => 'all',
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
		  		$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pv->appId,
					 'version_name' => $paramsRow_pv->version,
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pv->appId,
					 'version_name' => $paramsRow_pv->version,
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
				$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_p->appId,
					 'version_name' => 'all',
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_p->appId,
					 'version_name' => 'all',
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
				$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcsv->appId,
					 'version_name' =>  $paramsRow_pcsv->version,
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcsv->appId,
					 'version_name' =>  $paramsRow_pcsv->version,
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
				$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcs->appId,
					 'version_name' =>  'all',
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcs->appId,
					 'version_name' =>  'all',
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
		  		$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcv->appId,
					 'version_name' =>  $paramsRow_pcv->version,
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcv->appId,
					 'version_name' =>  $paramsRow_pcv->version,
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
		  		$newusers = $this->dauusers->getNewuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$payusers = $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

				$leaveuser_all_7 = $this->getleaveuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7);
				$leaveuser_all_14 = $this->getleaveuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14);
				$leaveuser_all_30 = $this->getleaveuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30);
				$returnuser_all_7 = $this->getreturnuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7);
				$returnuser_all_14 = $this->getreturnuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14);
				$returnuser_all_30 = $this->getreturnuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30);

				$leaveuser_pay_7 = $this->getleaveuserBypay($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7);
				$leaveuser_pay_14 = $this->getleaveuserBypay($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14);
				$leaveuser_pay_30 = $this->getleaveuserBypay($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30);
				$returnuser_pay_7 = $this->getreturnuserBypay($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7);
				$returnuser_pay_14 = $this->getreturnuserBypay($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14);
				$returnuser_pay_30 = $this->getreturnuserBypay($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
				$data_alluser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pc->appId,
					 'version_name' =>  'all',
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'usertag' => 'alluser',
					 'sevenleaverate' => ($newusers==0)?0:($leaveuser_all_7/$newusers),
					 'fourteenleaverate' => ($newusers==0)?0:($leaveuser_all_14/$newusers),
					 'thirtyleaverate' => ($newusers==0)?0:($leaveuser_all_30/$newusers),
					 'sevenreturnrate' => $returnuser_all_7,
					 'fourteenreturnrate' => $returnuser_all_14,
					 'thirtyreturnrate' => $returnuser_all_30,
				);

				$data_payuser = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pc->appId,
					 'version_name' =>  'all',
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'usertag' => 'payuser',
					 'sevenleaverate' => ($payusers==0)?0:($leaveuser_pay_7/$payusers),
					 'fourteenleaverate' => ($payusers==0)?0:($leaveuser_pay_14/$payusers),
					 'thirtyleaverate' => ($payusers==0)?0:($leaveuser_pay_30/$payusers),
					 'sevenreturnrate' => $returnuser_pay_7,
					 'fourteenreturnrate' => $returnuser_pay_14,
					 'thirtyreturnrate' => $returnuser_pay_30,
				);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_alluser);
				$dwdb->insert_or_update('razor_sum_basic_leavecount', $data_payuser);
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }

  	 /**
	  * Sum_basic_leavecount_levelaly function
	  * count dau users
	  *
	  *
	  */
	 function sum_basic_leavecount_levelaly($countdate) {
	 	  $dwdb = $this->load->database('dw', true);

		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_psv->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) {
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7,$m,$m);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
                                                 
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,14,$j,$j);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,$k,$k);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_ps->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7,$m,$m);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',14,$j,$j);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,$k,$k);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pv->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7,$m,$m);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) {
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level"; 
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,14,$j,$j);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,$k,$k);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_p->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7,$m,$m);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',14,$j,$j);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,$k,$k);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcsv->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7,$m,$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,14,$j,$j);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,$k,$k);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcs->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) {
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level"; 
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7,$m,$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',14,$j,$j);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,$k,$k);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcv->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7,$m,$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,14,$j,$j);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,$k,$k);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pc->appId);
				$users_7_level_all= $this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7,1,100000000);
				$users_14_level_all= $this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14,1,100000000);
				$users_30_level_all= $this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,1,100000000);
				$payusers_7_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7,1,100000000);
				$payusers_14_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14,1,100000000);
				$payusers_30_level_all = $this->getleavepayuserByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,1,100000000);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$users_7_level_="users_7_level";
					$payusers_7_level_="payusers_7_level";
					${$users_7_level_.$m}= $this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7,$m,$m);
					${$payusers_7_level_.$m} = $this->getleavepayuserByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7,$m,$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$users_7_level_.$m},
						 'leveldistribitionrate' => ($users_7_level_all==0)?0:(${$users_7_level_.$m}/$users_7_level_all),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => (${$users_7_level_.$m}==0)?0:(${$payusers_7_level_.$m}['roleCount']/${$users_7_level_.$m})
					);

					${$data_payuser_.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $m,
						 'type' => 7,
						 'users' => ${$payusers_7_level_.$m}['roleCount'],
						 'leveldistribitionrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount']),
						 'payamount' => ${$payusers_7_level_.$m}['payAmount'],
						 'payusers' => ${$payusers_7_level_.$m}['roleCount'],
						 'userrate' => ($payusers_7_level_all['roleCount']==0)?0:(${$payusers_7_level_.$m}['roleCount']/$payusers_7_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$m});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$m});
				}
				for ($j=1; $j <$maxlevel+1 ; $j++) { 
					$users_14_level_="users_14_level";
					$payusers_14_level_="payusers_14_level";
					${$users_14_level_.$j}= $this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14,$j,$j);
					${$payusers_14_level_.$j} = $this->getleavepayuserByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',14,$j,$j);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$users_14_level_.$j},
						 'leveldistribitionrate' => ($users_14_level_all==0)?0:(${$users_14_level_.$j}/$users_14_level_all),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => (${$users_14_level_.$j}==0)?0:(${$payusers_14_level_.$j}['roleCount']/${$users_14_level_.$j})
					);

					${$data_payuser_.$j} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $j,
						 'type' => 14,
						 'users' => ${$payusers_14_level_.$j}['roleCount'],
						 'leveldistribitionrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount']),
						 'payamount' => ${$payusers_14_level_.$j}['payAmount'],
						 'payusers' => ${$payusers_14_level_.$j}['roleCount'],
						 'userrate' => ($payusers_14_level_all['roleCount']==0)?0:(${$payusers_14_level_.$j}['roleCount']/$payusers_14_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$j});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$j});
				}
				for ($k=1; $k <$maxlevel+1 ; $k++) { 
					$users_30_level_="users_30_level";
					$payusers_30_level_="payusers_30_level";
					${$users_30_level_.$k}= $this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,$k,$k);
					${$payusers_30_level_.$k} = $this->getleavepayuserByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,$k,$k);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
					$data_alluser_="data_alluser";
					$data_payuser_="data_payuser";
					${$data_alluser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'alluser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$users_30_level_.$k},
						 'leveldistribitionrate' => ($users_30_level_all==0)?0:(${$users_30_level_.$k}/$users_30_level_all),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => (${$users_30_level_.$k}==0)?0:(${$payusers_30_level_.$k}['roleCount']/${$users_30_level_.$k})
					);

					${$data_payuser_.$k} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'usertag' => 'payuser',
						 'levelfield' => $k,
						 'type' => 30,
						 'users' => ${$payusers_30_level_.$k}['roleCount'],
						 'leveldistribitionrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount']),
						 'payamount' => ${$payusers_30_level_.$k}['payAmount'],
						 'payusers' => ${$payusers_30_level_.$k}['roleCount'],
						 'userrate' => ($payusers_30_level_all['roleCount']==0)?0:(${$payusers_30_level_.$k}['roleCount']/$payusers_30_level_all['roleCount'])
					);
				
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_alluser_.$k});
					$dwdb->insert_or_update('razor_sum_basic_leavecount_levelaly', ${$data_payuser_.$k});
				}
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }

	 
}