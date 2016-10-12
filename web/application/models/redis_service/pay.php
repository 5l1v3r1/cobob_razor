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
 * Pay Model
 *
 * create role info
 *
 * @category PHP
 * @package Model
 * @author Cobub Team <open.cobub@gmail.com>
 * @license http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link http://www.cobub.com
 */
class Pay extends CI_Model {
	
	/**
	 * Pay load
	 * Pay function
	 *
	 * @return void
	 */
	function Pay() {
		parent::__construct ();
		$this->load->database ();
		$this->load->helper ( "date" );
		$this->load->library ( 'redis' );
		$this->load->model ( 'redis_service/utility', 'utility' );
	}
	
	/**
	 * Add pay data
	 * AddPay function
	 *
	 * @param string $pay
	 *        	pay
	 *        	
	 * @return void
	 */
	function addPay($pay) {
		// get productId by appkey
		$productId = $this->utility->getProductIdByKey ( $pay->productkey );
		// For realtime User sessions
		$key = "razor_r_u_p_" . $productId . "_" . date ( 'Y-m-d-H-i', time () );
		$this->redis->hset ( $key, array (
				$data ["deviceid"] => $productId 
		) );
		// set expire time.unity is s.
		$this->redis->expire ( $key, 30 * 60 );
		
		$nowtime = date ( 'Y-m-d H:i:s' );
		if (isset ( $pay->pay_time )) {
			$nowtime = $pay->pay_time;
			if (strtotime ( $nowtime ) < strtotime ( '1970-01-01 00:00:00' ) || strtotime ( $nowtime ) == '') {
				$nowtime = date ( 'Y-m-d H:i:s' );
			}
		}
		$todayDate = date ( "Y-m-d", $nowtime );
		$data = array (
				'pay_date' => $todayDate,
				'chId' => $pay->chId,
				'subSrvId' => isset ( $pay->subSrvId ) ? $pay->subSrvId : '',
				'srvId' => isset ( $pay->srvId ) ? $pay->srvId : '',
				'version' => $pay->resolution,
				'obligate1' => isset ( $pay->obligate1 ) ? $pay->obligate1 : '',
				'obligate2' => isset ( $pay->obligate2 ) ? $pay->obligate2 : '',
				'obligate3' => isset ( $pay->obligate3 ) ? $pay->obligate3 : '',
				'obligate4' => isset ( $pay->obligate4 ) ? $pay->obligate4 : '',
				'userId' => $pay->userId,
				'productkey' => $pay->productkey,
				'deviceid' => $pay->deviceid,
				'pay_time' => $nowtime,
				'roleId' => $pay->roleId,
				'roleName' => $pay->roleName,
				'roleLevel' => isset ( $pay->roleLevel ) ? $pay->roleLevel : 0,
				'roleVip' => isset ( $pay->roleVip ) ? $pay->roleVip : 0,
				'goldCoin' => isset ( $pay->goldCoin ) ? $pay->goldCoin : 0,
				'sliverCoin' => isset ( $pay->sliverCoin ) ? $pay->sliverCoin : 0,
				'pay_unit' => $pay->pay_unit,
				'pay_type' => $pay->pay_type,
				'pay_amount' => $pay->pay_amount,
				'coinAmount' => isset ( $pay->coinAmount ) ? $pay->coinAmount : 0,
				'orderId' => $pay->orderId 
		);
		
		$this->redis->lpush ( "razor_pay", serialize ( $data ) );
		
		$this->processor->process ();
	}
}
?>
