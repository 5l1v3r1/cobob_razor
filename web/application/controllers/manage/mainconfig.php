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
class Mainconfig extends CI_Controller
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
		$this->load->model('mainconfigmodel', 'mainconfig');
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
		
		$this->data['action'] = $this->mainconfig->action($appId);
		$this->data['actiontype'] = $this->mainconfig->actiontype($appId);
		$this->data['function'] = $this->mainconfig->_function($appId);
		$this->data['newserveractivity'] = $this->mainconfig->newserveractivity($appId);
		$this->data['operationactivity'] = $this->mainconfig->operationactivity($appId);
		$this->data['prop'] = $this->mainconfig->prop($appId);
		$this->data['propertymoney'] = $this->mainconfig->propertymoney($appId);
		$this->data['tollgate'] = $this->mainconfig->tollgate($appId);
		$this->data['newusertask'] = $this->mainconfig->newusertask($appId);
		$this->data['mainlinetask'] = $this->mainconfig->mainlinetask($appId);
		$this->data['branchlinetask'] = $this->mainconfig->branchlinetask($appId);
		$this->data['generaltask'] = $this->mainconfig->generaltask($appId);
		$this->data['errorcode'] = $this->mainconfig->errorcode($appId);
		$this->data['newuserprogress'] = $this->mainconfig->newuserprogress($appId);
		$this->common->requireProduct();
		$this->common->loadHeaderWithDateControl(lang('m_daoRuZhuPeiZhiBiao_yao'));
		
		$ruteName = $this->router->fetch_class();
		$canRead = $this->common->isDisplay($ruteName);
		if($canRead == '1'){
			$this->load->view('manage/mainconfigview',$this->data);
		}
		else{
			$this->load->view('forbidden');
		}
	}

	function excel()
	{
		error_reporting(E_ALL ^ E_NOTICE);
		require_once 'excel_reader.php';
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		$data->read("{$_FILES['userfile']['tmp_name']}");

        $action = $data->sheets[0]['cells'];
        $actiontype = $data->sheets[1]['cells'];
        $function = $data->sheets[2]['cells'];
        $newserveractivity = $data->sheets[3]['cells'];
        $operationactivity = $data->sheets[4]['cells'];
        $prop = $data->sheets[5]['cells'];
        $propertymoney = $data->sheets[6]['cells'];
        $tollgate = $data->sheets[7]['cells'];
        $newusertask = $data->sheets[8]['cells'];
        $mainlinetask = $data->sheets[9]['cells'];
        $branchlinetask = $data->sheets[10]['cells'];
        $generaltask = $data->sheets[11]['cells'];
        $errorcode = $data->sheets[12]['cells'];
        $newuserprogress = $data->sheets[13]['cells'];

        $currentProduct = $this->session->userdata("currentProduct");
		$appId = $currentProduct->id;

		//行为表
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_action');
		for($i=2;$i<(count($action)+1);$i++){
			if(!empty($action[$i])){
				$data = array(
					'app_id' => $appId,
					'action_id' => (empty($action[$i][2]))?null:($action[$i][2]),
					'action_name' => (empty($action[$i][3]))?null:($action[$i][3]),
					'action_desc' => (empty($action[$i][4]))?null:($action[$i][4])
				);
				$this->db->insert('razor_mainconfig_action', $data);
			}
		}
		//行为类型表
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_actiontype');
		for($i=2;$i<(count($actiontype)+1);$i++){
			if(!empty($actiontype[$i])){
				$data = array(
					'app_id' => $appId,
					'actiontype_id' => (empty($actiontype[$i][2]))?null:($actiontype[$i][2]),
					'actiontype_name' => (empty($actiontype[$i][3]))?null:($actiontype[$i][3]),
					'action_category' => (empty($actiontype[$i][4]))?null:($actiontype[$i][4]),
					'split' => (string)$actiontype[$i][5]
				);
				$this->db->insert('razor_mainconfig_actiontype', $data);
			}
		}
		//功能表
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_function');
		for($i=2;$i<(count($function)+1);$i++){
			if(!empty($function[$i])){
				$data = array(
					'app_id' => $appId,
					'action_id' => (empty($function[$i][2]))?null:($function[$i][2]),
					'action_name' => (empty($function[$i][3]))?null:($function[$i][3]),
					'function_id' => (empty($function[$i][4]))?null:($function[$i][4]),
					'function_name' => (empty($function[$i][5]))?null:($function[$i][5])
				);
				$this->db->insert('razor_mainconfig_function', $data);
			}
		}
		//开服活动
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_newserveractivity');
		for($i=2;$i<(count($newserveractivity)+1);$i++){
			if(!empty($newserveractivity[$i])){
				$data = array(
					'app_id' => $appId,
					'newserveractivity_id' => (empty($newserveractivity[$i][2]))?null:($newserveractivity[$i][2]),
					'newserveractivity_name' => (empty($newserveractivity[$i][3]))?null:($newserveractivity[$i][3]),
					'newserveractivity_issue' => (empty($newserveractivity[$i][4]))?null:($newserveractivity[$i][4]),
					'startdate' => (empty($newserveractivity[$i][5]))?null:($newserveractivity[$i][5]),
					'enddate' => (empty($newserveractivity[$i][6]))?null:($newserveractivity[$i][6])
				);
				$this->db->insert('razor_mainconfig_newserveractivity', $data);
			}
		}
		//运营活动
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_operationactivity');
		for($i=2;$i<(count($operationactivity)+1);$i++){
			if(!empty($operationactivity[$i])){
				$data = array(
					'app_id' => $appId,
					'operationactivity_id' => (empty($operationactivity[$i][2]))?null:($operationactivity[$i][2]),
					'operationactivity_name' => (empty($operationactivity[$i][3]))?null:($operationactivity[$i][3]),
					'operationactivity_issue' => (empty($operationactivity[$i][4]))?null:($operationactivity[$i][4]),
					'startdate' => (empty($operationactivity[$i][5]))?null:($operationactivity[$i][5]),
					'enddate' => (empty($operationactivity[$i][6]))?null:($operationactivity[$i][6])
				);
				$this->db->insert('razor_mainconfig_operationactivity', $data);
			}
		}
		//道具表
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_prop');
		for($i=2;$i<(count($prop)+1);$i++){
			if(!empty($prop[$i])){
				$data = array(
					'app_id' => $appId,
					'prop_id' => (empty($prop[$i][2]))?null:($prop[$i][2]),
					'prop_name' => (empty($prop[$i][3]))?null:($prop[$i][3]),
					'prop_category' => (empty($prop[$i][4]))?null:($prop[$i][4])
				);
				$this->db->insert('razor_mainconfig_prop', $data);
			}
		}
		//属性货币表
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_propertymoney');
		for($i=2;$i<(count($propertymoney)+1);$i++){
			if(!empty($propertymoney[$i])){
				$data = array(
					'app_id' => $appId,
					'propertymoney_id' => (empty($propertymoney[$i][2]))?null:($propertymoney[$i][2]),
					'propertymoney_name' => (empty($propertymoney[$i][3]))?null:($propertymoney[$i][3])
				);
				$this->db->insert('razor_mainconfig_propertymoney', $data);
			}
		}
		//关卡表
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_tollgate');
		for($i=2;$i<(count($tollgate)+1);$i++){
			if(!empty($tollgate[$i])){
				$data = array(
					'app_id' => $appId,
					'tollgate_bigcategory_id' => (empty($tollgate[$i][2]))?null:($tollgate[$i][2]),
					'tollgate_bigcategory_name' => (empty($tollgate[$i][3]))?null:($tollgate[$i][3]),
					'tollgate_smallcategory_id' => (empty($tollgate[$i][4]))?null:($tollgate[$i][4]),
					'tollgate_smallcategory_name' => (empty($tollgate[$i][5]))?null:($tollgate[$i][5])
				);
				$this->db->insert('razor_mainconfig_tollgate', $data);
			}
		}
		//新手任务
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_newusertask');
		for($i=2;$i<(count($newusertask)+1);$i++){
			if(!empty($newusertask[$i])){
				$data = array(
					'app_id' => $appId,
					'task_id' => (empty($newusertask[$i][2]))?null:($newusertask[$i][2]),
					'task_name' => (empty($newusertask[$i][3]))?null:($newusertask[$i][3]),
					'step_id' => (empty($newusertask[$i][4]))?null:($newusertask[$i][4]),
					'step_name' => (empty($newusertask[$i][5]))?null:($newusertask[$i][5])
				);
				$this->db->insert('razor_mainconfig_newusertask', $data);	
			}
		}
		//主线任务
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_mainlinetask');
		for($i=2;$i<(count($mainlinetask)+1);$i++){
			if(!empty($mainlinetask[$i])){
				$data = array(
					'app_id' => $appId,
					'task_id' => (empty($mainlinetask[$i][2]))?null:($mainlinetask[$i][2]),
					'task_name' => (empty($mainlinetask[$i][3]))?null:($mainlinetask[$i][3]),
					'step_id' => (empty($mainlinetask[$i][4]))?null:($mainlinetask[$i][4]),
					'step_name' => (empty($mainlinetask[$i][5]))?null:($mainlinetask[$i][5])
				);
				$this->db->insert('razor_mainconfig_mainlinetask', $data);
			}
		}
		//支线任务
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_branchlinetask');
		for($i=2;$i<(count($branchlinetask)+1);$i++){
			if(!empty($branchlinetask[$i])){
				$data = array(
					'app_id' => $appId,
					'task_id' => (empty($branchlinetask[$i][2]))?null:($branchlinetask[$i][2]),
					'task_name' => (empty($branchlinetask[$i][3]))?null:($branchlinetask[$i][3]),
					'step_id' => (empty($branchlinetask[$i][4]))?null:($branchlinetask[$i][4]),
					'step_name' => (empty($branchlinetask[$i][5]))?null:($branchlinetask[$i][5])
				);
				if($data['step_id'] != null){
					$this->db->insert('razor_mainconfig_branchlinetask', $data);
				}
			}
		}
		//一般任务
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_generaltask');
		for($i=2;$i<(count($generaltask)+1);$i++){
			if(!empty($generaltask[$i])){
				$data = array(
					'app_id' => $appId,
					'task_id' => (empty($generaltask[$i][2]))?null:($generaltask[$i][2]),
					'task_name' => (empty($generaltask[$i][3]))?null:($generaltask[$i][3]),
					'step_id' => (empty($generaltask[$i][4]))?null:($generaltask[$i][4]),
					'step_name' => (empty($generaltask[$i][5]))?null:($generaltask[$i][5])
				);
				$this->db->insert('razor_mainconfig_generaltask', $data);
			}
		}
		//错误码
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_errorcode');
		for($i=2;$i<(count($errorcode)+1);$i++){
			if(!empty($errorcode[$i])){
				$data = array(
					'app_id' => $appId,
					'errorcode_id' => (empty($errorcode[$i][2]))?null:($errorcode[$i][2]),
					'errorcode_name' => (empty($errorcode[$i][3]))?null:($errorcode[$i][3])
				);
				$this->db->insert('razor_mainconfig_errorcode', $data);
			}
		}
		//新用户进度步骤
		$this->db->where('app_id',$appId);
		$this->db->delete('razor_mainconfig_newuserprogress');
		for($i=2;$i<(count($newuserprogress)+1);$i++){
			if(!empty($newuserprogress[$i])){
				$data = array(
					'app_id' => $appId,
					'newuserprogress_id' => (empty($newuserprogress[$i][2]))?null:($newuserprogress[$i][2]),
					'newuserprogress_name' => (empty($newuserprogress[$i][3]))?null:($newuserprogress[$i][3])
				);
				$this->db->insert('razor_mainconfig_newuserprogress', $data);
			}
		}

		$this->load->view('layout/reportheader');
		$this->load->view('manage/excelview',$this->data);
	}
}
