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
 * Viprolemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Viprolemodel extends CI_Model {

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
		 function getUseralanyData($fromTime, $toTime, $channel, $server, $version) {
					$list = array();
					$query = $this->UseralanyData($fromTime, $toTime, $channel, $server, $version);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['viplevel'] = $dauUsersRow->viplevel;
								$fRow['newvip'] = $dauUsersRow->newvip;
								$fRow['outvip'] = $dauUsersRow->outvip;
								$fRow['currentvip'] = $dauUsersRow->currentvip;
								$fRow['dauvip'] = $dauUsersRow->dauvip;
								$fRow['dayavggamecount'] = $dauUsersRow->dayavggamecount;
								$fRow['pergametime'] = $dauUsersRow->pergametime;
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }

		 function UseralanyData($fromTime, $toTime, $channel, $version, $server) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(viplevel, 0) viplevel,
						IFNULL(SUM(newvip), 0) newvip,
						IFNULL(SUM(outvip), 0) outvip,
						IFNULL(SUM(currentvip), 0) currentvip,
						IFNULL(SUM(dauvip), 0) dauvip,
						IFNULL(AVG(dayavggamecount), 0) dayavggamecount,
						IFNULL(SUM(pergametime), 0) pergametime
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_useralany') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						Group By viplevel
						Order By rid ASC";
				$query = $dwdb->query($sql);
				return $query;
		 }

		 function getPayalanyData($fromTime, $toTime, $channel, $server, $version) {
					$list = array();
					$query = $this->PayalanyData($fromTime, $toTime, $channel, $server, $version);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['viplevel'] = $dauUsersRow->viplevel;
								$fRow['payamount'] = $dauUsersRow->payamount;
								$fRow['payuser'] = $dauUsersRow->payuser;
								$fRow['paycount'] = $dauUsersRow->paycount;
								$fRow['dayavgpaycount'] = $dauUsersRow->dayavgpaycount;
								$fRow['dayavgpayuser'] = $dauUsersRow->dayavgpayuser;
								$fRow['useravgpaycount'] = $dauUsersRow->useravgpaycount;
								$fRow['payrate'] = $dauUsersRow->payrate;
								$fRow['arpu'] = $dauUsersRow->arpu;
								$fRow['arppu'] = $dauUsersRow->arppu;
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }

		 function PayalanyData($fromTime, $toTime, $channel, $version, $server) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(viplevel, 0) viplevel,
						IFNULL(SUM(payamount), 0) payamount,
						IFNULL(SUM(payuser), 0) payuser,
						IFNULL(SUM(paycount), 0) paycount,
						IFNULL(AVG(dayavgpaycount), 0) dayavgpaycount,
						IFNULL(AVG(dayavgpayuser), 0) dayavgpayuser,
						IFNULL(AVG(useravgpaycount), 0) useravgpaycount,
						IFNULL(payrate, 0) payrate,
						IFNULL(AVG(arpu), 0) arpu,
						IFNULL(AVG(arppu), 0) arppu
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_payalany') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						Group By viplevel
						Order By rid ASC";
				$query = $dwdb->query($sql);
				return $query;
		 }

		 function getLeavealanyData($fromTime, $toTime, $channel, $server, $version) {
					$list = array();
					$query = $this->LeavealanyData($fromTime, $toTime, $channel, $server, $version);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['viplevel'] = $dauUsersRow->viplevel;
								$fRow['dayleave3'] = $dauUsersRow->day3leave;
								$fRow['dayleaverate3'] = round(floatval($dauUsersRow->day3leaverate)*100,2);
								$fRow['dayleave7'] = $dauUsersRow->day7leave;
								$fRow['dayleaverate7'] = round(floatval($dauUsersRow->day7leaverate)*100,2);
								$fRow['dayleave14'] = $dauUsersRow->day14leave;
								$fRow['dayleaverate14'] = round(floatval($dauUsersRow->day14leaverate)*100,2);
								$fRow['dayleave30'] = $dauUsersRow->day30leave;
								$fRow['dayleaverate30'] = round(floatval($dauUsersRow->day30leaverate)*100,2);
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }

		 function LeavealanyData($fromTime, $toTime, $channel, $version, $server) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(viplevel, 0) viplevel,
						IFNULL(SUM(day3leave), 0) day3leave,
						IFNULL(AVG(day3leaverate), 0) day3leaverate,
						IFNULL(SUM(day7leave), 0) day7leave,
						IFNULL(AVG(day7leaverate), 0) day7leaverate,
						IFNULL(SUM(day14leave), 0) day14leave,
						IFNULL(AVG(day14leaverate), 0) day14leaverate,
						IFNULL(SUM(day30leave), 0) day30leave,
						IFNULL(AVG(day30leaverate), 0) day30leaverate
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_leavealany') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						Group By viplevel
						Order By rid ASC";
				$query = $dwdb->query($sql);
				return $query;
		 }

		 function getUseralanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel) {
					$list = array();
					$query = $this->UseralanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
								$fRow['viplevel'] = $dauUsersRow->viplevel;
								$fRow['newvip'] = $dauUsersRow->newvip;
								$fRow['outvip'] = $dauUsersRow->outvip;
								$fRow['currentvip'] = $dauUsersRow->currentvip;
								$fRow['dauvip'] = $dauUsersRow->dauvip;
								$fRow['dayavggamecount'] = $dauUsersRow->dayavggamecount;
								$fRow['pergametime'] = $dauUsersRow->pergametime;
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }

		 function UseralanyDataDetail($fromTime, $toTime, $channel, $version, $server, $viplevel) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(startdate_sk, 0) startdate_sk,
						IFNULL(viplevel, 0) viplevel,
						IFNULL(newvip, 0) newvip,
						IFNULL(outvip, 0) outvip,
						IFNULL(currentvip, 0) currentvip,
						IFNULL(dauvip, 0) dauvip,
						IFNULL(dayavggamecount, 0) dayavggamecount,
						IFNULL(pergametime, 0) pergametime
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_useralany') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						AND viplevel = '" . $viplevel . "'
						Order By startdate_sk DESC";
				$query = $dwdb->query($sql);
				return $query;
		 }

		 function getPayalanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel) {
					$list = array();
					$query = $this->PayalanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
								$fRow['viplevel'] = $dauUsersRow->viplevel;
								$fRow['payamount'] = $dauUsersRow->payamount;
								$fRow['payuser'] = $dauUsersRow->payuser;
								$fRow['paycount'] = $dauUsersRow->paycount;
								$fRow['dayavgpaycount'] = $dauUsersRow->dayavgpaycount;
								$fRow['dayavgpayuser'] = $dauUsersRow->dayavgpayuser;
								$fRow['useravgpaycount'] = $dauUsersRow->useravgpaycount;
								$fRow['payrate'] = $dauUsersRow->payrate;
								$fRow['arpu'] = $dauUsersRow->arpu;
								$fRow['arppu'] = $dauUsersRow->arppu;
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }
		 
		 function PayalanyDataDetail($fromTime, $toTime, $channel, $version, $server, $viplevel) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(startdate_sk, 0) startdate_sk,
						IFNULL(viplevel, 0) viplevel,
						IFNULL(payamount, 0) payamount,
						IFNULL(payuser, 0) payuser,
						IFNULL(paycount, 0) paycount,
						IFNULL(dayavgpaycount, 0) dayavgpaycount,
						IFNULL(dayavgpayuser, 0) dayavgpayuser,
						IFNULL(useravgpaycount, 0) useravgpaycount,
						IFNULL(payrate, 0) payrate,
						IFNULL(arpu, 0) arpu,
						IFNULL(arppu, 0) arppu
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_payalany') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						AND viplevel = '" . $viplevel . "'
						Order By startdate_sk DESC";
				$query = $dwdb->query($sql);
				return $query;
		 }

		 function getleavealanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel) {
					$list = array();
					$query = $this->leavealanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['startdate_sk'] = $dauUsersRow->startdate_sk;
								$fRow['viplevel'] = $dauUsersRow->viplevel;
								$fRow['dayleave3'] = $dauUsersRow->day3leave;
								$fRow['dayleaverate3'] = round(floatval($dauUsersRow->day3leaverate)*100,2);
								$fRow['dayleave7'] = $dauUsersRow->day7leave;
								$fRow['dayleaverate7'] = round(floatval($dauUsersRow->day7leaverate)*100,2);
								$fRow['dayleave14'] = $dauUsersRow->day14leave;
								$fRow['dayleaverate14'] = round(floatval($dauUsersRow->day14leaverate)*100,2);
								$fRow['dayleave30'] = $dauUsersRow->day30leave;
								$fRow['dayleaverate30'] = round(floatval($dauUsersRow->day30leaverate)*100,2);
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }

		 function leavealanyDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(startdate_sk, 0) startdate_sk,
						IFNULL(viplevel, 0) viplevel,
						IFNULL(day3leave, 0) day3leave,
						IFNULL(day3leaverate, 0) day3leaverate,
						IFNULL(day7leave, 0) day7leave,
						IFNULL(day7leaverate, 0) day7leaverate,
						IFNULL(day14leave, 0) day14leave,
						IFNULL(day14leaverate, 0) day14leaverate,
						IFNULL(day30leave, 0) day30leave,
						IFNULL(day30leaverate, 0) day30leaverate
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_leavealany') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						AND viplevel = '" . $viplevel . "'
						Order By startdate_sk DESC";
				$query = $dwdb->query($sql);
				return $query;
		 }

		 function getUserlevelDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel) {
					$list = array();
					$query = $this->UserlevelDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel);
					$dauUsersRow = $query->first_row();
					for ($i = 0; $i < $query->num_rows(); $i++) {
								$fRow = array();
								$fRow['userlevel'] = $dauUsersRow->userlevel;
								$fRow['dayleave3'] = $dauUsersRow->day3leave;
								$fRow['dayleaverate3'] = round(floatval($dauUsersRow->day3leaverate)*100,2);
								$fRow['dayleave7'] = $dauUsersRow->day7leave;
								$fRow['dayleaverate7'] = round(floatval($dauUsersRow->day7leaverate)*100,2);
								$fRow['dayleave14'] = $dauUsersRow->day14leave;
								$fRow['dayleaverate14'] = round(floatval($dauUsersRow->day14leaverate)*100,2);
								$fRow['dayleave30'] = $dauUsersRow->day30leave;
								$fRow['dayleaverate30'] = round(floatval($dauUsersRow->day30leaverate)*100,2);
								$dauUsersRow = $query->next_row();
								array_push($list, $fRow);
					}
					return $list;
		 }

		 function UserlevelDataDetail($fromTime, $toTime, $channel, $server, $version, $viplevel) {
				$currentProduct = $this->common->getCurrentProduct();
				$productId = $currentProduct->id;
				$dwdb = $this->load->database('dw', true);
				($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
				($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
				($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
				$sql = "SELECT
						IFNULL(userlevel, 0) userlevel,
						IFNULL(SUM(day3leave), 0) day3leave,
						IFNULL(AVG(day3leaverate), 0) day3leaverate,
						IFNULL(SUM(day7leave), 0) day7leave,
						IFNULL(AVG(day7leaverate), 0) day7leaverate,
						IFNULL(SUM(day14leave), 0) day14leave,
						IFNULL(AVG(day14leaverate), 0) day14leaverate,
						IFNULL(SUM(day30leave), 0) day30leave,
						IFNULL(AVG(day30leaverate), 0) day30leaverate
						FROM
								" . $dwdb->dbprefix('sum_basic_viprole_leavealany_userlevel') . "
						WHERE
						startdate_sk >= '" . $fromTime . "'
						AND enddate_sk <= '" . $toTime . "'
						AND channel_name in('" . $channel_list . "')
						AND version_name in('" . $version_list . "')
						AND server_name in('" . $server_list . "')
						AND product_id = '" . $productId . "'
						AND viplevel = '" . $viplevel . "'
						Group By userlevel
						Order By rid ASC";
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
			* getNewvipuser function
			* get new vip user
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int new vip user
			*/
		 function getNewvipuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) newrolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.currentRoleVip = '$viplevel';";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->newrolevip;
					}
		 }
		 
		 /**
			* getLosevipuser function
			* get lose vip user
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int lose vip user
			*/
		 function getLosevipuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												#AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(COUNT(DISTINCT vv.roleId), 0) loserolevip
												FROM
														razor_viplevelup vv
												WHERE
														vv.viplevelup_date >= '$fromdate'
												AND vv.viplevelup_date <= '$todate'
												AND vv.appId = '$appid'
												AND vv.chId = '$channelid'
												#AND vv.srvId = '$serverid'
												#AND vv.version = '$versionname'
												AND vv.beforeRoleVip = '$viplevel';";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->loserolevip;
					}
		 }
		
		 /**
			* getCurrentvipuser function
			* get current vip user
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int payuser
			*/
		 function getCurrentvipuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																#AND vv.chId = '$channelid'
																AND vv.srvId = '$serverid'
																AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																#AND vv.chId = '$channelid'
																AND vv.srvId = '$serverid'
																#AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																#AND vv.chId = '$channelid'
																#AND vv.srvId = '$serverid'
																AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																#AND vv.chId = '$channelid'
																#AND vv.srvId = '$serverid'
																#AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																AND vv.chId = '$channelid'
																AND vv.srvId = '$serverid'
																AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																AND vv.chId = '$channelid'
																AND vv.srvId = '$serverid'
																#AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																AND vv.chId = '$channelid'
																#AND vv.srvId = '$serverid'
																AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(vv1.roleId) currentvip
												FROM
														(
																SELECT
																		vv.roleId,
																		MAX(vv.currentRoleVip) max_rolevip
																FROM
																		razor_viplevelup vv
																WHERE
																		vv.viplevelup_date >= '$fromdate'
																AND vv.viplevelup_date <= '$todate'
																AND vv.appId = '$appid'
																AND vv.chId = '$channelid'
																#AND vv.srvId = '$serverid'
																#AND vv.version = '$versionname'
																GROUP BY
																		vv.roleId
														) vv1
												WHERE
														vv1.max_rolevip = '$viplevel';";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->currentvip;
					}
		 }


		 /**
			* getDauvipuser function
			* get dau vip user
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int payuser
			*/
		 function getDauvipuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel';";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->dau_vipuser;
					}
		 }

		/**
			* getDauvipuser function
			* get dau vip user
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int payuser
			*/
		 function getDauvipuserlevel($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel,$userlevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT lg.roleId) dau_vipuser
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.roleLevel = '$userlevel';";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->dau_vipuser;
					}
		 }
		 
		 /**
			* getDauvipcount function
			* get dau vip user game count
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int dauuser
			*/
		 function getDauvipcount($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(1) dau_vipcount
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'login';";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->dau_vipcount;
					}
		 }


		 /**
			* getDauvipgametime function
			* get dau vip user game time
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int dauuser
			*/
		 function getDauvipgametime($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
											 #AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												#AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(
																(
																		SUM(lg.offlineSettleTime) / 60
																),
																0
														) gametime
												FROM
														razor_login lg
												WHERE
														lg.login_date >= '$fromdate'
												AND lg.login_date <= '$todate'
												AND lg.appId = '$appid'
												AND lg.chId = '$channelid'
												#AND lg.srvId = '$serverid'
												#AND lg.version = '$versionname'
												AND lg.roleVip = '$viplevel'
												AND lg.type = 'logout';";
					}
				$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->gametime;
					}
		 }

			/**
			* getPayvipuser function
			* get pay user vip data
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Array pay user vip data
			*/
		 function getPayvipuser($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												#AND p.chId = '$channelid'
												AND p.srvId = '$serverid'
												AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												#AND p.chId = '$channelid'
												AND p.srvId = '$serverid'
												#AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												#AND p.chId = '$channelid'
											 # AND p.srvId = '$serverid'
												AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												#AND p.chId = '$channelid'
												#AND p.srvId = '$serverid'
												#AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												AND p.chId = '$channelid'
												AND p.srvId = '$serverid'
												AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												AND p.chId = '$channelid'
												AND p.srvId = '$serverid'
												#AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												AND p.chId = '$channelid'
												#AND p.srvId = '$serverid'
												AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														IFNULL(SUM(p.pay_amount), 0) pay_amount,
														COUNT(DISTINCT p.roleId) payrole,
														COUNT(1) paycount
												FROM
														razor_pay p
												WHERE
														p.pay_date >= '$fromdate'
												AND p.pay_date <= '$todate'
												AND p.appId = '$appid'
												AND p.chId = '$channelid'
												#AND p.srvId = '$serverid'
												#AND p.version = '$versionname'
												AND p.roleVip = '$viplevel';";
					}
				$query = $this->db->query($sql);
				if ($query != null && $query -> num_rows() > 0) {
						return $query -> row_array();
				}
		 }

		 /**
			* getLeavevipalany function
			* get leave user alany
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int new vip user
			*/
		 function getLeavevipalany($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel,$days) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								 $sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $days DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->leavecount;
					}
		 }


			/**
			* getLeaveviplevel function
			* get leave vip user level dist..
			* 
			* @param Date $date
			* @param String $appid
			* @param String $channelid
			* @param String $serverid
			* @param String $versionname
			* 
			* @return Int leave vip user level dist..
			*/
		 function getLeaveviplevel($fromdate,$todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$viplevel,$day,$userlevel) {
					if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
											 # AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												#AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														#AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
													 # AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														AND lg.version = '$versionname'
												);";
					} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
								$sql = "SELECT
														COUNT(DISTINCT c.roleId) leavecount
												FROM
														razor_login c
												WHERE
														c.login_date = '$fromdate'
												AND c.appId = '$appid'
												AND c.chId = '$channelid'
												#AND c.srvId = '$serverid'
												#AND c.version = '$versionname'
												AND c.roleVip = '$viplevel'
												AND c.roleLevel = '$userlevel'
												AND c.roleId NOT IN (
														SELECT DISTINCT
																lg.roleId
														FROM
																razor_login lg
														WHERE
																lg.login_date > '$fromdate'
														AND lg.login_date <= DATE_ADD('$todate', INTERVAL $day DAY)
														AND lg.appId = '$appid'
														AND lg.chId = '$channelid'
														#AND lg.srvId = '$serverid'
														#AND lg.version = '$versionname'
												);";
					}
					$query = $this->db->query($sql);
					$row = $query->first_row();
					if ($query->num_rows > 0) {
								return $row->leavecount;
					}
		 }
		function getMaxlevel($appId){
				$sql="SELECT
								IFNULL(MAX(vv.currentRoleVip), 0) max_rolevip
						FROM
								razor_viplevelup vv
						WHERE
								vv.appId = '$appId';";
				$query = $this->db->query($sql);
				$row = $query->first_row();
				if ($query->num_rows > 0) {
				return $row->max_rolevip;
				}       

		 }

		 /**
			* sum_basic_viprole function
			* count dau users
			* 
			* 
			*/
		 function sum_basic_viprole($countdate) {
					$dwdb = $this->load->database('dw', true);
					$params_psv = $this->product->getProductServerVersionOffChannel();
					$paramsRow_psv = $params_psv->first_row();
					for ($i = 0; $i < $params_psv->num_rows(); $i++) {
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_psv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_psv->appId,
												 'version_name' => $paramsRow_psv->version,
												 'channel_name' => 'all',
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_psv->appId,
												 'version_name' => $paramsRow_psv->version,
												 'channel_name' => 'all',
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_psv = $params_psv->next_row();
					}
					$params_ps = $this->product->getProductServerOffChannelVersion();
					$paramsRow_ps = $params_ps->first_row();
					for ($i = 0; $i < $params_ps->num_rows(); $i++) {
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_ps->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_ps->appId,
												 'version_name' => 'all',
												 'channel_name' => 'all',
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_ps->appId,
												 'version_name' => 'all',
												 'channel_name' => 'all',
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_ps = $params_ps->next_row();
					}
					$params_pv = $this->product->getProductVersionOffChannelServer();
					$paramsRow_pv = $params_pv->first_row();
					for ($i = 0; $i < $params_pv->num_rows(); $i++) {
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_pv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pv->appId,
												 'version_name' => $paramsRow_pv->version,
												 'channel_name' => 'all',
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pv->appId,
												 'version_name' => $paramsRow_pv->version,
												 'channel_name' => 'all',
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_pv = $params_pv->next_row();
					}
					$params_p = $this->product->getProductOffChannelServerVersion();
					$paramsRow_p = $params_p->first_row();
					for ($i = 0; $i < $params_p->num_rows(); $i++) {
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_p->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_p->appId,
												 'version_name' => 'all',
												 'channel_name' => 'all',
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_p->appId,
												 'version_name' => 'all',
												 'channel_name' => 'all',
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_p = $params_p->next_row();
					}
					$params_pcsv = $this->product->getProductChannelServerVersion();
					$paramsRow_pcsv = $params_pcsv->first_row();
					for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_pcsv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcsv->appId,
												 'version_name' => $paramsRow_pcsv->version,
												 'channel_name' => $channel_name,
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcsv->appId,
												 'version_name' => $paramsRow_pcsv->version,
												 'channel_name' => $channel_name,
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_pcsv = $params_pcsv->next_row();
					}
					$params_pcs = $this->product->getProductChannelServerOffVersion();
					$paramsRow_pcs = $params_pcs->first_row();
					for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_pcs->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcs->appId,
												 'version_name' => 'all',
												 'channel_name' => $channel_name,
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcs->appId,
												 'version_name' => 'all',
												 'channel_name' => $channel_name,
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_pcs = $params_pcs->next_row();
					}
					$params_pcv = $this->product->getProductChannelVersionOffServer();
					$paramsRow_pcv = $params_pcv->first_row();
					for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
								//get servername by serverid
								// $server_name = $this->server->getServernameById($paramsRow_pcv->srvId);
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_pcv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcv->appId,
												 'version_name' => $paramsRow_pcv->version,
												 'channel_name' => $channel_name,
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcv->appId,
												 'version_name' => $paramsRow_pcv->version,
												 'channel_name' => $channel_name,
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_pcv = $params_pcv->next_row();
					}
					$params_pc = $this->product-> getProductChannelOffServerVersion();
					$paramsRow_pc = $params_pc->first_row();
					for ($i = 0; $i < $params_pc->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
								//get servername by serverid
								// $server_name = $this->server->getServernameById($paramsRow_pc->srvId);
								// max vip level
								$Maxlevel=$this->getMaxlevel($paramsRow_pc->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										$Newvipuser=$this->getNewvipuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Losevipuser=$this->getLosevipuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Currentvipuser=$this->getCurrentvipuser('1970-01-01',$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Dauvipcount=$this->getDauvipcount($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Dauvipgametime=$this->getDauvipgametime($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Payvipuser=$this->getPayvipuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										// razor_sum_basic_viprole_useralany
										$data_viprole_useralany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pc->appId,
												 'version_name' => 'all',
												 'channel_name' => $channel_name,
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'newvip' => $Newvipuser,
												 'outvip' => $Losevipuser,
												 'currentvip' => $Currentvipuser,
												 'dauvip' => $Dauvipuser,
												 'dayavggamecount' => ($Dauvipuser==0)?0:($Dauvipcount/$Dauvipuser),
												 'pergametime' => ($Dauvipuser==0)?0:($Dauvipgametime/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_useralany', $data_viprole_useralany);
										// razor_sum_basic_viprole_payalany
										$data_viprole_payalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pc->appId,
												 'version_name' => 'all',
												 'channel_name' => $channel_name,
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'payamount' => $Payvipuser['pay_amount'],
												 'payuser' => $Payvipuser['payrole'],
												 'paycount' => $Payvipuser['paycount'],
												 'dayavgpaycount' => $Payvipuser['paycount'],
												 'dayavgpayuser' => $Payvipuser['payrole'],
												 'useravgpaycount' => ($Payvipuser['payrole']==0)?0:($Payvipuser['paycount']/$Payvipuser['payrole']),
												 'payrate' => '0',
												 'arpu' => ($Dauvipuser==0)?0:($Payvipuser['pay_amount']/$Dauvipuser),
												 'arppu' => ($Payvipuser['payrole']==0)?0:($Payvipuser['pay_amount']/$Payvipuser['payrole'])
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_payalany', $data_viprole_payalany);
								}
								$paramsRow_pc = $params_pc->next_row();
					}
		 }


			/**
			* sum_basic_viprole_leavealany function
			* count dau users
			* 
			* 
			*/
		 function sum_basic_viprole_leavealany($countdate) {
					$dwdb = $this->load->database('dw', true);

					$params_psv = $this->product->getProductServerVersionOffChannel();
					$paramsRow_psv = $params_psv->first_row();
					for ($i = 0; $i < $params_psv->num_rows(); $i++) {
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_psv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_psv->appId,
												 'version_name' => $paramsRow_psv->version,
												 'channel_name' => 'all',
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_psv->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_psv->appId,
														 'version_name' => $paramsRow_psv->version,
														 'channel_name' => 'all',
														 'server_name' => $server_name,
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_psv = $params_psv->next_row();
					}
					$params_ps = $this->product->getProductServerOffChannelVersion();
					$paramsRow_ps = $params_ps->first_row();
					for ($i = 0; $i < $params_ps->num_rows(); $i++) {
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_ps->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_ps->appId,
												 'version_name' => 'all',
												 'channel_name' => 'all',
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_ps->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_ps->appId,
														 'version_name' => 'all',
														 'channel_name' => 'all',
														 'server_name' => $server_name,
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_ps = $params_ps->next_row();
					}
					$params_pv = $this->product->getProductVersionOffChannelServer();
					$paramsRow_pv = $params_pv->first_row();
					for ($i = 0; $i < $params_pv->num_rows(); $i++) {
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_pv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pv->appId,
												 'version_name' => $paramsRow_pv->version,
												 'channel_name' => 'all',
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_pv->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_pv->appId,
														 'version_name' => $paramsRow_pv->version,
														 'channel_name' => 'all',
														 'server_name' => 'all',
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_pv = $params_pv->next_row();
					}
					$params_p = $this->product->getProductOffChannelServerVersion();
					$paramsRow_p = $params_p->first_row();
					for ($i = 0; $i < $params_p->num_rows(); $i++) {
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_p->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_p->appId,
												 'version_name' => 'all',
												 'channel_name' => 'all',
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_p->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_p->appId,
														 'version_name' => 'all',
														 'channel_name' => 'all',
														 'server_name' => 'all',
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_p = $params_p->next_row();
					}
					$params_pcsv = $this->product->getProductChannelServerVersion();
					$paramsRow_pcsv = $params_pcsv->first_row();
					for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_pcsv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcsv->appId,
												 'version_name' => $paramsRow_pcsv->version,
												 'channel_name' => $channel_name,
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcsv->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_pcsv->appId,
														 'version_name' => $paramsRow_pcsv->version,
														 'channel_name' => $channel_name,
														 'server_name' => $server_name,
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_pcsv = $params_pcsv->next_row();
					}
					$params_pcs = $this->product->getProductChannelServerOffVersion();
					$paramsRow_pcs = $params_pcs->first_row();
					for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
								//get servername by serverid
								$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_pcs->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcs->appId,
												 'version_name' => 'all',
												 'channel_name' => $channel_name,
												 'server_name' => $server_name,
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcs->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_pcs->appId,
														 'version_name' => 'all',
														 'channel_name' => $channel_name,
														 'server_name' => $server_name,
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_pcs = $params_pcs->next_row();
					}
					$params_pcv = $this->product->getProductChannelVersionOffServer();
					$paramsRow_pcv = $params_pcv->first_row();
					for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
								//get servername by serverid
								// 'all' = $this->server->getServernameById($paramsRow_pcv->srvId);
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_pcv->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pcv->appId,
												 'version_name' => $paramsRow_pcv->version,
												 'channel_name' => $channel_name,
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcv->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_pcv->appId,
														 'version_name' => $paramsRow_pcv->version,
														 'channel_name' => $channel_name,
														 'server_name' => 'all',
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_pcv = $params_pcv->next_row();
					}
					$params_pc = $this->product-> getProductChannelOffServerVersion();
					$paramsRow_pc = $params_pc->first_row();
					for ($i = 0; $i < $params_pc->num_rows(); $i++) {
								//get channelname by channelid
								$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
								//get servername by serverid
								// 'all' = $this->server->getServernameById($paramsRow_pc->srvId);
								//vip max level
								$Maxlevel=$this->getMaxlevel($paramsRow_pc->appId);
								for ($m=0; $m <$Maxlevel+1 ; $m++) { 
										// razor_sum_basic_viprole_leavealany
										$Dauvipuser=$this->getDauvipuser($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
										$Leavevipalany3=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,3);
										$Leavevipalany7=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,7);
										$Leavevipalany14=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,14);
										$Leavevipalany30=$this->getLeavevipalany($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,30);
										$data_Leavevipalany = array(
												 'startdate_sk' => $countdate,
												 'enddate_sk' => $countdate,
												 'product_id' => $paramsRow_pc->appId,
												 'version_name' => 'all',
												 'channel_name' => $channel_name,
												 'server_name' => 'all',
												 'viplevel' => $m,
												 'day3leave' => $Leavevipalany3,
												 'day3leaverate' => ($Dauvipuser==0)?0:($Leavevipalany3/$Dauvipuser),
												 'day7leave' => $Leavevipalany7,
												 'day7leaverate' => ($Dauvipuser==0)?0:($Leavevipalany7/$Dauvipuser),
												 'day14leave' => $Leavevipalany14,
												 'day14leaverate' => ($Dauvipuser==0)?0:($Leavevipalany14/$Dauvipuser),
												 'day30leave' => $Leavevipalany30,
												 'day30leaverate' => ($Dauvipuser==0)?0:($Leavevipalany30/$Dauvipuser)
										);
										$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany', $data_Leavevipalany);
										
										// razor_sum_basic_viprole_leavealany_userlevel
										//user max level
										$levelaly_Maxlevel=$this->levelaly->getMaxlevel($paramsRow_pc->appId);
										for ($u=1; $u < $levelaly_Maxlevel+1; $u++) { 
												$Dauvipuserlevel=$this->getDauvipuserlevel($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,$u);
												$Leaveviplevel3=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,3,$u);
												$Leaveviplevel7=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,7,$u);
												$Leaveviplevel14=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,14,$u);
												$Leaveviplevel30=$this->getLeaveviplevel($countdate,$countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m,30,$u);
												$data_Leaveviplevel = array(
														 'startdate_sk' => $countdate,
														 'enddate_sk' => $countdate,
														 'product_id' => $paramsRow_pc->appId,
														 'version_name' => 'all',
														 'channel_name' => $channel_name,
														 'server_name' => 'all',
														 'viplevel' => $m,
														 'userlevel' => $u,
														 'day3leave' =>  $Leaveviplevel3,
														 'day3leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel3/$Dauvipuserlevel),
														 'day7leave' => $Leaveviplevel7,
														 'day7leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel7/$Dauvipuserlevel),
														 'day14leave' => $Leaveviplevel14,
														 'day14leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel14/$Dauvipuserlevel),
														 'day30leave' => $Leaveviplevel30,
														 'day30leaverate' => ($Dauvipuserlevel==0)?0:($Leaveviplevel30/$Dauvipuserlevel)
												);
												$dwdb->insert_or_update('razor_sum_basic_viprole_leavealany_userlevel', $data_Leaveviplevel);
										}
								}
								$paramsRow_pc = $params_pc->next_row();
					}
		 }

}