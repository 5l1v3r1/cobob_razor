<?php
class Mainconfigmodel extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->model("product/productmodel", 'product');
		$this->load->model("channelmodel", 'channel');
		$this->load->model("servermodel", 'server');
	}
	//行为表
	function action($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_action');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['app_id'] = $row->app_id;
			$fRow['action_id'] = $row->action_id;
			$fRow['action_name'] = $row->action_name;
			$fRow['action_desc'] = $row->action_desc;
			array_push($list, $fRow);
		}
		return $list;
	}
	//行为类型表
	function actiontype($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_actiontype');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['app_id'] = $row->app_id;
			$fRow['actiontype_id'] = $row->actiontype_id;
			$fRow['actiontype_name'] = $row->actiontype_name;
			$fRow['action_category'] = $row->action_category;
			$fRow['split'] = $row->split;
			array_push($list, $fRow);
		}
		return $list;
	}
	//功能表
	function _function($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_function');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['app_id'] = $row->app_id;
			$fRow['action_id'] = $row->action_id;
			$fRow['action_name'] = $row->action_name;
			$fRow['function_id'] = $row->function_id;
			$fRow['function_name'] = $row->function_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//开服活动
	function newserveractivity($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_newserveractivity');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['app_id'] = $row->app_id;
			$fRow['newserveractivity_id'] = $row->newserveractivity_id;
			$fRow['newserveractivity_name'] = $row->newserveractivity_name;
			$fRow['newserveractivity_issue'] = $row->newserveractivity_issue;
			$fRow['startdate'] = $row->startdate;
			$fRow['enddate'] = $row->enddate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//运营活动
	function operationactivity($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_operationactivity');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['app_id'] = $row->app_id;
			$fRow['operationactivity_id'] = $row->operationactivity_id;
			$fRow['operationactivity_name'] = $row->operationactivity_name;
			$fRow['operationactivity_issue'] = $row->operationactivity_issue;
			$fRow['startdate'] = $row->startdate;
			$fRow['enddate'] = $row->enddate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//道具表
	function prop($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_prop');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['prop_id'] = $row->prop_id;
			$fRow['prop_name'] = $row->prop_name;
			$fRow['prop_category'] = $row->prop_category;
			array_push($list, $fRow);
		}
		return $list;
	}
	//属性货币表
	function propertymoney($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_propertymoney');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['propertymoney_id'] = $row->propertymoney_id;
			$fRow['propertymoney_name'] = $row->propertymoney_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//关卡表
	function tollgate($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_tollgate');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['tollgate_bigcategory_id'] = $row->tollgate_bigcategory_id;
			$fRow['tollgate_bigcategory_name'] = $row->tollgate_bigcategory_name;
			$fRow['tollgate_smallcategory_id'] = $row->tollgate_smallcategory_id;
			$fRow['tollgate_smallcategory_name'] = $row->tollgate_smallcategory_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//新手任务
	function newusertask($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_newusertask');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['task_id'] = $row->task_id;
			$fRow['task_name'] = $row->task_name;
			$fRow['step_id'] = $row->step_id;
			$fRow['step_name'] = $row->step_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//主线任务
	function mainlinetask($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_mainlinetask');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['task_id'] = $row->task_id;
			$fRow['task_name'] = $row->task_name;
			$fRow['step_id'] = $row->step_id;
			$fRow['step_name'] = $row->step_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//支线任务
	function branchlinetask($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_branchlinetask');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['task_id'] = $row->task_id;
			$fRow['task_name'] = $row->task_name;
			$fRow['step_id'] = $row->step_id;
			$fRow['step_name'] = $row->step_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//一般任务
	function generaltask($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_generaltask');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['task_id'] = $row->task_id;
			$fRow['task_name'] = $row->task_name;
			$fRow['step_id'] = $row->step_id;
			$fRow['step_name'] = $row->step_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//错误码
	function errorcode($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_errorcode');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['errorcode_id'] = $row->errorcode_id;
			$fRow['errorcode_name'] = $row->errorcode_name;
			array_push($list, $fRow);
		}
		return $list;
	}
	//新用户进度步骤
	function newuserprogress($appId){
		$this->db->where('app_id',$appId);
		$query = $this->db->get('razor_mainconfig_newuserprogress');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['app_id'] = $row->app_id;
			$fRow['newuserprogress_id'] = $row->newuserprogress_id;
			$fRow['newuserprogress_name'] = $row->newuserprogress_name;
			array_push($list, $fRow);
		}
		return $list;
	}
}
