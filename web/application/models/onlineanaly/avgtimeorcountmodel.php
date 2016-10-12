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
 * Avgtimeorcountmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Avgtimeorcountmodel extends CI_Model {

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
    
    function getAvgtimeorcount($fromTime,$toTime,$channel,$server,$version,$type,$date){
        $list = array();
        $query = $this->Avgtimeorcount($fromTime,$toTime,$channel,$server,$version,$type,$date);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
            $fRow['enddate_sk'] = $dauUsersRow->enddate_sk;
            $fRow['avggamecount'] = $dauUsersRow->avggamecount;
            $fRow['avggametime'] = $dauUsersRow->avggametime;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

	function Avgtimeorcount($fromTime,$toTime,$channel,$server,$version,$type,$date)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
                    IFNULL(startdate_sk, 0) startdate_sk,
                    IFNULL(enddate_sk, 0) enddate_sk,
					IFNULL(avggamecount, 0) avggamecount,
					IFNULL(avggametime, 0) avggametime
				FROM
					" . $dwdb->dbprefix('sum_basic_time_count_avg') . "
                WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
                AND datetype = '" . $date . "'
                AND playertype = '" . $type . "'
                AND (startdate_sk >= '" . $fromTime . "' OR enddate_sk <= '" . $toTime . "')
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
     * GetGamecountAndtime function
     * get acu
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getGamecountAndtime($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
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
     * GetAcu function
     * get acu
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getGamecountAndtimeBypay($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        #AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        #AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        #AND p.srvId = '$serverid'
                        #AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        #AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        AND p.chId = '$channelid'
                        #AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
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
     * GetAcu function
     * get acu
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getGamecountAndtimeByunpay($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        #AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        #AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        #AND p.chId = '$channelid'
                        #AND p.srvId = '$serverid'
                        #AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        AND p.chId = '$channelid'
                        AND p.srvId = '$serverid'
                        #AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
                        AND p.appId = '$appid'
                        AND p.chId = '$channelid'
                        #AND p.srvId = '$serverid'
                        AND p.version = '$versionname'
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'login' THEN
                                1
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId),0) logincount_avg,
                        IFNULL(SUM(
                            CASE
                            WHEN l.type = 'logout' THEN
                                l.offlineSettleTime
                            ELSE
                                0
                            END
                        ) / COUNT(DISTINCT l.userId)/60,0) logintime_avg
                    FROM
                        razor_login l
                    WHERE
                        l.login_date >= '$fromdate'
                    AND l.login_date <= '$todate'
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId not IN (
                        SELECT DISTINCT
                            p.userId
                        FROM
                            razor_pay p
                        WHERE
                            p.pay_date >= '$fromdate'
                        AND p.pay_date <= '$todate'
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
     * Sum_basic_time_count_avg function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_time_count_avg($countdate) {
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);

        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pc = $params_pc->next_row();
        }
    }


        /**
     * Sum_basic_time_count_avg function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_time_count_avg_week($countdate) {
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);

        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pc = $params_pc->next_row();
        }
    }


        /**
     * Sum_basic_time_count_avg function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_time_count_avg_month($countdate) {
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
        
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_p->appId, 'all', 'all', 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_p->appId, 'all', 'all', 'all');

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //day
            $gamecountandtime_day = $this->getGamecountAndtime($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_day_pay = $this->getGamecountAndtimeBypay($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_day_unpay = $this->getGamecountAndtimeByunpay($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //week
            $gamecountandtime_week = $this->getGamecountAndtime($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_week_pay = $this->getGamecountAndtimeBypay($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_week_unpay = $this->getGamecountAndtimeByunpay($last_monday,$last_sunday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //month
            $gamecountandtime_month = $this->getGamecountAndtime($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_month_pay = $this->getGamecountAndtimeBypay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $gamecountandtime_month_unpay = $this->getGamecountAndtimeByunpay($lastmonth_firstday,$lastmonth_lastday, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_day['logincount_avg'],
                'avggametime' => $gamecountandtime_day['logintime_avg']
            );
            $data_day_pay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_day_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_pay['logintime_avg']
            );
            $data_day_unpay = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'day',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_day_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_day_unpay['logintime_avg']
            );

            $data_week = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_week['logincount_avg'],
                'avggametime' => $gamecountandtime_week['logintime_avg']
            );
            $data_week_pay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_week_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_pay['logintime_avg']
            );
            $data_week_unpay = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'week',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_week_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_week_unpay['logintime_avg']
            );

            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'dauuser',
                'avggamecount' => $gamecountandtime_month['logincount_avg'],
                'avggametime' => $gamecountandtime_month['logintime_avg']
            );
            $data_month_pay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'payuser',
                'avggamecount' => $gamecountandtime_month_pay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_pay['logintime_avg']
            );
            $data_month_unpay = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'datetype' => 'month',
                'playertype' => 'unpayuser',
                'avggamecount' => $gamecountandtime_month_unpay['logincount_avg'],
                'avggametime' => $gamecountandtime_month_unpay['logintime_avg']
            );
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_day_unpay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_pay);
            // $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_week_unpay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_pay);
            $dwdb->insert_or_update('razor_sum_basic_time_count_avg', $data_month_unpay);
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
