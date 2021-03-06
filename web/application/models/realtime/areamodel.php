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
 * Areamodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Areamodel extends CI_Model
{


    /** 
     * Construct load 
     * Construct  function 
     * 
     * @return void 
     */
    function __construct()
    {
        parent::__construct();
        $this -> load -> helper('date');
        $this -> load -> library('redis');
    }
    
    /** 
     * Get area data 
     * GetAreasData function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getAreasData($productId)
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $ret = array();
        $allCountryKeys = $this -> redis -> keys("razor_r_arc_p_" . $productId . "_c_*");
        $countries = array();
        if ($allCountryKeys && count($allCountryKeys)) {
            for ($i = 0; $i < count($allCountryKeys); $i++) {
                $key = $allCountryKeys[$i];
                $countryNames = $this -> redis -> hkeys($key);
                if ($countryNames && count($countryNames) > 0) {
                    for ($j = 0; $j < count($countryNames); $j++) {
                        $cName = $countryNames[$j];
                        $countries[$cName] = 1;
                    }
                }
            }
        }

        $ret = array();
        if ($countries && count($countries) > 0) {
            foreach ($countries as $ckey => $value) {
                $regRegion = "razor_r_arrd_p_" . $productId . "_c_" . $ckey . "_r_*";
                $allRegionKeys = $this -> redis -> keys($regRegion);
                $countryArray = array("countryName" => $ckey, "countrySize" => 0);
                $regionsArray = array();
                $countrySize = 0;
                if ($allRegionKeys && count($allRegionKeys) > 0) {
                    for ($m = 0; $m < count($allRegionKeys); $m++) {
                        $region = $allRegionKeys[$m];

                        //Get Region name
                        $regionName = $this -> redis -> hget($region, "regionname");
                        if ($regionName && $regionName != "") {

                        } else {
                            continue;
                        }

                        $countryRegionArray = array("regionName" => $regionName, "regionSize" => 0);

                        $regRegionKey = "razor_r_arr_p_" . $productId . "_c_" . $ckey . "_r_" . $regionName . "_*";
                        $regionUsers = array();
                        $allRegionUsers = $this -> redis -> keys($regRegionKey);
                        if ($allRegionUsers && count($allRegionUsers) > 0) {
                            for ($n = 0; $n < count($allRegionUsers); $n++) {
                                $regionUser = $allRegionUsers[$n];
                                if (!isset($regionUsers["$regionUser"])) {
                                    $countryRegionArray["regionSize"] += (int)$countryRegionArray["regionSize"] + 1;
                                }
                            }
                        }

                        $countrySize += (int)$countryRegionArray["regionSize"];
                        array_push($regionsArray, $countryRegionArray);
                    }
                }
                $countryArray["regions"] = $regionsArray;
                $countryArray["countrySize"] = $countrySize;
                array_push($ret, $countryArray);
            }
        }

        return $ret;
    }
    
    /** 
     * Get area data for grid 
     * GetAreaDataForGrid function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getAreaDataForGrid($productId)
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $ret = array();
        $allCountryKeys = $this -> redis -> keys("razor_r_arc_p_" . $productId . "_c_*");
        $countries = array();
        if ($allCountryKeys && count($allCountryKeys)) {
            for ($i = 0; $i < count($allCountryKeys); $i++) {
                $key = $allCountryKeys[$i];
                $countryNames = $this -> redis -> hkeys($key);
                if ($countryNames && count($countryNames) > 0) {
                    for ($j = 0; $j < count($countryNames); $j++) {
                        $cName = $countryNames[$j];
                        $countries[$cName] = 1;
                    }
                }
            }
        }

        $ret = array();
        if ($countries && count($countries) > 0) {
            foreach ($countries as $ckey => $value) {
                $regRegion = "razor_r_arrd_p_" . $productId . "_c_" . $ckey . "_r_*";
                $allRegionKeys = $this -> redis -> keys($regRegion);
                $countryArray = array("countryName" => $ckey, "countrySize" => 0);
                $regionsArray = array();
                $countrySize = 0;
                if ($allRegionKeys && count($allRegionKeys) > 0) {
                    for ($m = 0; $m < count($allRegionKeys); $m++) {
                        $region = $allRegionKeys[$m];

                        //Get Region name
                        $regionName = $this -> redis -> hget($region, "regionname");
                        if ($regionName && $regionName != "") {

                        } else {
                            continue;
                        }

                        $countryRegionArray = array("regionName" => $regionName, "regionSize" => 0);

                        $regRegionKey = "razor_r_arr_p_" . $productId . "_c_" . $ckey . "_r_" . $regionName . "_*";
                        $regionUsers = array();
                        $allRegionUsers = $this -> redis -> keys($regRegionKey);
                        if ($allRegionUsers && count($allRegionUsers) > 0) {
                            for ($n = 0; $n < count($allRegionUsers); $n++) {
                                $regionUser = $allRegionUsers[$n];
                                if (!isset($regionUsers["$regionUser"])) {
                                    $countryRegionArray["regionSize"] += (int)$countryRegionArray["regionSize"] + 1;
                                }
                            }
                        }

                        $countrySize += (int)$countryRegionArray["regionSize"];
                        array_push($regionsArray, $countryRegionArray);
                    }
                }
                //$countryArray["regions"] = $regionsArray;
                $countryArray["countrySize"] = count($allRegionKeys);
                array_push($ret, $countryArray);
            }
        }

        $all = array('total' => count($ret), 'rows' => $ret);
        return $all;
    }
    
    /** 
     * Get region 
     * GetRegionsByCountry function 
     * 
     * @param string $productId   productid 
     * @param string $countryName countryname 
     * 
     * @return array 
     */
    function getRegionsByCountry($productId, $countryName)
    {
        $countryName = urldecode($countryName);
        $nameArray = explode('&', $countryName);
        if ($nameArray && count($nameArray) > 0) {
            $countryName = $nameArray[0];
        }
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $regRegion = "razor_r_arrd_p_" . $productId . "_c_" . $countryName . "_r_*";
        $allRegionKeys = $this -> redis -> keys($regRegion);
        $regionsArray = array();
        $countrySize = 0;
        $regions = array();
        if ($allRegionKeys && count($allRegionKeys) > 0) {
            for ($m = 0; $m < count($allRegionKeys); $m++) {
                $region = $allRegionKeys[$m];

                //Get Region name
                $regionName = $this -> redis -> hget($region, "regionname");
                if ($regionName && $regionName != "") {
                    if (isset($regions[$regionName])) {
                        continue;
                    }
                    $regions[$regionName] = $regionName;
                } else {
                    continue;
                }

                $countryRegionArray = array("regionName" => $regionName, "regionSize" => 0);

                $regRegionKey = "razor_r_arr_p_" . $productId . "_c_" . $countryName . "_r_" . $regionName . "_*";
                $regionUsers = array();
                $allRegionUsers = $this -> redis -> keys($regRegionKey);
                if ($allRegionUsers && count($allRegionUsers) > 0) {
                    for ($n = 0; $n < count($allRegionUsers); $n++) {
                        $regionUser = $allRegionUsers[$n];
                        if (!isset($regionUsers["$regionUser"])) {
                            $countryRegionArray["regionSize"] = (int)$countryRegionArray["regionSize"] + 1;
                        }
                    }
                }

                $countrySize += (int)$countryRegionArray["regionSize"];
                array_push($regionsArray, $countryRegionArray);
            }
        }
        return $regionsArray;
    }
    
    /** 
     * Get bubble 
     * GetBubbleAreasData function 
     * 
     * @param string $productId productid 
     * 
     * @return array 
     */
    function getBubbleAreasData($productId)
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $ret = array("name" => "All Countries");
        $allCountryKeys = $this -> redis -> keys("razor_r_arc_p_" . $productId . "_c_*");
        $countries = array();
        if ($allCountryKeys && count($allCountryKeys)) {
            for ($i = 0; $i < count($allCountryKeys); $i++) {
                $key = $allCountryKeys[$i];
                $countryNames = $this -> redis -> hkeys($key);
                if ($countryNames && count($countryNames) > 0) {
                    for ($j = 0; $j < count($countryNames); $j++) {
                        $cName = $countryNames[$j];
                        $countries[$cName] = 1;
                    }
                }
            }
        }

        $all = array();
        if ($countries && count($countries) > 0) {
            foreach ($countries as $ckey => $value) {
                $regRegion = "razor_r_arrd_p_" . $productId . "_c_" . $ckey . "_r_*";
                $allRegionKeys = $this -> redis -> keys($regRegion);
                $countryArray = array("name" => $ckey, "size" => 0);
                $regionsArray = array();
                $countrySize = 0;
                if ($allRegionKeys && count($allRegionKeys) > 0) {
                    for ($m = 0; $m < count($allRegionKeys); $m++) {
                        $region = $allRegionKeys[$m];

                        //Get Region name
                        $regionName = $this -> redis -> hget($region, "regionname");
                        if ($regionName && $regionName != "") {

                        } else {
                            continue;
                        }

                        $countryRegionArray = array("name" => $regionName, "size" => 0);

                        $regRegionKey = "razor_r_arr_p_" . $productId . "_c_" . $ckey . "_r_" . $regionName . "_*";
                        $regionUsers = array();
                        $allRegionUsers = $this -> redis -> keys($regRegionKey);
                        if ($allRegionUsers && count($allRegionUsers) > 0) {
                            for ($n = 0; $n < count($allRegionUsers); $n++) {
                                $regionUser = $allRegionUsers[$n];
                                if (!isset($regionUsers["$regionUser"])) {
                                    $countryRegionArray["size"] += (int)$countryRegionArray["size"] + 1;
                                }
                            }
                        }

                        $countrySize += (int)$countryRegionArray["size"];
                        array_push($regionsArray, $countryRegionArray);
                    }
                }
                $countryArray["children"] = $regionsArray;
                $countryArray["size"] = $countrySize;
                $ret["children"] = $countryArray;
                array_push($all, $countryArray);
                $ret["children"] = $all;
            }
        }

        return $ret;
    }

}
?>