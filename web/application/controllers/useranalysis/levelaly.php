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
 * Usersessions Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Levelaly extends CI_Controller
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
		$this->load->Model('common');
		$this->load->Model('useranalysis/levelalymodel','levelaly');
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
	 * Index funciton, load view usersessionsview
	 *
	 * @return void
	 */
	function index()
	{	
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$from = $this->common->getFromTime();
		$to = $this->common->getToTime();

		$this->data['result'] = $this->levelaly->getLevelalyDataByDay($from,$to,$channel,$server,$version);
		$this->data['SumResult'] = $this->levelaly->getSumLevelalyDataByDay($from,$to,$channel,$server,$version);
		$this->common->loadHeaderWithDateControl(lang('t_level_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('useranalysis/levelalyview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}

	function addlevelalyreport()
	{
		$levelfield = $_GET['levelfield'];
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$from = $this->common->getFromTime();
		$to = $this->common->getToTime();

		$this->data['gamestoprate_result'] = $this->levelaly->getGameStoprateByDay($from,$to,$channel,$server,$version,$levelfield);
		$this->data['totalupgradetime_result'] = $this->levelaly->getTotalupgradetime($from,$to,$channel,$server,$version,$levelfield);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/levelaly',$this->data);
	}

	function mainlevelalyreport()
	{
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$from = $this->common->getFromTime();
		$to = $this->common->getToTime();

		$this->data['result'] = $this->levelaly->getLevelalyDataByDay($from,$to,$channel,$server,$version);
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/mainlevelaly',$this->data);
	}
}