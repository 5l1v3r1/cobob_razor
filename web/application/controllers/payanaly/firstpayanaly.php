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

class Firstpayanaly extends CI_Controller
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
		$this->load->model('payanaly/firstpayanalymodel', 'firstpayanaly');
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

		$this->data['firstpayTime'] = $this->firstpayanaly->getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_firstpayanaly_time',1);
		$this->data['firstpayLevel'] = $this->firstpayanaly->getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_firstpayanaly_level',2);
		$this->data['firstpayAmount'] = $this->firstpayanaly->getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_firstpayanaly_amount',3);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_firstpay_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('payanaly/firstpayanalysview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}

	function echarts(){
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$this->data['firstpayTime'] = $this->firstpayanaly->getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_firstpayanaly_time',1);
		$this->data['firstpayLevel'] = $this->firstpayanaly->getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_firstpayanaly_level',2);
		$this->data['firstpayAmount'] = $this->firstpayanaly->getFirstPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_firstpayanaly_amount',3);
		$this->data['echarts'] = $_GET['type'];
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/firstpayanaly', $this->data);
	}
}
