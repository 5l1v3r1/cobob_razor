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
class Logquery extends CI_Controller
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
		//logquery model
		$this->load->model('logquerymodel', 'logquery');
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
		$this->load->library('session');
	}

	/**
	 * Index function , load view userremainview
	 *
	 * @return void
	 */
	function index()
	{	
		$channel = $this->common->getChannel();
		$version = $this->common->getVersion();
		$server = $this->common->getServer();

		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$currentProduct = $this->session->userdata("currentProduct");
		$appId = $currentProduct->id;
		
		$channel_list = $this->channel->getChannelidByName($channel);
		$version_list = ($version == 'all')?array():$version;
		$server_list = $this->server->getServeridByName($appId,$server);

		//设备信息表
		$this->data['clientdata'] = $this->logquery->clientdata($appId);
		$this->data['createrole'] = $this->logquery->createrole($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['install'] = $this->logquery->install($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['login'] = $this->logquery->login($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['pay'] = $this->logquery->pay($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['register'] = $this->logquery->register($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['realtimeonlineusers'] = $this->logquery->realtimeonlineusers($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['levelupgrade'] = $this->logquery->levelupgrade($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['viplevelup'] = $this->logquery->viplevelup($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['experiencevariation'] = $this->logquery->experiencevariation($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['functioncount'] = $this->logquery->functioncount($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['newuserguide'] = $this->logquery->newuserguide($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['propconsume'] = $this->logquery->propconsume($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['propertyvariation'] = $this->logquery->propertyvariation($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['propgain'] = $this->logquery->propgain($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['task'] = $this->logquery->task($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['tollgate'] = $this->logquery->tollgate($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['newuserprogress'] = $this->logquery->newuserprogress($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['errorcode'] = $this->logquery->errorcode($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);
		$this->data['deviceboot'] = $this->logquery->deviceboot($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId);

		$this->common->loadHeaderWithDateControl(lang('m_riZhiChaXun_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('manage/logqueryview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}
	function unescape($str) {
		$ret = '';
		$len = strlen($str);
		for ($i = 0; $i < $len; $i ++) {
			if ($str[$i] == '%' && $str[$i + 1] == 'u') {
				$val = hexdec(substr($str, $i + 2, 4));
				if ($val < 0x7f)
					$ret .= chr($val);
				else
				if ($val < 0x800)
					$ret .= chr(0xc0 | ($val >> 6)) .
							chr(0x80 | ($val & 0x3f));
				else
					$ret .= chr(0xe0 | ($val >> 12)) .
							chr(0x80 | (($val >> 6) & 0x3f)) .
							chr(0x80 | ($val & 0x3f));
				$i += 5;
			} else
			if ($str[$i] == '%') {
				$ret .= urldecode(substr($str, $i, 3));
				$i += 2;
			} else
				$ret .= $str[$i];
		}
		return $ret;
	}
}
