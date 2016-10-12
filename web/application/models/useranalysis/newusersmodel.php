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
 * Newusersmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Newusersmodel extends CI_Model {

    /**
     * Construct load
     * Construct function
     * 
     * @return void
     */
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model("product/productmodel", 'product');
        $this->load->model("channelmodel", 'channel');
    }

    /**
     * GetDetailNewUsersDataByDay function
     * get detailed data
     *
     * @param string $fromTime from time
     * @param string $toTime   to time
     * @param string $channel channel
     * @param string $version version
     * @param string $server server
     *
     * @return query list
     */
    function getDetailUsersDataByDay($fromTime, $toTime, $channel, $version) {
        $list = array();
        $query = $this->getDetailNewUsersData($fromTime, $toTime, $channel, $version);
        $newUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow["date"] = $newUsersRow->date_sk;
            $fRow['deviceactivations'] = $newUsersRow->deviceactivations;
            $fRow['newusers'] = $newUsersRow->newusers;
            $fRow['newdevices'] = $newUsersRow->newdevices;
            $fRow['userconversion'] = $newUsersRow->userconversion;
            $newUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    /**
     * GetDetailNewUsersData function
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
    function getDetailNewUsersData($fromTime, $toTime, $channel, $version) {
        $currentProduct = $this->common->getCurrentProduct();
        $productId = $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
        ($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
        $sql = "SELECT
                        IFNULL(date_sk, 0) date_sk,
                        IFNULL(deviceactivations, 0) deviceactivations,
                        IFNULL(newusers, 0) newusers,
                        IFNULL(newdevices, 0) newdevices,
                        IFNULL(userconversion, 0) userconversion
                FROM
                        " . $dwdb->dbprefix('sum_basic_newusers') . "
                WHERE
                date_sk >= '" . $fromTime . "'
                AND product_id = '" . $productId . "'
                AND channel_name IN ('" . $channel_list . "')
                AND version_name IN ('" . $version_list . "')
                Order By date_sk DESC";
        $query = $dwdb->query($sql);
        return $query;
    }

    /**
     * unescape 
     * 
     * @param String $str
     * 
     * @return String $ret
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
     * GetDeviceactivations function
     * get activation devices
     * 
     * @param Date $fromdate
     * @param Date $$todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int deviceactivations
     */
    function getDeviceactivations($fromdate,$todate, $appid, $channelid='all', $versionname='all') {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) deviceactivations
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        AND i.chId = '$channelid'
                        AND i.version = '$versionname';";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) deviceactivations
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        AND i.chId = '$channelid'
                        #AND i.version = '$versionname';";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) deviceactivations
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        #AND i.chId = '$channelid'
                        #AND i.version = '$versionname';";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) deviceactivations
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        #AND i.chId = '$channelid'
                        AND i.version = '$versionname';";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->deviceactivations;
        }
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
    function getNewusers($fromdate,$todate, $appid, $channelid='all', $versionname='all') {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(r.userId),0) newusers
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        AND r.chId = '$channelid'
                        AND r.version = '$versionname';";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(r.userId),0) newusers
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        AND r.chId = '$channelid'
                        #AND r.version = '$versionname';";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(r.userId),0) newusers
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        #AND r.chId = '$channelid'
                        #AND r.version = '$versionname';";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(r.userId),0) newusers
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        #AND r.chId = '$channelid'
                        AND r.version = '$versionname';";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newusers;
        }
    }


    /**
     * GetNewregisterdevice function
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
    function getNewregisterdevice($fromdate,$todate, $appid, $channelid='all', $versionname='all') {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(distinct r.deviceid),0) devicecount
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        AND r.chId = '$channelid'
                        AND r.version = '$versionname';";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(distinct r.deviceid),0) devicecount
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        AND r.chId = '$channelid'
                        #AND r.version = '$versionname';";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(distinct r.deviceid),0) devicecount
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        #AND r.chId = '$channelid'
                        #AND r.version = '$versionname';";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(distinct r.deviceid),0) devicecount
                FROM
                        razor_register r                      
                WHERE
                        r.register_date >= '$fromdate'
                        AND r.register_date <='$todate'
                        AND r.appId='$appid'      
                        #AND r.chId = '$channelid'
                        AND r.version = '$versionname';";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->devicecount;
        }
    }

    /**
     * GetNewdevices function
     * get new devices
     * 
     * @param Date $fromdate
     * @param Date $todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int newdevices
     */
    function getNewdevices($fromdate,$todate, $appid, $channelid='all', $versionname='all') {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) newdevices
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        AND i.chId = '$channelid'
                        AND i.version = '$versionname'
                        AND i.deviceid NOT IN (
                                SELECT
                                        deviceid
                                FROM
                                        razor_install
                                WHERE
                                        install_date < '$fromdate'
                                        AND appId='$appid'
                                        AND chId = '$channelid'
                                        AND version = '$versionname'
                        );";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) newdevices
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        AND i.chId = '$channelid'
                        #AND i.version = '$versionname'
                        AND i.deviceid NOT IN (
                                SELECT
                                        deviceid
                                FROM
                                        razor_install
                                WHERE
                                        install_date < '$fromdate'
                                        AND appId='$appid'
                                        AND chId = '$channelid'
                                        #AND version = '$versionname'
                        );";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) newdevices
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        #AND i.chId = '$channelid'
                        #AND i.version = '$versionname'
                        AND i.deviceid NOT IN (
                                SELECT
                                        deviceid
                                FROM
                                        razor_install
                                WHERE
                                        install_date < '$fromdate'
                                        AND appId='$appid'
                                        #AND chId = '$channelid'
                                        #AND version = '$versionname'
                        );";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                        IFNULL(count(i.deviceid),0) newdevices
                FROM
                        razor_install i
                WHERE
                        i.install_date >= '$fromdate'
                        AND i.install_date <= '$todate'
                        AND i.appId='$appid'
                        #AND i.chId = '$channelid'
                        AND i.version = '$versionname'
                        AND i.deviceid NOT IN (
                                SELECT
                                        deviceid
                                FROM
                                        razor_install
                                WHERE
                                        install_date < '$fromdate'
                                        AND appId='$appid'
                                        #AND chId = '$channelid'
                                        AND version = '$versionname'
                        );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newdevices;
        }
    }

    /**
     * Sum_basic_newusers function
     * count new users
     * 
     */
    function sum_basic_newusers($countdate) {
        $dwdb = $this->load->database('dw', true);
        $paramspcv = $this->product->getProductChannelVersion();
        $paramspcvRow = $paramspcv->first_row();
        for ($i = 0; $i < $paramspcv->num_rows(); $i++) {
            $deviceactivations = $this->getDeviceactivations($countdate,$countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            $newusers = $this->getNewusers($countdate,$countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            $newdevices = $this->getNewdevices($countdate,$countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            $Newregisterdevice = $this->getNewregisterdevice($countdate,$countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcvRow->chId);
            $data = array(
                'product_id' => $paramspcvRow->appId,
                'channel_name' => $channel_name,
                'version_name' => $paramspcvRow->version,
                'date_sk' => $countdate,
                'deviceactivations' => $deviceactivations,
                'newusers' => $newusers,
                'newdevices' => $newdevices,
                'userconversion' =>($newdevices==0)?0:($Newregisterdevice/$newdevices)
            );
            $dwdb->insert_or_update('razor_sum_basic_newusers', $data);
            $paramspcvRow = $paramspcv->next_row();
        }
        $paramspv = $this->product->getProductVersionOffChannel();
        $paramspvRow = $paramspv->first_row();
        for ($i = 0; $i < $paramspv->num_rows(); $i++) {
            $deviceactivations = $this->getDeviceactivations($countdate,$countdate, $paramspvRow->appId,'all', $paramspvRow->version);
            $newusers = $this->getNewusers($countdate,$countdate, $paramspvRow->appId,'all', $paramspvRow->version);
            $newdevices = $this->getNewdevices($countdate,$countdate, $paramspvRow->appId,'all', $paramspvRow->version);
            $Newregisterdevice = $this->getNewregisterdevice($countdate,$countdate, $paramspvRow->appId,'all', $paramspvRow->version);
            $data = array(
                'product_id' =>  $paramspvRow->appId,
                'channel_name' => 'all',
                'version_name' => $paramspvRow->version,
                'date_sk' => $countdate,
                'deviceactivations' => $deviceactivations,
                'newusers' => $newusers,
                'newdevices' => $newdevices,
                'userconversion' =>($newdevices==0)?0:($Newregisterdevice/$newdevices)
            );
            $dwdb->insert_or_update('razor_sum_basic_newusers', $data);
            $paramspvRow = $paramspv->next_row();
        }
        $paramspc = $this->product->getProductChannelOffVersion();
        $paramspcRow = $paramspc->first_row();
        for ($i = 0; $i < $paramspc->num_rows(); $i++) {
            $deviceactivations = $this->getDeviceactivations($countdate,$countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            $newusers = $this->getNewusers($countdate,$countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            $newdevices = $this->getNewdevices($countdate,$countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            $Newregisterdevice = $this->getNewregisterdevice($countdate,$countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcRow->chId);
            $data = array(
                'product_id' =>  $paramspcRow->appId,
                'channel_name' => $channel_name,
                'version_name' => 'all',
                'date_sk' => $countdate,
                'deviceactivations' => $deviceactivations,
                'newusers' => $newusers,
                'newdevices' => $newdevices,
                'userconversion' =>($newdevices==0)?0:($Newregisterdevice/$newdevices)
            );
            $dwdb->insert_or_update('razor_sum_basic_newusers', $data);
            $paramspcRow = $paramspc->next_row();
        }
        $paramsp = $this->product->getProductOffChannelVersion();
        $paramspRow = $paramsp->first_row();
        for ($i = 0; $i < $paramsp->num_rows(); $i++) {
            $deviceactivations = $this->getDeviceactivations($countdate,$countdate, $paramspRow->appId,'all','all');
            $newusers = $this->getNewusers($countdate,$countdate, $paramspRow->appId,'all','all');
            $newdevices = $this->getNewdevices($countdate,$countdate, $paramspRow->appId,'all','all');
            $Newregisterdevice = $this->getNewregisterdevice($countdate,$countdate, $paramspRow->appId,'all','all');
            $data = array(
                'product_id' => $paramspRow->appId,
                'channel_name' => 'all',
                'version_name' => 'all',
                'date_sk' => $countdate,
                'deviceactivations' => $deviceactivations,
                'newusers' => $newusers,
                'newdevices' => $newdevices,
                'userconversion' =>($newdevices==0)?0:($Newregisterdevice/$newdevices)
            );
            $dwdb->insert_or_update('razor_sum_basic_newusers', $data);
            $paramspRow = $paramsp->next_row();
        }
    }

}
