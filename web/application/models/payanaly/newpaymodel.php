<?php
set_time_limit(0);
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
 * Dauusersmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Newpaymodel extends CI_Model
{
	/** 
	 * Construct load
	 * Construct function
	 * 
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	  	$this->load->model("product/productmodel", 'product');
		$this->load->model("channelmodel", 'channel');
	    $this->load->model("servermodel", 'server');
	    $this->load->model("useranalysis/dauusersmodel", 'dauusers');
	}

	function getNewPayData($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->NewPayData($fromTime,$toTime,$channel,$version,$server);
		$dauUsersRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow["date"] = $dauUsersRow->date_sk;
			$fRow['firstdaypayuser'] = $dauUsersRow->firstdaypayuser;
			$fRow['firstdaypaycount'] = $dauUsersRow->firstdaypaycount;
			$fRow['firstweekpayuser'] = $dauUsersRow->firstweekpayuser;
			$fRow['firstweekpaycount'] = $dauUsersRow->firstweekpaycount;
			$fRow['firstmonthpayuser'] = $dauUsersRow->firstmonthpayuser;
			$fRow['firstmonthpaycount'] = $dauUsersRow->firstmonthpaycount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	/**
	 * GetDetailDauUsersData function
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
	function NewPayData($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(date_sk, 0) date_sk,
					IFNULL(firstdaypayuser, 0) firstdaypayuser,
					IFNULL(firstdaypaycount, 0) firstdaypaycount,
					IFNULL(firstweekpayuser, 0) firstweekpayuser,
					IFNULL(firstweekpaycount, 0) firstweekpaycount,
					IFNULL(firstmonthpayuser, 0) firstmonthpayuser,
					IFNULL(firstmonthpaycount, 0) firstmonthpaycount
				FROM
					".$dwdb->dbprefix('sum_basic_newpay')."
				WHERE
				date_sk >= '" . $fromTime . "'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				Order By date_sk DESC";
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
	  * GetNewPayuser function
	  * get new pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int new pay user
	  */
	 function getNewPayuser($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$days=0) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						#AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						AND p.srvId = '$serverid'
						#AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						AND p.version = '$versionname';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(DISTINCT p.roleId) pay_count
						FROM
							razor_pay p
						WHERE
							p.pay_date >= '$date'
						AND p.pay_date <= DATE_ADD('$date', INTERVAL $days DAY)
						AND p.roleId IN (
							SELECT
								c.roleId
							FROM
								razor_createrole c
							WHERE
								c.create_role_date = '$date'
						)
						AND p.appId = '$appid'
						AND p.chId = '$channelid'
						#AND p.srvId = '$serverid'
						#AND p.version = '$versionname';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->pay_count;
		  }
	 }

	 	 /**
	  * Sum_basic_newpay function
	  * count dau users
	  * 
	  * 
	  */
	 function sum_basic_newpay($countdate) {
		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$days=30);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_psv->appId,
					 'version_name' => $paramsRow_psv->version,
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$days=30);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_ps->appId,
					 'version_name' => 'all',
					 'channel_name' => 'all',
					 'server_name' => $server_name,
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$days=30);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pv->appId,
					 'version_name' => $paramsRow_pv->version,
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$days=30);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_p->appId,
					 'version_name' => 'all',
					 'channel_name' => 'all',
					 'server_name' => 'all',
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$days=30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcsv->appId,
					 'version_name' => $paramsRow_pcsv->version,
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$days=30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcs->appId,
					 'version_name' => 'all',
					 'channel_name' => $channel_name,
					 'server_name' => $server_name,
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$days=30);

				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pcv->appId,
					 'version_name' => $paramsRow_pcv->version,
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
				$newuser = $this->dauusers->getNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
				$newpayuser_day = $this->getNewPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$days=0);
				$newpayuser_week = $this->getNewPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$days=7);
				$newpayuser_month = $this->getNewPayuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$days=30);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
				$data = array(
					 'date_sk' => $countdate,
					 'product_id' => $paramsRow_pc->appId,
					 'version_name' => 'all',
					 'channel_name' => $channel_name,
					 'server_name' => 'all',
					 'firstdaypayuser' => $newpayuser_day,
					 'firstdaypaycount' => ($newuser==0)?0:($newpayuser_day/$newuser),
					 'firstweekpayuser' => $newpayuser_week,
					 'firstweekpaycount' => ($newuser==0)?0:($newpayuser_week/$newuser),
					 'firstmonthpayuser' => $newpayuser_month,
					 'firstmonthpaycount' => ($newuser==0)?0:($newpayuser_month/$newuser)
				);
				$dwdb = $this->load->database('dw', true);
				$dwdb->insert_or_update('razor_sum_basic_newpay', $data);
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }

}
