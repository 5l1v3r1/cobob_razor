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
 * Starttimesmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Starttimesmodel extends CI_Model {

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
    
    function getStarttimesDataByTimes($fromTime, $toTime, $channel, $server, $version) {
        $list = array();
        $query = $this->StarttimesData($fromTime, $toTime, $channel, $server, $version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
            $fRow['starttimes'] = $dauUsersRow->starttimes;
            $fRow['logintimes'] = $dauUsersRow->logintimes;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

	function StarttimesData($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(starttimes, 0) starttimes,
					IFNULL(logintimes, 0) logintimes
				FROM
					" . $dwdb->dbprefix('sum_basic_start_login_times') . "
                WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                Order By startdate_sk DESC";
        $query = $dwdb->query($sql);
        return $query;
    }

    function getPeoplesbycountData($date, $channel, $server, $version) {
        $list = array();
        $query = $this->PeoplesbycountData($date, $channel, $server, $version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
            $fRow['counts'] = $dauUsersRow->counts;
            $fRow['startpeoples'] = $dauUsersRow->startpeoples;
            $fRow['loginpeoples'] = $dauUsersRow->loginpeoples;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function PeoplesbycountData($date, $channel, $server, $version)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(startdate_sk, 0) startdate_sk,
                    IFNULL(counts, 0) counts,
                    IFNULL(SUM(startpeoples), 0) startpeoples,
                    IFNULL(SUM(loginpeoples), 0) loginpeoples
                FROM
                    " . $dwdb->dbprefix('sum_basic_start_login_peoplesbycount') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND startdate_sk = '" . $date . "'
                Group by counts
                Order By rid ASC";
        $query = $dwdb->query($sql);
        return $query;
    }

    function getPeoplesbytimeData($date, $channel, $server, $version) {
        $list = array();
        $query = $this->PeoplesbytimeData($date, $channel, $server, $version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
            $fRow['timefield'] = $dauUsersRow->timefield;
            $fRow['startpeoples'] = $dauUsersRow->startpeoples;
            $fRow['loginpeoples'] = $dauUsersRow->loginpeoples;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function PeoplesbytimeData($date, $channel, $server, $version)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(startdate_sk, 0) startdate_sk,
                    IFNULL(timefield, 0) timefield,
                    IFNULL(SUM(startpeoples), 0) startpeoples,
                    IFNULL(SUM(loginpeoples), 0) loginpeoples
                FROM
                    " . $dwdb->dbprefix('sum_basic_start_login_peoplesbytime') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND startdate_sk = '" . $date . "'
                Group by timefield
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
     * GetNewusers function
     * get new users
     * 
     * @param Date $fromdate
     * @param Date $todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int newusers
     */
    function getDevicebootAndlogincount($date, $appid, $channelid='all', $versionname='all') {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        db1.deviceboot_count,
                        lg1.login_count
                    FROM
                        (
                            SELECT
                                '$date' deviceboot_date,
                                COUNT(1) deviceboot_count
                            FROM
                                razor_deviceboot db
                            WHERE
                                db.deviceboot_date = '$date'
                            AND db.appId = '$appid'
                            AND db.chId = '$channelid'
                            AND db.version = '$versionname'
                        ) db1
                    LEFT JOIN (
                        SELECT
                            '$date' login_date,
                            COUNT(1) login_count
                        FROM
                            razor_login lg
                        WHERE
                            lg.login_date = '$date'
                        AND lg.appId = '$appid'
                        AND lg.chId = '$channelid'
                        AND lg.version = '$versionname'
                    ) lg1 ON db1.deviceboot_date = lg1.login_date;";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        db1.deviceboot_count,
                        lg1.login_count
                    FROM
                        (
                            SELECT
                                '$date' deviceboot_date,
                                COUNT(1) deviceboot_count
                            FROM
                                razor_deviceboot db
                            WHERE
                                db.deviceboot_date = '$date'
                            AND db.appId = '$appid'
                            AND db.chId = '$channelid'
                            #AND db.version = '$versionname'
                        ) db1
                    LEFT JOIN (
                        SELECT
                            '$date' login_date,
                            COUNT(1) login_count
                        FROM
                            razor_login lg
                        WHERE
                            lg.login_date = '$date'
                        AND lg.appId = '$appid'
                        AND lg.chId = '$channelid'
                        #AND lg.version = '$versionname'
                    ) lg1 ON db1.deviceboot_date = lg1.login_date;";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        db1.deviceboot_count,
                        lg1.login_count
                    FROM
                        (
                            SELECT
                                '$date' deviceboot_date,
                                COUNT(1) deviceboot_count
                            FROM
                                razor_deviceboot db
                            WHERE
                                db.deviceboot_date = '$date'
                            AND db.appId = '$appid'
                            #AND db.chId = '$channelid'
                            #AND db.version = '$versionname'
                        ) db1
                    LEFT JOIN (
                        SELECT
                            '$date' login_date,
                            COUNT(1) login_count
                        FROM
                            razor_login lg
                        WHERE
                            lg.login_date = '$date'
                        AND lg.appId = '$appid'
                        #AND lg.chId = '$channelid'
                        #AND lg.version = '$versionname'
                    ) lg1 ON db1.deviceboot_date = lg1.login_date;";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        db1.deviceboot_count,
                        lg1.login_count
                    FROM
                        (
                            SELECT
                                '$date' deviceboot_date,
                                COUNT(1) deviceboot_count
                            FROM
                                razor_deviceboot db
                            WHERE
                                db.deviceboot_date = '$date'
                            AND db.appId = '$appid'
                            #AND db.chId = '$channelid'
                            AND db.version = '$versionname'
                        ) db1
                    LEFT JOIN (
                        SELECT
                            '$date' login_date,
                            COUNT(1) login_count
                        FROM
                            razor_login lg
                        WHERE
                            lg.login_date = '$date'
                        AND lg.appId = '$appid'
                        #AND lg.chId = '$channelid'
                        AND lg.version = '$versionname'
                    ) lg1 ON db1.deviceboot_date = lg1.login_date;";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }

    /**
     * getDevicebootAndloginuserByfield function
     * get new users
     * 
     * @param Date $fromdate
     * @param Date $todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int newusers
     */
    function getDevicebootAndloginuserByfield($date, $appid, $channelid='all', $versionname='all',$fromtimes=1,$totimes=1) {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(roleId) loginuser
                            FROM
                                VIEW_razor_login_logintimes lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                            AND lg.chId = '$channelid'
                            AND lg.version = '$versionname'
                            AND lg.logintimes >= $fromtimes
                            AND lg.logintimes <= $totimes
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(deviceid) devicecount
                        FROM
                            VIEW_razor_deviceboot_deviceboottimes dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        AND dbt.chId = '$channelid'
                        AND dbt.version = '$versionname'
                        AND dbt.deviceboottimes >= $fromtimes
                        AND dbt.deviceboottimes <= $totimes
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(roleId) loginuser
                            FROM
                                VIEW_razor_login_logintimes lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                            AND lg.chId = '$channelid'
                          #  AND lg.version = '$versionname'
                            AND lg.logintimes >= $fromtimes
                            AND lg.logintimes <= $totimes
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(deviceid) devicecount
                        FROM
                            VIEW_razor_deviceboot_deviceboottimes dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        AND dbt.chId = '$channelid'
                       # AND dbt.version = '$versionname'
                        AND dbt.deviceboottimes >= $fromtimes
                        AND dbt.deviceboottimes <= $totimes
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(roleId) loginuser
                            FROM
                                VIEW_razor_login_logintimes lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                          #  AND lg.chId = '$channelid'
                          #  AND lg.version = '$versionname'
                            AND lg.logintimes >= $fromtimes
                            AND lg.logintimes <= $totimes
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(deviceid) devicecount
                        FROM
                            VIEW_razor_deviceboot_deviceboottimes dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                       # AND dbt.chId = '$channelid'
                       # AND dbt.version = '$versionname'
                        AND dbt.deviceboottimes >= $fromtimes
                        AND dbt.deviceboottimes <= $totimes
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(roleId) loginuser
                            FROM
                                VIEW_razor_login_logintimes lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                           # AND lg.chId = '$channelid'
                            AND lg.version = '$versionname'
                            AND lg.logintimes >= $fromtimes
                            AND lg.logintimes <= $totimes
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(deviceid) devicecount
                        FROM
                            VIEW_razor_deviceboot_deviceboottimes dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        #AND dbt.chId = '$channelid'
                        AND dbt.version = '$versionname'
                        AND dbt.deviceboottimes >= $fromtimes
                        AND dbt.deviceboottimes <= $totimes
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }

    /**
     * getDevicebootAndloginuserBytime function
     * get new users
     * 
     * @param Date $fromdate
     * @param Date $todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int newusers
     */
    function getDevicebootAndloginuserBytime($date, $appid, $channelid='all', $versionname='all',$fromtime=1,$totime=1) {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(distinct roleId) loginuser
                            FROM
                                razor_login lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                            AND lg.chId = '$channelid'
                            AND lg.version = '$versionname'
                            AND lg.login_time >= date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $fromtime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                            AND lg.login_time < date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $totime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(distinct deviceid) devicecount
                        FROM
                            razor_deviceboot dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        AND dbt.chId = '$channelid'
                        AND dbt.version = '$versionname'
                        AND dbt.deviceboot_time >= date_format(
                            date_add(
                                '$date',
                                INTERVAL $fromtime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                        AND dbt.deviceboot_time < date_format(
                            date_add(
                                '$date',
                                INTERVAL $totime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(distinct roleId) loginuser
                            FROM
                                razor_login lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                            AND lg.chId = '$channelid'
                           # AND lg.version = '$versionname'
                            AND lg.login_time >= date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $fromtime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                            AND lg.login_time < date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $totime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(distinct deviceid) devicecount
                        FROM
                            razor_deviceboot dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        AND dbt.chId = '$channelid'
                        #AND dbt.version = '$versionname'
                        AND dbt.deviceboot_time >= date_format(
                            date_add(
                                '$date',
                                INTERVAL $fromtime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                        AND dbt.deviceboot_time < date_format(
                            date_add(
                                '$date',
                                INTERVAL $totime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(distinct roleId) loginuser
                            FROM
                                razor_login lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                           # AND lg.chId = '$channelid'
                           # AND lg.version = '$versionname'
                            AND lg.login_time >= date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $fromtime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                            AND lg.login_time < date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $totime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(distinct deviceid) devicecount
                        FROM
                            razor_deviceboot dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        #AND dbt.chId = '$channelid'
                        #AND dbt.version = '$versionname'
                        AND dbt.deviceboot_time >= date_format(
                            date_add(
                                '$date',
                                INTERVAL $fromtime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                        AND dbt.deviceboot_time < date_format(
                            date_add(
                                '$date',
                                INTERVAL $totime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        lg1.loginuser,
                        dbt1.devicecount
                    FROM
                        (
                            SELECT
                                '$date' login_date,
                                COUNT(distinct roleId) loginuser
                            FROM
                                razor_login lg
                            WHERE
                                lg.login_date = '$date'
                            AND lg.appId = '$appid'
                           # AND lg.chId = '$channelid'
                            AND lg.version = '$versionname'
                            AND lg.login_time >= date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $fromtime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                            AND lg.login_time < date_format(
                                date_add(
                                    '$date',
                                    INTERVAL $totime HOUR
                                ),
                                '%Y-%m-%d %k:00:00'
                            )
                        ) lg1
                    LEFT JOIN (
                        SELECT
                            '$date' deviceboot_date,
                            COUNT(distinct deviceid) devicecount
                        FROM
                            razor_deviceboot dbt
                        WHERE
                            dbt.deviceboot_date = '$date'
                        AND dbt.appId = '$appid'
                        #AND dbt.chId = '$channelid'
                        AND dbt.version = '$versionname'
                        AND dbt.deviceboot_time >= date_format(
                            date_add(
                                '$date',
                                INTERVAL $fromtime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                        AND dbt.deviceboot_time < date_format(
                            date_add(
                                '$date',
                                INTERVAL $totime HOUR
                            ),
                            '%Y-%m-%d %k:00:00'
                        )
                    ) dbt1 ON lg1.login_date = dbt1.deviceboot_date;";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }

    /**
     * sum_basic_start_login function
     * count new users
     * 
     */
    function sum_basic_start_login($countdate) {
        $dwdb = $this->load->database('dw', true);
        $paramspcv = $this->product->getProductChannelVersion();
        $paramspcvRow = $paramspcv->first_row();
        for ($i = 0; $i < $paramspcv->num_rows(); $i++) {
            $DevicebootAndlogincount=$this->getDevicebootAndlogincount($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            $DevicebootAndloginuserByfield_1_1=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,1,1);
            $DevicebootAndloginuserByfield_2_2=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,2,2);
            $DevicebootAndloginuserByfield_3_3=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,3,3);
            $DevicebootAndloginuserByfield_4_4=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,4,4);
            $DevicebootAndloginuserByfield_5_5=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,5,5);
            $DevicebootAndloginuserByfield_6_10=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,6,10);
            $DevicebootAndloginuserByfield_10_above=$this->getDevicebootAndloginuserByfield($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,11,100000000);
            
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcvRow->chId);
            //razor_sum_basic_start_login_times
            $data_count = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'starttimes' => $DevicebootAndlogincount['deviceboot_count'],
                    'logintimes' => $DevicebootAndlogincount['login_count']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_times', $data_count);

            //razor_sum_basic_start_login_peoplesbycount
            $data_userByfield_1_1 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '1',
                    'loginpeoples' => $DevicebootAndloginuserByfield_1_1['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_1_1['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_1_1);
            $data_userByfield_2_2 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '2',
                    'loginpeoples' => $DevicebootAndloginuserByfield_2_2['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_2_2['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_2_2);
            $data_userByfield_3_3 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '3',
                    'loginpeoples' => $DevicebootAndloginuserByfield_3_3['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_3_3['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_3_3);
            $data_userByfield_4_4 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '4',
                    'loginpeoples' => $DevicebootAndloginuserByfield_4_4['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_4_4['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_4_4);
            $data_userByfield_5_5 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '5',
                    'loginpeoples' => $DevicebootAndloginuserByfield_5_5['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_5_5['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_5_5);
            $data_userByfield_6_10 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '6-10',
                    'loginpeoples' => $DevicebootAndloginuserByfield_6_10['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_6_10['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_6_10);
            $data_userByfield_10_above = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'counts' => '10+',
                    'loginpeoples' => $DevicebootAndloginuserByfield_10_above['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_10_above['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_10_above);

            //razor_sum_basic_start_login_peoplesbytime
            for ($t=0; $t < 24; $t++) { 
                $DevicebootAndloginuserBytime=$this->getDevicebootAndloginuserBytime($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version,$t,$t+1);
                ${'data_userBytime'.$t} = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspcvRow->appId,
                    'version_name' => $paramspcvRow->version,
                    'channel_name' => $channel_name,
                    'timefield' => $t.'时',
                    'loginpeoples' => $DevicebootAndloginuserBytime['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserBytime['devicecount']
                );
                $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbytime', ${'data_userBytime'.$t});
            }
            $paramspcvRow = $paramspcv->next_row();
        }
        $paramspv = $this->product->getProductVersionOffChannel();
        $paramspvRow = $paramspv->first_row();
        for ($i = 0; $i < $paramspv->num_rows(); $i++) {
            $DevicebootAndlogincount=$this->getDevicebootAndlogincount($countdate, $paramspvRow->appId,'all', $paramspvRow->version);
            $DevicebootAndloginuserByfield_1_1=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,1,1);
            $DevicebootAndloginuserByfield_2_2=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,2,2);
            $DevicebootAndloginuserByfield_3_3=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,3,3);
            $DevicebootAndloginuserByfield_4_4=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,4,4);
            $DevicebootAndloginuserByfield_5_5=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,5,5);
            $DevicebootAndloginuserByfield_6_10=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,6,10);
            $DevicebootAndloginuserByfield_10_above=$this->getDevicebootAndloginuserByfield($countdate, $paramspvRow->appId,'all', $paramspvRow->version,11,100000000);
            
            //razor_sum_basic_start_login_times
            $data_count = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'starttimes' => $DevicebootAndlogincount['deviceboot_count'],
                    'logintimes' => $DevicebootAndlogincount['login_count']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_times', $data_count);

            //razor_sum_basic_start_login_peoplesbycount
            $data_userByfield_1_1 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '1',
                    'loginpeoples' => $DevicebootAndloginuserByfield_1_1['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_1_1['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_1_1);
            $data_userByfield_2_2 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '2',
                    'loginpeoples' => $DevicebootAndloginuserByfield_2_2['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_2_2['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_2_2);
            $data_userByfield_3_3 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '3',
                    'loginpeoples' => $DevicebootAndloginuserByfield_3_3['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_3_3['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_3_3);
            $data_userByfield_4_4 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '4',
                    'loginpeoples' => $DevicebootAndloginuserByfield_4_4['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_4_4['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_4_4);
            $data_userByfield_5_5 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '5',
                    'loginpeoples' => $DevicebootAndloginuserByfield_5_5['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_5_5['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_5_5);
            $data_userByfield_6_10 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '6-10',
                    'loginpeoples' => $DevicebootAndloginuserByfield_6_10['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_6_10['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_6_10);
            $data_userByfield_10_above = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'counts' => '10+',
                    'loginpeoples' => $DevicebootAndloginuserByfield_10_above['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_10_above['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_10_above);

            //razor_sum_basic_start_login_peoplesbytime
            for ($t=0; $t < 24; $t++) { 
                $DevicebootAndloginuserBytime=$this->getDevicebootAndloginuserBytime($countdate, $paramspvRow->appId,'all', $paramspvRow->version,$t,$t+1);
                ${'data_userBytime'.$t} = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspvRow->appId,
                    'version_name' => $paramspvRow->version,
                    'channel_name' => 'all',
                    'timefield' => $t.'时',
                    'loginpeoples' => $DevicebootAndloginuserBytime['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserBytime['devicecount']
                );
                $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbytime', ${'data_userBytime'.$t});
            }
            $paramspvRow = $paramspv->next_row();
        }
        $paramspc = $this->product->getProductChannelOffVersion();
        $paramspcRow = $paramspc->first_row();
        for ($i = 0; $i < $paramspc->num_rows(); $i++) {
            $DevicebootAndlogincount=$this->getDevicebootAndlogincount($countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            $DevicebootAndloginuserByfield_1_1=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',1,1);
            $DevicebootAndloginuserByfield_2_2=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',2,2);
            $DevicebootAndloginuserByfield_3_3=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',3,3);
            $DevicebootAndloginuserByfield_4_4=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',4,4);
            $DevicebootAndloginuserByfield_5_5=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',5,5);
            $DevicebootAndloginuserByfield_6_10=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',6,10);
            $DevicebootAndloginuserByfield_10_above=$this->getDevicebootAndloginuserByfield($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',11,100000000);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcRow->chId);
            
            //razor_sum_basic_start_login_times
            $data_count = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'starttimes' => $DevicebootAndlogincount['deviceboot_count'],
                    'logintimes' => $DevicebootAndlogincount['login_count']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_times', $data_count);

            //razor_sum_basic_start_login_peoplesbycount
            $data_userByfield_1_1 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '1',
                    'loginpeoples' => $DevicebootAndloginuserByfield_1_1['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_1_1['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_1_1);
            $data_userByfield_2_2 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '2',
                    'loginpeoples' => $DevicebootAndloginuserByfield_2_2['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_2_2['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_2_2);
            $data_userByfield_3_3 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '3',
                    'loginpeoples' => $DevicebootAndloginuserByfield_3_3['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_3_3['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_3_3);
            $data_userByfield_4_4 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '4',
                    'loginpeoples' => $DevicebootAndloginuserByfield_4_4['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_4_4['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_4_4);
            $data_userByfield_5_5 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '5',
                    'loginpeoples' => $DevicebootAndloginuserByfield_5_5['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_5_5['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_5_5);
            $data_userByfield_6_10 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '6-10',
                    'loginpeoples' => $DevicebootAndloginuserByfield_6_10['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_6_10['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_6_10);
            $data_userByfield_10_above = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'counts' => '10+',
                    'loginpeoples' => $DevicebootAndloginuserByfield_10_above['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_10_above['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_10_above);

            //razor_sum_basic_start_login_peoplesbytime
            for ($t=0; $t < 24; $t++) { 
                $DevicebootAndloginuserBytime=$this->getDevicebootAndloginuserBytime($countdate, $paramspcRow->appId, $paramspcRow->chId,'all',$t,$t+1);
                ${'data_userBytime'.$t} = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' =>  $paramspcRow->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'timefield' => $t.'时',
                    'loginpeoples' => $DevicebootAndloginuserBytime['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserBytime['devicecount']
                );
                $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbytime', ${'data_userBytime'.$t});
            }
            $paramspcRow = $paramspc->next_row();
        }
        $paramsp = $this->product->getProductOffChannelVersion();
        $paramspRow = $paramsp->first_row();
        for ($i = 0; $i < $paramsp->num_rows(); $i++) {
            $DevicebootAndlogincount=$this->getDevicebootAndlogincount($countdate, $paramspRow->appId,'all','all');
            $DevicebootAndloginuserByfield_1_1=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',1,1);
            $DevicebootAndloginuserByfield_2_2=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',2,2);
            $DevicebootAndloginuserByfield_3_3=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',3,3);
            $DevicebootAndloginuserByfield_4_4=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',4,4);
            $DevicebootAndloginuserByfield_5_5=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',5,5);
            $DevicebootAndloginuserByfield_6_10=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',6,10);
            $DevicebootAndloginuserByfield_10_above=$this->getDevicebootAndloginuserByfield($countdate, $paramspRow->appId,'all','all',11,100000000);
            
            //razor_sum_basic_start_login_times
            $data_count = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'starttimes' => $DevicebootAndlogincount['deviceboot_count'],
                    'logintimes' => $DevicebootAndlogincount['login_count']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_times', $data_count);

            //razor_sum_basic_start_login_peoplesbycount
            $data_userByfield_1_1 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '1',
                    'loginpeoples' => $DevicebootAndloginuserByfield_1_1['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_1_1['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_1_1);
            $data_userByfield_2_2 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '2',
                    'loginpeoples' => $DevicebootAndloginuserByfield_2_2['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_2_2['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_2_2);
            $data_userByfield_3_3 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '3',
                    'loginpeoples' => $DevicebootAndloginuserByfield_3_3['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_3_3['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_3_3);
            $data_userByfield_4_4 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '4',
                    'loginpeoples' => $DevicebootAndloginuserByfield_4_4['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_4_4['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_4_4);
            $data_userByfield_5_5 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '5',
                    'loginpeoples' => $DevicebootAndloginuserByfield_5_5['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_5_5['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_5_5);
            $data_userByfield_6_10 = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '6-10',
                    'loginpeoples' => $DevicebootAndloginuserByfield_6_10['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_6_10['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_6_10);
            $data_userByfield_10_above = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'counts' => '10+',
                    'loginpeoples' => $DevicebootAndloginuserByfield_10_above['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserByfield_10_above['devicecount']
            );
            $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbycount', $data_userByfield_10_above);

            //razor_sum_basic_start_login_peoplesbytime
            for ($t=0; $t < 24; $t++) { 
                $DevicebootAndloginuserBytime=$this->getDevicebootAndloginuserBytime($countdate, $paramspRow->appId,'all','all',$t,$t+1);
                ${'data_userBytime'.$t} = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramspRow->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'timefield' => $t.'时',
                    'loginpeoples' => $DevicebootAndloginuserBytime['loginuser'],
                    'startpeoples' => $DevicebootAndloginuserBytime['devicecount']
                );
                $dwdb->insert_or_update('razor_sum_basic_start_login_peoplesbytime', ${'data_userBytime'.$t});
            }
            $paramspRow = $paramsp->next_row();
        }
    }
}
