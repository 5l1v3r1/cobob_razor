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
class Payintervalmodel extends CI_Model
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

	function getPayData($fromTime,$toTime,$channel,$version,$server,$db)
	{
		$list = array();
		$query = $this->PayData($fromTime,$toTime,$channel,$version,$server,$db);
		$PayDataRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['firstpaytime'] = $PayDataRow->firstpaytime;
			$fRow['payusers'] = $PayDataRow->payusers;
			$fRow['payusersrate'] = $PayDataRow->payusersrate;
			$PayDataRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function PayData($fromTime,$toTime,$channel,$version,$server,$db)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(firstpaytime, 0) firstpaytime,
					IFNULL(SUM(payusers), 0) payusers,
					IFNULL(SUM(payusersrate), 0) payusersrate
				FROM
					".$dwdb->dbprefix($db)."
                WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
                GROUP BY firstpaytime
                ORDER BY rid asc";
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
    function getFirstPayusers($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$timefrom=0,$timeto=0) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT t.roleId) payuser
                    FROM
                            (
                                    SELECT
                                            a.*, c.create_role_time,
                                            TIMESTAMPDIFF(
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
                                                    MINUTE,
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
                    AND t.timedvalue < '$timeto'
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
     * GetSeveralPayusers function
     * get pay users  for several pay
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getSeveralPayusers($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$timefrom=0,$timeto=0,$cfrom=1,$cto=1) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                        COUNT(DISTINCT t.roleId) payuser
                    FROM
                        (
                            SELECT
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
                                rank1.*, TIMESTAMPDIFF(
                                    MINUTE,
                                    rank1.pay_time,
                                    rank2.pay_time
                                ) timedvalue
                            FROM
                                (
                                    SELECT
                                        *
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
                                                b.rownum < 4
                                        ) a
                                    WHERE
                                        a.roleId IN (
                                            SELECT
                                                roleId
                                            FROM
                                                VIEW_razor_pay_count
                                            WHERE
                                                pay_count >= $cto
                                        )
                                    AND a.paycountrank = $cto
                                ) rank2
                            LEFT JOIN (
                                SELECT
                                    *
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
                                            b.rownum < 4
                                    ) a
                                WHERE
                                    a.roleId IN (
                                        SELECT
                                            roleId
                                        FROM
                                            VIEW_razor_pay_count
                                        WHERE
                                            pay_count >= $cto
                                    )
                                AND a.paycountrank = $cfrom
                            ) rank1 ON rank1.roleId = rank2.roleId
                        ) t
                    WHERE
                        t.pay_date <= '$date'
                    AND t.timedvalue >= '$timefrom'
                    AND t.timedvalue < '$timeto'
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
     * Sum_basic_payinterval_first function
     * count pay data
     * 
     * 
     */
    function sum_basic_payinterval_first($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,100*60,100000000*60);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',100*60,100000000*60);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,100*60,100000000*60);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',100*60,100000000*60);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,100*60,100000000*60);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',100*60,100000000*60);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,100*60,100000000*60);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $payusers_time_0_10_min = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',0,10);
            $payusers_time_10_30_min = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10,30);
            $payusers_time_30_60_min = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,60);
            $payusers_time_1_2_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1*60,2*60);
            $payusers_time_2_4_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2*60,4*60);
            $payusers_time_4_6_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4*60,6*60);
            $payusers_time_6_10_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',6*60,10*60);
            $payusers_time_10_15_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10*60,15*60);
            $payusers_time_15_20_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',15*60,20*60);
            $payusers_time_20_30_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',20*60,30*60);
            $payusers_time_30_40_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30*60,40*60);
            $payusers_time_40_60_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',40*60,60*60);
            $payusers_time_60_100_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',60*60,100*60);
            $payusers_time_100_above_hour = $this->getFirstPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',100*60,100000000*60);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_first', $data_time_100_above_hour);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

    /**
     * Sum_basic_payinterval_second function
     * count pay data
     * 
     * 
     */
    function sum_basic_payinterval_second($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,100*60,100000000*60,1,2);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',100*60,100000000*60,1,2);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,100*60,100000000*60,1,2);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',100*60,100000000*60,1,2);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,100*60,100000000*60,1,2);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',100*60,100000000*60,1,2);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,100*60,100000000*60,1,2);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',0,10,1,2);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10,30,1,2);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,60,1,2);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1*60,2*60,1,2);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2*60,4*60,1,2);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4*60,6*60,1,2);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',6*60,10*60,1,2);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10*60,15*60,1,2);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',15*60,20*60,1,2);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',20*60,30*60,1,2);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30*60,40*60,1,2);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',40*60,60*60,1,2);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',60*60,100*60,1,2);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',100*60,100000000*60,1,2);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_second', $data_time_100_above_hour);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

    /**
     * Sum_basic_payinterval_third function
     * count pay data
     * 
     * 
     */
    function sum_basic_payinterval_third($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,100*60,100000000*60,2,3);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',100*60,100000000*60,2,3);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,100*60,100000000*60,2,3);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',100*60,100000000*60,2,3);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,100*60,100000000*60,2,3);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',100*60,100000000*60,2,3);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,100*60,100000000*60,2,3);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
                        $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $payusers_total=  $this->dauusers->getPayuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $payusers_time_0_10_min = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',0,10,2,3);
            $payusers_time_10_30_min = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10,30,2,3);
            $payusers_time_30_60_min = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30,60,2,3);
            $payusers_time_1_2_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1*60,2*60,2,3);
            $payusers_time_2_4_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2*60,4*60,2,3);
            $payusers_time_4_6_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4*60,6*60,2,3);
            $payusers_time_6_10_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',6*60,10*60,2,3);
            $payusers_time_10_15_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10*60,15*60,2,3);
            $payusers_time_15_20_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',15*60,20*60,2,3);
            $payusers_time_20_30_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',20*60,30*60,2,3);
            $payusers_time_30_40_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',30*60,40*60,2,3);
            $payusers_time_40_60_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',40*60,60*60,2,3);
            $payusers_time_60_100_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',60*60,100*60,2,3);
            $payusers_time_100_above_hour = $this->getSeveralPayusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',100*60,100000000*60,2,3);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data_time_0_10_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '<10分钟',
                'payusers' => $payusers_time_0_10_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_0_10_min/$payusers_total)
            );
            $data_time_10_30_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-30分钟',
                'payusers' => $payusers_time_10_30_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_30_min/$payusers_total)
            );
            $data_time_30_60_min = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-60分钟',
                'payusers' => $payusers_time_30_60_min,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_60_min/$payusers_total)
            );
            $data_time_1_2_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '1-2小时',
                'payusers' => $payusers_time_1_2_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_1_2_hour/$payusers_total)
            );
            $data_time_2_4_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '2-4小时',
                'payusers' => $payusers_time_2_4_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_2_4_hour/$payusers_total)
            );
            $data_time_4_6_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '4-6小时',
                'payusers' => $payusers_time_4_6_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_4_6_hour/$payusers_total)
            );
            $data_time_6_10_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '6-10小时',
                'payusers' => $payusers_time_6_10_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_6_10_hour/$payusers_total)
            );
            $data_time_10_15_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '10-15小时',
                'payusers' => $payusers_time_10_15_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_10_15_hour/$payusers_total)
            );
            $data_time_15_20_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '15-20小时',
                'payusers' => $payusers_time_15_20_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_15_20_hour/$payusers_total)
            );
            $data_time_20_30_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '20-30小时',
                'payusers' => $payusers_time_20_30_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_20_30_hour/$payusers_total)
            );
            $data_time_30_40_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '30-40小时',
                'payusers' => $payusers_time_30_40_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_30_40_hour/$payusers_total)
            );
            $data_time_40_60_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '40-60小时',
                'payusers' => $payusers_time_40_60_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_40_60_hour/$payusers_total)
            );
            $data_time_60_100_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '60-100小时',
                'payusers' => $payusers_time_60_100_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_60_100_hour/$payusers_total)
            );
            $data_time_100_above_hour = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'firstpaytime' => '>100小时',
                'payusers' => $payusers_time_100_above_hour,
                'payusersrate' => ($payusers_total==0)?0:($payusers_time_100_above_hour/$payusers_total)
            );
            $dwdb = $this->load->database('dw', true);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_0_10_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_30_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_60_min);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_1_2_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_2_4_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_4_6_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_6_10_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_10_15_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_15_20_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_20_30_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_30_40_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_40_60_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_60_100_hour);
            $dwdb->insert_or_update('razor_sum_basic_payinterval_third', $data_time_100_above_hour);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

}
