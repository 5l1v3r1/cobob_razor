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
class Dauusers extends CI_Controller
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
		$this->load->model('useranalysis/dauusersmodel', 'dauusers');
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
		if (isset($_GET['type']) && $_GET['type'] == 'compare') {
			$this->common->loadCompareHeader(lang('m_rpt_userRetention'));
			$this->load->view('compare/dauusers');
			return;
		}
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$this->data['dauUsersData'] = $this->getDauUsersData($channel,$server,$version);
		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_dauuser_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('useranalysis/dauusersview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}

	/**
	 * Adddauusersreport function , load report dauusers
	 *
	 *@param string $delete delete
	 *@param string $type   type
	 *
	 * @return void
	 */
	function adddauusersreport($delete = null, $type = null)
	{
		if (isset($_GET['type']) && $_GET['type'] == 'compare') {
			$products = $this->common->getCompareProducts();
			if (count($products) == 0) {
				echo 'redirecthome';
				return;
			}
			$this->load->view('layout/reportheader');
			$this->data['show_version'] = 0;
			$this->load->view('widgets/dauusers', $this->data);
		} else {
			$productId = $this->common->getCurrentProduct();
			$userid = $this->common->getUserId();
			$this->common->requireProduct();
			$productId = $productId->id;
			//get the all versions of the product
			$procuctversion = $this->userevent->getProductVersions($productId);
			//get the all channels of the product
			$product_channels = $this->channel->getChannelList($productId);
			//get the all servers of the product
			$product_servers = $this->server->getServerList($userid);
			//save into $this->data[]
			if ($procuctversion != null && $procuctversion->num_rows > 0) {
				$this->data['productversion'] = $procuctversion;
			}
			$this->data['product_channels'] = $product_channels;
			$this->data['product_servers'] = $product_servers;
			if ($delete == null) {
				$this->data['add'] = "add";
			}
			if ($delete == "del") {
				$this->data['delete'] = "delete";
			}
			if ($type != null) {
				$this->data['type'] = $type;
			}
			/*
				*danny edit
				*show dauuser line charts
			*/
			$channel = $this->common->getChannel();
			$server = $this->common->getServer();
			$version = $this->common->getVersion();

			$type = $_GET['type'];
			if($type == 'dau'){
				$this->data['echarts'] = 'dau';
			}
			else if($type == 'd_w_mau'){
				$this->data['echarts'] = 'd_w_mau';
			}
			else if($type == 'dau_mau'){
				$this->data['echarts'] = 'dau_mau';
			}

			$this->data['dauUsersData'] = $this->getDauUsersData($channel,$server,$version);
			$this->load->view('layout/reportheader');
			$this->load->view('widgets/dauusers', $this->data);
		}
	}

	/**
	 * GetDauUsersData function 
	 *
	 *@param string $channel channel
	 *@param string $server server
	 *@param string $version version
	 *
	 * @return encode json
	 */
	function getDauUsersData($channel = 'all',$server = 'all',$version = 'all')
	{
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$data = array();
		$productId = $this->common->getCurrentProduct();
		$from = $this->common->getFromTime();
		$to = $this->common->getToTime();
		$products = $this->common->getCompareProducts();
		$productId = $productId->id;
		$this->common->requireProduct();
		$dauusers_d = $this->dauusers->getDetailUsersDataByDay($from,$to,$channel,$version,$server);
		return $dauusers_d;
	}

		/**
	 * Exportdetaildata
	 * 
	 * @return void
	 */
	function exportdetaildata() 
	{
		//get from time
		$fromTime = $this -> common -> getFromTime();
		//get to time
		$toTime = $this -> common -> getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		//get current product info
		$currentProduct = $this -> common -> getCurrentProduct();
		//remove the space
		$productName = trim($currentProduct -> name);
		$detaildata = $this -> dauusers -> getDetailUsersDataByDay($fromTime, $toTime,$channel,$server,$version);
		if ($detaildata != null && count($detaildata) > 0) {
			$data = $detaildata;
			$titlename = getExportReportTitle($productName, lang("t_dauuser_analysis_yao"), $fromTime, $toTime);
			$title = iconv("UTF-8", "GBK", $titlename);
			$this -> export -> setFileName($title);
			//Set the column headings
			$excel_title = array(iconv("UTF-8", "GBK", lang('g_date')), iconv("UTF-8", "GBK", lang('t_newuser_analysis_yao')), iconv("UTF-8", "GBK", lang('t_payUser_yao')), iconv("UTF-8", "GBK", lang('t_notPayUser_yao')), iconv("UTF-8", "GBK", lang('t_dayDau_yao')), iconv("UTF-8", "GBK", lang('t_weekDau_yao')), iconv("UTF-8", "GBK", lang('t_monthDau_yao')), iconv("UTF-8", "GBK", lang('t_userActiveRate_yao')));
			$this -> export -> setTitle($excel_title);
			//output content
			for ($i = 0; $i < count($data); $i++) {
				if($data[$i]['newuser'])
				$data[$i]['useractiverate'] = ($data[$i]['useractiverate']*100).'%';
				$row = $data[$i];
				$this -> export -> addRow($row);
			}
			$this -> export -> export();
			die();

		} else {
			$this -> load -> view("usage/nodataview");
		}
	}
}
