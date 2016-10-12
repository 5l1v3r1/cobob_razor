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
class Paylevelmodel extends CI_Model
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
		$this->load->model("useranalysis/levelalymodel",'levelaly');
	}

	function getpayleveldata($fromTime,$toTime,$channel,$server,$version)
	{
		$list = array();
		$query = $this->payleveldata($fromTime,$toTime,$channel,$server,$version);
		$paylevelRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['rolelevel'] = $paylevelRow->rolelevel;
			$fRow['payamount'] = $paylevelRow->payamount;
			$fRow['paycount'] = $paylevelRow->paycount;
			$paylevelRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}

	function payleveldata($fromTime,$toTime,$channel,$server,$version)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(rolelevel, 0) rolelevel,
					IFNULL(SUM(payamount), 0) payamount,
					IFNULL(SUM(paycount), 0) paycount
				FROM
					".$dwdb->dbprefix('sum_basic_paylevel')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND startdate_sk >=  ('" . $fromTime . "')
				AND enddate_sk <=  ('" . $toTime . "')
				GROUP BY product_id,channel_name,version_name,server_name,rolelevel";
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
	 * GetDauPayuser function
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
	function getPaydataByrolelevel($date, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all',$rolelevel=1) {
		if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					#AND p.chId = '$channelid'
					AND p.srvId = '$serverid'
					AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					#AND p.chId = '$channelid'
					AND p.srvId = '$serverid'
					AND p.version = '$versionname'
					#AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					#AND p.chId = '$channelid'
					#AND p.srvId = '$serverid'
					AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					#AND p.chId = '$channelid'
					#AND p.srvId = '$serverid'
					#AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					AND p.chId = '$channelid'
					AND p.srvId = '$serverid'
					AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					AND p.chId = '$channelid'
					AND p.srvId = '$serverid'
					#AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					AND p.chId = '$channelid'
					#AND p.srvId = '$serverid'
					AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		} elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
			$sql = "SELECT
							IFNULL(SUM(p.pay_amount), 0) pay_amount,
							COUNT(1) pay_count
					FROM
							razor_pay p
					WHERE
							p.pay_date = '$date'
					AND p.appId = '$appid'
					AND p.chId = '$channelid'
					#AND p.srvId = '$serverid'
					#AND p.version = '$versionname'
					AND p.roleLevel = '$rolelevel';";
		}
		$query = $this->db->query($sql);
		if ($query != null && $query -> num_rows() > 0) {
			return $query -> row_array();
		}
	}
	/**
	 * Sum_basic_payleve function
	 * count pay data
	 * 
	 * 
	 */
	function sum_basic_paylevel($countdate) {
		$dwdb = $this->load->database('dw', true);

		$params_psv = $this->product->getProductServerVersionOffChannel();
		$paramsRow_psv = $params_psv->first_row();
		for ($i = 0; $i < $params_psv->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_psv->appId);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
				${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$m);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_psv->srvId);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_psv->appId,
					'version_name' => $paramsRow_psv->version,
					'channel_name' => 'all',
					'server_name' => $server_name,
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_psv = $params_psv->next_row();
		}
		$params_ps = $this->product->getProductServerOffChannelVersion();
		$paramsRow_ps = $params_ps->first_row();
		for ($i = 0; $i < $params_ps->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_ps->appId);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
				${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$m);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_ps->srvId);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_ps->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => $server_name,
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_ps = $params_ps->next_row();
		}
		$params_pv = $this->product->getProductVersionOffChannelServer();
		$paramsRow_pv = $params_pv->first_row();
		for ($i = 0; $i < $params_pv->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pv->appId	);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version,$m);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pv->appId,
					'version_name' => $paramsRow_pv->version,
					'channel_name' => 'all',
					'server_name' => 'all',
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_pv = $params_pv->next_row();
		}
		$params_p = $this->product->getProductOffChannelServerVersion();
		$paramsRow_p = $params_p->first_row();
		for ($i = 0; $i < $params_p->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_p->appId	);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
					${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_p->appId, 'all', 'all', 'all',$m);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_p->appId,
					'version_name' => 'all',
					'channel_name' => 'all',
					'server_name' => 'all',
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_p = $params_p->next_row();
		}
		$params_pcsv = $this->product->getProductChannelServerVersion();
		$paramsRow_pcsv = $params_pcsv->first_row();
		for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcsv->appId);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
				${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version,$m);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcsv->appId,
					'version_name' => $paramsRow_pcsv->version,
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_pcsv = $params_pcsv->next_row();
		}
		$params_pcs = $this->product->getProductChannelServerOffVersion();
		$paramsRow_pcs = $params_pcs->first_row();
		for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcs->appId);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
				${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all',$m);
			//get channelname by channelid
			$channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
				//get servername by serverid
				$server_name = $this->server->getServernameById($paramsRow_pcs->srvId);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcs->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => $server_name,
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_pcs = $params_pcs->next_row();
		}
		$params_pcv = $this->product->getProductChannelVersionOffServer();
		$paramsRow_pcv = $params_pcv->first_row();
		for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pcv->appId);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
				${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version,$m);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pcv->appId,
					'version_name' => $paramsRow_pcv->version,
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_pcv = $params_pcv->next_row();
		}
		$params_pc = $this->product-> getProductChannelOffServerVersion();
		$paramsRow_pc = $params_pc->first_row();
		for ($i = 0; $i < $params_pc->num_rows(); $i++) {
			$maxlevel=$this->levelaly->getMaxlevel($paramsRow_pc->appId);
			for ($m=1; $m <$maxlevel+1 ; $m++) { 
				${'level_'.$m}=$this->getPaydataByrolelevel($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all',$m);
				//get channelname by channelid
				$channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);
				${'data_'.$m}= array(
					'startdate_sk' => $countdate,
					'enddate_sk' => $countdate,
					'product_id' => $paramsRow_pc->appId,
					'version_name' => 'all',
					'channel_name' => $channel_name,
					'server_name' => 'all',
					'rolelevel' => $m,
					'payamount' => ${'level_'.$m}['pay_amount'],
					'paycount' => ${'level_'.$m}['pay_count']
				);            
				$dwdb->insert_or_update('razor_sum_basic_paylevel', ${'data_'.$m});
			}
			$paramsRow_pc = $params_pc->next_row();
		}
		}
	}
