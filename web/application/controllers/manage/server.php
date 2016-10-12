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
 * Hint Message
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Server Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Server extends CI_Controller
{

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
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->library('session');
		$this->load->model('common');
		$this->load->model('servermodel', 'server');
		$this->load->model('product/productmodel', 'product');
	}

	/**
	 * Index
	 *
	 * @return void
	 */
	function index()
	{
		$userid = $this->common->getUserId();
		$this->data['num'] = $this->server->getservernum($userid);
		$this->data['server'] = $this->server->getserver($userid);
		$this->data['product_name'] = $this->server->getproductname($userid);
		//get all server
		$this->data['allserver'] = $this->server->getallserver($userid);
		$this->data['isAdmin'] = $this->common->isAdmin();
		$userid = $this->common->getUserId();
		$this->data['guest_roleid'] = $this->common->getUserRoleById($userid);
		$this->common->loadHeader(lang('m_serverManagement'));
		$this->data['product_list'] = $this->server->getproductlist($userid);
		$this->load->view('manage/serverview', $this->data);
	}

	/**
	 * Addserver add server
	 * @return bool
	 */
	function addserver()
	{
		$userid = $this->common->getUserId();
		$server_id = $_POST['server_id'];
		$server_name = $_POST['server_name'];
		$product_name = $_POST['product_name'];
		$server_contain = $_POST['server_contain'];
		$isUnique = $this->server->isUniqueServer($userid, $server_name,$product_name,$server_contain);
		if (!empty($isUnique)) {
			echo false;
		} else {
			if ($server_name != '') {
				$this->server->addserver($server_name,$server_id,$product_name,$server_contain,$userid);
				echo true;
			}
		}
	}

	/**
	 * Addsychannel add system channel
	 *
	 * @return bool
	 */
	function addsychannel()
	{
		$userid = $this->common->getUserId();
		$channel_name = $_POST['channel_name'];
		$platform = $_POST['platform'];
		$isUnique = $this->channel->isUniqueSystemchannel($channel_name, $platform);
		if (!empty($isUnique)) {
			echo false;
		} else {
			if ($channel_name != '' && $platform != '') {
				$this->channel->addsychannel($channel_name, $platform, $userid);
				echo true;
			}
		}
	}

	/**
	 * Editserver function
	 * edit server
	 *
	 * @param string $server_id server_id
	 *            
	 * @return void
	 */
	function editserver($server_id)
	{
		$userid = $this->common->getUserId();
		$this->data['edit'] = $this->server->getserverinfo($userid, $server_id);
		$this->data['guest_roleid'] = $this->common->getUserRoleById($userid);
		$this->data['product_list'] = $this->server->getproductlist($userid);
		$edit = $this->server->getserverinfo($userid, $server_id);
		$this->common->loadHeader(lang('v_man_pr_editServer'));
		$this->load->view('manage/serveredit', $this->data);
	}

	/**
	 * Modifyserver function
	 * modify server
	 *
	 * @return bool
	 */
	function modifyserver()
	{
		$id = $_POST['id'];
		$server_id = $_POST['server_id'];
		$server_name = $_POST['server_name'];
		$product_name = $_POST['product_name'];
		$server_contain = $_POST['server_contain'];
		$isUnique = '';
		$userid = $this->common->getUserId();
		$isUnique = $this->server->isUniqueServer($userid,$server_id,$server_name,$product_name,$server_contain);
		if (!empty($isUnique)) {
			echo false;
		} else {
			if ($server_name != '') {
				$this->server->updateserver($server_name,$server_id,$product_name,$server_contain,$id);
				echo true;
			}
		}
	}

	/**
	 * Deleteserver function
	 * delete server
	 *
	 * @param string $server_id server_id
	 *            
	 * @return void
	 */
	function deleteserver($server_id)
	{
		$this->server->deleteserver($server_id);
		$this->index();
	}

	/**
	 * Appchannel function
	 * app channel
	 *
	 * @return void
	 */
	function appchannel()
	{
		$user_id = $this->common->getUserId();
		$product = $this->common->getCurrentProduct();
		if (!empty($product)) {
			$product_id = $product->id;
			$platform = $this->common->getCurrentProduct()->product_platform;
			// echo $platform;
			$this->data['productkey'] = $this->channel->getproductkey($user_id, $product_id, $platform);
			$this->data['deproductkey'] = $this->channel->getdefineproductkey($user_id, $product_id, $platform);
			$this->data['channel'] = $this->channel->getdefinechannel($user_id, $product_id, $platform);
			$this->data['sychannel'] = $this->channel->getsychannel($user_id, $product_id, $platform);
			$this->common->loadHeader(lang('m_rpt_appChannel'));
			$this->load->view('manage/appchannel', $this->data);
		} else {
			redirect('/auth/login/');
		}
	}

	/**
	 * Openchannel function
	 * open channel
	 *
	 * @param string $channel_id channel_id
	 *            
	 * @return void
	 */
	function openchannel($channel_id)
	{
		$user_id = $this->common->getUserId();
		$product = $this->common->getCurrentProduct();
		if (!empty($product)) {
			$product_id = $product->id;
			// $channel_id=$_GET['channelid'];
			$this->product->addproductchannel($user_id, $product_id, $channel_id);
			$this->appchannel();
		} else {
			redirect('/auth/login/');
		}
	}
}
