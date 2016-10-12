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
 * Dauusers Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Newuserprogress extends CI_Controller
{
	private $data = array();

	/**
	 * Construct funciton, to pre-load database configuration
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		//common function
		$this->load->Model('common');
		//dauusers model
		$this->load->model('useranalysis/newuserprogressmodel', 'newuserprogress');
		//check is_logged_in
		$this->common->requireLogin();
		//export csv lib
		$this->load->library('export');
		//get versions
		$this->load->model('event/userevent', 'userevent');
		//get channels
		$this->load->model('channelmodel', 'channel');
		//get servers
		$this->load->model('servermodel', 'server');
		//check compare product
		$this->common->checkCompareProduct();
	}

	/**
	 * Index function , load view userremainview
	 *
	 * @return void
	 */
	function index()
	{
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$currentProduct = $this->session->userdata("currentProduct");
		$appId = $currentProduct->id;

		$result = $this->newuserprogress->getNewuserProgressData($fromTime, $toTime, $channel, $server, $version);
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_newuserprogress');
		$list = array();
		foreach ($query->result() as $row)
		{	
			$fRow = array();
			$fRow['newuserprogress_id'] = $row->newuserprogress_id;
			$fRow['newuserprogress_name'] = $row->newuserprogress_name;
			array_push($list, $fRow);
		}

		//rehandel array
		$date = array();
		for($i=0;$i<count($result);$i++){
			array_push($date,$result[$i]['startdate_sk']);
		};
		$a = array_unique($date);
		$date_result = array_merge($a);
		$all_list = array();
		for($i=0;$i<count($date_result);$i++){
			$total = array();
			$step_list = array();
			$currentdate = $date_result[$i];
			for($r=0;$r<count($result);$r++){
				if($result[$r]['startdate_sk'] == $currentdate){
					array_push($step_list,$result[$r]['stepcount']);
				}
			}
			array_push($total,$currentdate,$step_list);
			array_push($all_list,$total);
		}

		$this->data['name'] = $list;
		$this->data['result'] = $all_list;

		$this->common->loadHeaderWithDateControl(lang('m_xinYongHuJinDu_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('useranalysis/newuserprogressview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}

	function charts(){
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$currentProduct = $this->session->userdata("currentProduct");
		$appId = $currentProduct->id;

		$result = $this->newuserprogress->getNewuserProgressData($fromTime, $toTime, $channel, $server, $version);
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_newuserprogress');
		$list = array();
		foreach ($query->result() as $row)
		{	
			array_push($list, $row->newuserprogress_name);
		}
		//rehandel array
		$date = array();
		for($i=0;$i<count($result);$i++){
			array_push($date,$result[$i]['startdate_sk']);
		};
		$a = array_unique($date);
		$date_result = array_merge($a);
		$all_list = array();
		for($i=0;$i<count($date_result);$i++){
			$total = array();
			$step_list = array();
			$currentdate = $date_result[$i];
			for($r=0;$r<count($result);$r++){
				if($result[$r]['startdate_sk'] == $currentdate){
					array_push($step_list,$result[$r]['stepcount']);
				}
			}
			array_push($total,$currentdate,$step_list);
			array_push($all_list,$total);
		}

		$this->data['name'] = $list;
		$this->data['result'] = array_reverse($all_list);
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/newuserprogress',$this->data);
	}
}
