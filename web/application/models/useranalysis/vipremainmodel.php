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
 * Vipremainmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Vipremainmodel extends CI_Model {

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
          $this->load->model("useranalysis/viprolemodel", 'viprole');
          $this->load->model("useranalysis/dauusersmodel", 'dauusers');
     }

	function getVipremainData($fromTime, $toTime, $channel, $server, $version) {
			$list = array();
			$query = $this->VipremainData($fromTime, $toTime, $channel, $server, $version);
			$dauUsersRow = $query->first_row();
			for ($i = 0; $i < $query->num_rows(); $i++) {
						$fRow = array();
						$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
						$fRow['usercount'] = $dauUsersRow->usercount;
						$fRow['day1'] = $dauUsersRow->day1*100;
						$fRow['day2'] = $dauUsersRow->day2*100;
						$fRow['day3'] = $dauUsersRow->day3*100;
						$fRow['day4'] = $dauUsersRow->day4*100;
						$fRow['day5'] = $dauUsersRow->day5*100;
						$fRow['day6'] = $dauUsersRow->day6*100;
						$fRow['day7'] = $dauUsersRow->day7*100;
						$fRow['day14'] = $dauUsersRow->day14*100;
						$fRow['day30'] = $dauUsersRow->day30*100;
						$dauUsersRow = $query->next_row();
						array_push($list, $fRow);
			}
			return $list;
	}

	function VipremainData($fromTime, $toTime, $channel, $version, $server) {
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		$sql = "SELECT
				IFNULL(startdate_sk, 0) startdate_sk,
				IFNULL(SUM(usercount), 0) usercount,
				IFNULL(AVG(day1), 0) day1,
				IFNULL(AVG(day2), 0) day2,
				IFNULL(AVG(day3), 0) day3,
				IFNULL(AVG(day4), 0) day4,
				IFNULL(AVG(day5), 0) day5,
				IFNULL(AVG(day6), 0) day6,
				IFNULL(AVG(day7), 0) day7,
				IFNULL(AVG(day14), 0) day14,
				IFNULL(AVG(day30), 0) day30
				FROM
						" . $dwdb->dbprefix('sum_basic_vipremain') . "
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND product_id = '" . $productId . "'
				Group By startdate_sk
				Order By startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	function getVipLevelData($fromTime, $toTime, $channel, $server, $version) {
			$list = array();
			$query = $this->VipLevelData($fromTime, $toTime, $channel, $server, $version);
			$dauUsersRow = $query->first_row();
			for ($i = 0; $i < $query->num_rows(); $i++) {
						$fRow = array();
						$fRow['viplevel'] = $dauUsersRow->viplevel;
						$dauUsersRow = $query->next_row();
						array_push($list, $fRow);
			}
			return $list;
	}
	function VipLevelData($fromTime, $toTime, $channel, $version, $server) {
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		$sql = "SELECT
					viplevel
				FROM
					" . $dwdb->dbprefix('sum_basic_vipremain') . "
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND product_id = '" . $productId . "'
				Group By viplevel
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getFilterData($fromTime, $toTime, $channel, $server, $version, $value) {
			$list = array();
			$query = $this->FilterData($fromTime, $toTime, $channel, $server, $version, $value);
			$dauUsersRow = $query->first_row();
			for ($i = 0; $i < $query->num_rows(); $i++) {
						$fRow = array();
						$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
						$fRow['usercount'] = $dauUsersRow->usercount;
						$fRow['day1'] = $dauUsersRow->day1*100;
						$fRow['day2'] = $dauUsersRow->day2*100;
						$fRow['day3'] = $dauUsersRow->day3*100;
						$fRow['day4'] = $dauUsersRow->day4*100;
						$fRow['day5'] = $dauUsersRow->day5*100;
						$fRow['day6'] = $dauUsersRow->day6*100;
						$fRow['day7'] = $dauUsersRow->day7*100;
						$fRow['day14'] = $dauUsersRow->day14*100;
						$fRow['day30'] = $dauUsersRow->day30*100;
						$dauUsersRow = $query->next_row();
						array_push($list, $fRow);
			}
			return $list;
	}

	function FilterData($fromTime, $toTime, $channel, $version, $server, $value) {
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		$sql = "SELECT
				IFNULL(startdate_sk, 0) startdate_sk,
				IFNULL(SUM(usercount), 0) usercount,
				IFNULL(AVG(day1), 0) day1,
				IFNULL(AVG(day2), 0) day2,
				IFNULL(AVG(day3), 0) day3,
				IFNULL(AVG(day4), 0) day4,
				IFNULL(AVG(day5), 0) day5,
				IFNULL(AVG(day6), 0) day6,
				IFNULL(AVG(day7), 0) day7,
				IFNULL(AVG(day14), 0) day14,
				IFNULL(AVG(day30), 0) day30
				FROM
						" . $dwdb->dbprefix('sum_basic_vipremain') . "
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND product_id = '" . $productId . "'
				AND viplevel = '" . $value . "'
				Group By startdate_sk
				Order By startdate_sk DESC";
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
     function getVipremainuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel,$days) {
          if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_viplevelup v
                            WHERE
                                v.viplevelup_date <= '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            AND v.currentRoleVip = '$viplevel'
                        );";
          }
          $query = $this->db->query($sql);
          $row = $query->first_row();
          if ($query->num_rows > 0) {
                return $row->vipuserremain;
          }
     }

      /**
      * getVipzeroremainuser function
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
     function getVipzeroremainuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel,$days) {
          if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        #AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            #AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
                $sql = "SELECT
                            IFNULL(COUNT(DISTINCT l.roleId), 0) vipuserremain
                        FROM
                            razor_login l
                        WHERE
                            l.login_date = DATE_ADD('$fromdate', INTERVAL $days DAY)
                        AND l.login_date <> CURDATE()
                        AND l.appId = '$appid'
                        AND l.chId = '$channelid'
                        #AND l.srvId = '$serverid'
                        #AND l.version = '$versionname'
                        AND l.roleId IN (
                            SELECT
                                DISTINCT roleId
                            FROM
                                razor_createrole v
                            WHERE
                                v.create_role_date = '$todate'
                            AND v.appId = '$appid'
                            AND v.chId = '$channelid'
                            #AND v.srvId = '$serverid'
                            #AND v.version = '$versionname'
                            #AND v.roleVip = '$viplevel'
                        );";
          }
          $query = $this->db->query($sql);
          $row = $query->first_row();
          if ($query->num_rows > 0) {
                return $row->vipuserremain;
          }
     }

      /**
      * sum_basic_vipremain function
      * count vip remain users
      * 
      * 
      */
     function sum_basic_vipremain($countdate) {
          $dwdb = $this->load->database('dw', true);

          $params_psv = $this->product->getProductServerVersionOffChannel();
          $paramsRow_psv = $params_psv->first_row();
          for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                //get servername by serverid
                $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_psv->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_psv->appId,
                         'version_name' => $paramsRow_psv->version,
                         'channel_name' => 'all',
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_psv = $params_psv->next_row();
          }
          $params_ps = $this->product->getProductServerOffChannelVersion();
          $paramsRow_ps = $params_ps->first_row();
          for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                                //get servername by serverid
                $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_ps->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_ps->appId,
                         'version_name' => 'all',
                         'channel_name' => 'all',
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_ps = $params_ps->next_row();
          }
          $params_pv = $this->product->getProductVersionOffChannelServer();
          $paramsRow_pv = $params_pv->first_row();
          for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pv->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pv->appId,
                         'version_name' => $paramsRow_pv->version,
                         'channel_name' => 'all',
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pv = $params_pv->next_row();
          }
          $params_p = $this->product->getProductOffChannelServerVersion();
          $paramsRow_p = $params_p->first_row();
          for ($i = 0; $i < $params_p->num_rows(); $i++) {
                                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_p->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_p->appId,
                         'version_name' => 'all',
                         'channel_name' => 'all',
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
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
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pcsv->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pcsv->appId,
                         'version_name' => $paramsRow_pcsv->version,
                         'channel_name' => $channel_name,
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
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
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pcs->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pcs->appId,
                         'version_name' => 'all',
                         'channel_name' => $channel_name,
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pcs = $params_pcs->next_row();
          }
          $params_pcv = $this->product->getProductChannelVersionOffServer();
          $paramsRow_pcv = $params_pcv->first_row();
          for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                //get channelname by channelid
                $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pcv->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pcv->appId,
                         'version_name' => $paramsRow_pcv->version,
                         'channel_name' => $channel_name,
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pcv = $params_pcv->next_row();
          }
          $params_pc = $this->product-> getProductChannelOffServerVersion();
          $paramsRow_pc = $params_pc->first_row();
          for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                //get channelname by channelid
                $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                //vip max level
                $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pc->appId);
                for ($m=1; $m <$Maxlevel+1 ; $m++) { 
                    $Newvipuser=$this->viprole->getNewvipuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pc->appId,
                         'version_name' => 'all',
                         'channel_name' => $channel_name,
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pc = $params_pc->next_row();
          }
     }

     /**
      * sum_basic_vipzeroremain function
      * count vip remain users
      * 
      * 
      */
     function sum_basic_vipzeroremain($countdate) {
          $dwdb = $this->load->database('dw', true);

          $params_psv = $this->product->getProductServerVersionOffChannel();
          $paramsRow_psv = $params_psv->first_row();
          for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                //get servername by serverid
                $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_psv->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_psv->appId,
                         'version_name' => $paramsRow_psv->version,
                         'channel_name' => 'all',
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_psv = $params_psv->next_row();
          }
          $params_ps = $this->product->getProductServerOffChannelVersion();
          $paramsRow_ps = $params_ps->first_row();
          for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                                //get servername by serverid
                $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_ps->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_ps->appId,
                         'version_name' => 'all',
                         'channel_name' => 'all',
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_ps = $params_ps->next_row();
          }
          $params_pv = $this->product->getProductVersionOffChannelServer();
          $paramsRow_pv = $params_pv->first_row();
          for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pv->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pv->appId,
                         'version_name' => $paramsRow_pv->version,
                         'channel_name' => 'all',
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pv = $params_pv->next_row();
          }
          $params_p = $this->product->getProductOffChannelServerVersion();
          $paramsRow_p = $params_p->first_row();
          for ($i = 0; $i < $params_p->num_rows(); $i++) {
                                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_p->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_p->appId,
                         'version_name' => 'all',
                         'channel_name' => 'all',
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
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
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pcsv->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pcsv->appId,
                         'version_name' => $paramsRow_pcsv->version,
                         'channel_name' => $channel_name,
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
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
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pcs->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pcs->appId,
                         'version_name' => 'all',
                         'channel_name' => $channel_name,
                         'server_name' => $server_name,
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pcs = $params_pcs->next_row();
          }
          $params_pcv = $this->product->getProductChannelVersionOffServer();
          $paramsRow_pcv = $params_pcv->first_row();
          for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                //get channelname by channelid
                $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pcv->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pcv->appId,
                         'version_name' => $paramsRow_pcv->version,
                         'channel_name' => $channel_name,
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pcv = $params_pcv->next_row();
          }
          $params_pc = $this->product-> getProductChannelOffServerVersion();
          $paramsRow_pc = $params_pc->first_row();
          for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                //get channelname by channelid
                $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                //vip max level
                // $Maxlevel=$this->viprole->getMaxlevel($paramsRow_pc->appId);
                for ($m=0; $m <1 ; $m++) { 
                    $Newvipuser=$this->dauusers->getNewuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                    $Vipremainuser1=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,1);
                    $Vipremainuser2=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,2);
                    $Vipremainuser3=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,3);
                    $Vipremainuser4=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,4);
                    $Vipremainuser5=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,5);
                    $Vipremainuser6=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,6);
                    $Vipremainuser7=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,7);
                    $Vipremainuser14=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,14);
                    $Vipremainuser30=$this->getVipremainuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,30);
                    $data_vipremain = array(
                         'startdate_sk' => $countdate,
                         'enddate_sk' => $countdate,
                         'product_id' => $paramsRow_pc->appId,
                         'version_name' => 'all',
                         'channel_name' => $channel_name,
                         'server_name' => 'all',
                         'viplevel' => $m,
                         'usercount' => $Newvipuser,
                         'day1' => ($Newvipuser==0)?0:($Vipremainuser1/$Newvipuser),
                         'day2' => ($Newvipuser==0)?0:($Vipremainuser2/$Newvipuser),
                         'day3' => ($Newvipuser==0)?0:($Vipremainuser3/$Newvipuser),
                         'day4' => ($Newvipuser==0)?0:($Vipremainuser4/$Newvipuser),
                         'day5' => ($Newvipuser==0)?0:($Vipremainuser5/$Newvipuser),
                         'day6' => ($Newvipuser==0)?0:($Vipremainuser6/$Newvipuser),
                         'day7' => ($Newvipuser==0)?0:($Vipremainuser7/$Newvipuser),
                         'day14' => ($Newvipuser==0)?0:($Vipremainuser14/$Newvipuser),
                         'day30' => ($Newvipuser==0)?0:($Vipremainuser30/$Newvipuser),
                    );
                    $dwdb->insert_or_update('razor_sum_basic_vipremain', $data_vipremain);
                }
                $paramsRow_pc = $params_pc->next_row();
          }
     }

}
