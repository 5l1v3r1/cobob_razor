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
class Taskanalysis extends CI_Controller
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
		$this->load->model('sysanaly/taskanalysismodel', 'taskanalysis');
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

		$this->data['result1'] = $this->taskanalysis->getTaskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype="newuser");
		$this->data['result2'] = $this->taskanalysis->getTaskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype="main");
		$this->data['result3'] = $this->taskanalysis->getTaskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype="branch");
		$this->data['result4'] = $this->taskanalysis->getTaskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype="general");
		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_task_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('sysanaly/taskanalysisview',$this->data);
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

		$taskName = $_GET['taskName'];
		$tasktype = $_GET['tasktype'];
		$this->data['result1'] = $this->taskanalysis->getTaskanalysisDetail1($fromTime,$toTime,$channel,$server,$version,$taskName,$tasktype);
		$this->data['result2'] = $this->taskanalysis->getTaskanalysisDetail2($fromTime,$toTime,$channel,$server,$version,$taskName,$tasktype);
		$this->data['taskName'] = $taskName;
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/taskanalysis',$this->data);
	}
}
