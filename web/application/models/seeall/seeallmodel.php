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
 * Seeallmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Seeallmodel extends CI_Model {

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
        $this->load->model('useranalysis/newusersmodel', 'newusers');
        $this->load->model('useranalysis/dauusersmodel', 'dauusers');
        $this->load->model('payanaly/paydatamodel', 'paydata');
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
    function getDeviceactivations($fromdate,$todate, $userid) {
        $sql = "SELECT
					IFNULL(
						count(i.deviceid),
						0
					) deviceactivations
				FROM
					razor_install i
				WHERE
					i.install_date >= '$fromdate'
				AND i.install_date <= '$todate'
				AND i.appId IN (
					SELECT
						t.product_id
					FROM
						razor_user2product t
					WHERE
						t.user_id = $userid
				);";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->deviceactivations;
        }
    }

    /**
     * getRegisteruser function
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
    function getRegisteruser($fromdate,$todate, $userid) {
        $sql = "SELECT
                    IFNULL(count(r.userId),0) newusers
            FROM
                    razor_register r                      
            WHERE
                    r.register_date >= '$fromdate'
                    AND r.register_date <='$todate'
                    AND r.appId IN (
					SELECT
						t.product_id
					FROM
						razor_user2product t
					WHERE
						t.user_id = $userid
				);";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newusers;
        }
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
	 function getNewuser($fromdate,$todate, $userid) {
		$sql = "SELECT
						count(distinct c.userId) newuser
				 FROM
							razor_createrole c
				 WHERE
							c.create_role_date >= '$fromdate'
							AND c.create_role_date <= '$todate'
							AND c.appId  IN (
					SELECT
						t.product_id
					FROM
						razor_user2product t
					WHERE
						t.user_id = $userid
				);";
		$query = $this->db->query($sql);
		$row = $query->first_row();
		if ($query->num_rows > 0) {
			return $row->newuser;
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
	 function getDauuser($fromdate,$todate, $userid) {
		$sql = "
				  SELECT
							 COUNT(DISTINCT l.roleId) dauuser
				  FROM
							 razor_login l
				  WHERE
					l.login_date >= '$fromdate'
				  AND l.login_date <= '$todate'
				  AND l.appId  IN (
					SELECT
						t.product_id
					FROM
						razor_user2product t
					WHERE
						t.user_id = $userid
				);";
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->dauuser;
		  }
	 }

	 	 /**
	  * GetPayuser function
	  * get pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int payuser
	  */
	 function getPayuser($fromdate,$todate, $userid) {
			$sql = "SELECT
							count(DISTINCT p.roleId) payuser
				 FROM
							" . $this->db->dbprefix('pay') . " p
				 WHERE
							p.pay_date >= '$fromdate'
							AND p.pay_date <= '$todate'
							AND p.appId IN (
				SELECT
					t.product_id
				FROM
					razor_user2product t
				WHERE
					t.user_id = $userid
			);";
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->payuser;
		  }
	 }

     /**
     * GetPayamount function
     * get pay user from dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getPayamount($fromdate,$todate, $userid) {
        $sql = "SELECT
                        IFNULL(SUM(p.pay_amount), 0) payamount
                FROM
                        razor_pay p
                WHERE
                    p.pay_date >= '$fromdate' 
                AND p.pay_date <= '$todate'
                AND p.appId  IN (
					SELECT
						t.product_id
					FROM
						razor_user2product t
					WHERE
						t.user_id = $userid
				);";
        $query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->payamount;
        }
    }
	 
	 

	 /**
	  * gatherall function
	  *
	  * @param $appId product id
	  * 
	  * @return $data gatherall
	  */
	 function gatherall($userid) {
		 	$today=date('Y-m-d');
		 	// 今日-设备激活
			$today_Deviceactivations= $this->getDeviceactivations($today,$today, $userid);
			// 今日-新增注册
			$today_Registeruser= $this->getRegisteruser($today,$today, $userid);
			// 今日-新增用户
			$today_Newuser= $this->getNewuser($today,$today, $userid);
			// 今日-活跃用户
			$today_Dauuser= $this->getDauuser($today,$today, $userid);
			// 今日-付费人数
			$today_Payuser= $this->getPayuser($today,$today, $userid);
			// 今日-付费金额
			$today_Payamount= $this->getPayamount($today,$today, $userid);

			// 累计-设备激活
			$total_Deviceactivations= $this->getDeviceactivations('1970-01-01',$today, $userid);
			// 累计-新增注册
			$total_Registeruser= $this->getRegisteruser('1970-01-01',$today, $userid);
			// 累计-新增用户
			$total_Newuser= $this->getNewuser('1970-01-01',$today, $userid);
			// 累计-活跃用户
			$total_Dauuser= $this->getDauuser('1970-01-01',$today, $userid);
			// 累计-付费人数
			$total_Payuser= $this->getPayuser('1970-01-01',$today, $userid);
			// 累计-付费金额
			$total_Payamount= $this->getPayamount('1970-01-01',$today, $userid);

			$data = array(
				 'today_Deviceactivations' => $today_Deviceactivations,
				 'today_Registeruser' => $today_Registeruser,
				 'today_Newuser' => $today_Newuser,
				 'today_Dauuser' => $today_Dauuser,
				 'today_Payuser' => $today_Payuser,
				 'today_Payamount' => $today_Payamount,
				 'total_Deviceactivations' => $total_Deviceactivations,
				 'total_Registeruser' => $total_Registeruser,
				 'total_Newuser' => $total_Newuser,
				 'total_Dauuser' => $total_Dauuser,
				 'total_Payuser' => $total_Payuser,
				 'total_Payamount' => $total_Payamount
			);
			return $data;
	  }


	  	 /**
	  * gatherall function
	  *
	  * @param $appId product id
	  * 
	  * @return $data gatherall
	  */
	 function gathertochart($date,$userid) {
		 	// 设备激活
			$Deviceactivations= $this->getDeviceactivations($date,$date, $userid);
			// 新增注册
			$Registeruser= $this->getRegisteruser($date,$date, $userid);
			// 新增用户
			$Newuser= $this->getNewuser($date,$date, $userid);
			// 活跃用户
			$Dauuser= $this->getDauuser($date,$date, $userid);
			// 付费人数
			$Payuser= $this->getPayuser($date,$date, $userid);
			// 付费金额
			$Payamount= $this->getPayamount($date,$date, $userid);

			$data = array(
				 'Date' => $date,
				 'Deviceactivations' => $Deviceactivations,
				 'Registeruser' => $Registeruser,
				 'Newuser' => $Newuser,
				 'Dauuser' => $Dauuser,
				 'Payuser' => $Payuser,
				 'Payamount' => $Payamount
			);
			return $data;
	  }
          
      /**
	  * gatherallpergame function
	  *
	  * @param $appId product id
	  * 
	  * @return $data gatherall per game
	  */
	 function gatherallpergame($appid) {
	 	$today=date('Y-m-d');
	 	$this->load->model("useranalysis/newcreaterolemodel", 'newcreaterole');
	 	// 今日-设备激活
		$today_Deviceactivations= $this->newusers->getDeviceactivations($today,$today, $appid, 'all', 'all');
		// 今日-新增注册
		$today_Registeruser= $this->newusers->getNewusers($today,$today, $appid, 'all', 'all');
		// 今日-新增用户
		$today_Newuser= $this->newcreaterole->getNewuser($today,$today, $appid, 'all', 'all', 'all');
		// 今日-活跃用户
		$today_Dauuser= (int)$this->dauusers->getDauuser($today,$today, $appid, 'all', 'all', 'all');
		// 今日-付费人数
		$today_Payuser= (int)$this->dauusers->getPayuser($today,$today, $appid, 'all', 'all', 'all');
		// 今日-付费金额
		$today_Payamount= (int)$this->paydata->getPayamount($today,$today, $appid, 'all', 'all', 'all');

		// 累计-设备激活
		$total_Deviceactivations= $this->newusers->getDeviceactivations('1970-01-01',$today, $appid, 'all', 'all');
		// 累计-新增注册
		$total_Registeruser= $this->newusers->getNewusers('1970-01-01',$today, $appid, 'all', 'all');
		// 累计-新增用户
		$total_Newuser= $this->newcreaterole->getNewuser('1970-01-01',$today, $appid, 'all', 'all', 'all');
		// 累计-活跃用户
		$total_Dauuser= (int)$this->dauusers->getDauuser('1970-01-01',$today, $appid, 'all', 'all', 'all');
		// 累计-付费人数
		$total_Payuser= (int)$this->dauusers->getPayuser('1970-01-01',$today, $appid, 'all', 'all', 'all');
		// 累计-付费金额
		$total_Payamount= (int)$this->paydata->getPayamount('1970-01-01',$today, $appid, 'all', 'all', 'all');

		$this->db->select('name');
		$this->db->where('id',$appid);
		$res = $this->db->get('razor_product')->result();
		$gameName = $res[0]->name;


		$data = array(
			'id' => $appid,
			'name' => $gameName,
			'today_Deviceactivations' => $today_Deviceactivations,
			'today_Registeruser' => $today_Registeruser,
			'today_Newuser' => $today_Newuser,
			'today_Dauuser' => $today_Dauuser,
			'today_Payuser' => $today_Payuser,
			'today_Payamount' => $today_Payamount,
			'today_Payarpu' => (int)(($today_Dauuser==0)?0:($today_Payamount/$today_Dauuser)),
			'today_Payarrpu' => (int)(($today_Payuser==0)?0:($today_Payamount/$today_Payuser)),
			'today_Payrate' => (int)(($today_Dauuser==0)?0:($today_Payuser/$today_Dauuser)),
			'total_Deviceactivations' => $total_Deviceactivations,
			'total_Registeruser' => $total_Registeruser,
			'total_Newuser' => $total_Newuser,
			'total_Dauuser' => $total_Dauuser,
			'total_Payuser' => $total_Payuser,
			'total_Payamount' => $total_Payamount,
			'total_Payarpu' => (int)(($total_Dauuser==0)?0:($total_Payamount/$total_Dauuser)),
			'total_Payarrpu' => (int)(($total_Payuser==0)?0:($total_Payamount/$total_Payuser)),
			'total_Payrate' => (int)(($total_Dauuser==0)?0:($total_Payamount/$total_Dauuser))
		);
		return $data;
	  }

}
