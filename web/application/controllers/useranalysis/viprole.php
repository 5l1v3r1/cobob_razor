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
class Viprole extends CI_Controller
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
		$this->load->model('useranalysis/viprolemodel', 'viprole');
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
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$this->data['useralany'] = $this->viprole->getUseralanyData($fromTime, $toTime, $channel, $server, $version);
		$this->data['payalanyData'] = $this->viprole->getPayalanyData($fromTime, $toTime, $channel, $server, $version);
		$this->data['leavealany'] = $this->viprole->getLeavealanyData($fromTime, $toTime, $channel, $server, $version);

		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('m_vipYongHu_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('useranalysis/viproleview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	//用户分析
	function useralany()
	{
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$viplevel = $_GET['viprole'];
		$this->data['result'] = $this->viprole->getUseralanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/viprole_useralany',$this->data);
	}

	//付费分析
	function payalany()
	{
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$viplevel = $_GET['viprole'];
		$this->data['result'] = $this->viprole->getPayalanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/viprole_payalany',$this->data);
	}

	//流失分析
	function leavealany()
	{
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();

		$viplevel = $_GET['viprole'];
		$this->data['result'] = $this->viprole->getleavealanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);
		$this->data['userlevel'] = $this->viprole->getUserlevelDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/viprole_leavealany',$this->data);
	}
}
