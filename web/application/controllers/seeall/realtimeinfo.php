<?php
class Realtimeinfo extends CI_Controller
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
		$this->load->model('seeall/realtimeinfomodel', 'realtimeinfo');
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
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;

		$this->data['result'] = $this->realtimeinfo->overview($productId);
		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_shiShiGaiKuang_yao'));

		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('seeall/realtimeinfoview',$this->data);
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

		$currentProduct = $this->session->userdata("currentProduct");
		$appId = $currentProduct->id;

		$this->data['result'] = $this->realtimeinfo->getRealtimeinfoByDay($fromTime,$toTime,$channel,$server,$version);

		$this->data['y_result'] = $this->realtimeinfo->getRealtimeinfoByYestoday($fromTime,$toTime,$channel,$server,$version);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/realtimeinfo',$this->data);
	}
}
