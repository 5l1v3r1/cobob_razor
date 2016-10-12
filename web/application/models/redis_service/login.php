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
 * Login Model
 *
 * create role info
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Login extends CI_Model
{


    /** 
     * Login load 
     * Login function 
     * 
     * @return void 
     */
    function Login()
    {
        parent::__construct();
        $this->load->model('utility');
        $this -> load -> database();
        $this -> load -> helper("date");
        $this -> load -> library('redis');
        $this -> load -> model('redis_service/utility', 'utility');
    }
    
    /** 
     * Add login data 
     * AddLogin function 
     * 
     * @param string $login login 
     * 
     * @return void 
     */
    function addLogin($login)
    {
        $data[]=[];
        //get productId by appkey
        $productId = $this -> utility -> getProductIdByKey($login -> productkey);
        //For realtime User sessions
        $key = "razor_r_u_p_" . $productId . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($key, array($data["deviceid"] => $productId));
        //set expire time.unity is s.
        $this -> redis -> expire($key, 30 * 60);
        
        $ip = $this->utility->getOnlineIP();
        $nowtime = date('Y-m-d H:i:s');
        if (isset($login -> login_time)) {
            $nowtime = $login -> login_time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $todayDate = date ( "Y-m-d", $nowtime );
        $data = ['login_date' => $todayDate,
            'chId' => $login->chId,
            'subSrvId' => isset($login->subSrvId) ? $login->subSrvId : '',
            'srvId' => isset($login->srvId) ? $login->srvId : '',
            'appId' => $login->appId,
            'version' => $login->resolution,
            'type' => $login->type,
            'offlineSettleTime' => isset($login->offlineSettleTime) ? $login->offlineSettleTime : '',
            'obligate1' => isset($login->obligate1) ? $login->obligate1 : '',
            'obligate2' => isset($login->obligate2) ? $login->obligate2 : '',
            'obligate3' => isset($login->obligate3) ? $login->obligate3 : '',
            'obligate4' => isset($login->obligate4) ? $login->obligate4 : '',
            'userId' => $login->userId,
            'roleId' => $login->roleId,
            'roleLevel' => isset($login->roleLevel) ? $login->roleLevel : 0,
            'roleVip' => isset($login->roleVip) ? $login->roleVip : 0,
            'goldCoin' => isset($login->goldCoin) ? $login->goldCoin : 0,
            'sliverCoin' => isset($login->sliverCoin) ? $login->sliverCoin : 0,
            'productkey' => $login->productkey,
            'login_time' => $nowtime,
            'deviceid' => $login->deviceid,
            'ip' => $ip,
            ];

        $this -> redis -> lpush("razor_login", serialize($data));

        $this -> processor -> process();
    }

}
