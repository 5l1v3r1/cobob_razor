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
 * Capacityvmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Capacityvmodel extends CI_Model {

	/**
	 * Construct load
	 * Construct function
	 * 
	 * @return void
	 */
	function __construct() {
		parent::__construct();
		$this->load->model("product/productmodel", 'product');
		$this->load->model("channelmodel", 'channel');
		$this->load->model("servermodel", 'server');
	}
	
	function getCapacityvDataByTotalserver($fromTime, $toTime, $channel, $server, $version) {
		$list = array();
		$query = $this->CapacityvDataByTotalserver($fromTime, $toTime, $channel, $server, $version);
		$CapacityvRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $CapacityvRow->startdate_sk;
			$fRow['totalpcu'] = $CapacityvRow->totalpcu;
			$fRow['totalcapacity'] = $CapacityvRow->totalcapacity;
			$fRow['userate'] = $CapacityvRow->userate;
			$CapacityvRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function CapacityvDataByTotalserver($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		$sql = "SELECT * FROM
				VIEW_razor_sum_basic_capacityview_totalserver
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND startdate_sk <= '" . $toTime . "'
				AND startdate_sk >= '" . $fromTime . "'
				Order By startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getCapacityvDataByPerserver($date, $channel, $server, $version){
		$list = array();
		$query = $this->CapacityvDataByPerserver($date, $channel, $server, $version);
		$PerserverRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $PerserverRow->startdate_sk;
			$fRow['server_name'] = $PerserverRow->server_name;
			$fRow['curdate_pcu'] = $PerserverRow->curdate_pcu;
			$fRow['server_capacity'] = $PerserverRow->server_capacity;
			$fRow['userate'] = $PerserverRow->userate;
			$PerserverRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function CapacityvDataByPerserver($date, $channel, $server, $version){
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		$sql = "SELECT * FROM
				VIEW_razor_sum_basic_capacityview_perserver
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND startdate_sk = '" . $date . "'
				Order By startdate_sk ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	/**
	 * unescape 
	 * 
	 * @param Array $str
	 * 
	 * @return Array $ret
	 */
	function unescape($str) {
		$ret = '';
		$len = strlen($str);
		for ($i = 0; $i < $len; $i ++) {
			if ($str[$i] == '%' && $str[$i + 1] == 'u') {
				$val = hexdec(substr($str, $i + 2, 4));
				if ($val < 0x7f)
					$ret .= chr($val);
				else
				if ($val < 0x800)
					$ret .= chr(0xc0 | ($val >> 6)) .
							chr(0x80 | ($val & 0x3f));
				else
					$ret .= chr(0xe0 | ($val >> 12)) .
							chr(0x80 | (($val >> 6) & 0x3f)) .
							chr(0x80 | ($val & 0x3f));
				$i += 5;
			} else
			if ($str[$i] == '%') {
				$ret .= urldecode(substr($str, $i, 3));
				$i += 2;
			} else
				$ret .= $str[$i];
		}
		return $ret;
	}
}
