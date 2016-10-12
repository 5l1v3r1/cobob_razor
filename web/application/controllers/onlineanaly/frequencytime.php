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
class Frequencytime extends CI_Controller
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
		$this->load->model('onlineanaly/frequencytimemodel', 'frequencytime');
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

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_frequencyonline_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('onlineanaly/frequencytimeview');
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function chartsCountday(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$countdatetype = 'day';
		$type1 = $_GET['type1'];
		$type2 = $_GET['type2'];
		$type3 = $_GET['type3'];
		
		switch ($type1) {
			case '0':
				$usertype = 'dauuser';
				break;
			case '1':
				$usertype = 'payuser';
				break;
			case '2':
				$usertype = 'unpayuser';
				break;
		}

		if($type2 == 0){
			switch ($type3) {
				case '0':
					$counttype = 'gamecount';
					$countdatetype = 'day';
					break;
				case '1':
					$counttype = 'gamecount';
					$countdatetype = 'week';
					break;
				case '2':
					$counttype = 'gameday';
					$countdatetype = 'week';
					break;
				case '3':
					$counttype = 'gameday';
					$countdatetype = 'month';
					break;
			}
			$this->data['result'] = $this->frequencytime->getCountday0($fromTime,$toTime,$channel,$server,$version,$usertype,$counttype,$countdatetype);
		}
		elseif($type2 == 1){
			switch ($type3) {
				case '0':
					$countdatetype = 'day';
					break;
				case '1':
					$countdatetype = 'week';
					break;
				case '2':
					$countdatetype = 'onecount';
					break;
			}
			$this->data['result'] = $this->frequencytime->getCountday1($fromTime,$toTime,$channel,$server,$version,$usertype,$countdatetype);
		}
		elseif($type2 == 2){
			$this->data['result'] = $this->frequencytime->getCountday2($fromTime,$toTime,$channel,$server,$version,$usertype);
		}
		$this->data['type1'] = $type1;
		$this->data['type2'] = $type2;
		$this->data['type3'] = $type3;
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/frequencytimeCountday',$this->data);
	}
}
