<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Leavecount extends CI_Controller
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
		$this->load->model('leaveanaly/leavecountmodel', 'leavecount');
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

		$this->data['alluser'] = $this->leavecount->getLeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag = 'alluser');
		$this->data['payuser'] = $this->leavecount->getLeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag = 'payuser');

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_losecount_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('leaveanaly/leavecountview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function showDetail(){
		$getdate = $_GET['date'];
		$usertag = $_GET['usertag'];
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$this->data['alluserdetail_7'] = $this->leavecount->getLeavecountDetailData($channel, $server, $version,$usertag,$getdate,$type = '7');
		$this->data['alluserdetail_14'] = $this->leavecount->getLeavecountDetailData($channel, $server, $version,$usertag,$getdate,$type = '14');
		$this->data['alluserdetail_30'] = $this->leavecount->getLeavecountDetailData($channel, $server, $version,$usertag,$getdate,$type = '30');

		$this->common->requireProduct();
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/leavecount',$this->data);
	}
	function charts(){
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$this->data['alluser'] = $this->leavecount->getLeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag = 'alluser');
		$this->data['payuser'] = $this->leavecount->getLeavecountData($fromTime, $toTime, $channel, $server, $version,$usertag = 'payuser');
		$this->data['charts'] = $_GET['type'];
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/leavetopcount',$this->data);
	}
}
