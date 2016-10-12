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
 * Virtualmoneyanalysismodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Virtualmoneyanalysismodel extends CI_Model {

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
    
    function getVirtualmoneyanalysis($fromTime,$toTime,$channel,$server,$version){
        $list = array();
        $query = $this->virtualmoneyanalysis($fromTime,$toTime,$channel,$server,$version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['virtualmoney'] = $dauUsersRow->virtualmoney;
            $fRow['virtualmoney_output'] = $dauUsersRow->virtualmoney_output;
            $fRow['virtualmoney_consume'] = $dauUsersRow->virtualmoney_consume;
            $fRow['virtualmoney_outputuser'] = $dauUsersRow->virtualmoney_outputuser;
            $fRow['virtualmoney_consumeuser'] = $dauUsersRow->virtualmoney_consumeuser;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function virtualmoneyanalysis($fromTime,$toTime,$channel,$server,$version)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(virtualmoney, 0) virtualmoney,
                    IFNULL(SUM(virtualmoney_output), 0) virtualmoney_output,
                    IFNULL(SUM(virtualmoney_consume), 0) virtualmoney_consume,
                    IFNULL(SUM(virtualmoney_outputuser), 0) virtualmoney_outputuser,
                    IFNULL(SUM(virtualmoney_consumeuser), 0) virtualmoney_consumeuser
                FROM
                    " . $dwdb->dbprefix('sum_basic_sa_virtualmoney') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                Group By virtualmoney
                Order By rid DESC";
        $query = $dwdb->query($sql);
        return $query;
    }

    function getVirtualmoneyanalysis1($fromTime,$toTime,$channel,$server,$version,$name){
        $list = array();
        $query = $this->virtualmoneyanalysis1($fromTime,$toTime,$channel,$server,$version,$name);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['enddate_sk'] = $dauUsersRow->enddate_sk;
            $fRow['virtualmoney'] = $dauUsersRow->virtualmoney;
            $fRow['virtualmoney_output'] = $dauUsersRow->virtualmoney_output;
            $fRow['virtualmoney_consume'] = $dauUsersRow->virtualmoney_consume;
            $fRow['virtualmoney_outputuser'] = $dauUsersRow->virtualmoney_outputuser;
            $fRow['virtualmoney_consumeuser'] = $dauUsersRow->virtualmoney_consumeuser;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function virtualmoneyanalysis1($fromTime,$toTime,$channel,$server,$version,$name)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(enddate_sk, 0) enddate_sk,
                    IFNULL(virtualmoney, 0) virtualmoney,
                    IFNULL(virtualmoney_output, 0) virtualmoney_output,
                    IFNULL(virtualmoney_consume, 0) virtualmoney_consume,
                    IFNULL(virtualmoney_outputuser, 0) virtualmoney_outputuser,
                    IFNULL(virtualmoney_consumeuser, 0) virtualmoney_consumeuser
                FROM
                    " . $dwdb->dbprefix('sum_basic_sa_virtualmoney') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND virtualmoney = '" . $name . "'
                Order By enddate_sk DESC";
        $query = $dwdb->query($sql);
        return $query;
    }
    function getVirtualmoneyanalysis2($fromTime,$toTime,$channel,$server,$version,$name,$type){
        $list = array();
        $query = $this->virtualmoneyanalysis2($fromTime,$toTime,$channel,$server,$version,$name,$type);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['virtualmoney_outputway'] = $dauUsersRow->virtualmoney_outputway;
            $fRow['virtualmoney_outputid'] = $dauUsersRow->virtualmoney_outputid;
            $fRow['virtualmoney_output'] = $dauUsersRow->virtualmoney_output;
            $fRow['virtualmoney_outputrate'] = $dauUsersRow->virtualmoney_outputrate;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function virtualmoneyanalysis2($fromTime,$toTime,$channel,$server,$version,$name,$type)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(virtualmoney_outputway, 0) virtualmoney_outputway,
                    IFNULL(virtualmoney_outputid, 0) virtualmoney_outputid,
                    IFNULL(SUM(virtualmoney_output), 0) virtualmoney_output,
                    IFNULL(SUM(virtualmoney_outputrate), 0) virtualmoney_outputrate
                FROM
                    " . $dwdb->dbprefix('sum_basic_sa_virtualmoney_outputway') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND type = '" . $type . "'
                AND virtualmoney = '" . $name . "'
                AND virtualmoney_outputway <> '-1'
                Group By virtualmoney_outputway
                Order By rid ASC";
        $query = $dwdb->query($sql);
        return $query;
    }
    function getVirtualmoneyanalysisDetail($fromTime,$toTime,$channel,$server,$version,$name){
        $list = array();
        $query = $this->VirtualmoneyanalysisDetail($fromTime,$toTime,$channel,$server,$version,$name);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['virtualmoney_viplevel'] = $dauUsersRow->virtualmoney_viplevel;
            $fRow['virtualmoney_usercount'] = $dauUsersRow->virtualmoney_usercount;
            $fRow['virtualmoney_outputcount'] = $dauUsersRow->virtualmoney_outputcount;
            $fRow['virtualmoney_consumecount'] = $dauUsersRow->virtualmoney_consumecount;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }
    function VirtualmoneyanalysisDetail($fromTime,$toTime,$channel,$server,$version,$name)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(virtualmoney_viplevel, 0) virtualmoney_viplevel,
                    IFNULL(SUM(virtualmoney_usercount), 0) virtualmoney_usercount,
                    IFNULL(SUM(virtualmoney_outputcount), 0) virtualmoney_outputcount,
                    IFNULL(SUM(virtualmoney_consumecount), 0) virtualmoney_consumecount
                FROM
                    " . $dwdb->dbprefix('sum_basic_sa_virtualmoney_viplevel') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND enddate_sk = '" . $name . "'
                AND virtualmoney_viplevel <> '-1'
                Group By virtualmoney_viplevel
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
     * getProperCoin function
     * get ProperCoin
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array ProperCoin info
     */
    function getProperCoin($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.gain, 0) gain,
                        IFNULL(pc.consume, 0) consume,
                        IFNULL(pc.gainuser, 0) gainuser,
                        IFNULL(pc.consumeuser, 0) consumeuser
                    FROM
                        (
                            SELECT
                                c.property,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) gain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) consume,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) gainuser,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.roleId
                                    ELSE
                                        NULL
                                    END
                                ) consumeuser
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getProperCoinFunction function
     * get ProperCoin Function
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array ProperCoin Function
     */
    function getProperCoinFunction($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                   #AND c.srvId = '$serverid'
                                   #AND c.version = '$versionname'
                                    AND c.property_variation = 1
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

        /**
     * getProperCoinFunction function
     * get ProperCoin Function
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array ProperCoin Function
     */
    function getProperCoinFunctionConsume($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pmc.functionid function_id,
                        IFNULL(
                            pmc.propertymoney_name,
                            '未知-配置表中不存在'
                        ) propertymoney_name,
                        IFNULL(
                            mf.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        pmc.gain
                    FROM
                        (
                            SELECT
                                pm.propertymoney_name,
                                pc.actionid,
                                pc.functionid,
                                IFNULL(pc.gain, 0) gain
                            FROM
                                (
                                    SELECT
                                        c.property,
                                        c.actionid,
                                        c.functionid,
                                        SUM(c.count) gain
                                    FROM
                                        razor_propertyvariation c
                                    WHERE
                                        c.propertyvariation_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    AND c.property_variation = 0
                                    GROUP BY
                                        c.property,
                                        c.actionid
                                ) pc
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_propertymoney
                                WHERE
                                    app_id = '$appid'
                            ) pm ON pc.property = pm.propertymoney_id
                        ) pmc
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function
                        WHERE
                            app_id = '$appid'
                    ) mf ON pmc.actionid = mf.action_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getProperCoinVip function
     * get ProperCoin Vip
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array ProperCoin Vip
     */
    function getProperCoinVip($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pm.propertymoney_name,
                        IFNULL(pc.roleVip, '-1') roleVip,
                        IFNULL(pc.vipuser, 0) vipuser,
                        IFNULL(pc.vipgain, 0) vipgain,
                        IFNULL(pc.vipconsume, 0) vipconsume
                    FROM
                        (
                            SELECT
                                c.property,
                                c.roleVip,
                                COUNT(DISTINCT c.roleId) vipuser,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 1 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipgain,
                                SUM(
                                    CASE
                                    WHEN c.property_variation = 0 THEN
                                        c.count
                                    ELSE
                                        NULL
                                    END
                                ) vipconsume
                            FROM
                                razor_propertyvariation c
                            WHERE
                                c.propertyvariation_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            GROUP BY
                                c.property,
                                c.roleVip
                        ) pc
                    RIGHT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_propertymoney
                        WHERE
                            app_id = '$appid'
                    ) pm ON pc.property = pm.propertymoney_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


    /**
     * sum_basic_sa_virtualmoney function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_sa_virtualmoney($countdate) {
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_pcv = $params_pcv->next_row();
        }
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $ProperCoin=$this->getProperCoin($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $ProperCoinFunction=$this->getProperCoinFunction($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $ProperCoinFunctionConsume=$this->getProperCoinFunctionConsume($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $ProperCoinVip=$this->getProperCoinVip($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $paramsRow_t=$ProperCoin->first_row();
            for ($j=0; $j < $ProperCoin->num_rows() ; $j++) { 
                $data_propercoin = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_t->propertymoney_name,
                    'virtualmoney_output' => $paramsRow_t->gain,
                    'virtualmoney_consume' => $paramsRow_t->consume,
                    'virtualmoney_outputuser' => $paramsRow_t->gainuser,
                    'virtualmoney_consumeuser' => $paramsRow_t->consumeuser
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney', $data_propercoin);
                $paramsRow_t = $ProperCoin->next_row();
            }

            $paramsRow_s=$ProperCoinFunction->first_row();
            for ($s=0; $s < $ProperCoinFunction->num_rows() ; $s++) {
                $data_propercoinfunction = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_s->propertymoney_name,
                    'type' => 'gain',
                    'virtualmoney_outputid' => $paramsRow_s->function_id,
                    'virtualmoney_outputway' => $paramsRow_s->function_name,
                    'virtualmoney_output' => $paramsRow_s->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinfunction);
                $paramsRow_s = $ProperCoinFunction->next_row();
            }

            $paramsRow_c=$ProperCoinFunctionConsume->first_row();
            for ($s=0; $s < $ProperCoinFunctionConsume->num_rows() ; $s++) {
                $data_propercoinconsume = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_c->propertymoney_name,
                    'type' => 'consume',
                    'virtualmoney_outputid' => $paramsRow_c->function_id,
                    'virtualmoney_outputway' => $paramsRow_c->function_name,
                    'virtualmoney_output' => $paramsRow_c->gain,
                    'virtualmoney_outputrate' => 0
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_outputway', $data_propercoinconsume);
                $paramsRow_c = $ProperCoinFunctionConsume->next_row();
            }

            $paramsRow_v=$ProperCoinVip->first_row();
            for ($s=0; $s < $ProperCoinVip->num_rows() ; $s++) {
                $data_propercoinvip = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'virtualmoney' => $paramsRow_v->propertymoney_name,
                    'virtualmoney_viplevel' => $paramsRow_v->roleVip,
                    'virtualmoney_usercount' => $paramsRow_v->vipuser,
                    'virtualmoney_outputcount' => $paramsRow_v->vipgain,
                    'virtualmoney_consumecount' => $paramsRow_v->vipconsume
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_virtualmoney_viplevel', $data_propercoinvip);
                $paramsRow_v = $ProperCoinVip->next_row();
            }
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
