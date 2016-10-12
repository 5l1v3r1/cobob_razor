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
class Borderintervaltimemodel extends CI_Model {

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
    
    function getBorderintervaltime($fromTime,$toTime,$channel,$server,$version){
        $list = array();
        $query = $this->borderintervaltime($fromTime,$toTime,$channel,$server,$version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['logininterval'] = $dauUsersRow->logininterval;
            $fRow['rolecount'] = $dauUsersRow->rolecount;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

	function borderintervaltime($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(logininterval, 0) logininterval,
					IFNULL(SUM(rolecount), 0) rolecount
				FROM
					" . $dwdb->dbprefix('sum_basic_borderintervaltime') . "
                WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                Group By logininterval
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
    function getRolecount($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$fromhour=1,$tohour=1) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    #AND a.chId = '$channelid'
                    AND a.srvId = '$serverid'
                    AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    #AND a.chId = '$channelid'
                    AND a.srvId = '$serverid'
                    #AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    #AND a.chId = '$channelid'
                    #AND a.srvId = '$serverid'
                    AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    #AND a.chId = '$channelid'
                    #AND a.srvId = '$serverid'
                    #AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    AND a.chId = '$channelid'
                    AND a.srvId = '$serverid'
                    AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    AND a.chId = '$channelid'
                    AND a.srvId = '$serverid'
                    #AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    AND a.chId = '$channelid'
                    #AND a.srvId = '$serverid'
                    AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        COUNT(DISTINCT a.roleId) rolecount
                    FROM
                        (
                            SELECT
                                t1.login_date,
                                t1.appId,
                                t1.chId,
                                t1.srvId,
                                t1.version,
                                t1.roleId,
                                IFNULL(
                                    TIMESTAMPDIFF(
                                        HOUR,
                                        str_to_date(
                                            t1.login_time,
                                            '%Y-%m-%d %T'
                                        ),
                                        str_to_date(
                                            (
                                                SELECT
                                                    t3.login_time
                                                FROM
                                                    razor_login t3
                                                WHERE
                                                    t3.type = 'login'
                                                AND t3.login_date = '$date'
                                                AND t3.roleId = t1.roleId
                                                AND t3.ID > t1.ID
                                                LIMIT 1
                                            ),
                                            '%Y-%m-%d %T'
                                        )
                                    ),
                                    100000000
                                ) timefield
                            FROM
                                razor_login t1
                            WHERE
                                t1.type = 'login'
                            AND t1.login_date = '$date'
                            ORDER BY
                                t1.roleId,
                                t1.login_time
                        ) a
                    WHERE
                        a.appId = '$appid'
                    AND a.chId = '$channelid'
                    #AND a.srvId = '$serverid'
                    #AND a.version = '$versionname'
                    AND a.timefield >= $fromhour
                    AND a.timefield < $tohour;";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->rolecount;
        }
    }

    /**
     * Sum_basic_borderintervaltime function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_borderintervaltime($countdate) {
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,24*2,24*3);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',24*2,24*3);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,24*2,24*3);

            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_p->appId, 'all', 'all', 'all',24*2,24*3);

            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,24*2,24*3);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',24*2,24*3);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,24*2,24*3);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $rolecount_min_0_60=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',0,1);
            $rolecount_hour_1_2=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1,2);
            $rolecount_hour_2_3=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',2,3);
            $rolecount_hour_3_4=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',3,4);
            $rolecount_hour_4_5=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',4,5);
            $rolecount_hour_5_8=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',5,8);
            $rolecount_hour_8_12=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',8,12);
            $rolecount_hour_12_24=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',12,24);
            $rolecount_day_1_2=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',24*1,24*2);
            $rolecount_day_2_3=$this->getRolecount($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',24*2,24*3);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_min_0_60 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '0-60分钟',
                'rolecount' => $rolecount_min_0_60
            );
            $data_hour_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '1-2小时',
                'rolecount' => $rolecount_hour_1_2
            );
            $data_hour_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '2-3小时',
                'rolecount' => $rolecount_hour_2_3
            );
            $data_hour_3_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '3-4小时',
                'rolecount' => $rolecount_hour_3_4
            );
            $data_hour_4_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '4-5小时',
                'rolecount' => $rolecount_hour_4_5
            );
            $data_hour_5_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '5-8小时',
                'rolecount' => $rolecount_hour_5_8
            );
            $data_hour_8_12 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '8-12小时',
                'rolecount' => $rolecount_hour_8_12
            );
            $data_hour_12_24 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '12-24小时',
                'rolecount' => $rolecount_hour_12_24
            );
            $data_day_1_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '1-2天',
                'rolecount' => $rolecount_day_1_2
            );
            $data_day_2_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'logininterval' => '2-3天',
                'rolecount' => $rolecount_day_2_3
            );
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_min_0_60);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_2_3);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_3_4);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_4_5);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_5_8);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_8_12);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_hour_12_24);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_1_2);
            $dwdb->insert_or_update('razor_sum_basic_borderintervaltime', $data_day_2_3);
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
