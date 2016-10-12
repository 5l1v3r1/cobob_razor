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
class Excel extends CI_Controller
{
	private $data = array();
	function __construct()
	{
		parent::__construct();
		//common function
		$this->load->Model('common');
		//check is_logged_in
		$this->common->requireLogin();
		$this->load->library('session');
		//check compare product
		$this->common->checkCompareProduct();
	}
	function index()
	{
		header("Content-type:application/vnd.ms-excel"); 
		header("Content-Disposition:filename=test.xls");
		$con = (isset($_POST['con']))?$_POST['con']:0;
		if($con != '0'){
			$this->session->set_userdata('razorImportExcel', $con);
		}
		echo $this->session->userdata('razorImportExcel');
	}
}
