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
 * Propanalymodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Propanalymodel extends CI_Model {

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
    
    function getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type,$propType){
        $list = array();
        if(isset($propType)){
            $query = $this->PropanalyData($fromTime,$toTime,$channel,$server,$version,$type,$propType);
        }
        else{
            $query = $this->PropanalyData($fromTime,$toTime,$channel,$server,$version,$type);
        }
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['prop_name'] = $dauUsersRow->prop_name;
            $fRow['prop_type'] = $dauUsersRow->prop_type;
            $fRow['shopbuy'] = $dauUsersRow->shopbuy;
            $fRow['shopbuyuser'] = $dauUsersRow->shopbuyuser;
            $fRow['systemdonate'] = $dauUsersRow->systemdonate;
            $fRow['systemdonateuser'] = $dauUsersRow->systemdonateuser;
            $fRow['functiongaincount'] = $dauUsersRow->functiongaincount;
            $fRow['functiongaincountuser'] = $dauUsersRow->functiongaincountuser;
            $fRow['activitygaincount'] = $dauUsersRow->activitygaincount;
            $fRow['activitygaincountuser'] = $dauUsersRow->activitygaincountuser;
            $fRow['totalgaincount'] = $dauUsersRow->totalgaincount;
            $fRow['totalconsumecount'] = $dauUsersRow->totalconsumecount;
            $fRow['totalrate'] = round((($dauUsersRow->totalconsumecount)/($dauUsersRow->totalgaincount)),2);
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function PropanalyData($fromTime,$toTime,$channel,$server,$version,$type,$propType)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        if(isset($propType) && $propType != '全部'){
            $sql = "SELECT
                    IFNULL(prop_name, 0) prop_name,
                    IFNULL(prop_type, 0) prop_type,
                    IFNULL(SUM(shopbuy), 0) shopbuy,
                    IFNULL(SUM(shopbuyuser), 0) shopbuyuser,
                    IFNULL(SUM(systemdonate), 0) systemdonate,
                    IFNULL(SUM(systemdonateuser), 0) systemdonateuser,
                    IFNULL(SUM(functiongaincount), 0) functiongaincount,
                    IFNULL(SUM(functiongaincountuser), 0) functiongaincountuser,
                    IFNULL(SUM(activitygaincount), 0) activitygaincount,
                    IFNULL(SUM(activitygaincountuser), 0) activitygaincountuser,
                    IFNULL(SUM(totalgaincount), 0) totalgaincount,
                    IFNULL(SUM(totalconsumecount), 0) totalconsumecount
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND type = '" . $type . "'
                AND prop_type = '" . $propType . "'
                Group By prop_name
                Order By rid ASC";
        }
        else{
            $sql = "SELECT
                    IFNULL(prop_name, 0) prop_name,
                    IFNULL(prop_type, 0) prop_type,
                    IFNULL(SUM(shopbuy), 0) shopbuy,
                    IFNULL(SUM(shopbuyuser), 0) shopbuyuser,
                    IFNULL(SUM(systemdonate), 0) systemdonate,
                    IFNULL(SUM(systemdonateuser), 0) systemdonateuser,
                    IFNULL(SUM(functiongaincount), 0) functiongaincount,
                    IFNULL(SUM(functiongaincountuser), 0) functiongaincountuser,
                    IFNULL(SUM(activitygaincount), 0) activitygaincount,
                    IFNULL(SUM(activitygaincountuser), 0) activitygaincountuser,
                    IFNULL(SUM(totalgaincount), 0) totalgaincount,
                    IFNULL(SUM(totalconsumecount), 0) totalconsumecount
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND type = '" . $type . "'
                Group By prop_name
                Order By rid ASC";
        }
        $query = $dwdb->query($sql);
        return $query;
    }

    function gettotalCGByDay($fromTime,$toTime,$channel,$server,$version,$name,$type){
        $list = array();
        $query = $this->totalCGByDay($fromTime,$toTime,$channel,$server,$version,$name,$type);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
            $fRow['totalgaincount'] = $dauUsersRow->totalgaincount;
            $fRow['totalconsumecount'] = $dauUsersRow->totalconsumecount;
            $fRow['propcgrate'] = round(($dauUsersRow->totalconsumecount)/($dauUsersRow->totalgaincount),2);
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function totalCGByDay($fromTime,$toTime,$channel,$server,$version,$name,$type)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(startdate_sk, 0) startdate_sk,
                    IFNULL(totalgaincount, 0) totalgaincount,
                    IFNULL(totalconsumecount, 0) totalconsumecount
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND prop_name = '" . $name . "'
                AND type = '" . $type . "'
                Order By startdate_sk DESC";
        $query = $dwdb->query($sql);
        return $query;
    }

    function getOutputDistrbute($fromTime,$toTime,$channel,$server,$version,$name,$type,$product_type){
        $list = array();
        $query = $this->OutputDistrbute($fromTime,$toTime,$channel,$server,$version,$name,$type,$product_type);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['action_name'] = $dauUsersRow->action_name;
            $fRow['prop_count'] = $dauUsersRow->prop_count;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function OutputDistrbute($fromTime,$toTime,$channel,$server,$version,$name,$type,$product_type)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(action_name, 0) action_name,
                    IFNULL(SUM(prop_count), 0) prop_count
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly_gainconsume') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND prop_name = '" . $name . "'
                AND type = '" . $type . "'
                AND product_type = '" . $product_type . "'
                Group By action_name
                Order By rid ASC";
        $query = $dwdb->query($sql);
        return $query;
    }

    function getPeopleCountData($fromTime,$toTime,$channel,$server,$version,$name,$type){
        $list = array();
        $query = $this->PeopleCountData($fromTime,$toTime,$channel,$server,$version,$name,$type);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
            $fRow['shopbuyuser'] = $dauUsersRow->shopbuyuser;
            $fRow['systemdonateuser'] = $dauUsersRow->systemdonateuser;
            $fRow['functiongaincountuser'] = $dauUsersRow->functiongaincountuser;
            $fRow['activitygaincountuser'] = $dauUsersRow->activitygaincountuser;
            $fRow['shopbuy'] = $dauUsersRow->shopbuy;
            $fRow['systemdonate'] = $dauUsersRow->systemdonate;
            $fRow['functiongaincount'] = $dauUsersRow->functiongaincount;
            $fRow['activitygaincount'] = $dauUsersRow->activitygaincount;
            $fRow['shopbuyrate'] = round(($dauUsersRow->shopbuy)/($dauUsersRow->shopbuyuser),2);
            $fRow['systemdonaterate'] = round(($dauUsersRow->systemdonate)/($dauUsersRow->systemdonateuser),2);
            $fRow['functiongaincountrate'] = round(($dauUsersRow->functiongaincount)/($dauUsersRow->functiongaincountuser),2);
            $fRow['activitygaincountrate'] = round(($dauUsersRow->activitygaincount)/($dauUsersRow->activitygaincountuser),2);
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function PeopleCountData($fromTime,$toTime,$channel,$server,$version,$name,$type)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(startdate_sk, 0) startdate_sk,
                    IFNULL(shopbuyuser, 0) shopbuyuser,
                    IFNULL(systemdonateuser, 0) systemdonateuser,
                    IFNULL(functiongaincountuser, 0) functiongaincountuser,
                    IFNULL(activitygaincountuser, 0) activitygaincountuser,
                    IFNULL(shopbuy, 0) shopbuy,
                    IFNULL(systemdonate, 0) systemdonate,
                    IFNULL(functiongaincount, 0) functiongaincount,
                    IFNULL(activitygaincount, 0) activitygaincount
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND prop_name = '" . $name . "'
                AND type = '" . $type . "'
                Order By startdate_sk DESC";
        $query = $dwdb->query($sql);
        return $query;
    }

    function getVipuserData($fromTime,$toTime,$channel,$server,$version,$type,$prop_name,$action_type){
        $list = array();
        $query = $this->VipuserData($fromTime,$toTime,$channel,$server,$version,$type,$prop_name,$action_type);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['viplevel'] = $dauUsersRow->viplevel;
            $fRow['rolecount'] = $dauUsersRow->rolecount;
            $fRow['propgaincount'] = $dauUsersRow->propgaincount;
            $fRow['propconsumecount'] = $dauUsersRow->propconsumecount;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function VipuserData($fromTime,$toTime,$channel,$server,$version,$type,$prop_name,$action_type)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(viplevel, 0) viplevel,
                    IFNULL(SUM(rolecount), 0) rolecount,
                    IFNULL(SUM(propgaincount), 0) propgaincount,
                    IFNULL(SUM(propconsumecount), 0) propconsumecount
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly_vipuser') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                AND type = '" . $type . "'
                AND prop_name = '" . $prop_name . "'
                AND action_type = '" . $action_type . "'
                Group By viplevel
                Order By viplevel ASC";
        $query = $dwdb->query($sql);
        return $query;
    }
    function getPropClassify($fromTime,$toTime,$channel,$server,$version)
    {
        $list = array();
        $query = $this->PropClassify($fromTime,$toTime,$channel,$server,$version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow['prop_type'] = $dauUsersRow->prop_type;
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }
    function PropClassify($fromTime,$toTime,$channel,$server,$version)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId= $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
        ($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
        ($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
        $sql = "SELECT
                    IFNULL(prop_type, 0) prop_type
                FROM
                    " . $dwdb->dbprefix('sum_basic_propanaly') . "
                WHERE
                product_id = $productId
                AND channel_name in('" . $channel_list . "')
                AND version_name in('" . $version_list . "')
                AND server_name in('" . $server_list . "')
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
                Group By prop_type
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
     * getPropdataBynewuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropdataBynewuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                         #   AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                         #   AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                              #  AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                              #  AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                 #   AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                 #   AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                      #  AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                      #  AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                          #  AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                               # AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                  #  AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                       # AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                         #   AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                              #  AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                 #   AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                      #  AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                       #     AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                            #    AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
               #     AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                    #    AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                          #  AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_createrole lgi
                                WHERE
                                    lgi.create_role_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                               # AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                  #  AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_createrole lgi
                        WHERE
                            lgi.create_role_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                       # AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getPropdataBydauuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropdataBydauuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                         #   AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                         #   AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                              #  AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                              #  AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                 #   AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                 #   AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                      #  AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                      #  AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                          #  AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                               # AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                  #  AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                       # AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                         #   AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                              #  AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                 #   AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                      #  AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                       #     AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                            #    AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
               #     AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                    #    AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                          #  AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_login lgi
                                WHERE
                                    lgi.login_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                               # AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                  #  AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_login lgi
                        WHERE
                            lgi.login_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                       # AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


    /**
     * getPropdataBypayuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropdataBypayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                         #   AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                         #   AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                              #  AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                              #  AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                 #   AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                 #   AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                      #  AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                      #  AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                          #  AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                          #  AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                               # AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                               # AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                  #  AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                  #  AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                       # AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                       # AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                            AND pg.srvId = '$serverid'
                         #   AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                                AND lgi.srvId = '$serverid'
                              #  AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                    AND pg.srvId = '$serverid'
                 #   AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                        AND lgi.srvId = '$serverid'
                      #  AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                       #     AND pg.srvId = '$serverid'
                            AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                            #    AND lgi.srvId = '$serverid'
                                AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
               #     AND pg.srvId = '$serverid'
                    AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                    #    AND lgi.srvId = '$serverid'
                        AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                    mcppg1.prop_name,
                    mcppg1.prop_category,
                    mcppg1.shopbuy,
                    mcppg1.shopbuyuser,
                    mcppg1.systemdonate,
                    mcppg1.systemdonateuser,
                    mcppg1.functiongaincount,
                    mcppg1.functiongaincountuser,
                    mcppg1.activitygaincount,
                    mcppg1.activitygaincountuser,
                    mcppg1.totalgaincount,
                    IFNULL(pg2.totalconsumecount, 0) totalconsumecount
                FROM
                    (
                        SELECT
                            *
                        FROM
                            (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1
                        LEFT JOIN (
                            SELECT
                                pg.propid,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '001' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) shopbuy,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '001' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) shopbuyuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '002' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) systemdonate,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '002' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) systemdonateuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '003' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) functiongaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '003' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) functiongaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid = '004' THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) activitygaincount,
                                COUNT(
                                    DISTINCT CASE
                                    WHEN pg.acionttypeid = '004' THEN
                                        pg.roleId
                                    ELSE
                                        NULL
                                    END
                                ) activitygaincountuser,
                                IFNULL(
                                    SUM(
                                        CASE
                                        WHEN pg.acionttypeid IS NOT NULL THEN
                                            pg.propgain_count
                                        ELSE
                                            NULL
                                        END
                                    ),
                                    0
                                ) totalgaincount
                            FROM
                                razor_propgain pg
                            WHERE
                                pg.propgain_date = '$date'
                            AND pg.appId = '$appid'
                            AND pg.chId = '$channelid'
                          #  AND pg.srvId = '$serverid'
                          #  AND pg.version = '$versionname'
                            AND pg.roleId IN (
                                SELECT DISTINCT
                                    lgi.roleId
                                FROM
                                    razor_pay lgi
                                WHERE
                                    lgi.pay_date = '$date'
                                AND lgi.appId = '$appid'
                                AND lgi.chId = '$channelid'
                               # AND lgi.srvId = '$serverid'
                               # AND lgi.version = '$versionname'
                            )
                            GROUP BY
                                pg.propid
                        ) pg1 ON mcp1.prop_id = pg1.propid
                    ) mcppg1
                LEFT JOIN (
                    SELECT
                        pg.propid,
                        IFNULL(
                            SUM(
                                CASE
                                WHEN pg.acionttypeid IS NOT NULL THEN
                                    pg.propconsume_count
                                ELSE
                                    NULL
                                END
                            ),
                            0
                        ) totalconsumecount
                    FROM
                        razor_propconsume pg
                    WHERE
                        pg.propconsume_date = '$date'
                    AND pg.appId = '$appid'
                    AND pg.chId = '$channelid'
                  #  AND pg.srvId = '$serverid'
                  #  AND pg.version = '$versionname'
                    AND pg.roleId IN (
                        SELECT DISTINCT
                            lgi.roleId
                        FROM
                            razor_pay lgi
                        WHERE
                            lgi.pay_date = '$date'
                        AND lgi.appId = '$appid'
                        AND lgi.chId = '$channelid'
                       # AND lgi.srvId = '$serverid'
                       # AND lgi.version = '$versionname'
                    )
                    GROUP BY
                        pg.propid
                ) pg2 ON mcppg1.prop_id = pg2.propid
                WHERE
                    mcppg1.shopbuy IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


    /**
     * getPropgainconsumedataBynewuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropgainconsumedataBynewuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                   # AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                      #  AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                       # AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                          #  AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                   # AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                   # AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                      #  AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                      #  AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                       # AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                       # AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                          #  AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                          #  AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                  #  AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                     #   AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                      #  AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                         #   AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                  #  AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                  #  AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                     #   AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                     #   AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                      #  AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                      #  AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                         #   AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                         #   AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                 #   AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                    #    AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                     #   AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                        #    AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                   # AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                      #  AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                       # AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                          #  AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                  #  AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_createrole c
                                        WHERE
                                            c.create_role_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                     #   AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                      #  AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_createrole c
                                            WHERE
                                                c.create_role_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                         #   AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getPropgainconsumedataBydauuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropgainconsumedataBydauuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                   # AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                      #  AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                       # AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                          #  AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                   # AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                   # AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                      #  AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                      #  AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                       # AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                       # AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                          #  AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                          #  AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                  #  AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                     #   AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                      #  AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                         #   AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                  #  AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                  #  AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                     #   AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                     #   AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                      #  AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                      #  AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                         #   AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                         #   AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                 #   AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                    #    AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                     #   AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                        #    AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                   # AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                      #  AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                       # AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                          #  AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                  #  AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_login c
                                        WHERE
                                            c.login_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                     #   AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                      #  AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_login c
                                            WHERE
                                                c.login_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                         #   AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getPropgainconsumedataBypayuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropgainconsumedataBypayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                   # AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                      #  AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                       # AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                          #  AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                   # AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                   # AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                      #  AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                      #  AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                       # AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                       # AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                          #  AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                          #  AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                  #  AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                     #   AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                      #  AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                         #   AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                  #  AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                  #  AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                     #   AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                     #   AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                      #  AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                      #  AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                         #   AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                         #   AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                    AND pg.srvId = '$serverid'
                                 #   AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                        AND c.srvId = '$serverid'
                                    #    AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                        AND pg.srvId = '$serverid'
                                     #   AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                            AND c.srvId = '$serverid'
                                        #    AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                   # AND pg.srvId = '$serverid'
                                    AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                      #  AND c.srvId = '$serverid'
                                        AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                       # AND pg.srvId = '$serverid'
                                        AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                          #  AND c.srvId = '$serverid'
                                            AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(pgmcp.prop_name,'未知-配置表中不存在') prop_name,
                        pgmcp.tag,
                        IFNULL(mcf1.function_name,'未知-配置表中不存在') function_name,
                        pgmcp.consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        'gain' tag,
                                        pg.propid,
                                        pg.functionid,
                                        SUM(pg.propgain_count) consumecount
                                    FROM
                                        razor_propgain pg
                                    WHERE
                                        pg.propgain_date = '$date'
                                    AND pg.appId = '$appid'
                                    AND pg.chId = '$channelid'
                                  #  AND pg.srvId = '$serverid'
                                  #  AND pg.version = '$versionname'
                                    AND pg.roleId IN (
                                        SELECT DISTINCT
                                            c.roleId
                                        FROM
                                            razor_pay c
                                        WHERE
                                            c.pay_date = '$date'
                                        AND c.appId = '$appid'
                                        AND c.chId = '$channelid'
                                     #   AND c.srvId = '$serverid'
                                     #   AND c.version = '$versionname'
                                    )
                                    GROUP BY
                                        pg.propid,
                                        pg.functionid
                                    UNION ALL
                                        SELECT
                                            'consume' tag,
                                            pg.propid,
                                            pg.functionid,
                                            SUM(pg.propconsume_count) consumecount
                                        FROM
                                            razor_propconsume pg
                                        WHERE
                                            pg.propconsume_date = '$date'
                                        AND pg.appId = '$appid'
                                        AND pg.chId = '$channelid'
                                      #  AND pg.srvId = '$serverid'
                                      #  AND pg.version = '$versionname'
                                        AND pg.roleId IN (
                                            SELECT DISTINCT
                                                c.roleId
                                            FROM
                                                razor_pay c
                                            WHERE
                                                c.pay_date = '$date'
                                            AND c.appId = '$appid'
                                            AND c.chId = '$channelid'
                                         #   AND c.srvId = '$serverid'
                                         #   AND c.version = '$versionname'
                                        )
                                        GROUP BY
                                            pg.propid,
                                            pg.functionid
                                ) pg2
                            LEFT JOIN (
                                SELECT
                                    mcp.prop_id,
                                    mcp.prop_name
                                FROM
                                    razor_mainconfig_prop mcp
                                WHERE
                                    mcp.app_id = '$appid'
                            ) mcp1 ON pg2.propid = mcp1.prop_id
                        ) pgmcp
                    LEFT JOIN (
                        SELECT DISTINCT
                            mcf.function_id,
                            mcf.function_name
                        FROM
                            razor_mainconfig_function mcf
                        WHERE
                            mcf.app_id = '$appid'
                    ) mcf1 ON pgmcp.functionid = mcf1.function_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getPropvipdataBynewuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropvipdataBynewuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                              #  AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                   # AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                      #  AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                           # AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                           #     AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                #    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                   #     AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                        #    AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                        lgi.create_role_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                                lgi.create_role_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getPropvipdataBydauuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropvipdataBydauuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                              #  AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                   # AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                      #  AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                           # AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                           #     AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                #    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                   #     AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                        #    AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                        lgi.login_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                                lgi.login_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getPropvipdataBypayuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getPropvipdataBypayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                              #  AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                   # AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                      #  AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                           # AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                             #   AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                  #  AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                     #   AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                          #  AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                           #     AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                #    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                   #     AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                        #    AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                        mcppg1.acionttypeid,
                        mcppg1.roleVip,
                        mcppg1.rolecount,
                        IFNULL(mcppg1.gaincount, 0) gaincount,
                        IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                             #   AND pg.srvId = '$serverid'
                             #   AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                        lgi.pay_date = '$date'
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                  #  AND lgi.srvId = '$serverid'
                                  #  AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.acionttypeid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip,
                            IFNULL(
                                SUM(pg.propconsume_count),
                                0
                            ) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                     #   AND pg.srvId = '$serverid'
                     #   AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                                lgi.pay_date = '$date'
                            AND lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                          #  AND lgi.srvId = '$serverid'
                          #  AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.acionttypeid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    AND mcppg1.acionttypeid = pg2.acionttypeid
                    WHERE
                        mcppg1.propid IS NOT NULL";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getProptotalvipdataBydauuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getProptotalvipdataBynewuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_createrole lgi
                                    WHERE
                                    lgi.create_role_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_createrole lgi
                            WHERE
                             lgi.create_role_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

        /**
     * getProptotalvipdataBydauuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getProptotalvipdataBydauuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_login lgi
                                    WHERE
                                    lgi.login_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_login lgi
                            WHERE
                             lgi.login_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

        /**
     * getProptotalvipdataBydauuser function
     * get Function Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Data
     */
    function getProptotalvipdataBypayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                               # AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    #AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                       # AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            #AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                        AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                        AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcppg1.prop_name,
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) roleVip,
                    IF (
                        IFNULL(pg2.rolecount, 0) > IFNULL(mcppg1.rolecount, 0),
                        IFNULL(pg2.rolecount, 0),
                        IFNULL(mcppg1.rolecount, 0)
                    ) rolecount,
                     IFNULL(mcppg1.gaincount, 0) gaincount,
                     IFNULL(pg2.consumecount, 0) consumecount
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_prop mcp
                                    WHERE
                                        mcp.app_id = '$appid'
                                ) mcp1
                            LEFT JOIN (
                                SELECT
                                    pg.propid,
                                    pg.roleVip,
                                    COUNT(DISTINCT pg.roleId) rolecount,
                                    SUM(pg.propgain_count) gaincount
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                               # AND pg.srvId = '$serverid'
                               # AND pg.version = '$versionname'
                                AND pg.roleId IN (
                                    SELECT DISTINCT
                                        lgi.roleId
                                    FROM
                                        razor_pay lgi
                                    WHERE
                                    lgi.pay_date = '$date'    
                                    AND lgi.appId = '$appid'
                                    AND lgi.chId = '$channelid'
                                    #AND lgi.srvId = '$serverid'
                                    #AND lgi.version = '$versionname'
                                )
                                GROUP BY
                                    pg.propid,
                                    pg.roleVip
                            ) pg1 ON mcp1.prop_id = pg1.propid
                        ) mcppg1
                    LEFT JOIN (
                        SELECT
                            pg.propid,
                            pg.roleVip,
                            COUNT(DISTINCT pg.roleId) rolecount,
                            SUM(pg.propconsume_count) consumecount
                        FROM
                            razor_propconsume pg
                        WHERE
                            pg.propconsume_date = '$date'
                        AND pg.appId = '$appid'
                        AND pg.chId = '$channelid'
                       # AND pg.srvId = '$serverid'
                       # AND pg.version = '$versionname'
                        AND pg.roleId IN (
                            SELECT DISTINCT
                                lgi.roleId
                            FROM
                                razor_pay lgi
                            WHERE
                             lgi.pay_date = '$date' 
                            AND  lgi.appId = '$appid'
                            AND lgi.chId = '$channelid'
                            #AND lgi.srvId = '$serverid'
                            #AND lgi.version = '$versionname'
                        )
                        GROUP BY
                            pg.propid,
                            pg.roleVip
                    ) pg2 ON mcppg1.prop_id = pg2.propid
                    WHERE
                    IF (
                        pg2.roleVip IS NULL,
                        mcppg1.roleVip,
                        pg2.roleVip
                    ) IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }



    /**
     * sum_basic_propanaly function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_propanaly($countdate) {
        $dwdb = $this->load->database('dw', true);
        
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }
            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

             foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);


            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            
            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');


            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            
            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);


            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_pcv = $params_pcv->next_row();
        }
        $params_pc = $this->product->getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $PropdataBynewuser= $this->getPropdataBynewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropdataBydauuser= $this->getPropdataBydauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropdataBypayuser= $this->getPropdataBypayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropvipdataBynewuser= $this->getPropvipdataBynewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropvipdataBydauuser= $this->getPropvipdataBydauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropvipdataBypayuser= $this->getPropvipdataBypayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            $PropgainconsumedataBynewuser= $this->getPropgainconsumedataBynewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropgainconsumedataBydauuser= $this->getPropgainconsumedataBydauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $PropgainconsumedataBypayuser= $this->getPropgainconsumedataBypayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            $ProptotalvipdataBynewuser= $this->getProptotalvipdataBynewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $ProptotalvipdataBydauuser= $this->getProptotalvipdataBydauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $ProptotalvipdataBypayuser= $this->getProptotalvipdataBypayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');


            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            foreach ($PropdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount

                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'prop_type' => $row->prop_category,
                    'shopbuy' => $row->shopbuy,
                    'shopbuyuser' => $row->shopbuyuser,
                    'systemdonate' => $row->systemdonate,
                    'systemdonateuser' => $row->systemdonateuser,
                    'functiongaincount' => $row->functiongaincount,
                    'functiongaincountuser' => $row->functiongaincountuser,
                    'activitygaincount' => $row->activitygaincount,
                    'activitygaincountuser' => $row->activitygaincountuser,
                    'totalgaincount' => $row->totalgaincount,
                    'totalconsumecount' => $row->totalconsumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly', $data);
            }
            foreach ($PropvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($PropvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => $row->acionttypeid,
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }

            foreach ($PropgainconsumedataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($PropgainconsumedataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'product_type' => $row->tag,
                    'action_name' => $row->function_name,
                    'prop_count' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_gainconsume', $data);
            }

            foreach ($ProptotalvipdataBynewuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'newuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBydauuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'dauuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            foreach ($ProptotalvipdataBypayuser->result() as $row)
            {
                $data = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'type' => 'payuser',
                    'prop_name' => $row->prop_name,
                    'action_type' => 'total',
                    'viplevel' => $row->roleVip,
                    'rolecount' => $row->rolecount,
                    'propgaincount' => $row->gaincount,
                    'propconsumecount' => $row->consumecount
                );
                $dwdb->insert_or_update('razor_sum_basic_propanaly_vipuser', $data);
            }
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
