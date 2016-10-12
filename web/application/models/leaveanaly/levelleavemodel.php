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
 * Levelleavemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Levelleavemodel extends CI_Model {

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
				$this->load->model("useranalysis/levelalymodel", 'levelaly');
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
		function getLevelLeaveData($fromTime, $toTime, $channel, $server, $version) {
				$list = array();
				$query = $this->LevelLeaveData($fromTime, $toTime, $channel, $server, $version);
				$dauUsersRow = $query->first_row();
				for ($i = 0; $i < $query->num_rows(); $i++) {
						$fRow = array();
						$fRow['date_sk'] = $dauUsersRow->date_sk;
						$fRow['rolelevel'] = $dauUsersRow->rolelevel;
						$fRow['levelleaverate'] = $dauUsersRow->levelleaverate;
						$fRow['avgupdatetime'] = $dauUsersRow->avgupdatetime;
						$dauUsersRow = $query->next_row();
						array_push($list, $fRow);
				}
				return $list;
		}

		/**
		 * GetDetailDauUsersData function
		 * get detailed data
		 *
		 * @param string $fromTime from time
		 * @param string $toTime   to time
		 * @param string $channel channel
		 * @param string $server server
		 * @param string $version version
		 *
		 * @return query query
		 */
		function LevelLeaveData($fromTime, $toTime,  $channel, $server, $version) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
					IFNULL(date_sk, 0) date_sk,
					IFNULL(rolelevel, 0) rolelevel,
					IFNULL(AVG(levelleaverate), 0) levelleaverate,
					IFNULL(SUM(avgupdatetime), 0) avgupdatetime
				FROM
					" . $dwdb->dbprefix('sum_basic_levelleave') . "
				WHERE
				date_sk >= '" . $fromTime . "'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				GROUP BY rolelevel
				ORDER BY rid asc";
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
	  * GetUsersByLevel function
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
	 function getUsersByLevel($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$startLevel=1,$endLevel=1) {
		  if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
				$sql = "SELECT
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
							COUNT(a.roleId) usercount
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
											c.create_role_date <= '$date'
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
										lu.levelupgrade_date <= '$date'
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
	  * Sum_basic_levelleave function
	  * count dau users
	  *
	  *
	  */
	 function sum_basic_levelleave($countdate) {
	 	  $dwdb = $this->load->database('dw', true);
		  $params_psv = $this->product->getProductServerVersionOffChannel();
		  $paramsRow_psv = $params_psv->first_row();
		  for ($i = 0; $i < $params_psv->num_rows(); $i++) {
		  		$maxlevel=$this->levelaly->getMaxlevel($paramsRow_psv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,100000000);

					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_psv->appId,
						 'version_name' => $paramsRow_psv->version,
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_psv = $params_psv->next_row();
		  }
		  $params_ps = $this->product->getProductServerOffChannelVersion();
		  $paramsRow_ps = $params_ps->first_row();
		  for ($i = 0; $i < $params_ps->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_ps->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,100000000);

					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_ps->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => $server_name,
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_ps = $params_ps->next_row();
		  }
		  $params_pv = $this->product->getProductVersionOffChannelServer();
		  $paramsRow_pv = $params_pv->first_row();
		  for ($i = 0; $i < $params_pv->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,100000000);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pv->appId,
						 'version_name' => $paramsRow_pv->version,
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_pv = $params_pv->next_row();
		  }
		  $params_p = $this->product->getProductOffChannelServerVersion();
		  $paramsRow_p = $params_p->first_row();
		  for ($i = 0; $i < $params_p->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_p->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,100000000);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_p->appId,
						 'version_name' => 'all',
						 'channel_name' => 'all',
						 'server_name' => 'all',
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_p = $params_p->next_row();
		  }
		  $params_pcsv = $this->product->getProductChannelServerVersion();
		  $paramsRow_pcsv = $params_pcsv->first_row();
		  for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcsv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,100000000);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcsv->appId,
						 'version_name' => $paramsRow_pcsv->version,
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_pcsv = $params_pcsv->next_row();
		  }
		  $params_pcs = $this->product->getProductChannelServerOffVersion();
		  $paramsRow_pcs = $params_pcs->first_row();
		  for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcs->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,100000000);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
					//get servername by serverid
					$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcs->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => $server_name,
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_pcs = $params_pcs->next_row();
		  }
		  $params_pcv = $this->product->getProductChannelVersionOffServer();
		  $paramsRow_pcv = $params_pcv->first_row();
		  for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcv->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,100000000);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pcv->appId,
						 'version_name' => $paramsRow_pcv->version,
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_pcv = $params_pcv->next_row();
		  }
		  
		  $params_pc = $this->product-> getProductChannelOffServerVersion();
		  $paramsRow_pc = $params_pc->first_row();
		  for ($i = 0; $i < $params_pc->num_rows(); $i++) {
				$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pc->appId);
				for ($m=1; $m <$maxlevel+1 ; $m++) { 
					$Avglevelupgrade=$this->levelaly->getAvglevelupgrade($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
					${'users_level_'.$m}=$this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,$m);
					${'users_level_'.$m.'_above'}=$this->getUsersByLevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,100000000);
					//get channelname by channelid
					$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
					${'data'.$m} = array(
						 'date_sk' => $countdate,
						 'product_id' => $paramsRow_pc->appId,
						 'version_name' => 'all',
						 'channel_name' => $channel_name,
						 'server_name' => 'all',
						 'rolelevel' => $m,
						 'levelleaverate' => (${'users_level_'.$m.'_above'}==0)?0:(${'users_level_'.$m}/${'users_level_'.$m.'_above'}),
						 'avgupdatetime' => $Avglevelupgrade
					);
					$dwdb->insert_or_update('razor_sum_basic_levelleave', ${'data'.$m});
				}
				$paramsRow_pc = $params_pc->next_row();
		  }
	 }
}
