<?php
class Report extends CI_Controller
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
		$this->load->model('seeall/reportmodel', 'report');
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

		$currentProduct = $this->session->userdata("currentProduct");
		$appId = $currentProduct->id;

		$timeDifference = strtotime('now')-strtotime($toTime);
		//获取时间差天数
		$timeDifferenceResult =  floor($timeDifference/60/60/24);

		//day table
		$day_array = array();
		$day_date_array = array();
		for($i=$timeDifferenceResult;$i<($timeDifferenceResult+8);$i++){
			$day_array[] = $this->report->report_day($i,$appId);
		}
		$this->data['day_result'] = $day_array;

		$this->data['day_lastWeek_date_result'] = $this->report->report_day(($timeDifferenceResult+8),$appId);

		//week table
		$weekDifferenceResult = floor($timeDifferenceResult/7);
		$week_array = array();
		$week_date_array = array();
		for($i=$weekDifferenceResult;$i<(8+$weekDifferenceResult);$i++){
			$week_array[] = $this->report->report_week($i,$appId);
		}
		$this->data['week_result'] = $week_array;
		$this->data['week_lastWeek_date_result'] = $this->report->report_week(($weekDifferenceResult+1),$appId);

		//month table
		$monthDifferenceResult = floor($timeDifferenceResult/30);
		$month_array = array();
		$month_date_array = array();
		for($i=$monthDifferenceResult;$i<(8+$monthDifferenceResult);$i++){
			$month_array[] = $this->report->report_month($i,$appId);
		}
		$this->data['month_result'] = $month_array;
		$this->data['month_lastMonth_date_result'] = $this->report->report_month(($monthDifferenceResult+8),$appId);

		//同比 环比
		function compare($a,$b){
			$_a = floatval($a);
			$_b = floatval($b);
			$_c = ($_a - $_b)/$_b;
			return round($_c,2)*100;
		}

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_baoBiao_yao'));

		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('seeall/reportview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
}
