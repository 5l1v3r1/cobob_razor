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
class Errorcodeanaly extends CI_Controller
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
		$this->load->model('exceptionanaly/errorcodeanalymodel', 'errorcodeanaly');
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

		$this->data['errorcodeanaly'] = $this->errorcodeanaly->getExceptionanaly($fromTime,$toTime,$channel,$server,$version);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('m_cuoWuMaFenXi_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('exceptionanaly/errorcodeanalyview',$this->data);
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
		$this->data['errorcodeanaly'] = $this->errorcodeanaly->getExceptionanaly($fromTime,$toTime,$channel,$server,$version);
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/errorcodeanaly',$this->data);
	}
	function chartsDetail(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$id = $_GET['id'];
		$this->data['errorcodeanalyAction'] = $this->errorcodeanaly->getExceptionanalyAction($fromTime,$toTime,$channel,$server,$version,$id);
		$this->data['id'] = $id;
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/errorcodeanalydetail',$this->data);
	}
	function chartsDate(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$errorid = $_GET['errorid'];
		$result = $this->errorcodeanaly->getExceptionanalyDate($fromTime,$toTime,$channel,$server,$version,$errorid);
		echo json_encode($result);
	}
}
