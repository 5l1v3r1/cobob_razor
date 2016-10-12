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
class Propanaly extends CI_Controller
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
		$this->load->model('sysanaly/propanalymodel', 'propanaly');
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

		$propType = (isset($_GET['propType']))?$_GET['propType']:false;
		if($propType){
			$this->data['propType'] = $propType;
			$this->data['result1'] = $this->propanaly->getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type = 'newuser',$propType);
			$this->data['result2'] = $this->propanaly->getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type = 'dauuser',$propType);
			$this->data['result3'] = $this->propanaly->getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type = 'payuser',$propType);
		}
		else{
			$this->data['result1'] = $this->propanaly->getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type = 'newuser');
			$this->data['result2'] = $this->propanaly->getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type = 'dauuser');
			$this->data['result3'] = $this->propanaly->getPropanalyData($fromTime,$toTime,$channel,$server,$version,$type = 'payuser');
		}


		$this->data['propClassify'] = $this->propanaly->getPropClassify($fromTime,$toTime,$channel,$server,$version);

		$this->common->loadHeaderWithDateControl(lang('m_daoJuFenXi_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('sysanaly/propanalyview',$this->data);
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

		$propname = $_GET['date'];
		$proptype = $_GET['type'];
		$this->data['propname'] = $propname;
		$this->data['proptype'] = $proptype;
		$this->data['totalCG'] = $this->propanaly->gettotalCGByDay($fromTime,$toTime,$channel,$server,$version,$name=$propname,$type=$proptype);
		$this->data['outputDistrbute'] = $this->propanaly->getOutputDistrbute($fromTime,$toTime,$channel,$server,$version,$name,$type,$product_type='gain');
		$this->data['consumeDistrbute'] = $this->propanaly->getOutputDistrbute($fromTime,$toTime,$channel,$server,$version,$name,$type,$product_type='consume');
		$this->data['peopleCount'] = $this->propanaly->getPeopleCountData($fromTime,$toTime,$channel,$server,$version,$name=$propname,$type=$proptype);

		$this->load->view('layout/reportheader');
		$this->load->view('widgets/propanaly',$this->data);
	}
	function chartsDetail(){
		$channel = $this->common->getChannel();
		$server = $this->common->getServer();
		$version = $this->common->getVersion();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();

		$type = $_GET['type'];
		$prop_name = $_GET['prop_name'];
		$action_type = $_GET['action_type'];

		$result = $this->propanaly->getVipuserData($fromTime,$toTime,$channel,$server,$version,$type,$prop_name,$action_type);
		echo json_encode($result);
	}
}
