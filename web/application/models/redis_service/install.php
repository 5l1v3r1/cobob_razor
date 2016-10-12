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
 * Install Model
 *
 * create role info
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Install extends CI_Model
{


    /** 
     * Install load 
     * Install function 
     * 
     * @return void 
     */
    function Install()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> helper("date");
        $this -> load -> library('redis');
        $this -> load -> model('redis_service/utility', 'utility');
    }
    
    /** 
     * Add install data 
     * AddInstall function 
     * 
     * @param string $install install 
     * 
     * @return void 
     */
    function addInstall($install)
    {
        //get productId by appkey
        $productId = $this -> utility -> getProductIdByKey($install -> productkey);
        //For realtime User sessions
        $key = "razor_r_u_p_" . $productId . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($key, array($data["deviceid"] => $productId));
        //set expire time.unity is s.
        $this -> redis -> expire($key, 30 * 60);
        
        $nowtime = date('Y-m-d H:i:s');
        if (isset($install -> install_time)) {
            $nowtime = $install -> install_time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $todayDate = date ( "Y-m-d", $nowtime );
        $data = array(
            'install_date' => $todayDate,
            'chId' => $install->chId,
            'version' => $install->version,
            'userId' => $install->userId,
            'productkey' => $install->productkey,
            'deviceid' => $install->deviceid,
            'install_time' => $nowtime,
        );

        $this -> redis -> lpush("razor_install", serialize($data));

        $this -> processor -> process();
    }

}
?>
