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
 * Userremainmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Userremainmodel extends CI_Model
{
	/** 
	 * Construct load
	 * Construct function
	 * 
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}
	//用户留存
	function getUserRemainData($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->UserRemainData($fromTime,$toTime,$channel,$server,$version);
		$UserRemainRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow["date"] = $UserRemainRow->startdate_sk;
			$fRow['usercount'] = $UserRemainRow->usercount;
			$fRow['day1'] = $UserRemainRow->day1;
			$fRow['day2'] = $UserRemainRow->day2;
			$fRow['day3'] = $UserRemainRow->day3;
			$fRow['day4'] = $UserRemainRow->day4;
			$fRow['day5'] = $UserRemainRow->day5;
			$fRow['day6'] = $UserRemainRow->day6;
			$fRow['day7'] = $UserRemainRow->day7;
			$fRow['day14'] = $UserRemainRow->day14;
			$fRow['day30'] = $UserRemainRow->day30;
			$UserRemainRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function UserRemainData($fromTime,$toTime,$channel,$server,$version)
	{   
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(usercount, 0) usercount,
					IFNULL(day1, 0) day1,
					IFNULL(day2, 0) day2,
					IFNULL(day3, 0) day3,
					IFNULL(day4, 0) day4,
					IFNULL(day5, 0) day5,
					IFNULL(day6, 0) day6,
					IFNULL(day7, 0) day7,
					IFNULL(day14, 0) day14,
					IFNULL(day30, 0) day30
				FROM
					".$dwdb->dbprefix('sum_basic_userremain_daily')."
				WHERE startdate_sk >= '" . $fromTime . "'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND product_id = '" . $productId . "'
				ORDER BY startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	//设备留存
	function getDeviceRemainData($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->DeviceRemainData($fromTime,$toTime,$channel,$server,$version);
		$UserRemainRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow["date"] = $UserRemainRow->startdate_sk;
			$fRow['devicecount'] = $UserRemainRow->devicecount;
			$fRow['day1'] = $UserRemainRow->day1;
			$fRow['day2'] = $UserRemainRow->day2;
			$fRow['day3'] = $UserRemainRow->day3;
			$fRow['day4'] = $UserRemainRow->day4;
			$fRow['day5'] = $UserRemainRow->day5;
			$fRow['day6'] = $UserRemainRow->day6;
			$fRow['day7'] = $UserRemainRow->day7;
			$fRow['day14'] = $UserRemainRow->day14;
			$fRow['day30'] = $UserRemainRow->day30;
			$UserRemainRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function DeviceRemainData($fromTime,$toTime,$channel,$server,$version)
	{   
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(devicecount, 0) devicecount,
					IFNULL(day1, 0) day1,
					IFNULL(day2, 0) day2,
					IFNULL(day3, 0) day3,
					IFNULL(day4, 0) day4,
					IFNULL(day5, 0) day5,
					IFNULL(day6, 0) day6,
					IFNULL(day7, 0) day7,
					IFNULL(day14, 0) day14,
					IFNULL(day30, 0) day30
				FROM
					".$dwdb->dbprefix('sum_basic_deviceremain_daily')."
				WHERE startdate_sk >= '" . $fromTime . "'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND product_id = '" . $productId . "'
				ORDER BY startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	//留存详情
	function getRemainDetailData($fromTime,$toTime,$channel,$server,$version){
		$list = array();
		$query = $this->RemainDetailData($fromTime,$toTime,$channel,$server,$version);
		$UserRemainRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['count'] = $UserRemainRow->count;
			$fRow['startdate_sk'] = $UserRemainRow->startdate_sk;
			$fRow['day1'] = $UserRemainRow->day1;
			$fRow['day2'] = $UserRemainRow->day2;
			$fRow['day3'] = $UserRemainRow->day3;
			$fRow['day4'] = $UserRemainRow->day4;
			$fRow['day5'] = $UserRemainRow->day5;
			$fRow['day6'] = $UserRemainRow->day6;
			$fRow['day7'] = $UserRemainRow->day7;
			$fRow['day14'] = $UserRemainRow->day14;
			$fRow['day30'] = $UserRemainRow->day30;
			$UserRemainRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function RemainDetailData($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(count, 0) count,
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(day1, 0) day1,
					IFNULL(day2, 0) day2,
					IFNULL(day3, 0) day3,
					IFNULL(day4, 0) day4,
					IFNULL(day5, 0) day5,
					IFNULL(day6, 0) day6,
					IFNULL(day7, 0) day7,
					IFNULL(day14, 0) day14,
					IFNULL(day30, 0) day30
				FROM
					".$dwdb->dbprefix('sum_basic_customremain_daily')."
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND type = 'newuser'
				AND logintimes = 1
				AND product_id = $productId
				ORDER BY startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	function filterAdata($fromTime,$toTime,$channel,$server,$version,$type,$logintimes)
	{
		$list = array();
		$query = $this->filterA($fromTime,$toTime,$channel,$server,$version,$type,$logintimes);
		$UserRemainRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['count'] = $UserRemainRow->count;
			$fRow['startdate_sk'] = $UserRemainRow->startdate_sk;
			$fRow['day1'] = $UserRemainRow->day1;
			$fRow['day2'] = $UserRemainRow->day2;
			$fRow['day3'] = $UserRemainRow->day3;
			$fRow['day4'] = $UserRemainRow->day4;
			$fRow['day5'] = $UserRemainRow->day5;
			$fRow['day6'] = $UserRemainRow->day6;
			$fRow['day7'] = $UserRemainRow->day7;
			$fRow['day14'] = $UserRemainRow->day14;
			$fRow['day30'] = $UserRemainRow->day30;
			$UserRemainRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function filterA($fromTime,$toTime,$channel,$server,$version,$type,$logintimes)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(count, 0) count,
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(day1, 0) day1,
					IFNULL(day2, 0) day2,
					IFNULL(day3, 0) day3,
					IFNULL(day4, 0) day4,
					IFNULL(day5, 0) day5,
					IFNULL(day6, 0) day6,
					IFNULL(day7, 0) day7,
					IFNULL(day14, 0) day14,
					IFNULL(day30, 0) day30
				FROM
					".$dwdb->dbprefix('sum_basic_customremain_daily')."
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND type = '" . $type . "'
				AND logintimes = '" . $logintimes . "'
				AND product_id = $productId
				ORDER BY startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	function filterBdata($fromTime,$toTime,$channel,$server,$version,$type,$logintimes)
	{
		$list = array();
		$query = $this->filterB($fromTime,$toTime,$channel,$server,$version,$type,$logintimes);
		$UserRemainRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['count'] = $UserRemainRow->count;
			$fRow['startdate_sk'] = $UserRemainRow->startdate_sk;
			$fRow['week1'] = $UserRemainRow->week1;
			$fRow['week2'] = $UserRemainRow->week2;
			$fRow['week3'] = $UserRemainRow->week3;
			$fRow['week4'] = $UserRemainRow->week4;
			$fRow['week5'] = $UserRemainRow->week5;
			$fRow['week6'] = $UserRemainRow->week6;
			$fRow['week7'] = $UserRemainRow->week7;
			$fRow['week14'] = $UserRemainRow->week14;
			$fRow['week30'] = $UserRemainRow->week30;
			$UserRemainRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function filterB($fromTime,$toTime,$channel,$server,$version,$type,$logintimes)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(count, 0) count,
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(week1, 0) week1,
					IFNULL(week2, 0) week2,
					IFNULL(week3, 0) week3,
					IFNULL(week4, 0) week4,
					IFNULL(week5, 0) week5,
					IFNULL(week6, 0) week6,
					IFNULL(week7, 0) week7,
					IFNULL(week14, 0) week14,
					IFNULL(week30, 0) week30
				FROM
					".$dwdb->dbprefix('sum_basic_customremain_weekly')."
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND type = '" . $type . "'
				AND logintimes = '" . $logintimes . "'
				AND product_id = $productId
				ORDER BY startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	function filterCdata($fromTime,$toTime,$channel,$server,$version,$type,$logintimes)
	{
		$list = array();
		$query = $this->filterC($fromTime,$toTime,$channel,$server,$version,$type,$logintimes);
		$UserRemainRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['count'] = $UserRemainRow->count;
			$fRow['startdate_sk'] = $UserRemainRow->startdate_sk;
			$fRow['month1'] = $UserRemainRow->month1;
			$fRow['month2'] = $UserRemainRow->month2;
			$fRow['month3'] = $UserRemainRow->month3;
			$fRow['month4'] = $UserRemainRow->month4;
			$fRow['month5'] = $UserRemainRow->month5;
			$fRow['month6'] = $UserRemainRow->month6;
			$fRow['month7'] = $UserRemainRow->month7;
			$fRow['month14'] = $UserRemainRow->month14;
			$fRow['month30'] = $UserRemainRow->month30;
			$UserRemainRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function filterC($fromTime,$toTime,$channel,$server,$version,$type,$logintimes)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(count, 0) count,
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(month1, 0) month1,
					IFNULL(month2, 0) month2,
					IFNULL(month3, 0) month3,
					IFNULL(month4, 0) month4,
					IFNULL(month5, 0) month5,
					IFNULL(month6, 0) month6,
					IFNULL(month7, 0) month7,
					IFNULL(month14, 0) month14,
					IFNULL(month30, 0) month30
				FROM
					".$dwdb->dbprefix('sum_basic_customremain_monthly')."
				WHERE
				startdate_sk >= '" . $fromTime . "'
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND type = '" . $type . "'
				AND logintimes = '" . $logintimes . "'
				AND product_id = $productId
				ORDER BY startdate_sk DESC";
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
