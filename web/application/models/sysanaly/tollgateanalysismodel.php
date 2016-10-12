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
 * Borderintervaltimemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Tollgateanalysismodel extends CI_Model {

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
	}
	
	function getTollgateanalysis($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->Tollgateanalysis($fromTime,$toTime,$channel,$server,$version);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['tollgate_bigcategory'] = $dauUsersRow->tollgate_bigcategory;
			$fRow['tollgate_totalcount'] = $dauUsersRow->tollgate_totalcount;
			$fRow['tollgate_attackcount'] = $dauUsersRow->tollgate_attackcount;
			$fRow['tollgate_successcount'] = $dauUsersRow->tollgate_successcount;
			$fRow['tollgate_successrate'] = $dauUsersRow->tollgate_successrate;
			$fRow['tollgate_attackuser'] = $dauUsersRow->tollgate_attackuser;
			$fRow['tollgate_passuser'] = $dauUsersRow->tollgate_passuser;
			$fRow['tollgate_passrate'] = $dauUsersRow->tollgate_passrate;
			$fRow['tollgate_sweepcount'] = $dauUsersRow->tollgate_sweepcount;
			$fRow['tollgate_sweepuser'] = $dauUsersRow->tollgate_sweepuser;
			$fRow['avg_passtime'] = $dauUsersRow->avg_passtime;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function Tollgateanalysis($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(tollgate_bigcategory, 0) tollgate_bigcategory,
					IFNULL(tollgate_totalcount, 0) tollgate_totalcount,
					IFNULL(SUM(tollgate_attackcount), 0) tollgate_attackcount,
					IFNULL(SUM(tollgate_successcount), 0) tollgate_successcount,
					IFNULL(AVG(tollgate_successrate), 0) tollgate_successrate,
					IFNULL(SUM(tollgate_attackuser), 0) tollgate_attackuser,
					IFNULL(SUM(tollgate_passuser), 0) tollgate_passuser,
					IFNULL(AVG(tollgate_passrate), 0) tollgate_passrate,
					IFNULL(SUM(tollgate_sweepcount), 0) tollgate_sweepcount,
					IFNULL(SUM(tollgate_sweepuser), 0) tollgate_sweepuser,
					IFNULL(AVG(avg_passtime), 0) avg_passtime
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_tollgateanalysis_big') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				Group By tollgate_bigcategory
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getTollgateanalysisSmall($fromTime,$toTime,$channel,$server,$version,$id){
		$list = array();
		$query = $this->TollgateanalysisSmall($fromTime,$toTime,$channel,$server,$version,$id);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['tollgate_bigcategory'] = $dauUsersRow->tollgate_bigcategory;
			$fRow['tollgate_id'] = $dauUsersRow->tollgate_id;
			$fRow['tollgate_name'] = $dauUsersRow->tollgate_name;
			$fRow['tollgate_attackcount'] = $dauUsersRow->tollgate_attackcount;
			$fRow['tollgate_successcount'] = $dauUsersRow->tollgate_successcount;
			$fRow['tollgate_failcount'] = $dauUsersRow->tollgate_failcount;
			$fRow['tollgate_successrate'] = $dauUsersRow->tollgate_successrate;
			$fRow['tollgate_attackuser'] = $dauUsersRow->tollgate_attackuser;
			$fRow['tollgate_passuser'] = $dauUsersRow->tollgate_passuser;
			$fRow['tollgate_passrate'] = $dauUsersRow->tollgate_passrate;
			$fRow['tollgate_sweepcount'] = $dauUsersRow->tollgate_sweepcount;
			$fRow['tollgate_sweepuser'] = $dauUsersRow->tollgate_sweepuser;
			$fRow['avg_passtime'] = $dauUsersRow->avg_passtime;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function TollgateanalysisSmall($fromTime,$toTime,$channel,$server,$version,$id)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(tollgate_bigcategory, 0) tollgate_bigcategory,
					tollgate_id,
					tollgate_name,
					IFNULL(SUM(tollgate_attackcount), 0) tollgate_attackcount,
					IFNULL(SUM(tollgate_successcount), 0) tollgate_successcount,
					IFNULL(SUM(tollgate_failcount), 0) tollgate_failcount,
					IFNULL(AVG(tollgate_successrate), 0) tollgate_successrate,
					IFNULL(SUM(tollgate_attackuser), 0) tollgate_attackuser,
					IFNULL(SUM(tollgate_passuser), 0) tollgate_passuser,
					IFNULL(AVG(tollgate_passrate), 0) tollgate_passrate,
					IFNULL(SUM(tollgate_sweepcount), 0) tollgate_sweepcount,
					IFNULL(SUM(tollgate_sweepuser), 0) tollgate_sweepuser,
					IFNULL(AVG(avg_passtime), 0) avg_passtime
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_tollgateanalysis_small') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND tollgate_bigcategory = '" . $id . "'
				AND tollgate_successcount >= 0
				AND tollgate_failcount >= 0
				Group By tollgate_bigcategory,tollgate_id
				Order By tollgate_id ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getTollgateanalysisSmallDetail($fromTime,$toTime,$channel,$server,$version,$id){
		$list = array();
		$query = $this->TollgateanalysisSmallDetail($fromTime,$toTime,$channel,$server,$version,$id);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['enddate_sk'] = $dauUsersRow->enddate_sk;
			$fRow['tollgate_name'] = $dauUsersRow->tollgate_name;
			$fRow['tollgate_attackcount'] = $dauUsersRow->tollgate_attackcount;
			$fRow['tollgate_successcount'] = $dauUsersRow->tollgate_successcount;
			$fRow['tollgate_failcount'] = $dauUsersRow->tollgate_failcount;
			$fRow['tollgate_successrate'] = $dauUsersRow->tollgate_successrate;
			$fRow['tollgate_attackuser'] = $dauUsersRow->tollgate_attackuser;
			$fRow['tollgate_passuser'] = $dauUsersRow->tollgate_passuser;
			$fRow['tollgate_passrate'] = $dauUsersRow->tollgate_passrate;
			$fRow['tollgate_sweepcount'] = $dauUsersRow->tollgate_sweepcount;
			$fRow['tollgate_sweepuser'] = $dauUsersRow->tollgate_sweepuser;
			$fRow['avg_passtime'] = $dauUsersRow->avg_passtime;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function TollgateanalysisSmallDetail($fromTime,$toTime,$channel,$server,$version,$id)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(enddate_sk, 0) enddate_sk,
					IFNULL(tollgate_name, 0) tollgate_name,
					IFNULL(tollgate_attackcount, 0) tollgate_attackcount,
					IFNULL(tollgate_successcount, 0) tollgate_successcount,
					IFNULL(tollgate_failcount, 0) tollgate_failcount,
					IFNULL(tollgate_successrate, 0) tollgate_successrate,
					IFNULL(tollgate_attackuser, 0) tollgate_attackuser,
					IFNULL(tollgate_passuser, 0) tollgate_passuser,
					IFNULL(tollgate_passrate, 0) tollgate_passrate,
					IFNULL(tollgate_sweepcount, 0) tollgate_sweepcount,
					IFNULL(tollgate_sweepuser, 0) tollgate_sweepuser,
					IFNULL(avg_passtime, 0) avg_passtime
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_tollgateanalysis_small') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND tollgate_id = '" . $id . "'
				Order By enddate_sk ASC";
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
     * GetBigTollgateData function
     * get bigtollgate data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getBigTollgateData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_totalcount,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60,0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                count(t.tollgateid) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            count(
                                t.tollgate_smallcategory_id
                            ) tollgate_totalcount
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                        GROUP BY
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * GetSmallTollgateData function
     * get smalltollgate data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getSmallTollgateData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            #AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        t2.tollgate_bigcategory_name,
                        t2.tollgate_smallcategory_id,
                        t2.tollgate_smallcategory_name,
                        IFNULL(t1.tollgate_attackcount, 0) tollgate_attackcount,
                        IFNULL(t1.tollgate_successcount, 0) tollgate_successcount,
                        IFNULL(t1.tollgate_failcount, 0) tollgate_failcount,
                        IFNULL(t1.tollgate_attackuser, 0) tollgate_attackuser,
                        IFNULL(t1.tollgate_passuser, 0) tollgate_passuser,
                        IFNULL(t1.tollgate_sweepcount, 0) tollgate_sweepcount,
                        IFNULL(t1.tollgate_sweepuser, 0) tollgate_sweepuser,
                        IFNULL(t1.avg_passtime/60, 0) avg_passtime
                    FROM
                        (
                            SELECT
                                t.moduleid,
                                t.tollgateid,
                                count(1) tollgate_attackcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_successcount,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.pass = 2 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_failcount,
                                COUNT(DISTINCT t.roleId) tollgate_attackuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.pass = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_passuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN t.tollgatesweep = 1 THEN
                                            1
                                        ELSE
                                            0
                                        END
                                    ),
                                    0
                                ) tollgate_sweepcount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN t.tollgatesweep = 1 THEN
                                        t.roleId
                                    ELSE
                                        NULL
                                    END
                                ) tollgate_sweepuser,
                                AVG(t.passtime) avg_passtime
                            FROM
                                razor_tollgate t
                            WHERE
                                t.tollgate_date = '$date'
                            AND t.appId = '$appid'
                            #AND t.srvId = '$serverid'
                            AND t.chId = '$channelid'
                            #AND t.version = '$versionname'
                            GROUP BY
                                t.moduleid,
                                t.tollgateid
                        ) t1
                    RIGHT JOIN (
                        SELECT
                            t.tollgate_bigcategory_id,
                            t.tollgate_bigcategory_name,
                            t.tollgate_smallcategory_id,
                            t.tollgate_smallcategory_name
                        FROM
                            razor_mainconfig_tollgate t
                        WHERE
                            t.app_id = '$appid'
                    ) t2 ON t1.moduleid = t2.tollgate_bigcategory_id
                    AND t1.tollgateid = t2.tollgate_smallcategory_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * sum_basic_sa_tollgateanalysis function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_sa_tollgateanalysis($countdate) {
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime

                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_pcv = $params_pcv->next_row();
        }
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $BigTollgateData=$this->getBigTollgateData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $SmallTollgateData=$this->getSmallTollgateData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            //关卡分析-大关卡
            $paramsRow_t=$BigTollgateData->first_row();
            for ($j=0; $j < $BigTollgateData->num_rows() ; $j++) { 
                $data_bigtollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_t->tollgate_bigcategory_name,
                    'tollgate_totalcount' => $paramsRow_t->tollgate_totalcount,
                    'tollgate_attackcount' => $paramsRow_t->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_t->tollgate_successcount,
                    'tollgate_successrate' => ($paramsRow_t->tollgate_attackcount==0)?0:($paramsRow_t->tollgate_successcount/$paramsRow_t->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_t->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_t->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_t->tollgate_attackuser==0)?0:($paramsRow_t->tollgate_passuser/$paramsRow_t->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_t->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_t->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_t->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_big', $data_bigtollgate);
                $paramsRow_t = $BigTollgateData->next_row();
            }

            //关卡分析-小关卡
            $paramsRow_s=$SmallTollgateData->first_row();
            for ($s=0; $s < $SmallTollgateData->num_rows() ; $s++) {
                $data_smalltollgate = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'tollgate_bigcategory' => $paramsRow_s->tollgate_bigcategory_name,
                    'tollgate_id' => $paramsRow_s->tollgate_smallcategory_id,
                    'tollgate_name' => $paramsRow_s->tollgate_smallcategory_name,
                    'tollgate_attackcount' => $paramsRow_s->tollgate_attackcount,
                    'tollgate_successcount' => $paramsRow_s->tollgate_successcount,
                    'tollgate_failcount' => $paramsRow_s->tollgate_failcount,
                    'tollgate_successrate' => ($paramsRow_s->tollgate_attackcount==0)?0:($paramsRow_s->tollgate_successcount/$paramsRow_s->tollgate_attackcount),
                    'tollgate_attackuser' => $paramsRow_s->tollgate_attackuser,
                    'tollgate_passuser' => $paramsRow_s->tollgate_passuser,
                    'tollgate_passrate' => ($paramsRow_s->tollgate_attackuser==0)?0:($paramsRow_s->tollgate_passuser/$paramsRow_s->tollgate_attackuser),
                    'tollgate_sweepcount' => $paramsRow_s->tollgate_sweepcount,
                    'tollgate_sweepuser' => $paramsRow_s->tollgate_sweepuser,
                    'avg_passtime' => $paramsRow_s->avg_passtime
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_tollgateanalysis_small', $data_smalltollgate);
                $paramsRow_s = $SmallTollgateData->next_row();
            }
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
