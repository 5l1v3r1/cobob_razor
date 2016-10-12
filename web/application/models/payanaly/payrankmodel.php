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
 * Dauusersmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Payrankmodel extends CI_Model
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
	//获取全部用户信息排行
	function getAllUserRankData($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->AllUserRankData($fromTime,$toTime,$channel,$version,$server);
		$dauUsersRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['roleId'] = $dauUsersRow->roleId;
			$fRow['ranks'] = $dauUsersRow->ranks;
			$fRow['account'] = $dauUsersRow->account;
			$fRow['rolelevel'] = $dauUsersRow->rolelevel;
			$fRow['roleviplevel'] = $dauUsersRow->roleviplevel;
			$fRow['registerdate'] = $dauUsersRow->registerdate;
			$fRow['firstpaydate'] = $dauUsersRow->firstpaydate;
			$fRow['totalpayamount'] = $dauUsersRow->totalpayamount;
			$fRow['paycount'] = $dauUsersRow->paycount;
			$fRow['onlinedays'] = $dauUsersRow->onlinedays;
			$fRow['gametime'] = $dauUsersRow->gametime;
			$fRow['gamecount'] = $dauUsersRow->gamecount;
			$fRow['tag'] = $dauUsersRow->tag;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function AllUserRankData($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(roleId, 0) roleId,
					IFNULL(ranks, 0) ranks,
					IFNULL(account, 0) account,
					IFNULL(rolelevel, 0) rolelevel,
					IFNULL(roleviplevel, 0) roleviplevel,
					IFNULL(registerdate, 0) registerdate,
					IFNULL(firstpaydate, 0) firstpaydate,
					IFNULL(totalpayamount, 0) totalpayamount,
					IFNULL(paycount, 0) paycount,
					IFNULL(onlinedays, 0) onlinedays,
					IFNULL(gametime, 0) gametime,
					IFNULL(gamecount, 0) gamecount,
					IFNULL(tag, 0) tag
				FROM
					".$dwdb->dbprefix('sum_basic_payrank')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')";
		$query = $dwdb->query($sql);
		return $query;
	}
	//获取单个用户信息排行详细
	function getSingleUserRankData($fromTime,$toTime,$id)
	{
		$list = array();
		$query = $this->SingleUserRankData($fromTime,$toTime,$id);
		$dauUsersRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow["date"] = substr($dauUsersRow->datevalue, 0, 10);
			$fRow['gametimemin'] = $dauUsersRow->gametimemin;
			$fRow['logintimes'] = $dauUsersRow->logintimes;
			$fRow['payamount'] = $dauUsersRow->payamount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function SingleUserRankData($fromTime,$toTime,$id)
	{	
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		$sql = "SELECT
					d.datevalue,
					IFNULL(gametimemin, 0) gametimemin,
					IFNULL(logintimes, 0) logintimes,
					IFNULL(payamount, 0) payamount
				FROM
					".$dwdb->dbprefix('sum_basic_payrank_trend')." s
				RIGHT JOIN
				(
					SELECT
						date_sk,
						datevalue
					FROM
						".$dwdb->dbprefix('dim_date')."
					WHERE
						datevalue BETWEEN '$fromTime'
					AND '$toTime'
				) d ON s.startdate_sk = d.date_sk
				WHERE s.roleId = '".$id."'
				GROUP BY
					d.datevalue
				ORDER BY
					d.datevalue DESC;";
		$query = $dwdb->query($sql);
		return $query;
	}
	//获取单个用户账户信息
	function getSingleAcountData($fromTime,$toTime,$channel,$version,$server,$id)
	{
		$list = array();
		$query = $this->SingleAcountData($fromTime,$toTime,$channel,$version,$server,$id);
		$dauUsersRow = $query->first_row();
		return $dauUsersRow;
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['roleId'] = $dauUsersRow->roleId;
			$fRow['account'] = $dauUsersRow->account;
			$fRow['rolelevel'] = $dauUsersRow->rolelevel;
			$fRow['roleviplevel'] = $dauUsersRow->roleviplevel;
			$fRow['totalpayamount'] = $dauUsersRow->totalpayamount;
			$fRow['server_name'] = $dauUsersRow->server_name;
			$fRow['channel_name'] = $dauUsersRow->channel_name;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}

		return $list;
	}

	function SingleAcountData($fromTime,$toTime,$channel,$version,$server,$id)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(roleId, 0) roleId,
					IFNULL(account, 0) account,
					IFNULL(rolelevel, 0) rolelevel,
					IFNULL(roleviplevel, 0) roleviplevel,
					IFNULL(totalpayamount, 0) totalpayamount,
					IFNULL(server_name, 0) server_name,
					IFNULL(channel_name, 0) channel_name
				FROM
					".$dwdb->dbprefix('sum_basic_payrank')."
				WHERE roleId = '".$id."'";
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
     * GetRanktenUserinfo function
     * get pay user from dau user
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int pay data
     */
    function getRanktenUserinfo($date,$appid='1', $channelid='all', $serverid='all', $versionname='all',$rank=10) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
									#					p.chId,
														p.srvId,
														p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
												#	AND p.chId = '$channelid'
													AND p.srvId = '$serverid'
													AND p.version = '$versionname'
													GROUP BY
														p.appId,
									#					p.chId,
														p.srvId,
														p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								#AND rl.chId = '$channelid'
								AND rl.srvId = '$serverid'
								AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
					#				rl.chId,
									rl.srvId,
									rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						#AND rl.chId = '$channelid'
						AND rl.srvId = '$serverid'
						AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
			#				rl.chId,
							rl.srvId,
							rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
									#					p.chId,
														p.srvId,
									#					p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
												#	AND p.chId = '$channelid'
													AND p.srvId = '$serverid'
												#	AND p.version = '$versionname'
													GROUP BY
														p.appId,
									#					p.chId,
														p.srvId,
									#					p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								#AND rl.chId = '$channelid'
								AND rl.srvId = '$serverid'
								#AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
					#				rl.chId,
									rl.srvId,
					#				rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						#AND rl.chId = '$channelid'
						AND rl.srvId = '$serverid'
						#AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
			#				rl.chId,
							rl.srvId,
			#				rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
									#					p.chId,
									#					p.srvId,
														p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
												#	AND p.chId = '$channelid'
												#	AND p.srvId = '$serverid'
													AND p.version = '$versionname'
													GROUP BY
														p.appId,
									#					p.chId,
									#					p.srvId,
														p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								#AND rl.chId = '$channelid'
								#AND rl.srvId = '$serverid'
								AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
					#				rl.chId,
					#				rl.srvId,
									rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						#AND rl.chId = '$channelid'
						#AND rl.srvId = '$serverid'
						AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
			#				rl.chId,
			#				rl.srvId,
							rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
									#					p.chId,
									#					p.srvId,
									#					p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
												#	AND p.chId = '$channelid'
												#	AND p.srvId = '$serverid'
												#	AND p.version = '$versionname'
													GROUP BY
														p.appId,
									#					p.chId,
									#					p.srvId,
									#					p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								#AND rl.chId = '$channelid'
								#AND rl.srvId = '$serverid'
								#AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
					#				rl.chId,
					#				rl.srvId,
					#				rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						#AND rl.chId = '$channelid'
						#AND rl.srvId = '$serverid'
						#AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
			#				rl.chId,
			#				rl.srvId,
			#				rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
														p.chId,
														p.srvId,
														p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
													AND p.chId = '$channelid'
													AND p.srvId = '$serverid'
													AND p.version = '$versionname'
													GROUP BY
														p.appId,
														p.chId,
														p.srvId,
														p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								AND rl.chId = '$channelid'
								AND rl.srvId = '$serverid'
								AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
									rl.chId,
									rl.srvId,
									rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						AND rl.chId = '$channelid'
						AND rl.srvId = '$serverid'
						AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
							rl.chId,
							rl.srvId,
							rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
														p.chId,
														p.srvId,
									#					p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
													AND p.chId = '$channelid'
													AND p.srvId = '$serverid'
												#	AND p.version = '$versionname'
													GROUP BY
														p.appId,
														p.chId,
														p.srvId,
									#					p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								AND rl.chId = '$channelid'
								AND rl.srvId = '$serverid'
								#AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
									rl.chId,
									rl.srvId,
					#				rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						AND rl.chId = '$channelid'
						AND rl.srvId = '$serverid'
						#AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
							rl.chId,
							rl.srvId,
			#				rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
														p.chId,
									#					p.srvId,
														p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
													AND p.chId = '$channelid'
												#	AND p.srvId = '$serverid'
													AND p.version = '$versionname'
													GROUP BY
														p.appId,
														p.chId,
									#					p.srvId,
														p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								AND rl.chId = '$channelid'
								#AND rl.srvId = '$serverid'
								AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
									rl.chId,
					#				rl.srvId,
									rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						AND rl.chId = '$channelid'
						#AND rl.srvId = '$serverid'
						AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
							rl.chId,
			#				rl.srvId,
							rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						ptencl.rank rank,
						ptencl.roleId roleid,
						ptencl.roleName rolename,
						ptencl.max_rolelevel rolelevel,
						ptencl.max_rolevip rolevip,
						ptencl.createrole_date createrole_date,
						ptencl.min_paydate pay_date,
						ptencl.sum_paytotal pay_amount,
						ptencl.pay_count pay_count,
						IFNULL(lt.onlinedates, 0) onlinedates,
						IFNULL(lt.onlinetime, 0) onlinetime,
						IFNULL(lt.logintimes, 0) logintimes
					FROM
						(
							SELECT
								ptenc.*, IFNULL(ml.max_rolelevel, 1) max_rolelevel
							FROM
								(
									SELECT
										pten.*, IFNULL(
											c.create_role_date,
											pten.min_paydate
										) createrole_date
									FROM
										(
											SELECT
												@rownum :=@rownum + 1 AS rank,
												ten.*
											FROM
												(
													SELECT
														p.appId,
														p.chId,
									#					p.srvId,
									#					p.version,
														p.roleId,
														p.roleName,
														/*总付费*/
														SUM(p.pay_amount) sum_paytotal,
														/*付费次数*/
														COUNT(1) pay_count,
														/*首付日期*/
														MIN(p.pay_date) min_paydate,
														/*首付日期*/
														MAX(roleVip) max_rolevip
													FROM
														(SELECT @rownum := 0) r,
														razor_pay p
													WHERE
														p.pay_date <= '$date'
													AND p.appId = '$appid'
													AND p.chId = '$channelid'
												#	AND p.srvId = '$serverid'
												#	AND p.version = '$versionname'
													GROUP BY
														p.appId,
														p.chId,
									#					p.srvId,
									#					p.version,
														p.roleId
													ORDER BY
														sum_paytotal DESC
													LIMIT $rank
												) ten
										) pten
									LEFT JOIN razor_createrole c ON pten.roleId = c.roleId
								) ptenc
							LEFT JOIN (
								SELECT
									rl.roleId,
									/*等级*/
									MAX(rl.roleLevel) max_rolelevel
								FROM
									razor_levelupgrade rl
								WHERE
									rl.levelupgrade_date <= '$date'
								AND rl.appId = '$appid'
								AND rl.chId = '$channelid'
								#AND rl.srvId = '$serverid'
								#AND rl.version = '$versionname'
								GROUP BY
									rl.appId,
									rl.chId,
					#				rl.srvId,
					#				rl.version,
									rl.roleId
							) ml ON ptenc.roleId = ml.roleId
						) ptencl
					LEFT JOIN (
						SELECT
							rl.roleId,
							/*在线天数*/
							COUNT(DISTINCT rl.login_date) onlinedates,
							/*游戏时长(单位:h)*/
							IFNULL(
								SUM(
									CASE
									WHEN rl.type = 'logout' THEN
										rl.offlineSettleTime / 60
									ELSE
										0
									END
								),
								0
							) onlinetime,
							/*游戏次数*/
							COUNT(1) logintimes
						FROM
							razor_login rl
						WHERE
							rl.login_date <= '$date'
						AND rl.appId = '$appid'
						AND rl.chId = '$channelid'
						#AND rl.srvId = '$serverid'
						#AND rl.version = '$versionname'
						GROUP BY
							rl.appId,
							rl.chId,
			#				rl.srvId,
			#				rl.version,
							rl.roleId
					) lt ON ptencl.roleId = lt.roleId";
        }
        $query = $this->db->query($sql);
        return $query;
    }

     /**
	  * GetRanknumByRoleid function
	  * get new pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int new pay user
	  */
	 function getRanknumByRoleid($date, $roleid) {
				$sql = "SELECT
							IFNULL(p.ranks,0) ranknum
						FROM
							razor_sum_basic_payrank p
						WHERE
							p.startdate_sk = DATE_SUB($date,INTERVAL 1 DAY)
						AND p.roleId='$roleid';";
		  $dwdb = $this->load->database('dw', true);
		  $query = $dwdb->query($sql);
		  $row = $query->first_row();
		  if ($query->num_rows > 0) {
				return $row->ranknum;
		  }
	 }

	 /**
	  * GetRanknumByRoleid function
	  * get new pay user
	  * 
	  * @param Date $date
	  * @param String $appid
	  * @param String $channelid
	  * @param String $serverid
	  * @param String $versionname
	  * 
	  * @return Int new pay user
	  */
	 function getUserinfo($date, $roleid) {
				$sql = "SELECT
							lt.login_count logincount,
							IFNULL(lt.onlinetime, 0) onlinetime,
							IFNULL(pt.pay_totalamount, 0) payamount
						FROM
							(
								SELECT
									rl.roleId,
									rl.login_date,
									COUNT(1) login_count,
									SUM(offlineSettleTime / 60) onlinetime
								FROM
									razor_login rl
								WHERE
									rl.login_date = '$date'
								AND rl.roleId = '$roleid'
								AND rl.type = 'logout'
							) lt
						LEFT JOIN (
							SELECT
								p.roleId,
								SUM(p.pay_amount) pay_totalamount
							FROM
								razor_pay p
							WHERE
								p.roleId = '$roleid'
							AND p.pay_date = '$date'
						) pt ON lt.roleId = pt.roleId;";
	  	$query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
	 }
    
        
    /**
     * Sum_basic_payrank function
     * count pay data
     * 
     * 
     */
    function sum_basic_payrank($countdate) {
        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
           	$ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	//get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_psv->appId,
	                'version_name' => $paramsRow_psv->version,
	                'channel_name' => 'all',
	                'server_name' => $server_name,
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_psv->appId,
		                'version_name' => $paramsRow_psv->version,
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	//get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_ps->appId,
	                'version_name' => 'all',
	                'channel_name' => 'all',
	                'server_name' => $server_name,
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_ps->appId,
		                'version_name' => 'all',
		                'channel_name' => 'all',
		                'server_name' => $server_name,
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_pv->appId,
	                'version_name' => $paramsRow_pv->version,
	                'channel_name' => 'all',
	                'server_name' => 'all',
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_pv->appId,
		                'version_name' => $paramsRow_pv->version,
		                'channel_name' => 'all',
		                'server_name' => 'all',
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_p->appId, 'all', 'all', 'all',10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_p->appId,
	                'version_name' => 'all',
	                'channel_name' => 'all',
	                'server_name' => 'all',
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_p->appId,
		                'version_name' => 'all',
		                'channel_name' => 'all',
		                'server_name' => 'all',
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_pcsv->appId,
	                'version_name' => $paramsRow_pcsv->version,
	                'channel_name' => $channel_name,
	                'server_name' => $server_name,
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_pcsv->appId,
		                'version_name' => $paramsRow_pcsv->version,
		                'channel_name' => $channel_name,
		                'server_name' => $server_name,
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_pcs->appId,
	                'version_name' => 'all',
	                'channel_name' => $channel_name,
	                'server_name' => $server_name,
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_pcs->appId,
		                'version_name' => 'all',
		                'channel_name' => $channel_name,
		                'server_name' => $server_name,
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_pcv->appId,
	                'version_name' => $paramsRow_pcv->version,
	                'channel_name' => $channel_name,
	                'server_name' => 'all',
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_pcv->appId,
		                'version_name' => $paramsRow_pcv->version,
		                'channel_name' => $channel_name,
		                'server_name' => 'all',
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $ranktenuserinfo = $this->getRanktenUserinfo($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',10);
           	$ranktenuserinfoRow = $ranktenuserinfo->first_row();
           	//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
           	for ($j = 0; $j < $ranktenuserinfo->num_rows(); $j++) {
           		// $yesterday_rank=$this->getRanknumByRoleid($countdate,$ranktenuserinfoRow->roleid);
           		// if ($yesterday_rank<>0&&$yesterday_rank<$ranktenuserinfoRow->rank) {
           		// 	$tag='down';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank=$ranktenuserinfoRow->rank) {
           		// 	$tag='-';
           		// }elseif ($yesterday_rank<>0&&$yesterday_rank>$ranktenuserinfoRow->rank) {
           		// 	$tag='up';
           		// }elseif ($yesterday_rank==0) {
           		// 	$tag='up';
           		// }
	            $data = array(
	                'startdate_sk' => $countdate,
	                'enddate_sk' => $countdate,
	                'product_id' => $paramsRow_pc->appId,
	                'version_name' => 'all',
	                'channel_name' => $channel_name,
	                'server_name' => 'all',
	                'ranks' => $ranktenuserinfoRow->rank,
	                'roleId' => $ranktenuserinfoRow->roleid,
	                'account' => $ranktenuserinfoRow->rolename,
	                'rolelevel' => $ranktenuserinfoRow->rolelevel,
	                'roleviplevel' => $ranktenuserinfoRow->rolevip,
	                'registerdate' => $ranktenuserinfoRow->createrole_date,
	                'firstpaydate' => $ranktenuserinfoRow->pay_date,
	                'totalpayamount' => $ranktenuserinfoRow->pay_amount,
	                'paycount' => $ranktenuserinfoRow->pay_count,
	                'onlinedays' => $ranktenuserinfoRow->onlinedates,
	                'gametime' => $ranktenuserinfoRow->onlinetime,
	                'gamecount' => $ranktenuserinfoRow->logintimes,
	                'tag' => '-'
	            );
				for($d=0;$d<7;$d++){
					$paytrend_date=date("Y-m-d",strtotime("-$d day"));
		            $paytrend=$this->getUserinfo($paytrend_date, $ranktenuserinfoRow->roleid);
		            $data_trend = array(
		                'startdate_sk' => $paytrend_date,
		                'enddate_sk' => $paytrend_date,
		                'product_id' => $paramsRow_pc->appId,
		                'version_name' => 'all',
		                'channel_name' => $channel_name,
		                'server_name' => 'all',
		                'roleId' => $ranktenuserinfoRow->roleid,
		                'gametimemin' => $paytrend['onlinetime'],
		                'logintimes' => $paytrend['logincount'],
		                'payamount' => $paytrend['payamount']
		            );
					$dwdb = $this->load->database('dw', true);
	            	$dwdb->insert_or_update('razor_sum_basic_payrank_trend', $data_trend);
		        }
	            $dwdb = $this->load->database('dw', true);
	            $dwdb->insert_or_update('razor_sum_basic_payrank', $data);
	            $ranktenuserinfoRow = $ranktenuserinfo->next_row();
           	}
            $paramsRow_pc = $params_pc->next_row();
        }
    }
   
}
