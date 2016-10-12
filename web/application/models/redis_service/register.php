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
 * Register Model
 *
 * create role info
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Register extends CI_Model
{


    /** 
     * Register load 
     * Register function 
     * 
     * @return void 
     */
    function Register()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> helper("date");
        $this -> load -> library('redis');
        $this -> load -> model('redis_service/utility', 'utility');
    }
    
    /** 
     * Add register data 
     * AddRegister function 
     * 
     * @param string $register register 
     * 
     * @return void 
     */
    function addRegister($register)
    {
        //get productId by appkey
        $productId = $this -> utility -> getProductIdByKey($register -> productkey);
        //For realtime User sessions
        $key = "razor_r_u_p_" . $productId . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($key, array($data["deviceid"] => $productId));
        //set expire time.unity is s.
        $this -> redis -> expire($key, 30 * 60);
        
        $nowtime = date('Y-m-d H:i:s');
        if (isset($register -> register_time)) {
            $nowtime = $register -> register_time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $todayDate = date ( "Y-m-d", $nowtime );
        $data = array(
            'register_date' => $todayDate,
            'chId' => $register->chId,
            'appId' => $register->appId,
            'version' => $register->resolution,
            'obligate1' => isset($register->obligate1) ? $register->obligate1 : '',
            'obligate2' => isset($register->obligate2) ? $register->obligate2 : '',
            'obligate3' => isset($register->obligate3) ? $register->obligate3 : '',
            'obligate4' => isset($register->obligate4) ? $register->obligate4 : '',
            'userId' => $register->userId,
            'productkey' => $register->productkey,
            'deviceid' => $register->deviceid,
            'register_time' => $nowtime,
        );

        $this -> redis -> lpush("razor_register", serialize($data));

        $this -> processor -> process();
    }

}
?>
