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
 * Userremain Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Userremain extends CI_Controller
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
		$this->load->Model('common');
		$this->load->model('product/userremainmodel', 'userremain');
		$this->common->requireLogin();
		$this->load->library('export');
		$this->load->model('event/userevent', 'userevent');
		$this->load->model('channelmodel', 'channel');
		$this->common->checkCompareProduct();
	}

	/**
	 * Index function , load view userremainview
	 *
	 * @return void
	 */
	function index()
	{
		if (isset($_GET['type']) && $_GET['type'] == 'compare') {
			$this->common->loadCompareHeader(lang('m_rpt_userRetention'));
			$this->load->view('compare/userremain');
			return;
		}
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$this->data['userremain'] = $this->userremain->getUserRemainData($fromTime,$toTime,$channel,$server,$version);
		$this->data['deviceremain'] = $this->userremain->getDeviceRemainData($fromTime,$toTime,$channel,$server,$version);
		$this->data['remaindetail'] = $this->userremain->getRemainDetailData($fromTime,$toTime,$channel,$server,$version);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl();
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('usage/userremainview', $this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function detailFilter(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$datetype = (isset($_GET['datetype']) && $_GET['datetype'] != null)?$_GET['datetype']:0;
		$type = (isset($_GET['type']) && $_GET['type'] != null)?$_GET['type']:0;
		$logintimes = (isset($_GET['logintimes']) && $_GET['logintimes'] != null)?$_GET['logintimes']:0;

		if($datetype == 1){
			$result = $this->userremain->filterAdata($fromTime,$toTime,$channel,$server,$version,$type,$logintimes);
			echo(json_encode($result));
		}
		elseif($datetype == 2){
			$result = $this->userremain->filterBdata($fromTime,$toTime,$channel,$server,$version,$type,$logintimes);
			echo(json_encode($result));
		}
		elseif($datetype == 3){
			$result = $this->userremain->filterCdata($fromTime,$toTime,$channel,$server,$version,$type,$logintimes);
			echo(json_encode($result));
		}
	}
	function echarts(){

		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$type = $_GET['type'];

		if($type == 'userremain'){
			$this->data['result'] = $this->userremain->getUserRemainData($fromTime,$toTime,$channel,$server,$version);
		}
		else if($type == 'deviceremain'){
			$this->data['result'] = $this->userremain->getDeviceRemainData($fromTime,$toTime,$channel,$server,$version);
		}
		else if($type == 'maindetail'){
			$this->data['result'] = $this->userremain->getRemainDetailData($fromTime,$toTime,$channel,$server,$version);
		}
		$this->data['echarts'] = $type;

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/userremain', $this->data);
	}
}
