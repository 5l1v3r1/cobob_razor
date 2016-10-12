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
 * Createrole Model
 *
 * create role info
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Createrole extends CI_Model
{


    /** 
     * Createrole load 
     * Createrole function 
     * 
     * @return void 
     */
    function Createrole()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> helper("date");
        $this -> load -> library('redis');
        $this -> load -> model('redis_service/utility', 'utility');
    }
    
    /** 
     * Add createrole data 
     * AddCreaterole function 
     * 
     * @param string $createrole createrole 
     * 
     * @return void 
     */
    function addCreaterole($createrole)
    {
        //get productId by appkey
        $productId = $this -> utility -> getProductIdByKey($createrole -> productkey);
        //For realtime User sessions
        $key = "razor_r_u_p_" . $productId . "_" . date('Y-m-d-H-i', time());
        $this -> redis -> hset($key, array($data["deviceid"] => $productId));
        //set expire time.unity is s.
        $this -> redis -> expire($key, 30 * 60);
        
        $nowtime = date('Y-m-d H:i:s');
        if (isset($createrole -> create_role_time)) {
            $nowtime = $createrole -> create_role_time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $todayDate = date ( "Y-m-d", $nowtime );
        $data = array(
            'create_role_date' => $todayDate,
            'chId' => $createrole->chId,
            'subSrvId' => isset($createrole->subSrvId) ? $createrole->subSrvId : '',
            'srvId' => isset($createrole->srvId) ? $createrole->srvId : '',
            'appId' => $createrole->appId,
            'version' => $createrole->resolution,
            'obligate1' => isset($createrole->obligate1) ? $createrole->obligate1 : '',
            'obligate2' => isset($createrole->obligate2) ? $createrole->obligate2 : '',
            'obligate3' => isset($createrole->obligate3) ? $createrole->obligate3 : '',
            'obligate4' => isset($createrole->obligate4) ? $createrole->obligate4 : '',
            'obligate5' => isset($createrole->obligate5) ? $createrole->obligate5 : '',
            'obligate6' => isset($createrole->obligate6) ? $createrole->obligate6 : '',
            'userId' => $createrole->userId,
            'productkey' => $createrole->productkey,
            'deviceid' => $createrole->deviceid,
            'create_role_time' => $nowtime,
            'roleId' => $createrole->roleId,
            'roleName' => $createrole->roleName,
            'roleLevel' => isset($createrole->roleLevel) ? $createrole->roleLevel : 0,
            'roleSex' => isset($createrole->roleSex) ? $createrole->roleSex : '',
            'roleVip' => isset($createrole->roleVip) ? $createrole->roleVip : 0,
            'goldCoin' => isset($createrole->goldCoin) ? $createrole->goldCoin : 0,
            'sliverCoin' => isset($createrole->sliverCoin) ? $createrole->sliverCoin : 0,
        );

        $this -> redis -> lpush("razor_createrole", serialize($data));

        $this -> processor -> process();
    }

}
?>
