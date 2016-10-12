<?php

/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class Common extends CI_Model {

	function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('ums_acl');
		$this->load->library('export');
		$this->load->model('pluginm');
		$this->load->model('headFilterModel', 'headFilter');
		$this->load->database();
	}

	function getdbprefixtable($name) {
		$tablename = $this->db->dbprefix($name);
		return $tablename;
	}

	function getMaxY($count) {
		if ($count <= 5) {
			return 5;
		} else {
			return $count + $count / 10;
		}
	}

	function getTimeTick($days) {
		if ($days <= 7) {
			return 1;
		}

		if ($days > 7 && $days <= 30) {
			return 2;
		}

		if ($days > 30 && $days <= 90) {
			return 10;
		}

		if ($days > 90 && $days <= 270) {
			return 30;
		}

		if ($days > 270 && $days <= 720) {
			return 70;
		}
		return 1;
	}

	function getStepY($count) {
		if ($count <= 5) {
			return 1;
		} else {
			return round($count / 5, 0);
		}
	}

	function loadCompareHeader($viewname = "", $showDate = TRUE, $showFilter = TRUE) {
		$this->load->model('product/productmodel', 'product');
		$this->load->helper('cookie');
		if (!$this->common->isUserLogin()) {
			$dataheader['login'] = false;
			$this->load->view('layout/compareHeader', $dataheader);
		} else {
			$dataheader['user_id'] = $this->getUserId();
			$dataheader['pageTitle'] = lang("c_" . $this->router->fetch_class());
			if ($this->isAdmin()) {
				$dataheader['admin'] = true;
			}
			$dataheader['login'] = true;
			$dataheader['username'] = $this->getUserName();
			$product = $this->getCurrentProduct();
			if (isset($product) && $product != null) {
				$dataheader['product'] = $product;
				log_message("error", "HAS Product");
			}

			$productList = $this->product->getAllProducts($dataheader['user_id']);
			if ($productList != null && $productList->num_rows() > 0) {
				$productInfo = array();
				$userId = $this->getUserId();
				foreach ($productList->result() as $row) {
					if (!$this->product->isAdmin() && !$this->product->isUserHasProductPermission($userId, $row->id)) {
						continue;
					}
					$productObj = array('id' => $row->id, 'name' => $row->name);
					array_push($productInfo, $productObj);
				}
				if (count($productInfo) > 0) {
					$dataheader["productList"] = $productInfo;
				}
			}
			$products = $this->getCompareProducts();
			$dataheader['products'] = $products;
			log_message("error", "Load Header 123");
			$dataheader['language'] = $this->config->item('language');
			$dataheader['viewname'] = $viewname;
			if ($showDate) {
				$dataheader["showDate"] = true;
			}
			if ($showFilter) {
				$dataheader["showFilter"] = true;
			}
			$this->load->view('layout/compareHeader', $dataheader);
		}
	}

	function checkCompareProduct() {
		$products = $this->common->getCompareProducts();
		if (isset($_GET['type']) && 'compare' == $_GET['type']) {
			if (empty($products) || count($products) < 2 || count($products) > 4) {
				redirect(base_url());
			}
		} else {
			
		}
	}

	function loadHeaderWithDateControl($viewname = "") {
		$this->loadHeader($viewname, TRUE, TRUE);
	}

	function loadHeader($viewname = "", $showDate = FALSE, $showFilter = FALSE) {
		$this->load->model('interface/getnewversioninfo', 'getnewversioninfo');
		$this->load->model('product/productmodel', 'product');
		$this->load->model('pluginlistmodel', 'plugin');
		$this->load->helper('cookie');
		if (!$this->common->isUserLogin()) {

			$dataheader['login'] = false;
			$version = $this->config->item('version');
			$versiondata = $this->getnewversioninfo->newversioninfo($version);
			if ($versiondata) {
				$dataheader['versionvalue'] = $versiondata['version'];
				$dataheader['version'] = $versiondata['updateurl'];
			}
			$inform = $this->session->userdata('newversion');
			if ($inform == "noinform") {
				$dataheader['versioninform'] = $inform;
			}
			$this->load->view('layout/header', $dataheader);
		} else {
			//get user id
			$dataheader['user_id'] = $this->getUserId();
			$dataheader['pageTitle'] = lang("c_" . $this->router->fetch_class());
			//check user isAdmin
			if ($this->isAdmin()) {
				$dataheader['admin'] = true;
			}
			//check user isLogin
			$dataheader['login'] = true;
			//get username
			$dataheader['username'] = $this->getUserName();
			//get current product info
			$product = $this->getCurrentProduct();
			if (isset($product) && $product != null) {
				$dataheader['product'] = $product;
				log_message("error", "HAS Product");
			}
			//get all product info:product_id and product_name
			$productList = $this->product->getAllProducts($dataheader['user_id']);
			if ($productList != null && $productList->num_rows() > 0) {
				$productInfo = array();
				$userId = $this->getUserId();
				foreach ($productList->result() as $row) {
					if (!$this->product->isAdmin() && !$this->product->isUserHasProductPermission($userId, $row->id)) {
						continue;
					}
					$productObj = array('id' => $row->id, 'name' => $row->name);
					array_push($productInfo, $productObj);
				}
				if (count($productInfo) > 0) {
					$dataheader["productList"] = $productInfo;
				}
			}
			log_message("error", "Load Header 123");
			//get the language from the config.php
			$dataheader['language'] = $this->config->item('language');
			//get the view name
			$dataheader['viewname'] = $viewname;
			if ($showDate) {
				$dataheader["showDate"] = true;
			}
			if ($showFilter) {
				$dataheader["showFilter"] = true;
			}
			//get the version about razor
			$version = $this->config->item('version');
			$versiondata = $this->getnewversioninfo->newversioninfo($version);
			if ($versiondata) {
				$dataheader['versionvalue'] = $versiondata['version'];
				$dataheader['version'] = $versiondata['updateurl'];
			}
			$dataheader['versionvalue'] = $versiondata['version'];
			$dataheader['version'] = $versiondata['updateurl'];
			$inform = $this->session->userdata('newversion');
			if ($inform == "noinform") {
				$dataheader['versioninform'] = $inform;
			}


			$this->load->model('pluginlistmodel');

			$userId = $this->getUserId();
			$userKeys = $this->pluginlistmodel->getUserKeys($userId);
			if ($userKeys) {
				$dataheader['key'] = $userKeys->user_key;
				$dataheader['secret'] = $userKeys->user_secret;
			}

			//筛选聚到 区服 版本 查询显示
			$dataheader['filter'] = $this->headFilter->getFilterData();
			$dataheader['product_id'] = $product;

			//菜单读取显示
			$total_nav = array();
			$overview = array();//总览
			$useranaly = array();//用户分析
			$payanaly = array();//用户分析
			$onlineanaly = array();//在线分析
			$lossanaly = array();//流失分析
			$systemanaly = array();//系统分析
			$channelanaly = array();//渠道分析
			$erroranaly = array();//异常分析
			$management = array();//管理

			$classifyNum = 9;//分类数

			for($i=0;$i<($classifyNum+1);$i++){
				$this->db->where('classify',$i);
				$this->db->order_by('classify','asc');
				$query = $this->db->get('razor_user_resources_item')->result();
				switch($i)
				{
					case 1:
						array_push($overview,$query);
						break;
					case 2:
						array_push($useranaly,$query);
						break;
					case 3:
						array_push($payanaly,$query);
						break;
					case 4:
						array_push($onlineanaly,$query);
						break;
					case 5:
						array_push($lossanaly,$query);
						break;
					case 6:
						array_push($systemanaly,$query);
						break;
					case 7:
						array_push($channelanaly,$query);
						break;
					case 8:
						array_push($erroranaly,$query);
						break;
					case 9:
						array_push($management,$query);
						break;
				}
				
			}
			array_push($total_nav,$overview,$useranaly,$payanaly,$onlineanaly,$lossanaly,$systemanaly,$channelanaly,$erroranaly,$management);
			
			$roleId = $this->common->getUserRoleById($userId);

			$query = $this->db->get('razor_user_permissions_item')->result();

			$catalog = array();
			foreach ($query as $key) {
				$row = array(
					'source'=>$key->source,
					'read'=>$key->read
				);
				array_push($catalog,$row);
			}

			$dataheader['nav'] = $total_nav;
			$dataheader['classifyNum'] = $classifyNum;
			$dataheader['catalog'] = $catalog;

			//结束
			$this->load->view('layout/header', $dataheader);
		}
	}
	//权限显示配置 danny 2016.5.12
	function isDisplay($ruteName){
		$userId = $this->getUserId();
		$role = $this->getUserRoleById($userId);//获取角色ID
		$this->db->select('id');
		$this->db->where('name',$ruteName);
		$query = $this->db->get('razor_user_resources_item');
		$result = $query->row();//菜单ID
		$resourcesId = $result->id;

		$this->db->select('read');
		$this->db->where('source',$resourcesId);
		$this->db->where('role',$role);
		$query = $this->db->get('razor_user_permissions_item');
		$result = $query->row();
		if(empty($result)){
			return null;
		}
		return $result->read;
	}
	function show_message($message) {
		$this->session->set_userdata('message', $message);
		redirect('/auth/');
	}

	function requireLogin() {
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
	}

	function requireProduct() {
		$product = $this->getCurrentProduct();
		if (empty($product)) {
			redirect(site_url());
		} else {
			$userId = $this->getUserId();
			$productId = $product->id;
			$this->checkUserPermissionToProduct($userId, $productId);
		}
	}

	function checkUserPermissionToProduct($userId, $productId) {
		$this->load->model('product/productmodel', 'product');
		if (!$this->isAdmin() && !$this->product->isUserHasProductPermission($userId, $productId)) {
			$this->session->set_userdata('message', "You don't have permission to view this product.");
			redirect(site_url());
		}
	}

	function isAdmin() {
		$userid = $this->tank_auth->get_user_id();
		$role = $this->getUserRoleById($userid);
		if ($role == 3) {
			return true;
		}
		return false;
	}

	function getUserId() {
		return $this->tank_auth->get_user_id();
	}

	function getUserName() {
		return $this->tank_auth->get_username();
	}

	function isUserLogin() {
		return $this->tank_auth->is_logged_in();
	}

	function canRead($controllerName) {
		$id = $this->getResourceIdByControllerName($controllerName);
		if ($id) {
			$acl = new Ums_acl();
			$userid = $this->tank_auth->get_user_id();
			$role = $this->getUserRoleById($userid);
			if ($acl->can_read($role, $id)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function getPageTitle($controllerName) {
		$this->db->where('name', $controllerName);
		$query = $this->db->get('user_resources');
		if ($query != null && $query->num_rows() > 0) {
			return $query->first_row()->description;
		}
		return "";
	}

	// private functiosn
	function getResourceIdByControllerName($controllerName) {
		$this->db->where('name', $controllerName);
		$query = $this->db->get('user_resources');
		if ($query != null && $query->num_rows() > 0) {
			return $query->first_row()->id;
		}
		return null;
	}

	function getUserRoleById($id) {
		if ($id == '')
			return '2';
		$this->db->select('roleid');
		$this->db->where('userid', $id);
		$query = $this->db->get('user2role');
		$row = $query->first_row();
		if ($query->num_rows > 0) {
			return $row->roleid;
		} else
			return '2';
	}

	function getCurrentProduct() {
		$currentProduct = $this->session->userdata("currentProduct");
		if ($currentProduct) {
			$userId = $this->getUserId();
			$productId = $currentProduct->id;
			$this->checkUserPermissionToProduct($userId, $productId);
		}
		return $currentProduct;
	}

	function getCurrentProductIfExist() {
		$currentProduct = $this->session->userdata("currentProduct");
		if (isset($currentProduct)) {
			return $currentProduct;
		} else {
			return false;
		}
	}

	function cleanCurrentProduct() {
		$this->session->unset_userdata("currentProduct");
	}

	function setCurrentProduct($productId) {
		$this->load->model('product/productmodel', 'product');
		$currentProduct = $this->product->getProductById($productId);
		$this->session->set_userdata("currentProduct", $currentProduct);
	}

	function setCompareProducts($productIds = array()) {
		$this->session->set_userdata('compareProducts', $productIds);
	}

	function getCompareProducts() {
		$this->load->model('product/productmodel', 'product');
		$pids = $this->session->userdata("compareProducts");
		$products = array();
		for ($i = 0; $i < count($pids); $i++) {
			$product = $this->product->getProductById($pids[$i]);
			$products[$i] = $product;
		}
		return $products;
	}

	function changeTimeSegment($pase, $from, $to) {
		$this->load->model('product/productmodel', 'product');
		$toTime = date('Y-m-d', time());
		switch ($pase) {
			case "0day" : {
					$fromTime = date("Y-m-d", strtotime("-0 day"));
				}
				break;
			case "1day" : {
					$toTime = date("Y-m-d", strtotime("-1 day"));
					$fromTime = date("Y-m-d", strtotime("-1 day"));
				}
				break;
			case "7day" : {
					$fromTime = date("Y-m-d", strtotime("-6 day"));
				}
				break;
			case "1month" : {
					$fromTime = date("Y-m-d", strtotime("-31 day"));
				}
				break;
			case "3month" : {
					$fromTime = date("Y-m-d", strtotime("-92 day"));
				}
				break;
			case "all" : {
					$currentProduct = $this->getCurrentProductIfExist();
					if ($currentProduct) {
						$fromTime = $this->product->getReportStartDate($currentProduct, '1970-02-01');
						$fromTime = date("Y-m-d", strtotime($fromTime));
					} else {
						$fromTime = $this->product->getUserStartDate($this->getUserId(), '1970-01-01');
						$fromTime = date("Y-m-d", strtotime($fromTime));
					}
				}
				break;
			case "any" : {
					$fromTime = $from;
					$toTime = $to;
				}
				break;
			default : {
					$fromTime = date("Y-m-d", strtotime("-6 day"));
				}
				break;
		}
		if ($fromTime > $toTime) {
			$tmp = $toTime;
			$toTime = $fromTime;
			$fromTime = $tmp;
		}
		$this->session->set_userdata('fromTime', $fromTime);
		$this->session->set_userdata('toTime', $toTime);
	}

	/*
	 * danny edit
	 * add server version channel session
	 */

	function svcChange($channel, $version, $server) {
		$this->deletesvcChange();
		if (!empty($server)) {
			$this->session->set_userdata('server', $server);
		}
		if (!empty($version)) {
			$this->session->set_userdata('version', $version);
		}
		if (!empty($channel)) {
			$this->session->set_userdata('channel', $channel);
		}
	}

	function deletesvcChange(){
		$this->session->unset_userdata('channel');
		$this->session->unset_userdata('version');
		$this->session->unset_userdata('server');
	}

	function getFromTime() {
		$fromTime = $this->session->userdata("fromTime");
		if (isset($fromTime) && $fromTime != null && $fromTime != "") {
			return $fromTime;
		}
		$fromTime = date("Y-m-d", strtotime("-6 day"));
		return $fromTime;
	}

	function getPredictiveValurFromTime() {
		$time = $this->getFromTime();
		$fromTime = date("Y-m-d", strtotime("$time -5 day"));
		return $fromTime;
	}

	function getToTime() {
		$toTime = $this->session->userdata("toTime");
		if (isset($toTime) && $toTime != null && $toTime != "") {
			return $toTime;
		}
		$toTime = date('Y-m-d', time());
		return $toTime;
	}

	function getChannel() {
		$channel = $this->session->userdata("channel");
		if (isset($channel) && $channel != null && $channel != "") {
			return $channel;
		}
		$channel = 'all';
		return $channel;
	}

	function getServer() {
		$server = $this->session->userdata("server");
		if (isset($server) && $server != null && $server != "") {
			return $server;
		}
		$server = 'all';
		return $server;
	}

	function getVersion() {
		$version = $this->session->userdata("version");
		if (isset($version) && $version != null && $version != "") {
			return $version;
		}
		$version = 'all';
		return $version;
	}

	function getDateList($from, $to) {
		$defdate = array();
		for ($i = strtotime($from); $i <= strtotime($to); $i += 86400) {
			if (!in_array(date('Y-m-d', $i), $defdate))
				array_push($defdate, date('Y-m-d', $i));
		}
		return $defdate;
	}

	function export($from, $to, $data) {
		$productId = $this->getCurrentProduct()->id;
		$productName = $this->getCurrentProduct()->name;

		$export = new Export();

		$export->setFileName($productName . '_' . $from . '_' . $to . '.xls');

		$fields = array();
		foreach ($data->list_fields() as $field) {
			array_push($fields, $field);
		}
		$export->setTitle($fields);

		foreach ($data->result() as $row)
			$export->addRow($row);
		$export->export();
		die();
	}

	function curl_post($url, $vars) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		$response = curl_exec($ch);
		curl_close($ch);

		if ($response) {
			return $response;
		} else {
			return false;
		}
	}
	
	//这个星期的星期一 
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	function this_monday($timestamp=0,$is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400)); 
			if($is_return_timestamp){ 
				$cache[$id] = strtotime($monday_date); 
			}else{ 
				$cache[$id] = $monday_date; 
			} 
		} 
		return $cache[$id]; 
	   
	} 
	   
	//这个星期的星期天 
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	function this_sunday($timestamp=0,$is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$sunday = $this->this_monday($timestamp) + /*6*86400*/518400; 
			if($is_return_timestamp){ 
				$cache[$id] = $sunday; 
			}else{ 
				$cache[$id] = date('Y-m-d',$sunday); 
			} 
		} 
		return $cache[$id]; 
	} 
	   
	//上周一 
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	function last_monday($timestamp=0,$is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$thismonday = $this->this_monday($timestamp) - /*7*86400*/604800; 
			if($is_return_timestamp){ 
				$cache[$id] = $thismonday; 
			}else{ 
				$cache[$id] = date('Y-m-d',$thismonday); 
			} 
		} 
		return $cache[$id]; 
	}

	//某个周一 
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	function last_monday_1($num,$timestamp=0,$is_return_timestamp=true){ 
		// public $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$thismonday = $this->this_monday($timestamp) - $num*7*86400; 
			if($is_return_timestamp){ 
				$cache[$id] = $thismonday; 
			}else{ 
				$cache[$id] = date('Y-m-d',$thismonday); 
			} 
		} 
		return $cache[$id]; 
	} 
	   
	//上个星期天 
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	function last_sunday($timestamp=0,$is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$thissunday = $this->this_sunday($timestamp) - /*7*86400*/604800; 
			if($is_return_timestamp){ 
				$cache[$id] = $thissunday; 
			}else{ 
				$cache[$id] = date('Y-m-d',$thissunday); 
			} 
		} 
		return $cache[$id]; 
	   
	} 

	//某个星期天 
	// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	function last_sunday_1($num,$timestamp=0,$is_return_timestamp=true){ 
		// public $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$thissunday = $this->this_sunday($timestamp) - $num*7*86400; 
			if($is_return_timestamp){ 
				$cache[$id] = $thissunday; 
			}else{ 
				$cache[$id] = date('Y-m-d',$thissunday); 
			} 
		} 
		return $cache[$id]; 
	   
	} 
	   
	//这个月的第一天 
	// @$timestamp ，某个月的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	   
	function month_firstday($timestamp = 0, $is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$firstday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp),1,date('Y',$timestamp))); 
			if($is_return_timestamp){ 
				$cache[$id] = strtotime($firstday); 
			}else{ 
				$cache[$id] = $firstday; 
			} 
		} 
		return $cache[$id]; 
	} 
   
	//这个月的第一天 
	// @$timestamp ，某个月的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	   
	function month_lastday($timestamp = 0, $is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$lastday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp),date('t',$timestamp),date('Y',$timestamp))); 
			if($is_return_timestamp){ 
				$cache[$id] = strtotime($lastday); 
			}else{ 
				$cache[$id] = $lastday; 
			} 
		} 
		return $cache[$id]; 
	} 
   
	//上个月的第一天 
	// @$timestamp ，某个月的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	   
	function lastmonth_firstday($timestamp = 0, $is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$firstday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp)-1,1,date('Y',$timestamp))); 
			if($is_return_timestamp){ 
				$cache[$id] = strtotime($firstday); 
			}else{ 
				$cache[$id] = $firstday; 
			} 
		} 
		return $cache[$id]; 
	} 
   
	//上个月的最后一天 
	// @$timestamp ，某个月的某一个时间戳，默认为当前时间 
	// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
	   
	function lastmonth_lastday($timestamp = 0, $is_return_timestamp=true){ 
		static $cache ; 
		$id = $timestamp.$is_return_timestamp; 
		if(!isset($cache[$id])){ 
			if(!$timestamp) $timestamp = time(); 
			$lastday = date('Y-m-d', mktime(0,0,0,date('m',$timestamp)-1, date('t',  $this->lastmonth_firstday($timestamp)),date('Y',$timestamp))); 
			if($is_return_timestamp){ 
				$cache[$id] = strtotime($lastday); 
			}else{ 
				$cache[$id] =  $lastday; 
			} 
		} 
		return $cache[$id]; 
	} 


	//某个月的第一天       
	function lastmonth_firstday_1($num){ 
		$firstday=date('Y-m-01',strtotime("-$num month")); //获取指定月份的第一天
		$lastday=date('Y-m-t',strtotime($firstday)); //获取指定月份的最后一天
		return $firstday;

	} 


	//某个月的最后一天       
	function lastmonth_lastday_1($num){ 
		$firstday=date('Y-m-01',strtotime("-$num month")); //获取指定月份的第一天
		$lastday=date('Y-m-t',strtotime($firstday)); //获取指定月份的最后一天
		return $lastday;

	} 

//     echo '本周星期一：'.  $this->common->this_monday(0,false).'<br>'; 
//     echo '本周星期天：'.  $this->common->this_sunday(0,false).'<br>'; 
//     echo '上周星期一：'.  $this->common->last_monday(0,false).'<br>'; 
//     echo '上周星期天：'.  $this->common->last_sunday(0,false).'<br>'; 
//     echo '本月第一天：'.  $this->common->month_firstday(0,false).'<br>'; 
//     echo '本月最后一天：'.  $this->common->month_lastday(0,false).'<br>'; 
//     echo '上月第一天：'.  $this->common->lastmonth_firstday(0,false).'<br>'; 
//     echo '上月最后一天：'.  $this->common->lastmonth_lastday(0,false).'<br>'; 
//     
//     
	function getDateadddate($date,$day){
		$sql="select DATE_ADD('$date',INTERVAL $day DAY) dateadddate;";
		$query = $this->db->query($sql);
		$row = $query->first_row();
		if ($query->num_rows > 0) {
		return $row->dateadddate;
		}       

	 }
}
