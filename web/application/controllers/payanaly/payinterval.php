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
class Payinterval extends CI_Controller
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
		$this->load->model('payanaly/payintervalmodel', 'payinterval');
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

		$this->data['firstpay'] = $this->payinterval->getPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_payinterval_first');
		$this->data['secondpay'] = $this->payinterval->getPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_payinterval_second');
		$this->data['thirdpay'] = $this->payinterval->getPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_payinterval_third');

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_paygap_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('payanaly/payintervalview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function charts()
	{
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$this->data['firstpay'] = $this->payinterval->getPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_payinterval_first');
		$this->data['secondpay'] = $this->payinterval->getPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_payinterval_second');
		$this->data['thirdpay'] = $this->payinterval->getPayData($fromTime,$toTime,$channel,$version,$server,$db = 'sum_basic_payinterval_third');
		$this->data['charts'] = $_GET['type'];
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/payinterval', $this->data);
	}
}
