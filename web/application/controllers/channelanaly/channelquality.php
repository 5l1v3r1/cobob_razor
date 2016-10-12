<?php

class Channelquality extends CI_Controller
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
		$this->load->model('channelanaly/channelqualitymodel', 'channelquality');
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

		$this->data['result'] = $this->channelquality->getChannelqualityData($fromTime, $toTime, $channel, $server, $version);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('m_quDaoZhiLiang_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('channelanaly/channelqualityview',$this->data);
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

		$this->data['result'] = $this->channelquality->getChannelqualityData($fromTime, $toTime, $channel, $server, $version);
		$this->data['type'] = $_GET['type'];

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/channelquality',$this->data);
	}

}
