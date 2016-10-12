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
 * Errorcodeanalymodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Errorcodeanalymodel extends CI_Model {

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
	}
	
	function getExceptionanaly($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->getExceptionanalyData($fromTime,$toTime,$channel,$server,$version);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['errorid'] = $dauUsersRow->errorid;
			$fRow['errorname'] = $dauUsersRow->errorname;
			$fRow['erroruser'] = $dauUsersRow->erroruser;
			$fRow['errorcount'] = $dauUsersRow->errorcount;
			$fRow['errorrate'] = $dauUsersRow->errorrate;
			$fRow['useravgcount'] = $dauUsersRow->useravgcount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function getExceptionanalyData($fromTime,$toTime,$channel,$server,$version)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(errorid, 0) errorid,
					IFNULL(errorname, 0) errorname,
					IFNULL(SUM(erroruser), 0) erroruser,
					IFNULL(SUM(errorcount), 0) errorcount,
					IFNULL(AVG(errorrate), 0) errorrate,
					IFNULL(SUM(useravgcount), 0) useravgcount
				FROM
					" . $dwdb->dbprefix('sum_basic_errorcodeanaly') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				Group By errorname
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getExceptionanalyAction($fromTime,$toTime,$channel,$server,$version,$id){
		$list = array();
		$query = $this->getExceptionanalyActionData($fromTime,$toTime,$channel,$server,$version,$id);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['errorid'] = $dauUsersRow->errorid;
			$fRow['actionid'] = $dauUsersRow->actionid;
			$fRow['actionname'] = $dauUsersRow->actionname;
			$fRow['actionuser'] = $dauUsersRow->actionuser;
			$fRow['actioncount'] = $dauUsersRow->actioncount;
			$fRow['useravgcount'] = $dauUsersRow->useravgcount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function getExceptionanalyActionData($fromTime,$toTime,$channel,$server,$version,$id)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(errorid, 0) errorid,
					IFNULL(actionid, 0) actionid,
					IFNULL(actionname, 0) actionname,
					IFNULL(SUM(actionuser), 0) actionuser,
					IFNULL(SUM(actioncount), 0) actioncount,
					IFNULL(SUM(useravgcount), 0) useravgcount
				FROM
					" . $dwdb->dbprefix('sum_basic_errorcodeanaly_action') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND errorid = '". $id ."'
				Group By actionname
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getExceptionanalyDate($fromTime,$toTime,$channel,$server,$version,$errorid){
		$list = array();
		$query = $this->ExceptionanalyDate($fromTime,$toTime,$channel,$server,$version,$errorid);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
			$fRow['errorid'] = $dauUsersRow->errorid;
			$fRow['errorname'] = $dauUsersRow->errorname;
			$fRow['erroruser'] = $dauUsersRow->erroruser;
			$fRow['errorcount'] = $dauUsersRow->errorcount;
			$fRow['errorrate'] = $dauUsersRow->errorrate;
			$fRow['useravgcount'] = $dauUsersRow->useravgcount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function ExceptionanalyDate($fromTime,$toTime,$channel,$server,$version,$errorid)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(errorid, 0) errorid,
					IFNULL(errorname, 0) errorname,
					IFNULL(erroruser, 0) erroruser,
					IFNULL(errorcount, 0) errorcount,
					IFNULL(errorrate, 0) errorrate,
					IFNULL(useravgcount, 0) useravgcount
				FROM
					" . $dwdb->dbprefix('sum_basic_errorcodeanaly') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND errorid = '".$errorid."'
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
     * getErrorcode function
     * get error code
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array error code
     */
    function getErrorcode($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						mce1.errorcode_id,
						mce1.errorcode_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid
						) ec1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_errorcode mce
						WHERE
							mce.app_id = '$appid'
					) mce1 ON ec1.errorcodeid = mce1.errorcode_id
					ORDER BY
						mce1.errorcode_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getErrorcodeaction function
     * get error code action
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int error code action
     */
    function getErrorcodeaction($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							#AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						ec1.errorcodeid,
						ec1.aciontid,
						mca1.action_name,
						IFNULL(erroruser, 0) erroruser,
						IFNULL(errorcount, 0) errorcount,
						IFNULL(errorrate, 0) errorrate
					FROM
						(
							SELECT
								ec.errorcodeid,
								ec.aciontid,
								COUNT(DISTINCT ec.roleId) erroruser,
								count(1) errorcount,
								count(1) / COUNT(DISTINCT ec.roleId) errorrate
							FROM
								razor_errorcode ec
							WHERE
								ec.errorcode_date >= '$date'
							AND ec.errorcode_date <= '$date'
							AND ec.appId = '$appid'
							AND ec.chId = '$channelid'
							#AND ec.srvId = '$serverid'
							#AND ec.version = '$versionname'
							GROUP BY
								ec.errorcodeid,
								ec.aciontid
						) ec1
					LEFT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_action mca
						WHERE
							mca.app_id = '$appid'
					) mca1 ON ec1.aciontid = mca1.action_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


    /**
     * Sum_basic_borderintervaltime function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_errorcodeanaly($countdate) {
		$dwdb = $this->load->database('dw', true);

		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$errorcode=$this->getErrorcode($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_psv = $params_psv->next_row();
		}

		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$errorcode=$this->getErrorcode($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_ps = $params_ps->next_row();
		}

		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$errorcode=$this->getErrorcode($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_pv = $params_pv->next_row();
		}

		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$errorcode=$this->getErrorcode($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_p = $params_p->next_row();
		}

		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$errorcode=$this->getErrorcode($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_pcsv = $params_pcsv->next_row();
		}

		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$errorcode=$this->getErrorcode($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_pcs = $params_pcs->next_row();
		}

		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$errorcode=$this->getErrorcode($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_pcv = $params_pcv->next_row();
		}

		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$errorcode=$this->getErrorcode($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$errorcodeaction=$this->getErrorcodeaction($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
			//错误码
			$paramsRow_t=$errorcode->first_row();
			for ($j=0; $j < $errorcode->num_rows() ; $j++) { 
				$data_errorcode = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'errorid' => $paramsRow_t->errorcode_id,
					'errorname' => $paramsRow_t->errorcode_name,
					'erroruser' => $paramsRow_t->erroruser,
					'errorcount' => $paramsRow_t->errorcount,
					'errorrate' => $paramsRow_t->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_t->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly', $data_errorcode);
				$paramsRow_t = $errorcode->next_row();
			}
			//错误码-行为
			$paramsRow_s=$errorcodeaction->first_row();
			for ($s=0; $s < $errorcodeaction->num_rows() ; $s++) {
				$data_errorcodeaction = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'errorid' => $paramsRow_s->errorcode_id,
					'actionid' => $paramsRow_s->errorcode_name,
					'actionname' => $paramsRow_s->erroruser,
					'actionuser' => $paramsRow_s->errorcount,
					'actioncount' => $paramsRow_s->errorrate,
					'useravgcount' => ($dauusers==0)?0:($paramsRow_s->erroruser/$dauusers)
				);
				$dwdb->insert_or_update('razor_sum_basic_errorcodeanaly_action', $data_errorcodeaction);
				$paramsRow_s = $errorcodeaction->next_row();
			}
			$paramsRow_pc = $params_pc->next_row();
		}
	}
}
