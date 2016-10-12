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
 * Dauusersmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Payratemodel extends CI_Model
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
	    $this->load->model('common');
	    $this->load->model("useranalysis/dauusersmodel", 'dauusers');
	}
	//日付费率
	function getPayrateDataByDay($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->PayrateDataByDay($fromTime,$toTime,$channel,$version,$server);
		$dauUsersRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow["date"] = $dauUsersRow->startdate_sk;
			$fRow['daypayrate'] = $dauUsersRow->daypayrate;
			$fRow['totalpayrate'] = $dauUsersRow->totalpayrate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function PayrateDataByDay($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(daypayrate, 0) daypayrate,
					IFNULL(totalpayrate, 0) totalpayrate
				FROM
					".$dwdb->dbprefix('sum_basic_payrate_daily')."
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				Order By startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	//周付费率
	function getPayrateDataByWeek($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->PayrateDataByWeek($fromTime,$toTime,$channel,$version,$server);
		$dauUsersRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
			$fRow['enddate_sk'] = $dauUsersRow->enddate_sk;
			$fRow['weekpayrate'] = $dauUsersRow->weekpayrate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function PayrateDataByWeek($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(enddate_sk, 0) enddate_sk,
					IFNULL(weekpayrate, 0) weekpayrate
				FROM
					".$dwdb->dbprefix('sum_basic_payrate_weekly')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND (startdate_sk >= '" . $fromTime . "' OR enddate_sk <= '" . $toTime . "')
				Order By startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	//月付费率
	function getPayrateDataByMonth($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->PayrateDataByMonth($fromTime,$toTime,$channel,$version,$server);
		$dauUsersRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
			$fRow['monthpayrate'] = $dauUsersRow->monthpayrate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function PayrateDataByMonth($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(monthpayrate, 0) monthpayrate
				FROM
					".$dwdb->dbprefix('sum_basic_payrate_monthly')." 
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND (startdate_sk >= '" . $fromTime . "' OR enddate_sk <= '" . $toTime . "')
				Order By startdate_sk DESC";
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
	  * Sum_basic_dauusers function
	  * count dau users
	  * 
	  * 
	  */
	function sum_basic_payrate($countdate) {
		$this_monday=$this->common->this_monday(0,false);
		$month_firstday=$this->common->month_firstday(0,false);
		$dwdb = $this->load->database('dw', true);
		
		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_psv = $params_psv->next_row();
		  }
		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_ps = $params_ps->next_row();
		}
		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pv = $params_pv->next_row();
		}
		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcsv = $params_pcsv->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcv = $params_pcv->next_row();
		}

		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			$dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pc = $params_pc->next_row();
		}
		}


			 /**
	  * Sum_basic_dauusers function
	  * count dau users
	  * 
	  * 
	  */
	function sum_basic_payrate_week($countdate) {
		$this_monday=$this->common->this_monday(0,false);
		$month_firstday=$this->common->month_firstday(0,false);
		//得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
		$dwdb = $this->load->database('dw', true);
		
		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_psv = $params_psv->next_row();
		  }
		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_ps = $params_ps->next_row();
		}
		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pv = $params_pv->next_row();
		}
		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcsv = $params_pcsv->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcv = $params_pcv->next_row();
		}

		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_week = $this->dauusers->getDauuser($last_monday,$last_sunday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_month = $this->dauusers->getDauuser($month_firstday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_week = $this->dauusers->getPayuser($last_monday,$last_sunday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_month = $this->dauusers->getPayuser($month_firstday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $last_monday,
				 'enddate_sk' => $last_sunday,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			$dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pc = $params_pc->next_row();
		}
		}


				 /**
	  * Sum_basic_dauusers function
	  * count dau users
	  * 
	  * 
	  */
	function sum_basic_payrate_month($countdate) {
		$this_monday=$this->common->this_monday(0,false);
		$month_firstday=$this->common->month_firstday(0,false);
		//得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
		$dwdb = $this->load->database('dw', true);
		
		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_psv->appId,
				 'version_name' => $paramsRow_psv->version,
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_psv = $params_psv->next_row();
		  }
		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_ps->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_ps = $params_ps->next_row();
		}
		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_pv->appId,
				 'version_name' => $paramsRow_pv->version,
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pv = $params_pv->next_row();
		}
		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_p->appId,
				 'version_name' => 'all',
				 'channel_name' => 'all',
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_pcsv->appId,
				 'version_name' => $paramsRow_pcsv->version,
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcsv = $params_pcsv->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_pcs->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => $server_name,
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_pcv->appId,
				 'version_name' => $paramsRow_pcv->version,
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pcv = $params_pcv->next_row();
		}

		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$dauuser_day = $this->dauusers->getDauuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_day = $this->dauusers->getPayuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_week = $this->dauusers->getDauuser($this_monday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_month = $this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_week = $this->dauusers->getPayuser($this_monday,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_month = $this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$dauuser_total = $this->dauusers->getDauuser('1970-01-01',$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$payuser_total = $this->dauusers->getPayuser('1970-01-01',$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
			$data_daily = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'daypayrate' => ($dauuser_day==0)?0:($payuser_day/$dauuser_day),
				 'totalpayrate' => ($dauuser_total==0)?0:($payuser_total/$dauuser_total)
			);
			$data_weekly = array(
				 'startdate_sk' => $countdate,
				 'enddate_sk' => $countdate,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'weekpayrate' => ($dauuser_week==0)?0:($payuser_week/$dauuser_week)
			);
			$data_monthly = array(
				 'startdate_sk' => $lastmonth_firstday,
				 'enddate_sk' => $lastmonth_lastday,
				 'product_id' => $paramsRow_pc->appId,
				 'version_name' => 'all',
				 'channel_name' => $channel_name,
				 'server_name' => 'all',
				 'monthpayrate' => ($dauuser_month==0)?0:($payuser_month/$dauuser_month)
			);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_daily', $data_daily);
			// $dwdb->insert_or_update('razor_sum_basic_payrate_weekly', $data_weekly);
			$dwdb->insert_or_update('razor_sum_basic_payrate_monthly', $data_monthly);
			$paramsRow_pc = $params_pc->next_row();
		}
		}
        
}
