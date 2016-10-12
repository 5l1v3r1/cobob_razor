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
 * Console Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Console extends CI_Controller
{

	/**
	 * Data array $data
	 */
	private $_data = array();

	/**
	 * Construct funciton, to pre-load database configuration
	 *
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->library('form_validation');
		$this->load->Model('common');
		$this->load->model('channelmodel', 'channel');
		$this->load->model('product/productmodel', 'product');
		$this->load->model('product/productanalyzemodel');
		$this->load->model('product/newusermodel', 'newusermodel');
		$this->load->model('analysis/trendandforecastmodel', 'trendmodel');
		$this->load->model('seeall/seeallmodel', 'seeall');

		$this->common->requireLogin();
	}

	/**
	 * Index
	 *
	 * @return void
	 */
	function index()
	{
		$this->common->cleanCurrentProduct();
		$this->_data['category'] = $this->product->getProductCategory();
		$this->_data['user_id'] = $this->common->getUserId();
		$this->_data["guest_roleid"] = $this->common->getUserRoleById($this->_data['user_id']);
		$today = date('Y-m-d', time());
		$yestoday = date("Y-m-d", strtotime("-1 day"));
		$query = $this->product->getProductListByPlatform(1, $this->_data['user_id'], $today, $yestoday);
		//获取当前用户所分配的游戏
		$getGame = $this->product->getUserProductGame($userId = $this->common->getUserId());

		$myGame = array();
		for($i=0;$i<count($getGame);$i++){
			$result = $this->seeall->gatherallpergame($getGame[$i]);
			array_push($myGame,$result);
		}

		$this->_data['myGame'] = $myGame;

		// active users num
		$this->_data['today_startuser'] = 0;
		$this->_data['yestoday_startuser'] = 0;
		
		// new users num
		$this->_data['today_newuser'] = 0;
		$this->_data['yestoday_newuser'] = 0;
		
		// session num
		$this->_data['today_startcount'] = 0;
		$this->_data['yestoday_startcount'] = 0;
		
		$this->_data['today_totaluser'] = 0;
		
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		
		$this->_data['reportTitle'] = array(
				'timePase' => getTimePhaseStr($fromTime, $toTime),
				'activeUser' => lang("v_activeUserTrend"),
				'newUser' => lang("v_newUserTrend"),
				'session' => lang("v_sessoinsTrend")
		);
		//修改首页总览
		$userid = $this->common->getUserRoleById($this->_data['user_id']);

		$gatherall = $this->seeall->gatherall($this->_data['user_id']);
		$this->_data['gatherall'] = $gatherall;


		$this->common->loadHeaderWithDateControl(lang('t_youXiZongLan_yao'));
		$this->load->view('main_form', $this->_data);
	}

	function charts(){
		$to = $this->common->getToTime();
		$this->_data['type'] = $_GET['type'];
		$userid = $this->common->getUserId();
		$arr = array();
		for($i=1;$i<8;$i++){
			$time = strtotime($to) - 3600*24*$i;
			$time = date('Y-m-d',$time);
			$arr[] = $this->seeall->gathertochart($time,$userid);
		};
		$this->_data['result'] = array_reverse($arr);
		$this->load->view('layout/reportheader');
		$this->load->view('widgets/indexCharts',$this->_data);
	}

	/**
	 * ChangeTimePhase change time phase and the data stored in the Session
	 *
	 * @param string $phase    phase
	 * @param string $fromTime fromTime
	 * @param string $toTime   toTime
	 *            
	 * @return json
	 */
	function changeTimePhase($phase = '7day', $fromTime = '', $toTime = '')
	{
		$this->common->changeTimeSegment($phase, $fromTime, $toTime);
		$ret = array();
		$ret["msg"] = "ok";
		echo json_encode($ret);
	}

	/*
		*danny edit
		*add server version channel session
	*/
	function svcPhase(){
		$_channel = $_POST['channels'];
		$_version = $_POST['version'];
		$_server = $_POST['server'];

		(isset($_server) && $_server != '')?$server = explode("-",$_server):$server = array();
		(isset($_version) && $_version != '')?$version = explode("-",$_version):$version = array();
		(isset($_channel) && $_channel != '')?$channel = explode("-",$_channel):$channel = array();

		$this->common->svcChange($channel,$version,$server);
		$ret = array();
		$ret["msg"] = "ok";
		echo json_encode($ret);
	}

	/**
	 * GetConsoleDatainfo Get Console Data by time phase
	 *
	 * @return json
	 */
	function getConsoleDatainfo()
	{
		$userId = $this->common->getUserId();
		$fromTime = $this->common->getFromTime();
		$toTime = $this->common->getToTime();
		$query = $this->newusermodel->getAlldataofVisittrends($fromTime, $toTime, $userId);
		$result = $this->newusermodel->getAlldataofVisittrends($this->common->getPredictiveValurFromTime(), $toTime, $userId);
		$res = $this->trendmodel->getPredictiveValur($result);
		$ret["content"] = $query;
		$ret["contentofTrend"] = $res;
		$ret["timeTick"] = $this->common->getTimeTick($toTime - $fromTime);
		echo json_encode($ret);
	}

	/**
	 * Setnewversion set new version inform
	 *
	 * @return json
	 */
	function setnewversion()
	{
		$this->session->set_userdata('newversion', 'noinform');
		$data = $this->session->userdata('newversion');
		echo $data;
	}
}
