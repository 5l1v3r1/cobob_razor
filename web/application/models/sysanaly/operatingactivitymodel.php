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
 * Operatingactivitymodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Operatingactivitymodel extends CI_Model {

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
		$this->load->model("sysanaly/newserveractivitymodel", 'newserveractivity');
	}
	
	function getOperatingactivityData($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->OperatingactivityData($fromTime,$toTime,$channel,$server,$version);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['activity_issue'] = $dauUsersRow->activity_issue;
			$fRow['activity_name'] = $dauUsersRow->activity_name;
			$fRow['startdate'] = $dauUsersRow->startdate;
			$fRow['enddate'] = $dauUsersRow->enddate;
			$fRow['validuser'] = $dauUsersRow->validuser;
			$fRow['joinuser'] = $dauUsersRow->joinuser;
			$fRow['joinrate'] = $dauUsersRow->joinrate;
			$fRow['coinconsume'] = $dauUsersRow->coinconsume;
			$fRow['useravgconsume'] = $dauUsersRow->useravgconsume;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function OperatingactivityData($fromTime,$toTime,$channel,$server,$version)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(activity_issue, 0) activity_issue,
					IFNULL(activity_name, 0) activity_name,
					IFNULL(startdate, 0) startdate,
					IFNULL(enddate, 0) enddate,
					IFNULL(validuser, 0) validuser,
					IFNULL(joinuser, 0) joinuser,
					IFNULL(joinrate, 0) joinrate,
					IFNULL(coinconsume, 0) coinconsume,
					IFNULL(useravgconsume, 0) useravgconsume
				FROM
					" . $dwdb->dbprefix('sum_basic_activity') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND type = 'operating'
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getSelectName($fromTime,$toTime,$channel,$server,$version){
		$dwdb = $this->load->database('dw', true);
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(activity_name, 0) activity_name
				FROM
					" . $dwdb->dbprefix('sum_basic_activity') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND type = 'operating'
				Group By activity_name
				Order By rid ASC";
		$query = $dwdb->query($sql);
		$dauUsersRow = $query->first_row();
		$list = array();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['activity_name'] = $dauUsersRow->activity_name;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function getFilterOperatingactivityData($fromTime,$toTime,$channel,$server,$version,$name){
		$list = array();
		$query = $this->FilterOperatingactivityData($fromTime,$toTime,$channel,$server,$version,$name);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['activity_issue'] = $dauUsersRow->activity_issue;
			$fRow['activity_name'] = $dauUsersRow->activity_name;
			$fRow['startdate'] = $dauUsersRow->startdate;
			$fRow['enddate'] = $dauUsersRow->enddate;
			$fRow['validuser'] = $dauUsersRow->validuser;
			$fRow['joinuser'] = $dauUsersRow->joinuser;
			$fRow['joinrate'] = $dauUsersRow->joinrate;
			$fRow['coinconsume'] = $dauUsersRow->coinconsume;
			$fRow['useravgconsume'] = $dauUsersRow->useravgconsume;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function FilterOperatingactivityData($fromTime,$toTime,$channel,$server,$version,$name)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(activity_issue, 0) activity_issue,
					IFNULL(activity_name, 0) activity_name,
					IFNULL(startdate, 0) startdate,
					IFNULL(enddate, 0) enddate,
					IFNULL(validuser, 0) validuser,
					IFNULL(joinuser, 0) joinuser,
					IFNULL(joinrate, 0) joinrate,
					IFNULL(coinconsume, 0) coinconsume,
					IFNULL(useravgconsume, 0) useravgconsume
				FROM
					" . $dwdb->dbprefix('sum_basic_activity') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND activity_name = '".$name."'
				AND type = 'operating'
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getDetailOperatingactivityData($fromTime,$toTime,$channel,$server,$version,$activityIssue,$detailstype){
		$list = array();
		$query = $this->DetailOperatingactivityData($fromTime,$toTime,$channel,$server,$version,$activityIssue,$detailstype);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['activity_issue'] = $dauUsersRow->activity_issue;
			$fRow['propid'] = $dauUsersRow->propid;
			$fRow['propname'] = $dauUsersRow->propname;
			$fRow['proptype'] = $dauUsersRow->proptype;
			$fRow['propcount'] = $dauUsersRow->propcount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function DetailOperatingactivityData($fromTime,$toTime,$channel,$server,$version,$activityIssue,$detailstype)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(activity_issue, 0) activity_issue,
					IFNULL(propid, 0) propid,
					IFNULL(propname, 0) propname,
					IFNULL(proptype, 0) proptype,
					IFNULL(propcount, 0) propcount
				FROM
					" . $dwdb->dbprefix('sum_basic_activity_details') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND activity_issue = '".$activityIssue."'
				AND detailstype = '".$detailstype."'
				AND type = 'operating'
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getDistributeddetails($fromTime,$toTime,$channel,$server,$version,$propid,$activity_issue,$detailstype){
		$list = array();
		$query = $this->Distributeddetails($fromTime,$toTime,$channel,$server,$version,$propid,$activity_issue,$detailstype);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['activity_issue'] = $dauUsersRow->activity_issue;
			$fRow['propid'] = $dauUsersRow->propid;
			$fRow['actionid'] = $dauUsersRow->actionid;
			$fRow['actionname'] = $dauUsersRow->actionname;
			$fRow['actioncount'] = $dauUsersRow->actioncount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function Distributeddetails($fromTime,$toTime,$channel,$server,$version,$propid,$activity_issue,$detailstype){
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(activity_issue, 0) activity_issue,
					IFNULL(propid, 0) propid,
					IFNULL(actionid, 0) actionid,
					IFNULL(actionname, 0) actionname,
					IFNULL(actioncount, 0) actioncount
				FROM
					" . $dwdb->dbprefix('sum_basic_activity_distributeddetails') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND activity_issue = '".$activity_issue."'
				AND detailstype = '".$detailstype."'
				AND propid = '".$propid."'
				AND type = 'operating'
				Order By rid ASC";
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
     * getOperatingmcdata function
     * get new server mainconfig data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int new server mainconfig data
     */
    function getOperatingmcdata($appid) {
    	$sql = "SELECT
					nsa.operationactivity_id newserveractivity_id,
					nsa.operationactivity_name newserveractivity_name,
					nsa.operationactivity_issue newserveractivity_issue,
					nsa.startdate,
					nsa.enddate
				FROM
					razor_mainconfig_operationactivity nsa
				WHERE
					nsa.app_id = '$appid';";

		$query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
		return $query;
    }

    /**
     * sum_basic_activity function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_activity_operating($countdate) {
		$dwdb = $this->load->database('dw', true);

		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_psv->appId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_psv->appId,
						'version_name' => $paramsRow_psv->version,
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_psv->appId,
						'version_name' => $paramsRow_psv->version,
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_psv->appId,
						'version_name' => $paramsRow_psv->version,
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_psv->appId,
						'version_name' => $paramsRow_psv->version,
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_psv->appId,
						'version_name' => $paramsRow_psv->version,
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_psv = $params_psv->next_row();
		}

		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_ps->appId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_ps->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_ps->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_ps->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_ps->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_ps->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_ps = $params_ps->next_row();
		}

		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_pv->appId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pv->appId,
						'version_name' => $paramsRow_pv->version,
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pv->appId,
						'version_name' => $paramsRow_pv->version,
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pv->appId,
						'version_name' => $paramsRow_pv->version,
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pv->appId,
						'version_name' => $paramsRow_pv->version,
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pv->appId,
						'version_name' => $paramsRow_pv->version,
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_pv = $params_pv->next_row();
		}

		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_p->appId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all');
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_p->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_p->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_p->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_p->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_p->appId, 'all', 'all', 'all',$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_p->appId,
						'version_name' => 'all',
						'channel_name' => 'all',
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_p = $params_p->next_row();
		}

		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_pcsv->appId);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcsv->appId,
						'version_name' => $paramsRow_pcsv->version,
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcsv->appId,
						'version_name' => $paramsRow_pcsv->version,
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcsv->appId,
						'version_name' => $paramsRow_pcsv->version,
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcsv->appId,
						'version_name' => $paramsRow_pcsv->version,
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcsv->appId,
						'version_name' => $paramsRow_pcsv->version,
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_pcs->appId);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcs->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcs->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcs->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcs->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcs->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => $server_name,
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_pcv->appId);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcv->appId,
						'version_name' => $paramsRow_pcv->version,
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcv->appId,
						'version_name' => $paramsRow_pcv->version,
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcv->appId,
						'version_name' => $paramsRow_pcv->version,
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcv->appId,
						'version_name' => $paramsRow_pcv->version,
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pcv->appId,
						'version_name' => $paramsRow_pcv->version,
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_pcv = $params_pcv->next_row();
		}
		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$Operatingmcdata=$this->getOperatingmcdata($paramsRow_pc->appId);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
			foreach ($Operatingmcdata->result() as $row)
			{
			   // razor_sum_basic_activity
			   $Validuser=$this->newserveractivity->getValiduser($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			   $Joinuser=$this->newserveractivity->getJoinuser($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_issue);
			   $Goldconsume=$this->newserveractivity->getGoldconsume($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_id);
			   $data_activity = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'type' => 'operating',
					'activity_issue' => $row->newserveractivity_issue,
					'activity_name' => $row->newserveractivity_name,
					'startdate' => $row->startdate,
					'enddate' => $row->enddate,
					'validuser' => $Validuser,
					'joinuser' => $Joinuser,
					'joinrate' => ($Validuser==0)?0:($Joinuser/$Validuser),
					'coinconsume' => $Goldconsume,
					'useravgconsume' => ($Joinuser==0)?0:($Goldconsume/$Joinuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_activity', $data_activity);

				// razor_sum_basic_activity_details:gain
				$Propgainbyactivity=$this->newserveractivity->getPropgainbyactivity($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_id);
				foreach ($Propgainbyactivity->result() as $row_Propgainbyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pc->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainbyactivity->prop_id,
						'propname' => $row_Propgainbyactivity->prop_name,
						'proptype' => $row_Propgainbyactivity->prop_category,
						'propcount' => $row_Propgainbyactivity->propgaincount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:consume
				$Propconsumebyactivity=$this->newserveractivity->getPropconsumebyactivity($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_id);
				foreach ($Propconsumebyactivity->result() as $row_Propconsumebyactivity) {
					$data_Propgainbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pc->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumebyactivity->prop_id,
						'propname' => $row_Propconsumebyactivity->prop_name,
						'proptype' => $row_Propconsumebyactivity->prop_category,
						'propcount' => $row_Propconsumebyactivity->propconsumecount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Propgainbyactivity);
				}
				// razor_sum_basic_activity_details:action
				$Actiondetail=$this->newserveractivity->getActiondetail($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_issue);
				foreach ($Actiondetail->result() as $row_Actiondetail) {
					$data_Actiondetail = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pc->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'action',
						'propid' => $row_Actiondetail->action_id,
						'propname' => $row_Actiondetail->action_name,
						'proptype' => '',
						'propcount' => $row_Actiondetail->eventcount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_details', $data_Actiondetail);
				}

				// razor_sum_basic_activity_distributeddetails:gain
				$Propgainactionbyactivity=$this->newserveractivity->getPropgainactionbyactivity($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_id);
				foreach ($Propgainactionbyactivity->result() as $row_Propgainactionbyactivity) {
					$data_Propgainactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pc->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'output',
						'propid' => $row_Propgainactionbyactivity->propid,
						'actionid' => $row_Propgainactionbyactivity->actionid,
						'actionname' => $row_Propgainactionbyactivity->action_name,
						'actioncount' => $row_Propgainactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propgainactionbyactivity);
				}
				// razor_sum_basic_activity_distributeddetails:consume
				$Propconsumeactionbyactivity=$this->newserveractivity->getPropconsumeactionbyactivity($row->startdate, $row->enddate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$row->newserveractivity_id);
				foreach ($Propconsumeactionbyactivity->result() as $row_Propconsumeactionbyactivity) {
					$data_Propconsumeactionbyactivity = array(
						'startdate_sk' => $countdate,
						'enddate_sk' => $countdate,
						'product_id' => $paramsRow_pc->appId,
						'version_name' => 'all',
						'channel_name' => $channel_name,
						'server_name' => 'all',
						'type' => 'operating',
						'activity_issue' => $row->newserveractivity_issue,
						'detailstype' => 'consume',
						'propid' => $row_Propconsumeactionbyactivity->propid,
						'actionid' => $row_Propconsumeactionbyactivity->actionid,
						'actionname' => $row_Propconsumeactionbyactivity->action_name,
						'actioncount' => $row_Propconsumeactionbyactivity->actioncount
					);
					$dwdb->insert_or_update('razor_sum_basic_activity_distributeddetails', $data_Propconsumeactionbyactivity);
				}
			}
			$paramsRow_pc = $params_pc->next_row();
		}
	}
}
