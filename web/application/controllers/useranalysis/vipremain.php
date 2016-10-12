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
 * Remaincount Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Vipremain extends CI_Controller
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
		//remaincount model
		$this->load->model('useranalysis/vipremainmodel', 'vipremain');
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

	function index()
	{
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$this->data['level'] = $this->vipremain->getVipLevelData($fromTime, $toTime, $channel, $server, $version);
		$this->data['result'] = $this->vipremain->getVipremainData($fromTime, $toTime, $channel, $server, $version);

		$this->common->loadHeaderWithDateControl(lang('m_vipLiuCun_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('useranalysis/vipremainview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function charts()
	{
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$avg = $_GET['avg'];
		$this->data['result'] = explode(',',$avg);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/vipremain', $this->data);
	}
	function filter(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$value = $_GET['value'];
		if($value == '-1'){
			$result = $this->vipremain->getVipremainData($fromTime, $toTime, $channel, $server, $version);
		}
		else{
			$result = $this->vipremain->getFilterData($fromTime, $toTime, $channel, $server, $version, $value);
		}
		echo json_encode($result);
	}
}
