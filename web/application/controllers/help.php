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
 * Help Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Help extends CI_Controller
{
	/**
	 * Construct funciton, to pre-load lang,model and helper configuration
	 *
	 * @return void
	 */
	private $data = array();
	function __construct()
	{
		parent::__construct();
		$this->lang->load('allview');
		$this->load->model('common');
		$this->load->helper('cookie');
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
	 * Index funciton, to load header and helperview
	 *
	 * @return void
	 */
	function index()
	{	
		$this->db->select();
		$query = $this->db->get('razor_helpiteminfo');
		$list = array();
		foreach ($query->result() as $row)
		{	
			$fRow = array();
			$fRow['id'] = $row->id;
			$fRow['helpitemid'] = $row->helpitemid;
			$fRow['helptype'] = $row->helptype;
			$fRow['helpitem'] = $row->helpitem;
			$fRow['equation'] = $row->equation;
			$fRow['statisticalcycle'] = $row->statisticalcycle;
			$fRow['explanationofnouns'] = $row->explanationofnouns;
			array_push($list, $fRow);
		}
		$this->data['result'] = $list;
		$this->common->loadHeaderWithDateControl(lang('m_termsAndD'));
		$this->load->view('helper/helpview',$this->data);
	}

	function edit()
	{
		$this->common->loadHeaderWithDateControl(lang('m_termsAndD'));
		$this->load->view('helper/helpeditview');
	}

	function add()
	{
		$uid = $_POST['uid'];
		$classify = $_POST['classify'];
		$rules = $_POST['rules'];
		$formula = $_POST['formula'];
		$calculation = $_POST['calculation'];
		$explain = $_POST['explain'];

		$data = array(
			'helpitemid' => $uid,
			'helptype' => $classify,
			'helpitem' => $rules,
			'equation' => $formula,
			'statisticalcycle' => $calculation,
			'explanationofnouns' => $explain
		);
		echo $this->db->insert('razor_helpiteminfo',$data);
	}

	function delete()
	{
		$uid = $_POST['uid'];
		$this->db->where('id',$uid);
		echo $this->db->delete('razor_helpiteminfo');
	}

	function search()
	{
		$uid = $_POST['uid'];
		$this->db->select();
		$this->db->where('id',$uid);
		$query = $this->db->get('razor_helpiteminfo');
		$list = array();
		foreach ($query->result() as $row)
		{	
			$fRow = array();
			$fRow['helpitemid'] = $row->helpitemid;
			$fRow['helptype'] = $row->helptype;
			$fRow['helpitem'] = $row->helpitem;
			$fRow['equation'] = $row->equation;
			$fRow['statisticalcycle'] = $row->statisticalcycle;
			$fRow['explanationofnouns'] = $row->explanationofnouns;
			array_push($list, $fRow);
		}
		echo json_encode($list);
	}

	function editInfo()
	{
		$id = $_POST['id'];
		$uid = $_POST['uid'];
		$classify = $_POST['classify'];
		$rules = $_POST['rules'];
		$formula = $_POST['formula'];
		$calculation = $_POST['calculation'];
		$explain = $_POST['explain'];

		$data = array(
			'helpitemid' => $uid,
			'helptype' => $classify,
			'helpitem' => $rules,
			'equation' => $formula,
			'statisticalcycle' => $calculation,
			'explanationofnouns' => $explain
		);
		$this->db->where('id',$id);
		echo $this->db->update('razor_helpiteminfo',$data);
	}
}