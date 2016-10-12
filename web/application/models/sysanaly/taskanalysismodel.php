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
 * Borderintervaltimemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Taskanalysismodel extends CI_Model {

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
	
	function getTaskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype){
		$list = array();
		$query = $this->Taskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['task'] = $dauUsersRow->task;
			$fRow['stepcount'] = $dauUsersRow->stepcount;
			$fRow['taskactivecount'] = $dauUsersRow->taskactivecount;
			$fRow['taskdonecount'] = $dauUsersRow->taskdonecount;
			$fRow['taskdonerate'] = $dauUsersRow->taskdonerate;
			$fRow['taskactiveuser'] = $dauUsersRow->taskactiveuser;
			$fRow['taskstayuser'] = $dauUsersRow->taskstayuser;
			$fRow['taskstayrate'] = $dauUsersRow->taskstayrate;
			$fRow['taskpassuser'] = $dauUsersRow->taskpassuser;
			$fRow['taskpassrate'] = $dauUsersRow->taskpassrate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function Taskanalysis($fromTime,$toTime,$channel,$server,$version,$tasktype)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(task, 0) task,
					IFNULL(stepcount, 0) stepcount,
					IFNULL(SUM(taskactivecount), 0) taskactivecount,
					IFNULL(SUM(taskdonecount), 0) taskdonecount,
					IFNULL(AVG(taskdonerate), 0) taskdonerate,
					IFNULL(SUM(taskactiveuser), 0) taskactiveuser,
					IFNULL(SUM(taskstayuser), 0) taskstayuser,
					IFNULL(AVG(taskstayrate), 0) taskstayrate,
					IFNULL(SUM(taskpassuser), 0) taskpassuser,
					IFNULL(AVG(taskpassrate), 0) taskpassrate
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_taskanalysis_task') . "
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND tasktype = '" . $tasktype . "'
				Group By task
				Order By rid ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getTaskanalysisDetail1($fromTime,$toTime,$channel,$server,$version,$taskName,$tasktype){
		$list = array();
		$query = $this->TaskanalysisDetail1($fromTime,$toTime,$channel,$server,$version,$taskName,$tasktype);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['step'] = $dauUsersRow->step;
			$fRow['step_desc'] = $dauUsersRow->step_desc;
			$fRow['stepactiveuser'] = $dauUsersRow->stepactiveuser;
			$fRow['steppassuser'] = $dauUsersRow->steppassuser;
			$fRow['steppassrate'] = $dauUsersRow->steppassrate;
			$fRow['stepstaycount'] = $dauUsersRow->stepstaycount;
			$fRow['stepstayrate'] = $dauUsersRow->stepstayrate;
			$fRow['steptotalstayrate'] = $dauUsersRow->steptotalstayrate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function TaskanalysisDetail1($fromTime,$toTime,$channel,$server,$version,$taskName,$tasktype)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(step, 0) step,
					IFNULL(step_desc, 0) step_desc,
					IFNULL(SUM(stepactiveuser), 0) stepactiveuser,
					IFNULL(SUM(steppassuser), 0) steppassuser,
					IFNULL(steppassrate, 0) steppassrate,
					IFNULL(SUM(stepstaycount), 0) stepstaycount,
					IFNULL(stepstayrate, 0) stepstayrate,
					IFNULL(AVG(steptotalstayrate), 0) steptotalstayrate
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_taskanalysis_step') . "
				WHERE
				channel_name in('" . $channel_list . "')
				AND server_name in('" . $server_list . "')
				AND version_name in('" . $version . "')
				AND startdate_sk >= '" . $fromTime . "'
				AND enddate_sk <= '" . $toTime . "'
				AND tasktype = '" . $tasktype . "'
				AND task = '" . $taskName . "'
				Group By step
				Order By step ASC";
		$query = $dwdb->query($sql);
		return $query;
	}

	function getTaskanalysisDetail2($fromTime,$toTime,$channel,$server,$version,$taskName){
		$list = array();
		$query = $this->TaskanalysisDetail2($fromTime,$toTime,$channel,$server,$version,$taskName);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow['enddate_sk'] = $dauUsersRow->enddate_sk;
			$fRow['taskactiveuser'] = $dauUsersRow->taskactiveuser;
			$fRow['taskpassuser'] = $dauUsersRow->taskpassuser;
			$fRow['taskpassrate'] = $dauUsersRow->taskpassrate;
			$fRow['taskstayuser'] = $dauUsersRow->taskstayuser;
			$fRow['taskstayrate'] = $dauUsersRow->taskstayrate;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function TaskanalysisDetail2($fromTime,$toTime,$channel,$server,$version,$taskName)
	{  
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(enddate_sk, 0) enddate_sk,
					IFNULL(taskactiveuser, 0) taskactiveuser,
					IFNULL(taskpassuser, 0) taskpassuser,
					IFNULL(taskpassrate, 0) taskpassrate,
					IFNULL(taskstayuser, 0) taskstayuser,
					IFNULL(taskstayrate, 0) taskstayrate
				FROM
					" . $dwdb->dbprefix('sum_basic_sa_taskanalysis_task') . "
				WHERE
				channel_name in('" . $channel_list . "')
				AND server_name in('" . $server_list . "')
				AND version_name in('" . $version . "')
				AND task = '" . $taskName . "'
				Order By enddate_sk DESC";
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
     * GetTaskData function
     * get task data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array task data
     */
    function getTaskData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						t2.tasktype tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_task t
							WHERE
								t.task_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.taskid
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.tasktype,
							t.task_id,
							t.task_name
						FROM
							VIEW_razor_mainconfig_task t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * GetNewuserguideData function
     * get task data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return array task data
     */
    function getNewuserguideData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							#AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						t2.task_name task_name,
						IFNULL(t1.stepcount, 0) stepcount,
						IFNULL(t1.taskactivecount, 0) taskactivecount,
						IFNULL(t1.taskdonecount, 0) taskdonecount,
						IFNULL(t1.taskactiveuser, 0) taskactiveuser,
						IFNULL(t1.taskpassuser, 0) taskpassuser
					FROM
						(
							SELECT
								t.newuserguide_id taskid,
								IFNULL(
									SUM(
										CASE
										WHEN t.stepid = 0 AND t.markid=0 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskactivecount,
								IFNULL(
									SUM(
										CASE
										WHEN t.markid = 1 THEN
											1
										ELSE
											0
										END
									),
									0
								) taskdonecount,
								COUNT(
									DISTINCT CASE
									WHEN t.stepid = 0 AND t.markid=0 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskactiveuser,
								COUNT(
									DISTINCT CASE
									WHEN t.markid = 1 THEN
										t.roleId
									ELSE
										NULL
									END
								) taskpassuser,
								COUNT(DISTINCT t.stepid) stepcount
							FROM
								razor_newuserguide t
							WHERE
								t.newuserguide_date = '$date'
							AND t.appId = '$appid'
							#AND t.srvId = '$serverid'
							AND t.chId = '$channelid'
							#AND t.version = '$versionname'
							GROUP BY
								t.newuserguide_id
						) t1
					RIGHT JOIN (
						SELECT
							DISTINCT
							t.task_id,
							t.task_name
						FROM
							razor_mainconfig_newusertask t
						WHERE
							t.app_id = '$appid'
					) t2 ON t1.taskid = t2.task_id;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    /**
     * GetStepData function
     * get step data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getStepData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													#AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												#AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										#AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													#AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
													#AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												#AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
												#AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										#AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
										#AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													#AND t.chId = '$channelid'
													#AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												#AND t.chId = '$channelid'
												#AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										#AND t.chId = '$channelid'
										#AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													#AND t.chId = '$channelid'
													#AND t.srvId = '$serverid'
													#AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												#AND t.chId = '$channelid'
												#AND t.srvId = '$serverid'
												#AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										#AND t.chId = '$channelid'
										#AND t.srvId = '$serverid'
										#AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
													#AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
												#AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
										#AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
													#AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
												#AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
										#AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						trmt.tasktype,
						trmt.taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.taskid,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.taskid,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_task t
													WHERE
														t.task_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
													#AND t.srvId = '$serverid'
													#AND t.version = '$versionname'
													GROUP BY
														t.taskid,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.taskid,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_task t
												WHERE
													t.task_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
												#AND t.srvId = '$serverid'
												#AND t.version = '$versionname'
												GROUP BY
													t.taskid,
													t.stepid
											) t2 ON t1.taskid = t2.taskid
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.taskid,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_task t
										WHERE
											t.task_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
										#AND t.srvId = '$serverid'
										#AND t.version = '$versionname'
										GROUP BY
											t.taskid,
											t.stepid
									) t4 ON t3.taskid = t4.taskid
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									VIEW_razor_mainconfig_task rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.taskid = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }


    /**
     * GetNewuserguideStepData function
     * get step data
     * 
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     * 
     * @return Int acu
     */
    function getNewuserguideStepData($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all') {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
												#	AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
											#	AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
									#	AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
												#	AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
												#	AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
											#	AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
											#	AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
									#	AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
									#	AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
												#	AND t.chId = '$channelid'
												#	AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
											#	AND t.chId = '$channelid'
											#	AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
									#	AND t.chId = '$channelid'
									#	AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
												#	AND t.chId = '$channelid'
												#	AND t.srvId = '$serverid'
												#	AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
											#	AND t.chId = '$channelid'
											#	AND t.srvId = '$serverid'
											#	AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
									#	AND t.chId = '$channelid'
									#	AND t.srvId = '$serverid'
									#	AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
													AND t.srvId = '$serverid'
												#	AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
												AND t.srvId = '$serverid'
											#	AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
										AND t.srvId = '$serverid'
									#	AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
												#	AND t.srvId = '$serverid'
													AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
											#	AND t.srvId = '$serverid'
												AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
									#	AND t.srvId = '$serverid'
										AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
						'newuser' tasktype,
						trmt.newuserguide_id taskid,
						trmt.task_name,
						trmt.stepid,
						IFNULL(trmt.stepactiveuser, 0) stepactiveuser,
						IFNULL(trmt.steppassuser, 0) steppassuser
					FROM
						(
							SELECT
								*
							FROM
								(
									SELECT
										t3.newuserguide_id,
										t3.stepid,
										IFNULL(t3.stepactiveuser, 0) stepactiveuser,
										IFNULL(t4.steppassuser, 0) steppassuser
									FROM
										(
											SELECT
												t1.*, t2.stepactiveuser
											FROM
												(
													SELECT
														t.newuserguide_id,
														t.stepid,
														t.stepid + 1 stepid_1
													FROM
														razor_newuserguide t
													WHERE
														t.newuserguide_date = '$date'
													AND t.appId = '$appid'
													AND t.chId = '$channelid'
												#	AND t.srvId = '$serverid'
												#	AND t.version = '$versionname'
													GROUP BY
														t.newuserguide_id,
														t.stepid
												) t1
											LEFT JOIN (
												SELECT
													t.newuserguide_id,
													t.stepid,
													COUNT(DISTINCT t.roleId) stepactiveuser
												FROM
													razor_newuserguide t
												WHERE
													t.newuserguide_date = '$date'
												AND t.appId = '$appid'
												AND t.chId = '$channelid'
											#	AND t.srvId = '$serverid'
											#	AND t.version = '$versionname'
												GROUP BY
													t.newuserguide_id,
													t.stepid
											) t2 ON t1.newuserguide_id = t2.newuserguide_id
											AND t1.stepid = t2.stepid
										) t3
									LEFT JOIN (
										SELECT
											t.newuserguide_id,
											t.stepid,
											COUNT(DISTINCT t.roleId) steppassuser
										FROM
											razor_newuserguide t
										WHERE
											t.newuserguide_date = '$date'
										AND t.appId = '$appid'
										AND t.chId = '$channelid'
									#	AND t.srvId = '$serverid'
									#	AND t.version = '$versionname'
										GROUP BY
											t.newuserguide_id,
											t.stepid
									) t4 ON t3.newuserguide_id = t4.newuserguide_id
									AND t3.stepid_1 = t4.stepid
								) t5
							LEFT JOIN (
								SELECT
									*
								FROM
									razor_mainconfig_newusertask rmt
								WHERE
									rmt.app_id = '$appid'
							) rmt1 ON t5.newuserguide_id = rmt1.task_id
						) trmt
					WHERE
						trmt.task_id IS NOT NULL;";
        }
        $query = $this->db->query($sql);
        // if ($query != null && $query -> num_rows() > 0) {
        //     return $query -> row_array();
        // }
        return $query;
    }

    function getTasknameAndStepname($taskid,$stepid){
    	$sql="SELECT
				IFNULL(rmt.step_name,'未知-配置表中不存在') step_name
			FROM
				VIEW_razor_mainconfig_task rmt
			WHERE
				rmt.task_id = '$taskid'
			AND rmt.step_id = '$stepid';";
		$query = $this->db->query($sql);
    	if ($query->num_rows() > 0) {
                return $query->row()->step_name;
        } else {
                return '未知-配置表中不存在';
        }
    }

    /**
     * Sum_basic_borderintervaltime function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_sa_taskanalysis($countdate) {
		$dwdb = $this->load->database('dw', true);

		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$stepdata=$this->getStepData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_psv = $params_psv->next_row();
		}
		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$stepdata=$this->getStepData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_ps = $params_ps->next_row();
		}
		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$stepdata=$this->getStepData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pv = $params_pv->next_row();
		}
		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$stepdata=$this->getStepData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$stepdata=$this->getStepData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$stepdata=$this->getStepData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$stepdata=$this->getStepData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pcv = $params_pcv->next_row();
		}
		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$taskdata=$this->getTaskData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$stepdata=$this->getStepData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pc = $params_pc->next_row();
		}
	}

    /**
     * Sum_basic_sa_newuserguideanalysis function
     * count online users by every day
     * 
     * 
     */
    function sum_basic_sa_newuserguideanalysis($countdate) {
		$dwdb = $this->load->database('dw', true);

		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_psv->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_psv = $params_psv->next_row();
		}
		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_ps->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_ps = $params_ps->next_row();
		}
		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pv = $params_pv->next_row();
		}
		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_p->appId, 'all', 'all', 'all');

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
			//get servername by serverid
			$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pcv = $params_pcv->next_row();
		}
		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$taskdata=$this->getNewuserguideData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
			$stepdata=$this->getNewuserguideStepData($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

			//任务分析-任务
			$paramsRow_t=$taskdata->first_row();
			for ($j=0; $j < $taskdata->num_rows() ; $j++) { 
				$data_task = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_t->tasktype,
					'task' => $paramsRow_t->task_name,
					'stepcount' => $paramsRow_t->stepcount,
					'taskactivecount' => $paramsRow_t->taskactivecount,
					'taskdonecount' => $paramsRow_t->taskdonecount,
					'taskdonerate' => ($paramsRow_t->taskactivecount==0)?0:($paramsRow_t->taskdonecount/$paramsRow_t->taskactivecount),
					'taskactiveuser' => $paramsRow_t->taskactiveuser,
					'taskstayuser' => $paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser,
					'taskstayrate' => ($paramsRow_t->taskactiveuser==0)?0:(($paramsRow_t->taskactiveuser-$paramsRow_t->taskpassuser)/$paramsRow_t->taskactiveuser),
					'taskpassuser' => $paramsRow_t->taskpassuser,
					'taskpassrate' => ($paramsRow_t->taskactiveuser==0)?0:($paramsRow_t->taskpassuser/$paramsRow_t->taskactiveuser)
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_task', $data_task);
				$paramsRow_t = $taskdata->next_row();
			}

			//任务分析-步骤
			$paramsRow_s=$stepdata->first_row();
			for ($s=0; $s < $stepdata->num_rows() ; $s++) {
				$TasknameAndStepname=$this->getTasknameAndStepname($paramsRow_s->taskid,$paramsRow_s->stepid);
				$data_step = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'tasktype' => $paramsRow_s->tasktype,
					'task' => $paramsRow_s->task_name,
					'step' => $paramsRow_s->stepid,
					'step_desc' => $TasknameAndStepname,
					'stepactiveuser' => $paramsRow_s->stepactiveuser,
					'steppassuser' => $paramsRow_s->steppassuser,
					'steppassrate' => ($paramsRow_s->stepactiveuser==0)?0:($paramsRow_s->steppassuser/$paramsRow_s->stepactiveuser),
					'stepstaycount' => $paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser,
					'stepstayrate' => ($paramsRow_s->stepactiveuser==0)?0:(($paramsRow_s->stepactiveuser-$paramsRow_s->steppassuser)/$paramsRow_s->stepactiveuser),
					'steptotalstayrate' => '0'
				);
				$dwdb->insert_or_update('razor_sum_basic_sa_taskanalysis_step', $data_step);
				$paramsRow_s = $stepdata->next_row();
			}
			$paramsRow_pc = $params_pc->next_row();
		}
	}
}
