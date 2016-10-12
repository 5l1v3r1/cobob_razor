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
 * Levelalymodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Levelalymodel extends CI_Model
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
		 $this->load->model("product/productmodel", 'product');
		 $this->load->model("channelmodel", 'channel');
		 $this->load->model("servermodel", 'server');
	}
	
	/** 
	 * Get user remain country 
	 * GetUserRemainCountByWeek 
	 * 
	 * @param string $version   version 
	 * @param string $productId productid 
	 * @param string $from      from 
	 * @param string $to        to 
	 * @param string $channel   channel 
	 * 
	 * @return query
	 */
	function getUserRemainCountByWeek($version, $productId, $from, $to, $channel = 'all')
	{
		$dwdb = $this -> load -> database('dw', true);
		$sql = "select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			f.version_name,
			f.usercount,
			f.week1,
			f.week2,
			f.week3,
			f.week4,
			f.week5,
			f.week6,
			f.week7,
			f.week8
			from  " . $dwdb -> dbprefix('sum_reserveusers_weekly') . "   f,
				" . $dwdb -> dbprefix('dim_date') . "    d1,
				" . $dwdb -> dbprefix('dim_date') . "    d2
				where  f.startdate_sk = d1.date_sk
				and f.enddate_sk = d2.date_sk
				and d1.datevalue >= '$from'
				and d2.datevalue <= '$to'
				and f.product_id = $productId 
				and f.version_name='$version'
				and f.channel_name='$channel'
				order by d1.datevalue desc;";
		log_message('debug', 'getUserRemainCountByWeek() SQL: ' . $sql);
		$query = $dwdb -> query($sql);
		return $query;
	}
	
	/** 
	 * Get user remain count day 
	 * GetUserRemainCountByDay function 
	 * 
	 * @param string $version   version 
	 * @param string $productId productid 
	 * @param string $from      from 
	 * @param string $to        to 
	 * @param string $channel   channel 
	 * 
	 * @return query 
	 */
	function getUserRemainCountByDay($version, $productId, $from, $to, $channel = 'all')
	{
		$dwdb = $this -> load -> database('dw', true);
		$sql = "select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			f.version_name,
			f.usercount,
				f.day1,
				   f.day2,
				   f.day3,
				   f.day4,
				   f.day5,
				   f.day6,
				   f.day7,
				   f.day8
			from  " . $dwdb -> dbprefix('sum_reserveusers_daily') . "   f,
				 " . $dwdb -> dbprefix('dim_date') . "    d1,
				 " . $dwdb -> dbprefix('dim_date') . "    d2
			where  f.startdate_sk = d1.date_sk
				   and f.enddate_sk = d2.date_sk
			  and d1.datevalue >= '$from'
			   and d2.datevalue <= '$to'
			  and f.product_id = $productId 
			  and f.version_name='$version'
			  and f.channel_name='$channel'
			 order by d1.datevalue desc;";
		log_message('debug', 'getUserRemainCountByDay() SQL: ' . $sql);
		$query = $dwdb -> query($sql);
		return $query;
	}
	
	/** 
	 * Get user remain count month 
	 * GetUserRemainCountByMonth function 
	 * 
	 * @param string $version   version 
	 * @param string $productId productid 
	 * @param string $from      from 
	 * @param string $to        to 
	 * @param string $channel   channel 
	 * 
	 * @return query 
	 */
	function getUserRemainCountByMonth($version, $productId, $from, $to, $channel)
	{
		$dwdb = $this -> load -> database('dw', true);
		$sql = "	select date(d1.datevalue) startdate,
			date(d2.datevalue) enddate,
			f.version_name,
			f.usercount,
			f.month1,
			f. month2,
			f.month3,
			f.month4,
			f.month5,
			f.month6,
			f.month7,
			f.month8
			from " . $dwdb -> dbprefix('sum_reserveusers_monthly') . "   f,
				" . $dwdb -> dbprefix('dim_date') . "    d1,
				" . $dwdb -> dbprefix('dim_date') . "     d2
				where  f.startdate_sk = d1.date_sk 
				and f.enddate_sk = d2.date_sk 
				and d1.datevalue >= '$from'
				and d2.datevalue <= '$to'
				and f.product_id = $productId
				and f.version_name = '$version'
				and f.channel_name = '$channel'
				order by d1.datevalue desc;";

		log_message('debug', 'getUserRemainCountByMonth() SQL: ' . $sql);
		$query = $dwdb -> query($sql);

		return $query;
	}

	/**
	 * GetDetailDauUsersDataByDay function
	 * get detailed data
	 *
	 * @param string $fromTime from time
	 * @param string $toTime   to time
	 * @param string $channel channel
	 * @param string $server server
	 * @param string $version version
	 *
	 * @return query list
	 */
	function getLevelalyDataByDay($fromTime,$toTime,$channel,$server,$version)
	{	
		$list = array();
		$query = $this->getLevelalyData($fromTime,$toTime,$channel,$server,$version);
		$LevelalyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['levelfield'] = $LevelalyRow->levelfield;
			$fRow['dauusers'] = $LevelalyRow->dauusers;
			$fRow['dauuserrate'] = $LevelalyRow->dauuserrate;
			$fRow['gamecount'] = $LevelalyRow->gamecount;
			$fRow['gamecountrate'] = $LevelalyRow->gamecountrate;
			$fRow['gamestoprate'] = $LevelalyRow->gamestoprate;
			$fRow['newusers'] = $LevelalyRow->newusers;
			$fRow['newuserrate'] = $LevelalyRow->newuserrate;
			$fRow['avglevelupgrade'] = $LevelalyRow->avglevelupgrade;
			$fRow['daylevelupgrade'] = $LevelalyRow->daylevelupgrade;
			$LevelalyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getLevelalyData($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(levelfield, 0) levelfield,
					IFNULL(SUM(dauusers), 0) dauusers,
					IFNULL(AVG(dauuserrate), 0) dauuserrate,
					IFNULL(SUM(gamecount), 0) gamecount,
					IFNULL(AVG(gamecountrate), 0) gamecountrate,
					IFNULL(AVG(gamestoprate), 0) gamestoprate,
					IFNULL(SUM(newusers), 0) newusers,
					IFNULL(AVG(newuserrate), 0) newuserrate,
					IFNULL(SUM(avglevelupgrade), 0) avglevelupgrade,
					IFNULL(SUM(daylevelupgrade), 0) daylevelupgrade
				FROM
					".$dwdb->dbprefix('sum_basic_levelanaly')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				GROUP BY levelfield
				ORDER BY rid asc";
		$query = $dwdb->query($sql);
		return $query;
	}

	//获得SUM数据 所有等级的活跃用户 | 游戏次数 | 新增注册
	function getSumLevelalyDataByDay($fromTime,$toTime,$channel,$server,$version)
	{	
		$list = array();
		$query = $this->getSumLevelalyData($fromTime,$toTime,$channel,$server,$version);
		$LevelalyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['dauusers'] = $LevelalyRow->dauusers;
			$fRow['gamecount'] = $LevelalyRow->gamecount;
			$fRow['newusers'] = $LevelalyRow->newusers;
			$LevelalyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getSumLevelalyData($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(SUM(dauusers), 0) dauusers,
					IFNULL(SUM(gamecount), 0) gamecount,
					IFNULL(SUM(newusers), 0) newusers
				FROM
					".$dwdb->dbprefix('sum_basic_levelanaly')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'";
		$query = $dwdb->query($sql);
		return $query;
	}

	//详细日停滞率
	function getGameStoprateByDay($fromTime,$toTime,$channel,$server,$version,$levelfield){
		$list = array();
		$query = $this->getGameStoprateData($fromTime,$toTime,$channel,$server,$version,$levelfield);
		$LevelalyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['gamestoprate'] = $LevelalyRow->gamestoprate;
			$fRow['daylevelupgrade'] = $LevelalyRow->daylevelupgrade;
			$fRow['date'] = $LevelalyRow->startdate_sk;
			$LevelalyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getGameStoprateData($fromTime,$toTime,$channel,$server,$version,$levelfield)
	{	
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(gamestoprate, 0) gamestoprate,
					IFNULL(daylevelupgrade, 0) daylevelupgrade
				FROM
					".$dwdb->dbprefix('sum_basic_levelanaly')." 
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND levelfield in('".$levelfield."')
				AND startdate_sk >= '".$fromTime."'
				AND enddate_sk <= '".$toTime."'
				ORDER BY
					enddate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}

	//总升级时长
	function getTotalupgradetime($fromTime,$toTime,$channel,$server,$version,$levelfield){
		$list = array();
		$query = $this->Totalupgradetime($fromTime,$toTime,$channel,$server,$version,$levelfield);
		$LevelalyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['upgradetime'] = $LevelalyRow->upgradetime;
			$fRow['dauusers'] = $LevelalyRow->dauusers;
			$LevelalyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function Totalupgradetime($fromTime,$toTime,$channel,$server,$version,$levelfield)
	{	
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(upgradetime, 0) upgradetime,
					IFNULL(SUM(dauusers), 0) dauusers
				FROM
					".$dwdb->dbprefix('sum_basic_levelanaly_totalupgradetime')." 
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND levelfield in('".$levelfield."')
				AND startdate_sk >= '".$fromTime."'
				AND enddate_sk <= '".$toTime."'
				Group By upgradetime
				ORDER BY
					rid ASC";
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


	 /**
	  * GetNewusers function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getNewusers($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$startLevel=1,$endLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										#AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) usercount
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											roleId
										FROM
											razor_createrole c
										WHERE
											c.create_role_date = '$date'
										AND c.appId = '$appid'
										AND c.chId = '$channelid'
										#AND c.srvId = '$serverid'
										#AND c.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->usercount;
		  }
	 }

 	 /**
	  * GetLoginusers function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getLoginusers($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$startLevel=1,$endLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										#AND rl.chId = '$channelid'
										AND rl.srvId = '$serverid'
										AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										#AND rl.chId = '$channelid'
										AND rl.srvId = '$serverid'
										#AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										#AND rl.chId = '$channelid'
										#AND rl.srvId = '$serverid'
										AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										#AND rl.chId = '$channelid'
										#AND rl.srvId = '$serverid'
										#AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									#AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										AND rl.chId = '$channelid'
										AND rl.srvId = '$serverid'
										AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										AND rl.chId = '$channelid'
										AND rl.srvId = '$serverid'
										#AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										AND rl.chId = '$channelid'
										#AND rl.srvId = '$serverid'
										AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(1) logincount,
							COUNT(DISTINCT a.roleId) dauusers
						FROM
							(
								SELECT
									cr.roleId,
									IFNULL(rm.maxlevel, 1) userlevel
								FROM
									(
										SELECT
											rl.roleId
										FROM
											razor_login rl
										WHERE
											rl.login_date = '$date'
										AND rl.appId = '$appid'
										AND rl.chId = '$channelid'
										#AND rl.srvId = '$serverid'
										#AND rl.version = '$versionname'
									) cr
								LEFT JOIN (
									SELECT
										roleId,
										MAX(roleLevel) maxlevel
									FROM
										razor_levelupgrade lu
									WHERE
										lu.levelupgrade_date = '$date'
									AND lu.appId = '$appid'
									AND lu.chId = '$channelid'
									#AND lu.srvId = '$serverid'
									#AND lu.version = '$versionname'
									GROUP BY
										roleId
								) rm ON cr.roleId = rm.roleId
							) a
						WHERE
							a.userlevel >= $startLevel
						AND a.userlevel <= $endLevel;";
		  }
		$query = $this->db->query($sql);
		if ($query != null && $query -> num_rows() > 0) {
			return $query -> row_array();
		}
	 }

	 /**
	  * getDauusers function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getDauusers($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) dauusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->dauusers;
		  }
	 }

 	 /**
	  * getGamecount_login function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getGamecount_login($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									#AND l.chId = '$channelid'
									AND l.srvId = '$serverid'
									AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									#AND l.chId = '$channelid'
									AND l.srvId = '$serverid'
									#AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									#AND l.chId = '$channelid'
									#AND l.srvId = '$serverid'
									AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									#AND l.chId = '$channelid'
									#AND l.srvId = '$serverid'
									#AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									AND l.chId = '$channelid'
									AND l.srvId = '$serverid'
									AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									AND l.chId = '$channelid'
									AND l.srvId = '$serverid'
									#AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									AND l.chId = '$channelid'
									#AND l.srvId = '$serverid'
									AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							count(1) login_times
						FROM
							razor_login l
						WHERE
							l.login_date = '$date'
						AND l.type = 'login'
						AND l.roleId IN (
							SELECT
								ml.roleId
							FROM
								(
									SELECT
										l.roleId,
										MAX(l.roleLevel) max_level
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId = '$appid'
									AND l.chId = '$channelid'
									#AND l.srvId = '$serverid'
									#AND l.version = '$versionname'
									GROUP BY
										l.roleId
								) ml
							WHERE
								ml.max_level = '$maxLevel'
						);";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->login_times;
		  }
	 }

 	 /**
	  * getGamecount_logout function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getGamecount_logout($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									#AND l.chId='$channelid'
									AND l.srvId='$serverid'
									AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									#AND l.chId='$channelid'
									AND l.srvId='$serverid'
									#AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									#AND l.chId='$channelid'
									#AND l.srvId='$serverid'
									AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									#AND l.chId='$channelid'
									#AND l.srvId='$serverid'
									#AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									AND l.chId='$channelid'
									AND l.srvId='$serverid'
									AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									AND l.chId='$channelid'
									AND l.srvId='$serverid'
									#AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									AND l.chId='$channelid'
									#AND l.srvId='$serverid'
									AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) logout_times
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId NOT IN (
									SELECT DISTINCT
										l.roleId
									FROM
										razor_login l
									WHERE
										l.login_date = '$date'
									AND l.appId='$appid'
									AND l.chId='$channelid'
									#AND l.srvId='$serverid'
									#AND l.version='$versionname'
									AND l.type = 'login'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->logout_times;
		  }
	 }

 	 /**
	  * getDauusers function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getNewcreaterole($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										#AND c.chId='$channelid'
										AND c.srvId='$serverid'
										AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										#AND c.chId='$channelid'
										AND c.srvId='$serverid'
										#AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										#AND c.chId='$channelid'
										#AND c.srvId='$serverid'
										AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								#AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										#AND c.chId='$channelid'
										#AND c.srvId='$serverid'
										#AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										AND c.chId='$channelid'
										AND c.srvId='$serverid'
										AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										AND c.chId='$channelid'
										AND c.srvId='$serverid'
										#AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										AND c.chId='$channelid'
										#AND c.srvId='$serverid'
										AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							COUNT(ml.roleId) newusers
						FROM
							(
								SELECT
									l.roleId,
									MAX(l.roleLevel) max_level
								FROM
									razor_login l
								WHERE
									l.login_date = '$date'
								AND l.appId = '$appid'
								AND l.chId = '$channelid'
								#AND l.srvId = '$serverid'
								#AND l.version = '$versionname'
								AND l.roleId IN (
									SELECT
										c.roleId
									FROM
										razor_createrole c
									WHERE
										c.create_role_date = '$date'
										AND c.appId='$appid'
										AND c.chId='$channelid'
										#AND c.srvId='$serverid'
										#AND c.version='$versionname'
								)
								GROUP BY
									l.roleId
							) ml
						WHERE
							ml.max_level = '$maxLevel';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->newusers;
		  }
	 }

  	 /**
	  * getDauusers function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getAvglevelupgrade($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
								#		AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
							#		AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
							#			AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(AVG(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
						#				AND luc.srvId = '$serverid'
						#				AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
					#				AND luc.srvId = '$serverid'
					#				AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
					#					AND lu.srvId = '$serverid'
					#					AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->avg_upgrade_time;
		  }
	 }

   	 /**
	  * getDaylevelupgrade function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getDaylevelupgrade($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
								#		AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
							#		AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
							#			AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								ROUND(SUM(luc3.upgrade_time), 2),
								0
							) avg_upgrade_time
						FROM
							(
								-- 升级时长=最大等级的升级时间-最大等级上一个等级的最大登录时间
								SELECT
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										-- 每个角色最大等级和最大升级时间
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
						#				AND luc.srvId = '$serverid'
						#				AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									-- 每个角色最大等级上一个等级的最大登录时间
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
					#				AND luc.srvId = '$serverid'
					#				AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
					#					AND lu.srvId = '$serverid'
					#					AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.roleLevel = '$maxLevel';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->avg_upgrade_time;
		  }
	 }

   	 /**
	  * getTotallevelupgrade function
	  * get leave user
	  *
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  *
	  * @return Int leaveuser
	  */
	 function getTotallevelupgrade($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$maxLevel=1,$frommin,$tomin) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
								#		AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
							#		AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
							#			AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
					$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
									#	AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
								#	AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
								#		AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
										AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
									AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
										AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
										AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
									AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
										AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
				$sql = "SELECT
							IFNULL(
								COUNT(DISTINCT luc3.roleId),
								0
							) dauuser
						FROM
							(
								SELECT
									luc1.roleId,
									luc1.roleLevel,
									TIMESTAMPDIFF(
										MINUTE,
										max_login_time,
										max_levelupgrade_time
									) upgrade_time
								FROM
									(
										SELECT
											luc.roleId,
											MAX(luc.roleLevel) roleLevel,
											MAX(luc.levelupgrade_time) max_levelupgrade_time
										FROM
											razor_levelupgrade luc
										WHERE
											luc.levelupgrade_date = '$date'
										AND luc.appId = '$appid'
										AND luc.chId = '$channelid'
									#	AND luc.srvId = '$serverid'
									#	AND luc.version = '$versionname'
										GROUP BY
											luc.roleId
									) luc1
								LEFT JOIN (
									SELECT
										luc.roleId,
										MAX(luc.login_time) max_login_time
									FROM
										razor_login luc
									WHERE
										luc.login_date = '$date'
									AND luc.appId = '$appid'
									AND luc.chId = '$channelid'
								#	AND luc.srvId = '$serverid'
								#	AND luc.version = '$versionname'
									AND luc.roleId IN (
										SELECT DISTINCT
											lu.roleId
										FROM
											razor_levelupgrade lu
										WHERE
											lu.levelupgrade_date = '$date'
										AND lu.appId = '$appid'
										AND lu.chId = '$channelid'
								#		AND lu.srvId = '$serverid'
								#		AND lu.version = '$versionname'
									)
									AND luc.roleLevel < '$maxLevel'
									GROUP BY
										luc.roleId
								) luc2 ON luc1.roleId = luc2.roleId
							) luc3
						WHERE
							luc3.upgrade_time >= $frommin
						AND luc3.upgrade_time < $tomin
						AND luc3.roleLevel = '$maxLevel';";
		  }
		  $query = $this->db->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->dauuser;
		  }
	 }

	 function getMaxlevel($appId){
	 	$sql="SELECT
				MAX(l.roleLevel) max_level
			FROM
				razor_levelupgrade l
			WHERE
				l.appId = '$appId';";
		$query = $this->db->query($sql);
        $row = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->max_level;
        }		

	 }


  	 /**
	  * Sum_basic_levelanaly function
	  * count dau users
	  *
	  *
	  */
	 function sum_basic_levelanaly($countdate) {
	 	  $dwdb = $this->load->database('dw', true);
		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
		  		$newusers_total = $this->getNewusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_psv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}

					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,6*60,100000000);
					$data_0_10m = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
		                'startdate_sk' => $countdate,
		                'enddate_sk' => $countdate,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
				}
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_ps->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);					
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
                    $dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
                    //总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                }
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_pv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);	
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                }
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_p->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                                }
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_pcsv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                                }
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_pcs->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                                }
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_pcv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                                }
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
				$newusers_total = $this->getNewusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1,100000000);
				$loginusers_total = $this->getLoginusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',1,100000000);
				$maxlevel=$this->getMaxlevel($paramsRow_pc->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'newusers_'.$m} = $this->getNewusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,$m);
					${'newusers_'.$m.'_above'} = $this->getNewusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,100000000);
					${'loginusers_'.$m} = $this->getLoginusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,$m);
					//new add
					${'Dauusers_'.$m}=$this->getDauusers($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					${'Gamecount_login_'.$m}=$this->getGamecount_login($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					${'Gamecount_logout_'.$m}=$this->getGamecount_logout($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					${'Newcreaterole_'.$m}=$this->getNewcreaterole($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					${'Avglevelupgrade_'.$m}=$this->getAvglevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					${'Daylevelupgrade_'.$m}=$this->getDaylevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					//${'Totallevelupgrade_'.$m}=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
					${'data_'.$m} = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'levelfield' => $m,
						 'newusers' => ${'Newcreaterole_'.$m},
						 'newuserrate' =>  '0',
						 'dauusers' => ${'Dauusers_'.$m},
						 'dauuserrate' => '0',
						 'gamecount' => ${'Gamecount_login_'.$m}+${'Gamecount_logout_'.$m},
						 'gamecountrate' => '0',
						 'gamestoprate' => (${'newusers_'.$m.'_above'}==0)?0:(${'newusers_'.$m}/${'newusers_'.$m.'_above'}),
 						 'avglevelupgrade' => ${'Avglevelupgrade_'.$m},
						 'daylevelupgrade' => ${'Daylevelupgrade_'.$m},
						 //'totallevelupgrade' => ${'Totallevelupgrade_'.$m}
					);
					$dwdb->insert_or_update('razor_sum_basic_levelanaly', ${'data_'.$m});
					//总升级时长
					$Totallevelupgrade_0_10m=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,0,10);
					$Totallevelupgrade_10_20m=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,10,20);
					$Totallevelupgrade_20_30m=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,20,30);
					$Totallevelupgrade_30_60m=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,30,60);
					$Totallevelupgrade_1_2h=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,1*60,2*60);
					$Totallevelupgrade_2_3h=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,2*60,3*60);
					$Totallevelupgrade_3_4h=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,3*60,4*60);
					$Totallevelupgrade_4_5h=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,4*60,5*60);
					$Totallevelupgrade_5_6h=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,5*60,6*60);
					$Totallevelupgrade_6_above=$this->getTotallevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,6*60,100000000);
					$data_0_10m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '<10m',
		                'dauusers' => $Totallevelupgrade_0_10m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_0_10m);
		            $data_10_20m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '10-20m',
		                'dauusers' => $Totallevelupgrade_10_20m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_10_20m);
		            $data_20_30m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '20-30m',
		                'dauusers' => $Totallevelupgrade_20_30m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_20_30m);
		            $data_30_60m = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '30-60m',
		                'dauusers' => $Totallevelupgrade_30_60m
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_30_60m);
		            $data_1_2h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '1-2h',
		                'dauusers' => $Totallevelupgrade_1_2h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_1_2h);
		            $data_2_3h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '2-3h',
		                'dauusers' => $Totallevelupgrade_2_3h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_2_3h);
		            $data_3_4h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '3-4h',
		                'dauusers' => $Totallevelupgrade_3_4h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_3_4h);
		            $data_4_5h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '4-5h',
		                'dauusers' => $Totallevelupgrade_4_5h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_4_5h);
		            $data_5_6h = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '5-6h',
		                'dauusers' => $Totallevelupgrade_5_6h
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_5_6h);
		            $data_6_above = array(
						 'startdate_sk' => $countdate,
						 'enddate_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
		                'levelfield' => $m,
		                'upgradetime' => '≥6h',
		                'dauusers' => $Totallevelupgrade_6_above
		            );
		            $dwdb->insert_or_update('razor_sum_basic_levelanaly_totalupgradetime', $data_6_above);
                                }
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }
}
