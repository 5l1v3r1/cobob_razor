<?php
class Logquerymodel extends CI_Model {

	private $dataLimit = 500;

	function __construct() {
		parent::__construct();
		$this->load->model("product/productmodel", 'product');
		$this->load->model("channelmodel", 'channel');
		$this->load->model("servermodel", 'server');
		$this->load->model('common');
		$this->load->database();
	}
	
	//设备信息表
	function clientdata($appId){
		$this->db->order_by('id','desc');
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		$query = $this->db->get('razor_clientdata');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['serviceversion'] = $row->serviceversion;
			$fRow['name'] = $row->name;
			$fRow['version'] = $row->version;
			$fRow['appId'] = $row->appId;
			$fRow['platform'] = $row->platform;
			$fRow['osversion'] = $row->osversion;
			$fRow['osaddtional'] = $row->osaddtional;
			$fRow['language'] = $row->language;
			$fRow['resolution'] = $row->resolution;
			$fRow['ismobiledevice'] = $row->ismobiledevice;
			$fRow['devicename'] = $row->devicename;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['defaultbrowser'] = $row->defaultbrowser;
			$fRow['javasupport'] = $row->javasupport;
			$fRow['flashversion'] = $row->flashversion;
			$fRow['modulename'] = $row->modulename;
			$fRow['imei'] = $row->imei;
			$fRow['imsi'] = $row->imsi;
			$fRow['salt'] = $row->salt;
			$fRow['havegps'] = $row->havegps;
			$fRow['havebt'] = $row->havebt;
			$fRow['havewifi'] = $row->havewifi;
			$fRow['havegravity'] = $row->havegravity;
			$fRow['wifimac'] = $row->wifimac;
			$fRow['latitude'] = $row->latitude;
			$fRow['longitude'] = $row->longitude;
			$fRow['date'] = $row->date;
			$fRow['clientip'] = $row->clientip;
			$fRow['productkey'] = $row->productkey;
			$fRow['service_supplier'] = $row->service_supplier;
			$fRow['country'] = $row->country;
			$fRow['region'] = $row->region;
			$fRow['city'] = $row->city;
			$fRow['street'] = $row->street;
			$fRow['streetno'] = $row->streetno;
			$fRow['postcode'] = $row->postcode;
			$fRow['network'] = $row->network;
			$fRow['isjailbroken'] = $row->isjailbroken;
			$fRow['insertdate'] = $row->insertdate;
			$fRow['useridentifier'] = $row->useridentifier;
			array_push($list, $fRow);
		}
		return $list;
	}
	//玩家角色信息表
	function createrole($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('create_role_date <=',$toTime);
		$this->db->where('create_role_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_createrole');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['create_role_date'] = $row->create_role_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['obligate1'] = $row->obligate1;
			$fRow['obligate2'] = $row->obligate2;
			$fRow['obligate3'] = $row->obligate3;
			$fRow['obligate4'] = $row->obligate4;
			$fRow['obligate5'] = $row->obligate5;
			$fRow['obligate6'] = $row->obligate6;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['create_role_time'] = $row->create_role_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleSex'] = $row->roleSex;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//安装表
	function install($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('install_date <=',$toTime);
		$this->db->where('install_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		$query = $this->db->get('razor_install');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['install_date'] = $row->install_date;
			$fRow['chId'] = $row->chId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['install_time'] = $row->install_time;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//登录表
	function login($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('login_date <=',$toTime);
		$this->db->where('login_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_login');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['login_date'] = $row->login_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId; 
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['type'] = $row->type;
			$fRow['offlineSettleTime'] = $row->offlineSettleTime;
			$fRow['obligate1'] = $row->obligate1;
			$fRow['obligate2'] = $row->obligate2;
			$fRow['obligate3'] = $row->obligate3;
			$fRow['obligate4'] = $row->obligate4;
			$fRow['userId'] = $row->userId;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['productkey'] = $row->productkey;
			$fRow['login_time'] = $row->login_time;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['ip'] = $row->ip;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//支付表
	function pay($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('pay_date <=',$toTime);
		$this->db->where('pay_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_pay');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['pay_date'] = $row->pay_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId; 
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['obligate1'] = $row->obligate1;
			$fRow['obligate2'] = $row->obligate2;
			$fRow['obligate3'] = $row->obligate3;
			$fRow['obligate4'] = $row->obligate4;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['pay_time'] = $row->pay_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['pay_unit'] = $row->pay_unit;
			$fRow['pay_type'] = $row->pay_type;
			$fRow['pay_amount'] = $row->pay_amount;
			$fRow['coinAmount'] = $row->coinAmount;
			$fRow['orderId'] = $row->orderId;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//注册表
	function register($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('register_date <=',$toTime);
		$this->db->where('register_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		$query = $this->db->get('razor_register');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['register_date'] = $row->register_date;
			$fRow['chId'] = $row->chId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['obligate1'] = $row->obligate1;
			$fRow['obligate2'] = $row->obligate2;
			$fRow['obligate3'] = $row->obligate3;
			$fRow['obligate4'] = $row->obligate4;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['register_time'] = $row->register_time;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//实时在线用户数表
	function realtimeonlineusers($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('count_date <=',$toTime);
		$this->db->where('count_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_realtimeonlineusers');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['count_date'] = $row->count_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['count_time'] = $row->count_time;
			$fRow['onlineusers'] = $row->onlineusers;
			$fRow['insertdate'] = $row->insertdate;
			$fRow['productkey'] = $row->productkey;
			array_push($list, $fRow);
		}
		return $list;
	}
	//玩家角色等级升级信息记录表
	function levelupgrade($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('levelupgrade_date <=',$toTime);
		$this->db->where('levelupgrade_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_levelupgrade');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['levelupgrade_date'] = $row->levelupgrade_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['levelupgrade_time'] = $row->levelupgrade_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//玩家角色VIP等级升级信息记录表
	function viplevelup($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('viplevelup_date <=',$toTime);
		$this->db->where('viplevelup_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_viplevelup');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['viplevelup_date'] = $row->viplevelup_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['levelupgrade_time'] = $row->levelupgrade_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['currentRoleVip'] = $row->currentRoleVip;
			$fRow['beforeRoleVip'] = $row->beforeRoleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//经验变化表
	function experiencevariation($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('experiencevariation_date <=',$toTime);
		$this->db->where('experiencevariation_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_experiencevariation');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['experiencevariation_date'] = $row->experiencevariation_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['actionid'] = $row->actionid;
			$fRow['experience'] = $row->experience;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['experiencevariation_time'] = $row->experiencevariation_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//功能统计表
	function functioncount($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->order_by('id','desc');
		$this->db->where('functioncount_date <=',$toTime);
		$this->db->where('functioncount_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$query = $this->db->get('razor_functioncount');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['functioncount_date'] = $row->functioncount_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['eventid'] = $row->eventid;
			$fRow['issue'] = $row->issue;
			$fRow['functionid'] = $row->functionid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['functioncount_time'] = $row->functioncount_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//新手引导表
	function newuserguide($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('newuserguide_date <=',$toTime);
		$this->db->where('newuserguide_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_newuserguide');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['newuserguide_date'] = $row->newuserguide_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['newuserguide_id'] = $row->newuserguide_id;
			$fRow['stepid'] = $row->stepid;
			$fRow['userId'] = $row->userId;
			$fRow['markid'] = $row->markid;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['newuserguide_time'] = $row->newuserguide_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//道具消耗表
	function propconsume($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('propconsume_date <=',$toTime);
		$this->db->where('propconsume_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_propconsume');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['propconsume_date'] = $row->propconsume_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['actionid'] = $row->actionid;
			$fRow['propid'] = $row->propid;
			$fRow['proplevel'] = $row->proplevel;
			$fRow['propconsume_count'] = $row->propconsume_count;
			$fRow['prop_stock'] = $row->prop_stock;
			$fRow['functionid'] = $row->functionid;
			$fRow['acionttypeid'] = $row->acionttypeid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['propconsume_time'] = $row->propconsume_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//属性变化表
	function propertyvariation($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('propertyvariation_date <=',$toTime);
		$this->db->where('propertyvariation_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_propertyvariation');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['propertyvariation_date'] = $row->propertyvariation_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['actionid'] = $row->actionid;
			$fRow['property'] = $row->property;
			$fRow['property_variation'] = $row->property_variation;
			$fRow['count'] = $row->count;
			$fRow['stock'] = $row->stock;
			$fRow['functionid'] = $row->functionid;
			$fRow['acionttypeid'] = $row->acionttypeid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['propertyvariation_time'] = $row->propertyvariation_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//道具获得表
	function propgain($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('propgain_date <=',$toTime);
		$this->db->where('propgain_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_propgain');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['propgain_date'] = $row->propgain_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['actionid'] = $row->actionid;
			$fRow['propid'] = $row->propid;
			$fRow['proplevel'] = $row->proplevel;
			$fRow['propgain_count'] = $row->propgain_count;
			$fRow['prop_stock'] = $row->prop_stock;
			$fRow['functionid'] = $row->functionid;
			$fRow['acionttypeid'] = $row->acionttypeid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['propgain_time'] = $row->propgain_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//任务表
	function task($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('task_date <=',$toTime);
		$this->db->where('task_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_task');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['task_date'] = $row->task_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['taskid'] = $row->taskid;
			$fRow['stepid'] = $row->stepid;
			$fRow['markid'] = $row->markid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['task_time'] = $row->task_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//关卡表
	function tollgate($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('tollgate_date <=',$toTime);
		$this->db->where('tollgate_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_tollgate');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['tollgate_date'] = $row->tollgate_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['moduleid'] = $row->moduleid;
			$fRow['tollgateid'] = $row->tollgateid;
			$fRow['tollgategrade'] = $row->tollgategrade;
			$fRow['tollgatesweep'] = $row->tollgatesweep;
			$fRow['pass'] = $row->pass;
			$fRow['passtime'] = $row->passtime;
			$fRow['combattimeout'] = $row->combattimeout;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['tollgate_time'] = $row->tollgate_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//新用户进度表
	function newuserprogress($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('newuserprogress_date <=',$toTime);
		$this->db->where('newuserprogress_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_newuserprogress');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['newuserprogress_date'] = $row->newuserprogress_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['newuserprogressid'] = $row->newuserprogressid;
			$fRow['stepid'] = $row->stepid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['newuserprogress_time'] = $row->newuserprogress_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//错误码表
	function errorcode($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('errorcode_date <=',$toTime);
		$this->db->where('errorcode_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		if(count($server_list) > 0){
			$this->db->where_in('srvId',$server_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_errorcode');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['errorcode_date'] = $row->errorcode_date;
			$fRow['chId'] = $row->chId;
			$fRow['subSrvId'] = $row->subSrvId;
			$fRow['srvId'] = $row->srvId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['errorcodeid'] = $row->errorcodeid;
			$fRow['aciontid'] = $row->aciontid;
			$fRow['functionid'] = $row->functionid;
			$fRow['userId'] = $row->userId;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['errorcode_time'] = $row->errorcode_time;
			$fRow['roleId'] = $row->roleId;
			$fRow['roleName'] = $row->roleName;
			$fRow['roleLevel'] = $row->roleLevel;
			$fRow['roleVip'] = $row->roleVip;
			$fRow['goldCoin'] = $row->goldCoin;
			$fRow['sliverCoin'] = $row->sliverCoin;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
	//设备启动表
	function deviceboot($fromTime, $toTime, $channel_list, $server_list, $version_list, $appId){
		$this->db->where('deviceboot_date <=',$toTime);
		$this->db->where('deviceboot_date >=',$fromTime);
		$this->db->where('appId =',$appId);
		$this->db->limit($this->dataLimit);
		if(count($version_list) > 0){
			$this->db->where_in('version',$version_list);
		}
		if(count($channel_list) > 0){
			$this->db->where_in('chId',$channel_list);
		}
		$this->db->order_by('id','desc');
		$query = $this->db->get('razor_deviceboot');
		$list = array();
		foreach ($query->result() as $row)
		{
			$fRow = array();
			$fRow['id'] = $row->ID;
			$fRow['deviceboot_date'] = $row->deviceboot_date;
			$fRow['chId'] = $row->chId;
			$fRow['appId'] = $row->appId;
			$fRow['version'] = $row->version;
			$fRow['productkey'] = $row->productkey;
			$fRow['deviceid'] = $row->deviceid;
			$fRow['deviceboot_time'] = $row->deviceboot_time;
			$fRow['insertdate'] = $row->insertdate;
			array_push($list, $fRow);
		}
		return $list;
	}
}
