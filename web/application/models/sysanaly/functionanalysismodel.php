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
 * Functionanalysismodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Functionanalysismodel extends CI_Model {

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
    
	function getFunctionanalysis($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->Functionanalysis($fromTime,$toTime,$channel,$server,$version);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['function'] = $dauUsersRow->function;
			$fRow['function_useuser'] = $dauUsersRow->function_useuser;
			$fRow['function_usecount'] = $dauUsersRow->function_usecount;
			$fRow['function_userate'] = $dauUsersRow->function_userate;
			$fRow['function_goldoutput'] = $dauUsersRow->function_goldoutput;
			$fRow['function_goldoutputrate'] = $dauUsersRow->function_goldoutputrate;
			$fRow['function_goldconsume'] = $dauUsersRow->function_goldconsume;
			$fRow['function_goldconsumerate'] = $dauUsersRow->function_goldconsumerate;
			$fRow['function_sliveroutput'] = $dauUsersRow->function_sliveroutput;
			$fRow['function_sliveroutputrate'] = $dauUsersRow->function_sliveroutputrate;
			$fRow['function_sliverconsume'] = $dauUsersRow->function_sliverconsume;
			$fRow['function_sliverconsumerate'] = $dauUsersRow->function_sliverconsumerate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function Functionanalysis($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(function, 0) function,
					IFNULL(SUM(function_useuser), 0) function_useuser,
					IFNULL(SUM(function_usecount), 0) function_usecount,
					IFNULL(AVG(function_userate), 0) function_userate,
					IFNULL(SUM(function_goldoutput), 0) function_goldoutput,
					IFNULL(AVG(function_goldoutputrate), 0) function_goldoutputrate,
					IFNULL(SUM(function_goldconsume), 0) function_goldconsume,
					IFNULL(AVG(function_goldconsumerate), 0) function_goldconsumerate,
					IFNULL(SUM(function_sliveroutput), 0) function_sliveroutput,
					IFNULL(AVG(function_sliveroutputrate), 0) function_sliveroutputrate,
					IFNULL(SUM(function_sliverconsume), 0) function_sliverconsume,
					IFNULL(AVG(function_sliverconsumerate), 0) function_sliverconsumerate
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_function') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				Group By function
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getFunctionanalysisData($fromTime,$toTime,$channel,$server,$version,$function,$type){
		$list = array();
		$query = $this->FunctionanalysisData($fromTime,$toTime,$channel,$server,$version,$function,$type);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['prop_id'] = $dauUsersRow->prop_id;
			$fRow['prop_name'] = $dauUsersRow->prop_name;
			$fRow['prop_type'] = $dauUsersRow->prop_type;
			$fRow['prop_count'] = $dauUsersRow->prop_count;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function FunctionanalysisData($fromTime,$toTime,$channel,$server,$version,$function,$type)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(function, 0) function,
					IFNULL(prop_id, 0) prop_id,
					IFNULL(prop_name, 0) prop_name,
					IFNULL(prop_type, 0) prop_type,
					IFNULL(SUM(prop_count), 0) prop_count
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_function_prop') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND function = '" . $function . "'
				AND type = '" . $type . "'
                AND prop_count <> 0
                Group By function,prop_id
				Order By prop_id ASC";
		$query = $dwdb->query($sql);
		return $query;
	}
	function getFunctionanalysisDataDetail($fromTime,$toTime,$channel,$server,$version,$prop_id,$functionName){
		$list = array();
		$query = $this->FunctionanalysisDataDetail($fromTime,$toTime,$channel,$server,$version,$prop_id,$functionName);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['behaviour_id'] = $dauUsersRow->behaviour_id;
			$fRow['behaviour_name'] = $dauUsersRow->behaviour_name;
			$fRow['behaviour_gaincount'] = $dauUsersRow->behaviour_gaincount;
			$fRow['behaviour_gainrate'] = $dauUsersRow->behaviour_gainrate;
			$fRow['behaviour_consumecount'] = $dauUsersRow->behaviour_consumecount;
			$fRow['behaviour_consumerate'] = $dauUsersRow->behaviour_consumerate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function FunctionanalysisDataDetail($fromTime,$toTime,$channel,$server,$version,$prop_id,$functionName)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(behaviour_id, 0) behaviour_id,
					IFNULL(behaviour_name, 0) behaviour_name,
					IFNULL(SUM(behaviour_gaincount), 0) behaviour_gaincount,
					IFNULL(AVG(behaviour_gainrate), 0) behaviour_gainrate,
					IFNULL(SUM(behaviour_consumecount), 0) behaviour_consumecount,
					IFNULL(AVG(behaviour_consumerate), 0) behaviour_consumerate
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_function_behaviour') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND prop_id = '" . $prop_id . "'
                AND startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
				AND function = '" . $functionName . "'
				Group By behaviour_id
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
     * getFunctionData function
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
    function getFunctionData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        #AND c.chId = '$channelid'
                        AND c.srvId = '$serverid'
                        AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        #AND c.chId = '$channelid'
                        AND c.srvId = '$serverid'
                        #AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        #AND c.chId = '$channelid'
                        #AND c.srvId = '$serverid'
                        AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    #AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        #AND c.chId = '$channelid'
                        #AND c.srvId = '$serverid'
                        #AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        AND c.chId = '$channelid'
                        AND c.srvId = '$serverid'
                        AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        AND c.chId = '$channelid'
                        AND c.srvId = '$serverid'
                        #AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
             $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        AND c.chId = '$channelid'
                        #AND c.srvId = '$serverid'
                        AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        pcmf.function_name,
                        pcmf.useuser,
                        pcmf.usecount,
                        IFNULL(pc.goldcoingain, 0) goldcoingain,
                        IFNULL(pc.goldcoinconsume, 0) goldcoinconsume,
                        IFNULL(pc.slivergain, 0) slivergain,
                        IFNULL(pc.sliverconsume, 0) sliverconsume
                    FROM
                        (
                            SELECT
                                mf.function_id,
                                mf.function_name,
                                IFNULL(pc.useuser, 0) useuser,
                                IFNULL(pc.usecount, 0) usecount
                            FROM
                                (
                                    SELECT
                                        c.functionid,
                                        COUNT(DISTINCT c.roleId) useuser,
                                        COUNT(1) usecount
                                    FROM
                                        razor_functioncount c
                                    WHERE
                                        c.functioncount_date = '$date'
                                    AND c.appId = '$appid'
                                    AND c.chId = '$channelid'
                                    #AND c.srvId = '$serverid'
                                    #AND c.version = '$versionname'
                                    GROUP BY
                                        c.functionid
                                ) pc
                            RIGHT JOIN (
                                SELECT DISTINCT
                                    function_id,
                                    function_name
                                FROM
                                    razor_mainconfig_function
                                WHERE
                                    app_id = '$appid'
                            ) mf ON pc.functionid = mf.function_id
                        ) pcmf
                    LEFT JOIN (
                        SELECT
                            c.functionid,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoingain,
                            SUM(
                                CASE
                                WHEN c.property = 3
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) goldcoinconsume,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 1 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) slivergain,
                            SUM(
                                CASE
                                WHEN c.property = 5
                                AND c.property_variation = 0 THEN
                                    c.count
                                ELSE
                                    NULL
                                END
                            ) sliverconsume
                        FROM
                            razor_propertyvariation c
                        WHERE
                            c.propertyvariation_date = '$date'
                        AND c.appId = '$appid'
                        AND c.chId = '$channelid'
                        #AND c.srvId = '$serverid'
                        #AND c.version = '$versionname'
                        GROUP BY
                            c.functionid
                    ) pc ON pcmf.function_id = pc.functionid";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


    function getFunctionidByName($function_name){
        $sql="SELECT function_id FROM razor_mainconfig_function  WHERE function_name='$function_name'";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->function_id;
        }
    }

        /**
     * getFunctionData function
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
    function getFunctionTotalgainAndConsume($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$function_name) {

        $function_id=$this->getFunctionidByName($function_name);

            $sql = "SELECT
                    IFNULL(SUM(
                        CASE
                        WHEN c.property = 3
                        AND c.property_variation = 1 THEN
                            c.count
                        ELSE
                            NULL
                        END
                    ),0) goldcoingain,
                    IFNULL(SUM(
                        CASE
                        WHEN c.property = 3
                        AND c.property_variation = 0 THEN
                            c.count
                        ELSE
                            NULL
                        END
                    ),0) goldcoinconsume,
                    IFNULL(SUM(
                        CASE
                        WHEN c.property = 5
                        AND c.property_variation = 1 THEN
                            c.count
                        ELSE
                            NULL
                        END
                    ),0) slivergain,
                    IFNULL(SUM(
                        CASE
                        WHEN c.property = 5
                        AND c.property_variation = 0 THEN
                            c.count
                        ELSE
                            NULL
                        END
                    ),0) sliverconsume
                FROM
                    razor_propertyvariation c
                WHERE
                    c.propertyvariation_date = '$date'
                AND c.appId = '$appid'
                AND c.chId = '$channelid'
                AND c.srvId = '$serverid'
                AND c.version = '$versionname'
                AND c.functionid = '$function_id';";
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }

    /**
     * getFunctionPropData function
     * get Function Prop Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Prop Data
     */
    function getFunctionPropConsumeData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                    FROM
                                        razor_propconsume p
                                    WHERE
                                        p.propconsume_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT DISTINCT
                            f.function_id,
                            f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

        /**
     * getFunctionPropData function
     * get Function Prop Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Prop Data
     */
    function getFunctionPropTotalConsume($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$function_name,$propid) {
            $sql = "SELECT
                        a.propconsume_sum_count
                    FROM
                        (
                            SELECT
                                f1.function_name,
                                mpa.propid,
                                mpa.prop_name,
                                mpa.prop_category,
                                mpa.propconsume_sum_count
                            FROM
                                (
                                    SELECT
                                        pa1.functionid,
                                        pa1.propid,
                                        mp1.prop_name,
                                        mp1.prop_category,
                                        pa1.propconsume_sum_count
                                    FROM
                                        (
                                            SELECT
                                                p.functionid,
                                                p.propid,
                                        SUM(
                                            p.propconsume_count
                                        ) propconsume_sum_count
                                            FROM
                                                razor_propconsume p
                                            WHERE
                                                p.propconsume_date = '$date'
                                            AND p.appId = '$appid'
                                            AND p.chId = '$channelid'
                                            AND p.srvId = '$serverid'
                                            AND p.version = '$versionname'
                                            GROUP BY
                                                p.functionid,
                                                p.propid
                                        ) pa1
                                    LEFT JOIN (
                                        SELECT
                                            *
                                        FROM
                                            razor_mainconfig_prop mp
                                        WHERE
                                            mp.app_id = '$appid'
                                    ) mp1 ON pa1.propid = mp1.prop_id
                                ) mpa
                            LEFT JOIN (
                                SELECT DISTINCT
                                    f.function_id,
                                    f.function_name
                                FROM
                                    razor_mainconfig_function f
                                WHERE
                                    f.app_id = '$appid'
                            ) f1 ON mpa.functionid = f1.function_id
                        ) a
                    WHERE
                        a.function_name = '$function_name'
                    AND a.propid = '$propid'";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->propconsume_sum_count;
        }
    }

        /**
     * getFunctionPropData function
     * get Function Prop Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Prop Data
     */
    function getFunctionPropGainData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    #AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(f1.function_name,'未知-配置表中不存在') function_name,
                        mpa.propid,
                        IFNULL(mpa.prop_name,'未知-配置表中不存在') prop_name,
                        IFNULL(mpa.prop_category,'未知-配置表中不存在') prop_category,
                        mpa.propgain_sum_count
                    FROM
                        (
                            SELECT
                                pa1.functionid,
                                pa1.propid,
                                IFNULL(mp1.prop_name,'未知-配置表中不存在') prop_name,
                                IFNULL(mp1.prop_category,'未知-配置表中不存在') prop_category,
                                pa1.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        p.functionid,
                                        p.propid,
                                        SUM(
                                           p.propgain_count
                                        ) propgain_sum_count
                                    FROM
                                        razor_propgain p
                                    WHERE
                                        p.propgain_date = '$date'
                                    AND p.appId = '$appid'
                                    AND p.chId = '$channelid'
                                    #AND p.srvId = '$serverid'
                                    #AND p.version = '$versionname'
                                    GROUP BY
                                        p.functionid,
                                        p.propid
                                ) pa1
                            LEFT JOIN (
                                SELECT
                                    *
                                FROM
                                    razor_mainconfig_prop mp
                                WHERE
                                    mp.app_id = '$appid'
                            ) mp1 ON pa1.propid = mp1.prop_id
                        ) mpa
                    LEFT JOIN (
                        SELECT
                            DISTINCT f.function_id,f.function_name
                        FROM
                            razor_mainconfig_function f
                        WHERE
                            f.app_id = '$appid'
                    ) f1 ON mpa.functionid = f1.function_id";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


            /**
     * getFunctionPropData function
     * get Function Prop Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Prop Data
     */
    function getFunctionPropTotalGain($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$function_name,$propid) {
            $sql = "SELECT
                        a.propgain_sum_count
                    FROM
                        (
                            SELECT
                                f1.function_name,
                                mpa.propid,
                                mpa.prop_name,
                                mpa.prop_category,
                                mpa.propgain_sum_count
                            FROM
                                (
                                    SELECT
                                        pa1.functionid,
                                        pa1.propid,
                                        mp1.prop_name,
                                        mp1.prop_category,
                                        pa1.propgain_sum_count
                                    FROM
                                        (
                                            SELECT
                                                p.functionid,
                                                p.propid,
                                                SUM(p.propgain_count) propgain_sum_count
                                            FROM
                                                razor_propgain p
                                            WHERE
                                                p.propgain_date = '$date'
                                            AND p.appId = '$appid'
                                            AND p.chId = '$channelid'
                                            AND p.srvId = '$serverid'
                                            AND p.version = '$versionname'
                                            GROUP BY
                                                p.functionid,
                                                p.propid
                                        ) pa1
                                    LEFT JOIN (
                                        SELECT
                                            *
                                        FROM
                                            razor_mainconfig_prop mp
                                        WHERE
                                            mp.app_id = '$appid'
                                    ) mp1 ON pa1.propid = mp1.prop_id
                                ) mpa
                            LEFT JOIN (
                                SELECT DISTINCT
                                    f.function_id,
                                    f.function_name
                                FROM
                                    razor_mainconfig_function f
                                WHERE
                                    f.app_id = '$appid'
                            ) f1 ON mpa.functionid = f1.function_id
                        ) a
                    WHERE
                        a.function_name = '$function_name'
                    AND a.propid = '$propid'";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->propgain_sum_count;
        }
    }

    /**
     * getFunctionPropData function
     * get Function Prop Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Prop Data
     */
    function getFunctionPropActionData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            #AND fc.chId = '$channelid'
                            AND fc.srvId = '$serverid'
                            AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            #AND fc.chId = '$channelid'
                            AND fc.srvId = '$serverid'
                            #AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            #AND fc.chId = '$channelid'
                            #AND fc.srvId = '$serverid'
                            AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            #AND fc.chId = '$channelid'
                            #AND fc.srvId = '$serverid'
                            #AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            AND fc.chId = '$channelid'
                            AND fc.srvId = '$serverid'
                            AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            AND fc.chId = '$channelid'
                            AND fc.srvId = '$serverid'
                            #AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            AND fc.chId = '$channelid'
                            #AND fc.srvId = '$serverid'
                            AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        IFNULL(
                            mf1.function_name,
                            '未知-配置表中不存在'
                        ) function_name,
                        fc1.eventid actionid,
                        IFNULL(
                            mf1.action_name,
                            '未知-配置表中不存在'
                        ) action_name,
                        '无' actiontype_name,
                        fc1.actioncount prop_count
                    FROM
                        (
                            SELECT
                                fc.functionid,
                                fc.eventid,
                                COUNT(1) actioncount
                            FROM
                                razor_functioncount fc
                            WHERE
                                fc.functioncount_date = '$date'
                            AND fc.appId = '$appid'
                            AND fc.chId = '$channelid'
                            #AND fc.srvId = '$serverid'
                            #AND fc.version = '$versionname'
                            GROUP BY
                                fc.functionid,
                                fc.eventid
                        ) fc1
                    LEFT JOIN (
                        SELECT
                            *
                        FROM
                            razor_mainconfig_function mf
                        WHERE
                            mf.app_id = '$appid'
                    ) mf1 ON fc1.eventid = mf1.action_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * getFunctionPropData function
     * get Function Prop Data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array Function Prop Data
     */
    function getFunctionActionData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                #AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        #AND pc.chId = '$channelid'
                        AND pc.srvId = '$serverid'
                        AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                #AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                #AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        #AND pc.chId = '$channelid'
                        AND pc.srvId = '$serverid'
                        #AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                #AND pg.chId = '$channelid'
                                #AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        #AND pc.chId = '$channelid'
                        #AND pc.srvId = '$serverid'
                        AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
             $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                #AND pg.chId = '$channelid'
                                #AND pg.srvId = '$serverid'
                                #AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        #AND pc.chId = '$channelid'
                        #AND pc.srvId = '$serverid'
                        #AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        AND pc.chId = '$channelid'
                        AND pc.srvId = '$serverid'
                        AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
             $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                AND pg.srvId = '$serverid'
                                #AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        AND pc.chId = '$channelid'
                        AND pc.srvId = '$serverid'
                        #AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                #AND pg.srvId = '$serverid'
                                AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        AND pc.chId = '$channelid'
                        #AND pc.srvId = '$serverid'
                        AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                        mcfpg1.function_name,
                        mcfpg1.propid,
                        mcfpg1.action_id,
                        mcfpg1.action_name,
                        IFNULL(mcfpg1.propgain_count,0) propgain_count,
                        IFNULL(pc1.propconsume_count,0) propconsume_count
                    FROM
                        (
                            SELECT
                                *
                            FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                        razor_mainconfig_function mcf
                                    WHERE
                                        mcf.app_id = '$appid'
                                ) mcf1
                            LEFT JOIN (
                                SELECT
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid,
                                    SUM(pg.propgain_count) propgain_count
                                FROM
                                    razor_propgain pg
                                WHERE
                                    pg.propgain_date = '$date'
                                AND pg.appId = '$appid'
                                AND pg.chId = '$channelid'
                                #AND pg.srvId = '$serverid'
                                #AND pg.version = '$versionname'
                                GROUP BY
                                    pg.functionid,
                                    pg.propid,
                                    pg.actionid
                            ) pg1 ON mcf1.function_id = pg1.functionid
                            AND mcf1.action_id = pg1.actionid
                        ) mcfpg1
                    LEFT JOIN (
                        SELECT
                            pc.functionid,
                            pc.propid,
                            pc.actionid,
                            SUM(pc.propconsume_count) propconsume_count
                        FROM
                            razor_propconsume pc
                        WHERE
                            pc.propconsume_date = '$date'
                        AND pc.appId = '$appid'
                        AND pc.chId = '$channelid'
                        #AND pc.srvId = '$serverid'
                        #AND pc.version = '$versionname'
                        GROUP BY
                            pc.functionid,
                            pc.propid,
                            pc.actionid
                    ) pc1 ON mcfpg1.function_id = pc1.functionid
                    AND mcfpg1.action_id = pc1.actionid
                    WHERE mcfpg1.propid IS NOT NULL and mcfpg1.action_id is not NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * sum_basic_sa_function function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_sa_function($countdate) {
        $dwdb = $this->load->database('dw', true);
        
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_psv->appId,
                    'version_name' => $paramsRow_psv->version,
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            //$FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_ps->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => $server_name,
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pv->appId,
                    'version_name' => $paramsRow_pv->version,
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_p->appId,
                    'version_name' => 'all',
                    'channel_name' => 'all',
                    'server_name' => 'all',
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcsv->appId,
                    'version_name' => $paramsRow_pcsv->version,
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcs->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => $server_name,
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pcv->appId,
                    'version_name' => $paramsRow_pcv->version,
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_pcv = $params_pcv->next_row();
        }
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $dauusers = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $FunctionData=$this->getFunctionData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            // $FunctionPropData=$this->getFunctionPropData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $FunctionPropConsumeData = $this->getFunctionPropConsumeData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $FunctionPropGainData = $this->getFunctionPropGainData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $FunctionPropActionData = $this->getFunctionPropActionData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $FunctionActionData=$this->getFunctionActionData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $paramsRow_t=$FunctionData->first_row();
            for ($j=0; $j < $FunctionData->num_rows() ; $j++) { 
                $functionconsumeandgain = $this->getFunctionTotalgainAndConsume($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$paramsRow_t->function_name);
                $data_FunctionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_t->function_name,
                    'function_useuser' => $paramsRow_t->useuser,
                    'function_usecount' => $paramsRow_t->usecount,
                    'function_userate' => ($dauusers==0)?0:($paramsRow_t->useuser/$dauusers),
                    'function_goldoutput' => $paramsRow_t->goldcoingain,
                    'function_goldoutputrate' => ($functionconsumeandgain['goldcoingain']==0)?0:($paramsRow_t->goldcoingain/$functionconsumeandgain['goldcoingain']),
                    'function_goldconsume' => $paramsRow_t->goldcoinconsume,
                    'function_goldconsumerate' => ($functionconsumeandgain['goldcoinconsume']==0)?0:($paramsRow_t->goldcoinconsume/$functionconsumeandgain['goldcoinconsume']),
                    'function_sliveroutput' => $paramsRow_t->slivergain,
                    'function_sliveroutputrate' => ($functionconsumeandgain['slivergain']==0)?0:($paramsRow_t->slivergain/$functionconsumeandgain['slivergain']),
                    'function_sliverconsume' => $paramsRow_t->sliverconsume,
                    'function_sliverconsumerate' => ($functionconsumeandgain['sliverconsume']==0)?0:($paramsRow_t->sliverconsume/$functionconsumeandgain['sliverconsume'])
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function', $data_FunctionData);
                $paramsRow_t = $FunctionData->next_row();
            }

            $paramsRow_s1=$FunctionPropGainData->first_row();
            for ($s=0; $s < $FunctionPropGainData->num_rows() ; $s++) {
                $data_FunctionPropGainData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_s1->function_name,
                    'type' => 'propgain',
                    'prop_id' => $paramsRow_s1->propid,
                    'prop_name' => $paramsRow_s1->prop_name,
                    'prop_type' => $paramsRow_s1->prop_category,
                    'prop_count' => $paramsRow_s1->propgain_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropGainData);
                $paramsRow_s1 = $FunctionPropGainData->next_row();
            }

            $paramsRow_s2=$FunctionPropConsumeData->first_row();
            for ($s=0; $s < $FunctionPropConsumeData->num_rows() ; $s++) {
                $data_FunctionPropConsumeData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_s2->function_name,
                    'type' => 'propconsume',
                    'prop_id' => $paramsRow_s2->propid,
                    'prop_name' => $paramsRow_s2->prop_name,
                    'prop_type' => $paramsRow_s2->prop_category,
                    'prop_count' => $paramsRow_s2->propconsume_sum_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropConsumeData);
                $paramsRow_s2 = $FunctionPropConsumeData->next_row();
            }

            $paramsRow_s3=$FunctionPropActionData->first_row();
            for ($s=0; $s < $FunctionPropActionData->num_rows() ; $s++) {
                $data_FunctionPropActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_s3->function_name,
                    'type' => 'action',
                    'prop_id' => $paramsRow_s3->actionid,
                    'prop_name' => $paramsRow_s3->action_name,
                    'prop_type' => $paramsRow_s3->actiontype_name,
                    'prop_count' => $paramsRow_s3->prop_count
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_prop', $data_FunctionPropActionData);
                $paramsRow_s3 = $FunctionPropActionData->next_row();
            }

            $paramsRow_c=$FunctionActionData->first_row();
            for ($C=0; $C < $FunctionActionData->num_rows() ; $C++) {
                $propconsume_sum_count=$this->getFunctionPropTotalConsume($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $propgain_sum_count=$this->getFunctionPropTotalGain($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$paramsRow_c->function_name,$paramsRow_c->propid);
                $data_FunctionActionData = array(
                    'startdate_sk' => $countdate,
                    'enddate_sk' => $countdate,
                    'product_id' => $paramsRow_pc->appId,
                    'version_name' => 'all',
                    'channel_name' => $channel_name,
                    'server_name' => 'all',
                    'function' => $paramsRow_c->function_name,
                    'prop_id' => $paramsRow_c->propid,
                    'behaviour_id' => $paramsRow_c->action_id,
                    'behaviour_name' => $paramsRow_c->action_name,
                    'behaviour_gaincount' => $paramsRow_c->propgain_count,
                    'behaviour_gainrate' => ($propconsume_sum_count==0)?0:($paramsRow_c->propgain_count/$propconsume_sum_count),
                    'behaviour_consumecount' => $paramsRow_c->propconsume_count,
                    'behaviour_consumerate' => ($propgain_sum_count==0)?0:($paramsRow_c->propconsume_count/$propgain_sum_count)
                );
                $dwdb->insert_or_update('razor_sum_basic_sa_function_behaviour', $data_FunctionActionData);
                $paramsRow_c = $FunctionActionData->next_row();
            }
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
