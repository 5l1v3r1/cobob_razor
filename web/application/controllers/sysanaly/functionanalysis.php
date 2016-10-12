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
class Functionanalysis extends CI_Controller
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
		$this->load->model('sysanaly/functionanalysismodel', 'functionanalysis');
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
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$this->data['result'] = $this->functionanalysis->getFunctionanalysis($fromTime,$toTime,$channel,$server,$version);
		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_function_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('sysanaly/functionanalysisview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function charts(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$function = $_GET['Name'];

		$this->data['result1'] = $this->functionanalysis->getFunctionanalysisData($fromTime,$toTime,$channel,$server,$version,$function,$type = 'propgain');
		$this->data['result2'] = $this->functionanalysis->getFunctionanalysisData($fromTime,$toTime,$channel,$server,$version,$function,$type = 'propconsume');
		$this->data['result3'] = $this->functionanalysis->getFunctionanalysisData($fromTime,$toTime,$channel,$server,$version,$function,$type = 'action');
		$this->data['functionName'] = $function;
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/functionanalysis',$this->data);
	}
	function chartsDetail(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$prop_id = $_GET['Name'];
		$functionName = $_GET['functionName'];
		$returnData = $this->functionanalysis->getFunctionanalysisDataDetail($fromTime,$toTime,$channel,$server,$version,$prop_id,$functionName);
		echo json_encode($returnData);
	}
}
