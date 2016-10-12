<?php
set_time_limit(0);
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
 * Paydatamodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Paydatamodel extends CI_Model
{
	/** 
	 * Construct load
	 * Construct function
	 * 
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
                $this->load->model("product/productmodel", 'product');
                $this->load->model("channelmodel", 'channel');
                $this->load->model("servermodel", 'server');                
	}
	
	/** 
	 * Get user remain country 
	 * GetUserRemainCountByWeek 
	 * 
	 * @param string $version   version 
	 * @param string $productId productid 
	 * @param string $from      from 
	 * @param string $to        to 
	 * @param string $channel   channel 
	 * 
	 * @return query
	 */
	function getPayDataByDay($fromTime,$toTime,$channel,$server,$version)
	{
		$list = array();
		$query = $this->getPayData($fromTime,$toTime,$channel,$server,$version);
		$PayDataRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow["date"] = substr($PayDataRow->date_sk, 0, 10);
			$fRow['daupayamount'] = $PayDataRow->daupayamount;//活跃用户-付费金额
			$fRow['daupayuser'] = $PayDataRow->daupayuser;//活跃用户-付费人数
			$fRow['daupaycount'] = $PayDataRow->daupaycount;//活跃用户-付费次数
			$fRow['firstpayamount'] = $PayDataRow->firstpayamount;//首次付费用户-付费金额
			$fRow['firstpayuser'] = $PayDataRow->firstpayuser;//首次付费用户-付费人数
			$fRow['firstpaycount'] = $PayDataRow->firstpaycount;//首次付费用户-付费次数
			$fRow['firstdaypayamount'] = $PayDataRow->firstdaypayamount;//首日付费用户-付费金额
			$fRow['firstdaypayuser'] = $PayDataRow->firstdaypayuser;//首日付费用户-付费人数
			$fRow['firstdaypaycount'] = $PayDataRow->firstdaypaycount;//首日付费用户-付费次数
			$PayDataRow = $query->next_row();
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
	function getPayData($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(date_sk, 0) date_sk,
					IFNULL(daupayamount, 0) daupayamount,
					IFNULL(daupayuser, 0) daupayuser,
					IFNULL(daupaycount, 0) daupaycount,
					IFNULL(firstpayamount, 0) firstpayamount,
					IFNULL(firstpayuser, 0) firstpayuser,
					IFNULL(firstpaycount, 0) firstpaycount,
					IFNULL(firstdaypayamount, 0) firstdaypayamount,
					IFNULL(firstdaypayuser, 0) firstdaypayuser,
					IFNULL(firstdaypaycount, 0) firstdaypaycount
				FROM
					".$dwdb->dbprefix('sum_basic_paydata')."
                WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
                AND date_sk >= '" . $fromTime . "'
                AND date_sk <= '" . $toTime . "'
				ORDER BY
					date_sk DESC";
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
     * GetDauPayuser function
     * get pay user from dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getDauPayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) daupayamount,
                            COUNT(DISTINCT p.roleId) daupayuser,
                            COUNT(1) daupaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date = '$date'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }
    
    /**
     * GetFirsttimePayuser function
     * count the pay users of the first paid
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getFirsttimePayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            #AND p.chId = '$channelid'
                            AND p.srvId = '$serverid'
                            AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            #AND p.chId = '$channelid'
                            AND p.srvId = '$serverid'
                            #AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            #AND p.chId = '$channelid'
                            #AND p.srvId = '$serverid'
                            AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            #AND p.chId = '$channelid'
                            #AND p.srvId = '$serverid'
                            #AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            AND p.chId = '$channelid'
                            AND p.srvId = '$serverid'
                            AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            AND p.chId = '$channelid'
                            AND p.srvId = '$serverid'
                            #AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            AND p.chId = '$channelid'
                            #AND p.srvId = '$serverid'
                            AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstpayamount,
                            COUNT(DISTINCT p.roleId) firstpayuser,
                            COUNT(1) firstpaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId NOT IN (
                            SELECT DISTINCT
                                    p2.roleId
                            FROM
                                    razor_pay p2
                            WHERE
                                    p.pay_date < '$date'
                            AND p.appId = '$appid'
                            AND p.chId = '$channelid'
                            #AND p.srvId = '$serverid'
                            #AND p.version = '$versionname'
                    );";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }
    
    /**
     * GetFisrtdayPayuser function
     * count the pay users of the first day paid
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getFisrtdayPayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) firstdaypayamount,
                            COUNT(DISTINCT p.roleId) firstdaypayuser,
                            COUNT(1) firstdaypaycount
                    FROM
                            razor_pay p
                    WHERE
                            p.pay_date = '$date'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT
                                    c.roleId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                    );";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }


    /**
     * GetPayamount function
     * get pay user from dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getPayamount($fromdate,$todate,$appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname';";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payamount;
        }
    }


        /**
     * GetPayamount function
     * get pay user from dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getPayamountbynewuser($fromdate,$todate,$appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_createrole l
                            WHERE
                                    l.create_role_date >= '$fromdate'
                            AND l.create_role_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payamount;
        }
    }

        /**
     * GetPayamount function
     * get pay user from dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getPayamountbydauuser($fromdate,$todate,$appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    #AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            #AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            AND l.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(p.pay_amount), 0) payamount
                    FROM
                            razor_pay p
                    WHERE
                        p.pay_date >= '$fromdate' 
                    AND p.pay_date <= '$todate'
                    AND p.appId = '$appid'
                    AND p.chId = '$channelid'
                    #AND p.srvId = '$serverid'
                    #AND p.version = '$versionname'
                    AND p.roleId IN (
                            SELECT DISTINCT
                                    l.roleId
                            FROM
                                    razor_login l
                            WHERE
                                    l.login_date >= '$fromdate'
                            AND l.login_date <= '$todate'
                            AND l.appId = '$appid'
                            AND l.chId = '$channelid'
                            #AND l.srvId = '$serverid'
                            #AND l.version = '$versionname'
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payamount;
        }
    }

    /**
     * Sum_basic_paydata function
     * count pay data
     * 
     * 
     */
    function sum_basic_paydata($countdate) {
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $daupayusers=$this->getDauPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $firsttimepayuser=$this->getFirsttimePayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $fisrtdaypayuser=$this->getFisrtdayPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data = array(
                'date_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'daupayamount' => $daupayusers['daupayamount'],
                'daupayuser' => $daupayusers['daupayuser'],
                'daupaycount' => $daupayusers['daupaycount'],
                'firstpayamount' => $firsttimepayuser['firstpayamount'],
                'firstpayuser' => $firsttimepayuser['firstpayuser'],
                'firstpaycount' => $firsttimepayuser['firstpaycount'],
                'firstdaypayamount' => $fisrtdaypayuser['firstdaypayamount'],
                'firstdaypayuser' => $fisrtdaypayuser['firstdaypayuser'],
                'firstdaypaycount' => $fisrtdaypayuser['firstdaypaycount']
            );
            $dwdb->insert_or_update('razor_sum_basic_paydata', $data);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

}
