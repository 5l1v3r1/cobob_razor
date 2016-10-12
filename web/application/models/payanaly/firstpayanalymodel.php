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
 * Firstpayanalymodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Firstpayanalymodel extends CI_Model
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
        $this->load->model("useranalysis/dauusersmodel", 'dauusers');
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
	function getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db,$type)
	{
		$list = array();
		$query = $this->FirstPayData($fromTime,$toTime,$channel,$version,$server,$db,$type);
		$PayDataRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			if($type == 1){
				$fRow['firstpayname'] = $PayDataRow->firstpayname;
			}
			elseif($type == 2) {
				$fRow['firstpaylevel'] = $PayDataRow->firstpaylevel;
			}
			elseif($type == 3) {
				$fRow['firstpayamount'] = $PayDataRow->firstpayamount;
			}
			$fRow['payusers'] = $PayDataRow->payusers;
			$fRow['payusersrate'] = $PayDataRow->payusersrate;
			$PayDataRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function FirstPayData($fromTime,$toTime,$channel,$version,$server,$db,$type)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		if($type == 1){
			$sql = "SELECT
						IFNULL(firstpayname, 0) firstpayname,
						IFNULL(SUM(payusers), 0) payusers,
						IFNULL(SUM(payusersrate), 0) payusersrate
					FROM
						".$dwdb->dbprefix($db)."
                    WHERE
					product_id = $productId
                    AND startdate_sk >= '" . $fromTime . "'
                    AND enddate_sk <= '" . $toTime . "'
					AND channel_name in('" . $channel_list . "')
					AND version_name in('" . $version_list . "')
					AND server_name in('" . $server_list . "')
                    GROUP BY firstpayname
                    ORDER BY rid asc";
		}
		if($type == 2){
			$sql = "SELECT
						IFNULL(firstpaylevel, 0) firstpaylevel,
						IFNULL(SUM(payusers), 0) payusers,
						IFNULL(SUM(payusersrate), 0) payusersrate
					FROM
						".$dwdb->dbprefix($db)."
                    WHERE
					product_id = $productId
                    AND startdate_sk >= '" . $fromTime . "'
                    AND enddate_sk <= '" . $toTime . "'
					AND channel_name in('" . $channel_list . "')
					AND version_name in('" . $version_list . "')
					AND server_name in('" . $server_list . "')
                    GROUP BY firstpaylevel
                    ORDER BY rid asc";
		}
		if($type == 3){
			$sql = "SELECT
						IFNULL(firstpayamount, 0) firstpayamount,
						IFNULL(SUM(payusers), 0) payusers,
						IFNULL(SUM(payusersrate), 0) payusersrate
					FROM
						".$dwdb->dbprefix($db)."
                    WHERE
                    product_id = $productId
                    AND startdate_sk >= '" . $fromTime . "'
                    AND enddate_sk <= '" . $toTime . "'
					AND channel_name in('" . $channel_list . "')
					AND version_name in('" . $version_list . "')
					AND server_name in('" . $server_list . "')
                    GROUP BY firstpayamount
                    ORDER BY rid asc";
		}
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
     * GetFirstPayusers function
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
    function getFirstPayusersBytime($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$timefrom=0,$timeto=0) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue <= '$timeto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        }
        $this->db->query("SET @ROW = 0;");
        $this->db->query("SET @mid = '';");
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payuser;
        }
    }

                /**
     * GetFirstPayusers function
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
    function getFirstPayusersBylevel($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$levelfrom=0,$levelto=0) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.roleLevel,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.roleLevel >= '$levelfrom'
                    AND t.roleLevel <= '$levelto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        }
        $this->db->query("SET @ROW = 0;");
        $this->db->query("SET @mid = '';");
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payuser;
        }
    }

                    /**
     * GetFirstPayusers function
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
    function getFirstPayusersByamount($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$amountfrom=1,$amountto=10) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    #AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    AND t.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    DAY,
                                                    a.pay_time,
                                                    c.create_role_time
                                            ) AS timedvalue
                                    FROM
                                            (
                                                    SELECT
                                                            a.appId,
                                                            a.chId,
                                                            a.srvId,
                                                            a.version,
                                                            a.roleId,
                                                            a.pay_time,
                                                            a.pay_date,
                                                            a.pay_amount,
                                                            b.rownum paycountrank
                                                    FROM
                                                            razor_pay a
                                                    INNER JOIN (
                                                            SELECT
                                                                    roleId,
                                                                    pay_time,
                                                                    CASE
                                                            WHEN @mid = roleId THEN
                                                                    @ROW :=@ROW + 1
                                                            ELSE
                                                                    @ROW := 1
                                                            END rownum,
                                                            @mid := roleId mid
                                                    FROM
                                                            razor_pay
                                                    ORDER BY
                                                            roleId,
                                                            pay_time
                                                    ) b ON b.roleId = a.roleId
                                                    AND b.pay_time = a.pay_time
                                                    WHERE
                                                            b.rownum < 2
                                            ) a
                                    LEFT JOIN razor_createrole c ON a.roleId = c.roleId
                            ) t
                    WHERE
                            t.pay_date <= '$date'
                    AND t.pay_amount >= '$amountfrom'
                    AND t.pay_amount <= '$amountto'
                    AND t.appId = '$appid'
                    AND t.chId = '$channelid'
                    #AND t.srvId = '$serverid'
                    #AND t.version = '$versionname';";
        }
        $this->db->query("SET @ROW = 0;");
        $this->db->query("SET @mid = '';");
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payuser;
        }
    }

        
    /**
     * Sum_basic_firstpayanaly_time function
     * count pay data
     * 
     * 
     */
    function sum_basic_firstpayanaly_time($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7*12+1,7*100000000);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7*12+1,7*100000000);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7*12+1,7*100000000);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7*12+1,7*100000000);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7*12+1,7*100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7*12+1,7*100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*12+1,7*100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $payusers_time_0_1_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',0,1);
            $payusers_time_2_3_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2,3);
            $payusers_time_4_7_day = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4,7);
            $payusers_time_2_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',101,200);
            $payusers_time_3_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*2+1,7*3);
            $payusers_time_4_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*3+1,7*4);
            $payusers_time_5_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*4+1,7*5);
            $payusers_time_6_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*5+1,7*6);
            $payusers_time_7_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*6+1,7*7);
            $payusers_time_8_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*7+1,7*8);
            $payusers_time_9_12_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*8+1,7*12);
            $payusers_time_12_above_week = $this->getFirstPayusersBytime($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7*12+1,7*100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data_time_0_1_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '首日',
                'payusers' => $payusers_time_0_1_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_1_day/$payusers_total)
            );
            $data_time_2_3_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '2-3天',
                'payusers' => $payusers_time_2_3_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_3_day/$payusers_total)
            );
            $data_time_4_7_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '4-7天',
                'payusers' => $payusers_time_4_7_day,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_7_day/$payusers_total)
            );
            $data_time_2_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '2周',
                'payusers' => $payusers_time_2_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_week/$payusers_total)
            );
            $data_time_3_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '3周',
                'payusers' => $payusers_time_3_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_3_week/$payusers_total)
            );
            $data_time_4_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '4周',
                'payusers' => $payusers_time_4_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_week/$payusers_total)
            );
            $data_time_5_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '5周',
                'payusers' => $payusers_time_5_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_5_week/$payusers_total)
            );
            $data_time_6_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '6周',
                'payusers' => $payusers_time_6_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_week/$payusers_total)
            );
            $data_time_7_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '7周',
                'payusers' => $payusers_time_7_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_7_week/$payusers_total)
            );
            $data_time_8_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '8周',
                'payusers' => $payusers_time_8_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_8_week/$payusers_total)
            );
            $data_time_9_12_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '9-12周',
                'payusers' => $payusers_time_9_12_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_9_12_week/$payusers_total)
            );
            $data_time_12_above_week = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayname' => '>12周',
                'payusers' => $payusers_time_12_above_week,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_12_above_week/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_0_1_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_3_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_7_day);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_2_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_3_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_4_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_5_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_6_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_7_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_8_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_9_12_week);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_time', $data_time_12_above_week);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

    /**
     * sum_basic_firstpayanaly_level function
     * count pay data
     * 
     * 
     */
    function sum_basic_firstpayanaly_level($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,11,10000);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',11,10000);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,11,10000);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',11,10000);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,11,10000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',11,10000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,11,10000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $payusers_level_1 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1,1);
            $payusers_level_2 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2,2);
            $payusers_level_3 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',3,3);
            $payusers_level_4 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4,4);
            $payusers_level_5 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',5,5);
            $payusers_level_6 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',6,6);
            $payusers_level_7 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',7,7);
            $payusers_level_8_10 = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',8,10);
            $payusers_level_10_above = $this->getFirstPayusersBylevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',11,10000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data_level_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '1',
                'payusers' => $payusers_level_1,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_1/$payusers_total)
            );
            $data_level_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '2',
                'payusers' => $payusers_level_2,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_2/$payusers_total)
            );
            $data_level_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '3',
                'payusers' => $payusers_level_3,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_3/$payusers_total)
            );
            $data_level_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '4',
                'payusers' => $payusers_level_4,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_4/$payusers_total)
            );
            $data_level_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '5',
                'payusers' => $payusers_level_5,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_5/$payusers_total)
            );
            $data_level_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '6',
                'payusers' => $payusers_level_6,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_6/$payusers_total)
            );
            $data_level_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '7',
                'payusers' => $payusers_level_7,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_7/$payusers_total)
            );
            $data_level_8_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '8-10',
                'payusers' => $payusers_level_8_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_8_10/$payusers_total)
            );
            $data_level_10_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaylevel' => '>10',
                'payusers' => $payusers_level_10_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_level_10_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_1);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_2);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_3);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_4);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_5);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_6);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_7);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_8_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_level', $data_level_10_above);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

    /**
     * Sum_basic_firstpayanaly_amount function
     * count pay data
     * 
     * 
     */
    function sum_basic_firstpayanaly_amount($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1001,100000000);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1001,100000000);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1001,100000000);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1001,100000000);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1001,100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1001,100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,7*1+1,7*2);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1001,100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $payusers_amount_1_10 = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1,10);
            $payusers_amount_11_50 = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',11,50);
            $payusers_amount_51_100 = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',51,100);
            $payusers_amount_101_200 = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',101,200);
            $payusers_amount_201_500 = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',201,500);
            $payusers_amount_501_1000 = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',501,1000);
            $payusers_amount_1000_above = $this->getFirstPayusersByamount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1001,100000000);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data_amount_1_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '1-10',
                'payusers' => $payusers_amount_1_10,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1_10/$payusers_total)
            );
            $data_amount_11_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '11-50',
                'payusers' => $payusers_amount_11_50,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_11_50/$payusers_total)
            );
            $data_amount_51_100 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '51-100',
                'payusers' => $payusers_amount_51_100,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_51_100/$payusers_total)
            );
            $data_amount_101_200 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '101-200',
                'payusers' => $payusers_amount_101_200,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_101_200/$payusers_total)
            );
            $data_amount_201_500 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '201-500',
                'payusers' => $payusers_amount_201_500,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_201_500/$payusers_total)
            );
            $data_amount_501_1000 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '501-1000',
                'payusers' => $payusers_amount_501_1000,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_501_1000/$payusers_total)
            );
            $data_amount_1000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpayamount' => '>1000',
                'payusers' => $payusers_amount_1000_above,
                'payusersrate' => ($payusers_total==0)?0:($payusers_amount_1000_above/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1_10);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_11_50);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_51_100);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_101_200);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_201_500);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_501_1000);
            $dwdb->insert_or_update('razor_sum_basic_firstpayanaly_amount', $data_amount_1000_above);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

}
