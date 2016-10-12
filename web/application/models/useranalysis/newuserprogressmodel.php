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
 * Newuserprogressmodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Newuserprogressmodel extends CI_Model {

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

	function getNewuserProgressData($fromTime, $toTime, $channel, $server, $version) {
		$list = array();
		$query = $this->NewuserProgressData($fromTime, $toTime, $channel, $server, $version);
		$dauUsersRow = $query->first_row();
		for ($i = 0; $i < $query->num_rows(); $i++) {
			$fRow = array();
			$fRow["startdate_sk"] = $dauUsersRow->startdate_sk;
			$fRow['step'] = $dauUsersRow->step;
			$fRow['stepname'] = $dauUsersRow->stepname;
			$fRow['stepcount'] = $dauUsersRow->stepcount;
			$dauUsersRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function NewuserProgressData($fromTime, $toTime, $channel, $version, $server) {
		$currentProduct = $this->common->getCurrentProduct();
		$productId = $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
		($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
		($server != 'all') ? $server_list = $this->unescape(implode("','", $server)) : $server_list = 'all';
		$sql = "SELECT
			IFNULL(startdate_sk, 0) startdate_sk,
			IFNULL(step, 0) step,
			IFNULL(stepname, 0) stepname,
			IFNULL(stepcount, 0) stepcount
			FROM
				" . $dwdb->dbprefix('sum_basic_newuserprogress') . "
			WHERE
			startdate_sk >= '" . $fromTime . "'
			AND startdate_sk <= '" . $toTime . "'
			AND channel_name in('" . $channel_list . "')
			AND version_name in('" . $version_list . "')
			AND server_name in('" . $server_list . "')
			AND product_id = '" . $productId . "'
			Order By startdate_sk DESC";
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
     * GetNewuserprogress function
     * get newuserprogress count
     * 
     * @param Date $fromdate
     * @param Date $todate
     * @param String $appid
     * @param String $channelid
     * @param String $versionname
     * 
     * @return Int newuserprogress count
     */
    function getNewuserprogress($fromdate,$todate, $appid, $channelid='all', $versionname='all') {
        if ($channelid<>'all' && $versionname<>'all') {
            $sql = "SELECT
						mn1.newuserprogress_id newuserprogress_id,
						mn1.newuserprogress_name newuserprogress_name,
						IFNULL(np1.stepcount, 0) stepcount
					FROM
						(
							SELECT
								newuserprogressid,
								COUNT(1) stepcount
							FROM
								razor_newuserprogress np
							WHERE
								np.newuserprogress_date >= '$fromdate'
							AND np.newuserprogress_date <= '$todate'
							AND np.appId = '$appid'
							AND np.chId = '$channelid'
							AND np.version = '$versionname'
							GROUP BY
								newuserprogressid
						) np1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_newuserprogress mn
						WHERE
							mn.app_id = '$appid'
					) mn1 ON np1.newuserprogressid = mn1.newuserprogress_id
					ORDER BY
						mn1.newuserprogress_id;";
        } elseif ($channelid<>'all' && $versionname=='all') {
            $sql = "SELECT
						mn1.newuserprogress_id newuserprogress_id,
						mn1.newuserprogress_name newuserprogress_name,
						IFNULL(np1.stepcount, 0) stepcount
					FROM
						(
							SELECT
								newuserprogressid,
								COUNT(1) stepcount
							FROM
								razor_newuserprogress np
							WHERE
								np.newuserprogress_date >= '$fromdate'
							AND np.newuserprogress_date <= '$todate'
							AND np.appId = '$appid'
							AND np.chId = '$channelid'
							#AND np.version = '$versionname'
							GROUP BY
								newuserprogressid
						) np1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_newuserprogress mn
						WHERE
							mn.app_id = '$appid'
					) mn1 ON np1.newuserprogressid = mn1.newuserprogress_id
					ORDER BY
						mn1.newuserprogress_id;";
        } elseif ($channelid=='all' && $versionname=='all') {
            $sql = "SELECT
						mn1.newuserprogress_id newuserprogress_id,
						mn1.newuserprogress_name newuserprogress_name,
						IFNULL(np1.stepcount, 0) stepcount
					FROM
						(
							SELECT
								newuserprogressid,
								COUNT(1) stepcount
							FROM
								razor_newuserprogress np
							WHERE
								np.newuserprogress_date >= '$fromdate'
							AND np.newuserprogress_date <= '$todate'
							AND np.appId = '$appid'
							#AND np.chId = '$channelid'
							#AND np.version = '$versionname'
							GROUP BY
								newuserprogressid
						) np1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_newuserprogress mn
						WHERE
							mn.app_id = '$appid'
					) mn1 ON np1.newuserprogressid = mn1.newuserprogress_id
					ORDER BY
						mn1.newuserprogress_id;";
        } elseif ($channelid=='all' && $versionname<>'all') {
            $sql = "SELECT
						mn1.newuserprogress_id newuserprogress_id,
						mn1.newuserprogress_name newuserprogress_name,
						IFNULL(np1.stepcount, 0) stepcount
					FROM
						(
							SELECT
								newuserprogressid,
								COUNT(1) stepcount
							FROM
								razor_newuserprogress np
							WHERE
								np.newuserprogress_date >= '$fromdate'
							AND np.newuserprogress_date <= '$todate'
							AND np.appId = '$appid'
							#AND np.chId = '$channelid'
							AND np.version = '$versionname'
							GROUP BY
								newuserprogressid
						) np1
					RIGHT JOIN (
						SELECT
							*
						FROM
							razor_mainconfig_newuserprogress mn
						WHERE
							mn.app_id = '$appid'
					) mn1 ON np1.newuserprogressid = mn1.newuserprogress_id
					ORDER BY
						mn1.newuserprogress_id;";
        }
        $query = $this->db->query($sql);
        return $query;
    }
	 

    /**
     * sum_basic_newuserprogress function
     * count new users
     * 
     */
    function sum_basic_newuserprogress($countdate) {
        $dwdb = $this->load->database('dw', true);
        $paramspcv = $this->product->getProductChannelVersion();
        $paramspcvRow = $paramspcv->first_row();
        for ($i = 0; $i < $paramspcv->num_rows(); $i++) {
            $newuserprogresscount = $this->getNewuserprogress($countdate,$countdate, $paramspcvRow->appId, $paramspcvRow->chId, $paramspcvRow->version);
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcvRow->chId);

            $paramsRow_t=$newuserprogresscount->first_row();
			for ($j=0; $j < $newuserprogresscount->num_rows() ; $j++) { 
	            $data_newuserprogresscount = array(
	                'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramspcvRow->appId,
					'version_name' => $paramspcvRow->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'step' => $paramsRow_t->newuserprogress_id,
					'stepname' => $paramsRow_t->newuserprogress_name,
					'stepcount' => $paramsRow_t->stepcount
	            );
	            $dwdb->insert_or_update('razor_sum_basic_newuserprogress', $data_newuserprogresscount);
				$paramsRow_t = $newuserprogresscount->next_row();
        	}
            $paramspcvRow = $paramspcv->next_row();
        }

        $paramspv = $this->product->getProductVersionOffChannel();
        $paramspvRow = $paramspv->first_row();
        for ($i = 0; $i < $paramspv->num_rows(); $i++) {
            $newuserprogresscount = $this->getNewuserprogress($countdate,$countdate, $paramspvRow->appId,'all', $paramspvRow->version);

            $paramsRow_t=$newuserprogresscount->first_row();
			for ($j=0; $j < $newuserprogresscount->num_rows() ; $j++) { 
	            $data_newuserprogresscount = array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramspvRow->appId,
					'version_name' => $paramspvRow->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'step' => $paramsRow_t->newuserprogress_id,
					'stepname' => $paramsRow_t->newuserprogress_name,
					'stepcount' => $paramsRow_t->stepcount
	            );
	            $dwdb->insert_or_update('razor_sum_basic_newuserprogress', $data_newuserprogresscount);
				$paramsRow_t = $newuserprogresscount->next_row();
        	}
            $paramspvRow = $paramspv->next_row();
        }       

        $paramspc = $this->product->getProductChannelOffVersion();
        $paramspcRow = $paramspc->first_row();
        for ($i = 0; $i < $paramspc->num_rows(); $i++) {
            $newuserprogresscount = $this->getNewuserprogress($countdate,$countdate, $paramspcRow->appId, $paramspcRow->chId,'all');
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramspcRow->chId);
            $paramsRow_t=$newuserprogresscount->first_row();
			for ($j=0; $j < $newuserprogresscount->num_rows() ; $j++) { 
	            $data_newuserprogresscount = array(
	                'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramspcRow->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'step' => $paramsRow_t->newuserprogress_id,
					'stepname' => $paramsRow_t->newuserprogress_name,
					'stepcount' => $paramsRow_t->stepcount
	            );
	            $dwdb->insert_or_update('razor_sum_basic_newuserprogress', $data_newuserprogresscount);
				$paramsRow_t = $newuserprogresscount->next_row();
        	}
            $paramspcRow = $paramspc->next_row();
        }
        $paramsp = $this->product->getProductOffChannelVersion();
        $paramspRow = $paramsp->first_row();
        for ($i = 0; $i < $paramsp->num_rows(); $i++) {
            $newuserprogresscount = $this->getNewuserprogress($countdate,$countdate, $paramspRow->appId,'all','all');
            $paramsRow_t=$newuserprogresscount->first_row();
			for ($j=0; $j < $newuserprogresscount->num_rows() ; $j++) { 
	            $data_newuserprogresscount = array(
	                'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramspRow->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'step' => $paramsRow_t->newuserprogress_id,
					'stepname' => $paramsRow_t->newuserprogress_name,
					'stepcount' => $paramsRow_t->stepcount
	            );
	            $dwdb->insert_or_update('razor_sum_basic_newuserprogress', $data_newuserprogresscount);
				$paramsRow_t = $newuserprogresscount->next_row();
        	}
            $paramspRow = $paramsp->next_row();
        }
    }

}
