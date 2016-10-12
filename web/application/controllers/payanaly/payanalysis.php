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
class Payanalysis extends CI_Controller
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
		$this->load->model('payanaly/payanalymodel','payanaly');
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
		$this->data['paymoney_day'] = $this->payanaly->getPaymoneyByDay($fromTime,$toTime,$channel,$version,$server,$date = 'day');
		$this->data['paymoney_week'] = $this->payanaly->getPaymoneyByDay($fromTime,$toTime,$channel,$version,$server,$date = 'week');
		$this->data['paymoney_month'] = $this->payanaly->getPaymoneyByDay($fromTime,$toTime,$channel,$version,$server,$date = 'month');
		$this->data['paycount_day'] = $this->payanaly->getPaycountByDay($fromTime,$toTime,$channel,$version,$server,$date = 'day');
		$this->data['paycount_week'] = $this->payanaly->getPaycountByDay($fromTime,$toTime,$channel,$version,$server,$date = 'week');
		$this->data['paycount_month'] = $this->payanaly->getPaycountByDay($fromTime,$toTime,$channel,$version,$server,$date = 'month');
		$this->data['payARPUday'] = $this->payanaly->getPayARPUdayByDay($fromTime,$toTime,$channel,$version,$server);
		$this->data['PayARPUmonth'] = $this->payanaly->getPayARPUmonthByDay($fromTime,$toTime,$channel,$version,$server);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_payanalysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('payanaly/payanalyview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function echarts(){
		$date = (isset($_GET['date']))?$_GET['date']:0;
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$this->data['paymoney'] = $this->payanaly->getPaymoneyByDay($fromTime,$toTime,$channel,$version,$server,$date);
		$this->data['paycount'] = $this->payanaly->getPaycountByDay($fromTime,$toTime,$channel,$version,$server,$date);
		$this->data['payARPUday'] = $this->payanaly->getPayARPUdayByDay($fromTime,$toTime,$channel,$version,$server);
		$this->data['PayARPUmonth'] = $this->payanaly->getPayARPUmonthByDay($fromTime,$toTime,$channel,$version,$server);

		$this->data['echarts'] = $_GET['type'];
		$this->data['date'] = $date;
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/payanaly', $this->data);
	}
}
