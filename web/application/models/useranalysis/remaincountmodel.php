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
 * Remaincountmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Remaincountmodel extends CI_Model {

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
        $this->load->model("useranalysis/dauusersmodel", 'dauusers');
        $this->load->model('useranalysis/newusersmodel', 'newusers');
        $this->load->model("useranalysis/newcreaterolemodel", 'newcreaterole');
    }

    /**
     * GetNewuser function
     * get new users
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int newuser
     */
    function getRemainByNewuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all', $days = 1) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            razor_login l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.userId IN (
                            SELECT
                                  DISTINCT  userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newuserremain;
        }
    }


    /**
     * getRemainByNewddevice function
     * get new devices remain
     * 
     * @param Date $fromdate
     * @param Date $todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int newdevices remain
     */
    function getRemainByNewddevice($date, $appid, $channelid = 'all',$versionname = 'all', $days = 1) {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.deviceid) deviceremain
                    FROM
                            razor_deviceboot l
                    WHERE
                            l.deviceboot_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.deviceboot_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.version = '$versionname'
                    AND l.deviceid IN (
                            SELECT
                                    deviceid
                            FROM
                                    razor_install c 
                            WHERE
                                    c.install_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.version = '$versionname'
                    );";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.deviceid) deviceremain
                    FROM
                            razor_deviceboot l
                    WHERE
                            l.deviceboot_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.deviceboot_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.version = '$versionname'
                    AND l.deviceid IN (
                            SELECT
                                    deviceid
                            FROM
                                    razor_install c 
                            WHERE
                                    c.install_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.version = '$versionname'
                    );";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.deviceid) deviceremain
                    FROM
                            razor_deviceboot l
                    WHERE
                            l.deviceboot_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.deviceboot_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.version = '$versionname'
                    AND l.deviceid IN (
                            SELECT
                                    deviceid
                            FROM
                                    razor_install c 
                            WHERE
                                    c.install_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.version = '$versionname'
                    );";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
                            COUNT(DISTINCT l.deviceid) deviceremain
                    FROM
                            razor_deviceboot l
                    WHERE
                            l.deviceboot_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.deviceboot_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.version = '$versionname'
                    AND l.deviceid IN (
                            SELECT
                                    deviceid
                            FROM
                                    razor_install c 
                            WHERE
                                    c.install_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.version = '$versionname'
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->deviceremain;
        }
    }

    /**
     * GetDauuser function
     * get dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int dauuser
     */
    function getRemainByNewuserAndStarttime($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all', $days = 1, $starttimes = 1) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_createrole_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newuserremain;
        }
    }

    /**
     * GetDauuser function
     * get dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int dauuser
     */
    function getRemainByPayuserAndStarttime($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all', $days = 1, $starttimes = 1) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_pay_logintimes l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newuserremain;
        }
    }

    /**
     * GetDauuser function
     * get dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int dauuser
     */
    function getRemainByDauuserAndStarttime($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all', $days = 1, $starttimes = 1) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    #AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            #AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                #AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                AND cr.version = '$versionname'
                            )
                    );";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "
                    SELECT
                            COUNT(DISTINCT l.userId) newuserremain
                    FROM
                            VIEW_razor_login_logintimes_srvid l
                    WHERE
                            l.login_date = DATE_ADD('$date', INTERVAL $days DAY)  AND l.login_date<>CURDATE()
                    AND l.appId = '$appid'
                    AND l.chId = '$channelid'
                    #AND l.srvId = '$serverid'
                    #AND l.version = '$versionname'
                    AND l.logintimes >=$starttimes
                    AND l.userId IN (
                            SELECT
                                    distinct userId
                            FROM
                                    razor_createrole c
                            WHERE
                                    c.create_role_date = '$date'
                            AND c.appId = '$appid'
                            AND c.chId = '$channelid'
                            #AND c.srvId = '$serverid'
                            #AND c.version = '$versionname'
                            AND c.userId IN (
                                SELECT DISTINCT
                                    cr.userId
                                FROM
                                    razor_register cr
                                WHERE
                                    cr.register_date = '$date'
                                AND cr.appId = '$appid'
                                AND cr.chId = '$channelid'
                                #AND cr.version = '$versionname'
                            )
                    );";
        }
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newuserremain;
        }
    }

    /**
     * Sum_basic_userremain function
     * count userremain by new user
     * 
     * 
     */
    function sum_basic_userremain($countdate) {
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', 29);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        $params_pc = $this->product->getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $day2 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 1);
            $day3 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 2);
            $day4 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 3);
            $day5 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 4);
            $day6 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 5);
            $day7 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 6);
            $day8 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 7);
            $day14 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 13);
            $day30 = $this->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 29);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'usercount' => $newusers,
                'day1' => ($newusers==0)?0:($day2 / $newusers),
                'day2' => ($newusers==0)?0:($day3 / $newusers),
                'day3' => ($newusers==0)?0:($day4 / $newusers),
                'day4' => ($newusers==0)?0:($day5 / $newusers),
                'day5' => ($newusers==0)?0:($day6 / $newusers),
                'day6' => ($newusers==0)?0:($day7 / $newusers),
                'day7' => ($newusers==0)?0:($day8 / $newusers),
                'day14' => ($newusers==0)?0:($day14 / $newusers),
                'day30' => ($newusers==0)?0:($day30 / $newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_userremain_daily', $data);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

    /**
     * sum_basic_deviceremain function
     * count new users
     * 
     */
    function sum_basic_deviceremain($countdate) {
        $dwdb = $this->load->database('dw', true);
        $paramspcv = $this->product->getProductChannelVersion();
        $paramspcvRow = $paramspcv->first_row();
        for ($i = 0; $i < $paramspcv->num_rows(); $i++) {
            $devicecount=  $this->newusers->getNewdevices($countdate,$countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            $day2 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 1);
            $day3 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 2);
            $day4 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 3);
            $day5 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 4);
            $day6 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 5);
            $day7 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 6);
            $day8 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 7);
            $day14 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 13);
            $day30 = $this->getRemainByNewddevice($countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version, 29);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcvRow->chId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramspcvRow->appId,
                'version_name' => $paramspcvRow->version,
                'channel_name' => $channel_name,
                'devicecount' => $devicecount,
                'day1' => ($devicecount==0)?0:($day2 / $devicecount),
                'day2' => ($devicecount==0)?0:($day3 / $devicecount),
                'day3' => ($devicecount==0)?0:($day4 / $devicecount),
                'day4' => ($devicecount==0)?0:($day5 / $devicecount),
                'day5' => ($devicecount==0)?0:($day6 / $devicecount),
                'day6' => ($devicecount==0)?0:($day7 / $devicecount),
                'day7' => ($devicecount==0)?0:($day8 / $devicecount),
                'day14' => ($devicecount==0)?0:($day14 / $devicecount),
                'day30' => ($devicecount==0)?0:($day30 / $devicecount)
            );
            $dwdb->insert_or_update('razor_sum_basic_deviceremain_daily', $data);
            $paramspcvRow = $paramspcv->next_row();
        }
        $paramspv = $this->product->getProductVersionOffChannel();
        $paramspvRow = $paramspv->first_row();
        for ($i = 0; $i < $paramspv->num_rows(); $i++) {
            $devicecount=  $this->newusers->getNewdevices($countdate,$countdate, $paramspvRow->appId,'all', $paramspvRow->version);
            $day2 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 1);
            $day3 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 2);
            $day4 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 3);
            $day5 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 4);
            $day6 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 5);
            $day7 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 6);
            $day8 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 7);
            $day14 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 13);
            $day30 = $this->getRemainByNewddevice($countdate, $paramspvRow->appId,'all', $paramspvRow->version, 29);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramspvRow->appId,
                'version_name' => $paramspvRow->version,
                'channel_name' => 'all',
                'devicecount' => $devicecount,
                'day1' => ($devicecount==0)?0:($day2 / $devicecount),
                'day2' => ($devicecount==0)?0:($day3 / $devicecount),
                'day3' => ($devicecount==0)?0:($day4 / $devicecount),
                'day4' => ($devicecount==0)?0:($day5 / $devicecount),
                'day5' => ($devicecount==0)?0:($day6 / $devicecount),
                'day6' => ($devicecount==0)?0:($day7 / $devicecount),
                'day7' => ($devicecount==0)?0:($day8 / $devicecount),
                'day14' => ($devicecount==0)?0:($day14 / $devicecount),
                'day30' => ($devicecount==0)?0:($day30 / $devicecount)
            );
            $dwdb->insert_or_update('razor_sum_basic_deviceremain_daily', $data);
            $paramspvRow = $paramspv->next_row();
        }
        $paramspc = $this->product->getProductChannelOffVersion();
        $paramspcRow = $paramspc->first_row();
        for ($i = 0; $i < $paramspc->num_rows(); $i++) {
            $devicecount=  $this->newusers->getNewdevices($countdate,$countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            $day2 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 1);
            $day3 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 2);
            $day4 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 3);
            $day5 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 4);
            $day6 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 5);
            $day7 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 6);
            $day8 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 7);
            $day14 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 13);
            $day30 = $this->getRemainByNewddevice($countdate, $paramspcRow->appId, $paramspcRow->chId,'all', 29);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcRow->chId);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramspcRow->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'devicecount' => $devicecount,
                'day1' => ($devicecount==0)?0:($day2 / $devicecount),
                'day2' => ($devicecount==0)?0:($day3 / $devicecount),
                'day3' => ($devicecount==0)?0:($day4 / $devicecount),
                'day4' => ($devicecount==0)?0:($day5 / $devicecount),
                'day5' => ($devicecount==0)?0:($day6 / $devicecount),
                'day6' => ($devicecount==0)?0:($day7 / $devicecount),
                'day7' => ($devicecount==0)?0:($day8 / $devicecount),
                'day14' => ($devicecount==0)?0:($day14 / $devicecount),
                'day30' => ($devicecount==0)?0:($day30 / $devicecount)
            );
            $dwdb->insert_or_update('razor_sum_basic_deviceremain_daily', $data);
            $paramspcRow = $paramspc->next_row();
        }
        $paramsp = $this->product->getProductOffChannelVersion();
        $paramspRow = $paramsp->first_row();
        for ($i = 0; $i < $paramsp->num_rows(); $i++) {
            $devicecount=  $this->newusers->getNewdevices($countdate,$countdate, $paramspRow->appId,'all','all');
            $day2 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 1);
            $day3 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 2);
            $day4 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 3);
            $day5 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 4);
            $day6 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 5);
            $day7 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 6);
            $day8 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 7);
            $day14 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 13);
            $day30 = $this->getRemainByNewddevice($countdate, $paramspRow->appId,'all','all', 29);
            $data = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramspRow->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'devicecount' => $devicecount,
                'day1' => ($devicecount==0)?0:($day2 / $devicecount),
                'day2' => ($devicecount==0)?0:($day3 / $devicecount),
                'day3' => ($devicecount==0)?0:($day4 / $devicecount),
                'day4' => ($devicecount==0)?0:($day5 / $devicecount),
                'day5' => ($devicecount==0)?0:($day6 / $devicecount),
                'day6' => ($devicecount==0)?0:($day7 / $devicecount),
                'day7' => ($devicecount==0)?0:($day8 / $devicecount),
                'day14' => ($devicecount==0)?0:($day14 / $devicecount),
                'day30' => ($devicecount==0)?0:($day30 / $devicecount)
            );
            $dwdb->insert_or_update('razor_sum_basic_deviceremain_daily', $data);
            $paramspRow = $paramsp->next_row();
        }
    }
    
    /**
     * Sum_basic_customremain_daily function
     * count userremain by day
     * 
     * 
     */
    function sum_basic_customremain_daily($countdate) {
        $dwdb = $this->load->database('dw', true);
        $type = array("newuser","payuser","dauuser"); 
        foreach ($type as $value) {
            //type
            if($value=='newuser'){
                //logintimes
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $newusers,
                            'day1' => ($newusers==0)?0:($day2 / $newusers),
                            'day2' => ($newusers==0)?0:($day3 / $newusers),
                            'day3' => ($newusers==0)?0:($day4 / $newusers),
                            'day4' => ($newusers==0)?0:($day5 / $newusers),
                            'day5' => ($newusers==0)?0:($day6 / $newusers),
                            'day6' => ($newusers==0)?0:($day7 / $newusers),
                            'day7' => ($newusers==0)?0:($day8 / $newusers),
                            'day14' => ($newusers==0)?0:($day14 / $newusers),
                            'day30' => ($newusers==0)?0:($day30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                }
            }elseif ($value=='payuser') {
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $day2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1, $t);
                        $day3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2, $t);
                        $day4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3, $t);
                        $day5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4, $t);
                        $day6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5, $t);
                        $day7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6, $t);
                        $day8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7, $t);
                        $day14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13, $t);
                        $day30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $payusers,
                            'day1' => ($payusers==0)?0:($day2 / $payusers),
                            'day2' => ($payusers==0)?0:($day3 / $payusers),
                            'day3' => ($payusers==0)?0:($day4 / $payusers),
                            'day4' => ($payusers==0)?0:($day5 / $payusers),
                            'day5' => ($payusers==0)?0:($day6 / $payusers),
                            'day6' => ($payusers==0)?0:($day7 / $payusers),
                            'day7' => ($payusers==0)?0:($day8 / $payusers),
                            'day14' => ($payusers==0)?0:($day14 / $payusers),
                            'day30' => ($payusers==0)?0:($day30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                } 
            }elseif ($value=='dauuser') {
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $day2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1, $t);
                        $day3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2, $t);
                        $day4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3, $t);
                        $day5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4, $t);
                        $day6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5, $t);
                        $day7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6, $t);
                        $day8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7, $t);
                        $day14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13, $t);
                        $day30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'day1' => ($dauusers==0)?0:($day2 / $dauusers),
                            'day2' => ($dauusers==0)?0:($day3 / $dauusers),
                            'day3' => ($dauusers==0)?0:($day4 / $dauusers),
                            'day4' => ($dauusers==0)?0:($day5 / $dauusers),
                            'day5' => ($dauusers==0)?0:($day6 / $dauusers),
                            'day6' => ($dauusers==0)?0:($day7 / $dauusers),
                            'day7' => ($dauusers==0)?0:($day8 / $dauusers),
                            'day14' => ($dauusers==0)?0:($day14 / $dauusers),
                            'day30' => ($dauusers==0)?0:($day30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_daily', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                } 
            }
        }
    }

    /**
     * Sum_basic_customremain_daily function
     * count userremain by day
     * 
     * 
     */
    function sum_basic_customremain_weekly($countdate) {
        $dwdb = $this->load->database('dw', true);
        $type = array("newuser","payuser","dauuser"); 
        foreach ($type as $value) {
            //type
            if($value=='newuser'){
                //logintimes
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29*7, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29*7, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29*7, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29*7, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $newusers,
                            'week1' => ($newusers==0)?0:($week2 / $newusers),
                            'week2' => ($newusers==0)?0:($week3 / $newusers),
                            'week3' => ($newusers==0)?0:($week4 / $newusers),
                            'week4' => ($newusers==0)?0:($week5 / $newusers),
                            'week5' => ($newusers==0)?0:($week6 / $newusers),
                            'week6' => ($newusers==0)?0:($week7 / $newusers),
                            'week7' => ($newusers==0)?0:($week8 / $newusers),
                            'week14' => ($newusers==0)?0:($week14 / $newusers),
                            'week30' => ($newusers==0)?0:($week30 / $newusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                }
            }elseif ($value=='payuser') {
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29*7, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29*7, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29*7, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29*7, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $week2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1*7, $t);
                        $week3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2*7, $t);
                        $week4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3*7, $t);
                        $week5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4*7, $t);
                        $week6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5*7, $t);
                        $week7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6*7, $t);
                        $week8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7*7, $t);
                        $week14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13*7, $t);
                        $week30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $payusers,
                            'week1' => ($payusers==0)?0:($week2 / $payusers),
                            'week2' => ($payusers==0)?0:($week3 / $payusers),
                            'week3' => ($payusers==0)?0:($week4 / $payusers),
                            'week4' => ($payusers==0)?0:($week5 / $payusers),
                            'week5' => ($payusers==0)?0:($week6 / $payusers),
                            'week6' => ($payusers==0)?0:($week7 / $payusers),
                            'week7' => ($payusers==0)?0:($week8 / $payusers),
                            'week14' => ($payusers==0)?0:($week14 / $payusers),
                            'week30' => ($payusers==0)?0:($week30 / $payusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                } 
            }elseif ($value=='dauuser') {
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29*7, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29*7, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29*7, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29*7, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $week2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1*7, $t);
                        $week3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2*7, $t);
                        $week4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3*7, $t);
                        $week5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4*7, $t);
                        $week6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5*7, $t);
                        $week7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6*7, $t);
                        $week8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7*7, $t);
                        $week14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13*7, $t);
                        $week30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29*7, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'week1' => ($dauusers==0)?0:($week2 / $dauusers),
                            'week2' => ($dauusers==0)?0:($week3 / $dauusers),
                            'week3' => ($dauusers==0)?0:($week4 / $dauusers),
                            'week4' => ($dauusers==0)?0:($week5 / $dauusers),
                            'week5' => ($dauusers==0)?0:($week6 / $dauusers),
                            'week6' => ($dauusers==0)?0:($week7 / $dauusers),
                            'week7' => ($dauusers==0)?0:($week8 / $dauusers),
                            'week14' => ($dauusers==0)?0:($week14 / $dauusers),
                            'week30' => ($dauusers==0)?0:($week30 / $dauusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_weekly', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                } 
            }
        }
    }

    /**
     * Sum_basic_customremain_daily function
     * count userremain by day
     * 
     * 
     */
    function sum_basic_customremain_monthly($countdate) {
        $dwdb = $this->load->database('dw', true);
        $type = array("newuser","payuser","dauuser"); 
        foreach ($type as $value) {
            //type
            if($value=='newuser'){
                //logintimes
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29*30, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29*30, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29*30, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29*30, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $newusers=  $this->newcreaterole->getNewuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $newusers,
                            'month1' => ($newusers==0)?0:($month2 / $newusers),
                            'month2' => ($newusers==0)?0:($month3 / $newusers),
                            'month3' => ($newusers==0)?0:($month4 / $newusers),
                            'month4' => ($newusers==0)?0:($month5 / $newusers),
                            'month5' => ($newusers==0)?0:($month6 / $newusers),
                            'month6' => ($newusers==0)?0:($month7 / $newusers),
                            'month7' => ($newusers==0)?0:($month8 / $newusers),
                            'month14' => ($newusers==0)?0:($month14 / $newusers),
                            'month30' => ($newusers==0)?0:($month30 / $newusers) 
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                }
            }elseif ($value=='payuser') {
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29*30, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29*30, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29*30, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29*30, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $payusers=  $this->dauusers->getPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $month2 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1*30, $t);
                        $month3 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2*30, $t);
                        $month4 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3*30, $t);
                        $month5 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4*30, $t);
                        $month6 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5*30, $t);
                        $month7 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6*30, $t);
                        $month8 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7*30, $t);
                        $month14 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13*30, $t);
                        $month30 = $this->getRemainByPayuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $payusers,
                            'month1' => ($payusers==0)?0:($month2 / $payusers),
                            'month2' => ($payusers==0)?0:($month3 / $payusers),
                            'month3' => ($payusers==0)?0:($month4 / $payusers),
                            'month4' => ($payusers==0)?0:($month5 / $payusers),
                            'month5' => ($payusers==0)?0:($month6 / $payusers),
                            'month6' => ($payusers==0)?0:($month7 / $payusers),
                            'month7' => ($payusers==0)?0:($month8 / $payusers),
                            'month14' => ($payusers==0)?0:($month14 / $payusers),
                            'month30' => ($payusers==0)?0:($month30 / $payusers)  
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                } 
            }elseif ($value=='dauuser') {
                for($t=1;$t<4;$t++){
                    $params_psv = $this->product->getProductServerVersionOffChannel();
                    $paramsRow_psv = $params_psv->first_row();
                    for ($i = 0; $i < $params_psv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version, 29*30, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_psv->appId,
                            'version_name' => $paramsRow_psv->version,
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_psv = $params_psv->next_row();
                    }
                    $params_ps = $this->product->getProductServerOffChannelVersion();
                    $paramsRow_ps = $params_ps->first_row();
                    for ($i = 0; $i < $params_ps->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all', 29*30, $t);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_ps->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_ps = $params_ps->next_row();
                    }
                    $params_pv = $this->product->getProductVersionOffChannelServer();
                    $paramsRow_pv = $params_pv->first_row();
                    for ($i = 0; $i < $params_pv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, 29*30, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pv->appId,
                            'version_name' => $paramsRow_pv->version,
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pv = $params_pv->next_row();
                    }
                    $params_p = $this->product->getProductOffChannelServerVersion();
                    $paramsRow_p = $params_p->first_row();
                    for ($i = 0; $i < $params_p->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_p->appId, 'all', 'all', 'all', 29*30, $t);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_p->appId,
                            'version_name' => 'all',
                            'channel_name' => 'all',
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_p = $params_p->next_row();
                    }
                    $params_pcsv = $this->product->getProductChannelServerVersion();
                    $paramsRow_pcsv = $params_pcsv->first_row();
                    for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcsv->appId,
                            'version_name' => $paramsRow_pcsv->version,
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcsv = $params_pcsv->next_row();
                    }
                    $params_pcs = $this->product->getProductChannelServerOffVersion();
                    $paramsRow_pcs = $params_pcs->first_row();
                    for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pcs->chId, $paramsRow_psv->srvId, 'all', 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
                        //get servername by serverid
                        $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcs->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => $server_name,
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcs = $params_pcs->next_row();
                    }
                    $params_pcv = $this->product->getProductChannelVersionOffServer();
                    $paramsRow_pcv = $params_pcv->first_row();
                    for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pcv->appId,
                            'version_name' => $paramsRow_pcv->version,
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pcv = $params_pcv->next_row();
                    }
                    $params_pc = $this->product->getProductChannelOffServerVersion();
                    $paramsRow_pc = $params_pc->first_row();
                    for ($i = 0; $i < $params_pc->num_rows(); $i++) {
                        $dauusers=  $this->dauusers->getDauuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
                        $month2 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 1*30, $t);
                        $month3 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 2*30, $t);
                        $month4 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 3*30, $t);
                        $month5 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 4*30, $t);
                        $month6 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 5*30, $t);
                        $month7 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 6*30, $t);
                        $month8 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 7*30, $t);
                        $month14 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 13*30, $t);
                        $month30 = $this->getRemainByNewuserAndStarttime($countdate, $paramsRow_psv->appId, $paramsRow_pc->chId, 'all', 'all', 29*30, $t);
                        //get channelname by channelid
                        $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
                        $data = array(
                            'startdate_sk' => $countdate,
                            'enddate_sk' => $countdate,
                            'type' => $value,
                            'logintimes' => $t,
                            'product_id' => $paramsRow_pc->appId,
                            'version_name' => 'all',
                            'channel_name' => $channel_name,
                            'server_name' => 'all',
                            'count' => $dauusers,
                            'month1' => ($dauusers==0)?0:($month2 / $dauusers),
                            'month2' => ($dauusers==0)?0:($month3 / $dauusers),
                            'month3' => ($dauusers==0)?0:($month4 / $dauusers),
                            'month4' => ($dauusers==0)?0:($month5 / $dauusers),
                            'month5' => ($dauusers==0)?0:($month6 / $dauusers),
                            'month6' => ($dauusers==0)?0:($month7 / $dauusers),
                            'month7' => ($dauusers==0)?0:($month8 / $dauusers),
                            'month14' => ($dauusers==0)?0:($month14 / $dauusers),
                            'month30' => ($dauusers==0)?0:($month30 / $dauusers)
                        );
                        $dwdb->insert_or_update('razor_sum_basic_customremain_monthly', $data);
                        $paramsRow_pc = $params_pc->next_row();
                    }
                } 
            }
        }
    }

}
