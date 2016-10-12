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
 * Newusers Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Newusers extends CI_Controller
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
		//newusers model
		$this->load->model('useranalysis/newusersmodel', 'newusers');
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
			$this->load->view('compare/newusers');
			return;
		}
		$channel = $this->common->getChannel();
		$version = $this->common->getVersion();
		$this->data['newUsersData'] = $this->getNewUsersData($channel,$version);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('t_newuser_analysis_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('useranalysis/newusersview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}

	/**
	 * Addnewusersreport function , load report newusers
	 *
	 *@param string $delete delete
	 *@param string $type   type
	 *
	 * @return void
	 */
	function addnewusersreport($delete = null, $type = null)
	{
		if (isset($_GET['type']) && $_GET['type'] == 'compare') {
			$products = $this->common->getCompareProducts();
			if (count($products) == 0) {
				echo 'redirecthome';
				return;
			}
			$this->load->view('layout/reportheader');
			$this->data['show_version'] = 0;
			$this->load->view('widgets/newusers', $this->data);
		} else {
			$productId = $this->common->getCurrentProduct();
			$this->common->requireProduct();
			$productId = $productId->id;
			//get the all versions of the product
			$procuctversion = $this->userevent->getProductVersions($productId);
			//get the all channels of the product
			$product_channels = $this->channel->getChannelList($productId);
			//save into $this->data[]
			if ($procuctversion != null && $procuctversion->num_rows > 0) {
				$this->data['productversion'] = $procuctversion;
			}
			$this->data['product_channels'] = $product_channels;
			if ($delete == null) {
				$this->data['add'] = "add";
			}
			if ($delete == "del") {
				$this->data['delete'] = "delete";
			}
			if ($type != null) {
				$this->data['type'] = $type;
			}

			$channel = $this->common->getChannel();
			$version = $this->common->getVersion();
			$this->data['newUsersData'] = $this->getNewUsersData($channel,$version);

			$this->load->view('layout/reportheader');
			$this->load->view('widgets/newusers', $this->data);
		}
	}

	/**
	 * GetNewUsersData function 
	 *
	 *@param string $channel channel
	 *@param string $server server
	 *@param string $version version
	 *
	 * @return encode json
	 */
	function getNewUsersData($channel = 'all',$version = 'all',$server = 'all')
	{
		$channel = urldecode($channel);
		// $this->session->set_userdata('channel', $channel);
		// $this->session->set_userdata('version', $version);
		$channel = $this->common->getChannel();
		$version = $this->common->getVersion();
		$server = $this->common->getServer();

		$data = array();
		$productId = $this->common->getCurrentProduct();
		$from = $this->common->getFromTime();
		$to = $this->common->getToTime();
		$products = $this->common->getCompareProducts();
		$productId = $productId->id;
		$this->common->requireProduct();
		$newusers_d = $this->newusers->getDetailUsersDataByDay($from,$to,$channel,$version);
		return $newusers_d;
	}

		/**
	 * Exportdetaildata
	 * 
	 * @return void
	 */
	function exportdetaildata() 
	{
		//get from time
		$fromTime = $this->common->getFromTime();
		//get to time
		$toTime = $this ->common->getToTime();
		$channel = $this->common->getChannel();
		$version = $this->common->getVersion();
		//get current product info
		$currentProduct = $this->common->getCurrentProduct();
		//remove the space
		$productName = trim($currentProduct->name);
		$detaildata = $this->newusers->getDetailUsersDataByDay($fromTime, $toTime,$channel,$version);
		if ($detaildata != null && count($detaildata) > 0) {
			$data = $detaildata;
			$titlename = getExportReportTitle($productName, lang("t_newuser_analysis_yao"), $fromTime, $toTime);
			$title = iconv("UTF-8", "GBK", $titlename);
			$this->export->setFileName($title);
			//Set the column headings
			$excel_title = array(iconv("UTF-8", "GBK", lang('g_date')), iconv("UTF-8", "GBK", lang('t_deviceActivation_yao')), iconv("UTF-8", "GBK", lang('t_newuser_analysis_yao')), iconv("UTF-8", "GBK", lang('t_newDevice_yao')), iconv("UTF-8", "GBK", lang('t_userConversion_yao')));
			$this->export->setTitle($excel_title);
			//output content
			for ($i = 0; $i < count($data); $i++) {
				if($data[$i]['deviceactivations'])
				$data[$i]['userconversion'] = round(($data[$i]['userconversion']/$data[$i]['deviceactivations'])*100,2);
				
				$data[$i]['userconversion'] = $data[$i]['userconversion'].'%';
				$row = $data[$i];
				$this->export->addRow($row);
			}
			$this->export->export();
			die();

		} else {
			$this->load->view("usage/nodataview");
		}
	}
}
