<?php
set_time_limit(0);
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
class Payanalymodel extends CI_Model
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
                $this->load->model("payanaly/paydatamodel", 'paydata');
                $this->load->model("useranalysis/dauusersmodel", 'dauusers');
                $this->load->model('common');
	}
	//付费金额
	function getPaymoneyByDay($fromTime,$toTime,$channel,$version,$server,$date)
	{
		$list = array();
		$query = $this->getPaymoney($fromTime,$toTime,$channel,$version,$server,$date);
		$PaymoneyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['payfield'] = $PaymoneyRow->payfield;
			$fRow['payusers'] = $PaymoneyRow->payusers;
			$PaymoneyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getPaymoney($fromTime,$toTime,$channel,$version,$server,$date)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(payfield, 0) payfield,
					IFNULL(SUM(payusers), 0) payusers
				FROM
					".$dwdb->dbprefix('sum_basic_payanaly_payfield')." 
				WHERE
				enddate_sk <= '" . $toTime . "'
				AND product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND type = '" . $date . "'
				GROUP BY payfield
				ORDER BY rid asc";
		$query = $dwdb->query($sql);
		return $query;
	}
	//付费次数
	function getPaycountByDay($fromTime,$toTime,$channel,$version,$server,$date)
	{
		$list = array();
		$query = $this->getPaycount($fromTime,$toTime,$channel,$version,$server,$date);
		$PaymoneyRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['paycount'] = $PaymoneyRow->paycount;
			$fRow['payusers'] = $PaymoneyRow->payusers;
			$PaymoneyRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getPaycount($fromTime,$toTime,$channel,$version,$server,$date)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(paycount, 0) paycount,
					IFNULL(SUM(payusers), 0) payusers
				FROM
					".$dwdb->dbprefix('sum_basic_payanaly_paycount')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND type = '" . $date . "'
				Group By paycount
				Order By rid";
		$query = $dwdb->query($sql);
		return $query;
	}
	//日ARPU
	function getPayARPUdayByDay($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->getPayARPUday($fromTime,$toTime,$channel,$version,$server);
		$PayARPUdayRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $PayARPUdayRow->startdate_sk;
			$fRow['dayarpu'] = $PayARPUdayRow->dayarpu;
			$fRow['dayarppu'] = $PayARPUdayRow->dayarppu;
			$PayARPUdayRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getPayARPUday($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(dayarpu, 0) dayarpu,
					IFNULL(dayarppu, 0) dayarppu
				FROM
					".$dwdb->dbprefix('sum_basic_payanaly_arpu_daily')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND (startdate_sk >= '".$fromTime."' OR enddate_sk <= '".$toTime."')
				Order By startdate_sk DESC";
		$query = $dwdb->query($sql);
		return $query;
	}
	//月ARPU
	function getPayARPUmonthByDay($fromTime,$toTime,$channel,$version,$server)
	{
		$list = array();
		$query = $this->getPayARPUmonth($fromTime,$toTime,$channel,$version,$server);
		$PayARPUmonthRow = $query->first_row();
		for ($i=0;$i<$query->num_rows();$i++) {
			$fRow = array();
			$fRow['startdate_sk'] = $PayARPUmonthRow->startdate_sk;
			$fRow['montharpu'] = $PayARPUmonthRow->montharpu;
			$fRow['montharppu'] = $PayARPUmonthRow->montharppu;
			$PayARPUmonthRow = $query->next_row();
			array_push($list, $fRow);
		}
		return $list;
	}
	function getPayARPUmonth($fromTime,$toTime,$channel,$version,$server)
	{
		$currentProduct = $this->common->getCurrentProduct();
		$productId= $currentProduct->id;
		$dwdb = $this->load->database('dw', true);
		($channel != 'all')?$channel_list = $this->unescape(implode("','", $channel)):$channel_list = 'all';
		($version != 'all')?$version_list = $this->unescape(implode("','", $version)):$version_list = 'all';
		($server != 'all')?$server_list = $this->unescape(implode("','", $server)):$server_list = 'all';
		$sql = "SELECT
					IFNULL(startdate_sk, 0) startdate_sk,
					IFNULL(montharpu, 0) montharpu,
					IFNULL(montharppu, 0) montharppu
				FROM
					".$dwdb->dbprefix('sum_basic_payanaly_arpu_monthly')."
				WHERE
				product_id = $productId
				AND channel_name in('" . $channel_list . "')
				AND version_name in('" . $version_list . "')
				AND server_name in('" . $server_list . "')
				AND (startdate_sk >= '".$fromTime."' OR enddate_sk <= '".$toTime."')
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
    function getPayusers($appid, $channelid, $serverid, $versionname,$startdate,$enddate,$afrom,$ato,$cfrom,$cto) {
        if ($channelid == 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    #AND pac.chId = '$channelid'
                    AND pac.srvId = '$serverid'
                    AND pac.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    #AND pac.chId = '$channelid'
                    AND pac.srvId = '$serverid'
                    #AND pac.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    #AND pac.chId = '$channelid'
                    #AND pac.srvId = '$serverid'
                    AND pac.version = '$versionname';";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    #AND pac.chId = '$channelid'
                    #AND pac.srvId = '$serverid'
                    #AND pac.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    AND pac.chId = '$channelid'
                    AND pac.srvId = '$serverid'
                    AND pac.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid <> 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    AND pac.chId = '$channelid'
                    AND pac.srvId = '$serverid'
                    #AND pac.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname <> 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    AND pac.chId = '$channelid'
                    #AND pac.srvId = '$serverid'
                    AND pac.version = '$versionname';";
        } elseif ($channelid <> 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_amount >= '$afrom'
                                    AND pac.pay_amount <= '$ato' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) payamountusers,
                            IFNULL(SUM(
                                    CASE
                                    WHEN pac.pay_count >= '$cfrom'
                                    AND pac.pay_count <= '$cto' THEN
                                            1
                                    ELSE
                                            0
                                    END
                            ),0) paycountusers
                    FROM
                            VIEW_razor_pay_amount_count pac
                    WHERE
                            pac.pay_date >= '$startdate'
                    AND pac.pay_date <= '$enddate'
                    AND pac.appId = '$appid'
                    AND pac.chId = '$channelid'
                    #AND pac.srvId = '$serverid'
                    #AND pac.version = '$versionname';";
        }
        $query = $this->db->query($sql);
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> row_array();
        }
    }
    
    
    /**
     * Sum_basic_paydata function
     * count pay data
     * 
     * 
     */
    function sum_basic_payanaly($countdate) {
    	//得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
        	// 1-9，10-49，50-99，100-199，200-499，500-999，1000-1999，2000-4999，5000及以上
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_1_9_week['payamountusers'],
                ////'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_10_49_week['payamountusers'],
                ////'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_50_99_week['payamountusers'],
                ////'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_100_199_week['payamountusers'],
                ////'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_200_499_week['payamountusers'],
                ////'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_500_999_week['payamountusers'],
                ////'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                ////'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                ////'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
//                ////'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                ////'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
//                ////'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                ////'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                //'monthpayusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_4999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcv = $params_pcv->next_row();
        }    
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //by payamount
            $payuser_1_9_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,1,9,0,0);
            $payuser_10_49_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,10,49,0,0);
            $payuser_50_99_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,50,99,0,0);
            $payuser_100_199_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,100,199,0,0);
            $payuser_200_499_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,200,499,0,0);
            $payuser_500_999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,500,999,0,0);
            $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,1000,1999,0,0);
            $payuser_2000_4999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,2000,4999,0,0);
            $payuser_5000_above_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,1,1);
            $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,2,2);
            $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,3,3);
            $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,4,4);
            $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,5,5);
            $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,6,6);
            $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,7,7);
            $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,8,8);
            $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,9,9);
            $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,10,10);
            $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,11,20);
            $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,21,30);
            $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,31,40);
            $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,41,50);
            $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,51,100);
            $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '10-49',
                'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '50-99',
                'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '100-199',
                'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '200-499',
                'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '500-999',
                'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '1000-1999',
                'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '2000-4999',
                'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'payfield' => '5000及以上',
                'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '1',
                'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '2',
                'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '3',
                'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '4',
                'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '5',
                'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '6',
                'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '7',
                'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '8',
                'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '9',
                'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '10',
                'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '11-20',
                'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '21-30',
                'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '31-40',
                'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '41-50',
                'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '51-100',
                'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'day',
                'paycount' => '100及以上',
                'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

        /**
     * Sum_basic_paydata function
     * count pay data
     * 
     * 
     */
    function sum_basic_payanaly_week($countdate) {
    	//得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
        	// 1-9，10-49，50-99，100-199，200-499，500-999，1000-1999，2000-4999，5000及以上
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcv = $params_pcv->next_row();
        }    
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //by payamount
            //$payuser_1_9_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,1,9,0,0);
            //$payuser_10_49_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,10,49,0,0);
            //$payuser_50_99_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,50,99,0,0);
            //$payuser_100_199_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,100,199,0,0);
            //$payuser_200_499_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,200,499,0,0);
            //$payuser_500_999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,500,999,0,0);
            //$payuser_1000_1999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,1000,1999,0,0);
            //$payuser_5000_above_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,5000,100000000,0,0);

            $payuser_1_9_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,1,9,0,0);
            $payuser_10_49_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,10,49,0,0);
            $payuser_50_99_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,50,99,0,0);
            $payuser_100_199_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,100,199,0,0);
            $payuser_200_499_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,200,499,0,0);
            $payuser_500_999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,500,999,0,0);
            $payuser_1000_1999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,1000,1999,0,0);
            $payuser_2000_4999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,2000,4999,0,0);
            $payuser_5000_above_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            //$payuser_1_9_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            //$payuser_10_49_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            //$payuser_50_99_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            //$payuser_100_199_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            //$payuser_200_499_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            //$payuser_500_999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            //$payuser_1000_1999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            //$payuser_5000_above_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            //$payuser_1_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,1,1);
            //$payuser_2_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,2,2);
            //$payuser_3_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,3,3);
            //$payuser_4_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,4,4);
            //$payuser_5_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,5,5);
            //$payuser_6_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,6,6);
            //$payuser_7_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,7,7);
            //$payuser_8_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,8,8);
            //$payuser_9_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,9,9);
            //$payuser_10_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,10,10);
            //$payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,11,20);
            //$payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,21,30);
            //$payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,31,40);
            //$payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,41,50);
            //$payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,51,100);
            //$payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,101,100000000);

            $payuser_1_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,1,1);
            $payuser_2_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,2,2);
            $payuser_3_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,3,3);
            $payuser_4_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,4,4);
            $payuser_5_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,5,5);
            $payuser_6_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,6,6);
            $payuser_7_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,7,7);
            $payuser_8_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,8,8);
            $payuser_9_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,9,9);
            $payuser_10_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,10,10);
            $payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,11,20);
            $payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,21,30);
            $payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,31,40);
            $payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,41,50);
            $payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,51,100);
            $payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,101,100000000);

            //$payuser_1_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            //$payuser_2_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            //$payuser_3_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            //$payuser_4_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            //$payuser_5_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            //$payuser_6_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            //$payuser_7_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            //$payuser_8_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            //$payuser_9_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            //$payuser_10_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            //$payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            //$payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            //$payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            //$payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            //$payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            //$payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                'payusers' => $payuser_1_9_week['payamountusers']//,
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                'payusers' => $payuser_10_49_week['payamountusers']//,
                //'monthpayusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                'payusers' => $payuser_50_99_week['payamountusers']//,
                //'monthpayusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                'payusers' => $payuser_100_199_week['payamountusers']//,
                //'monthpayusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                'payusers' => $payuser_200_499_week['payamountusers']//,
                //'monthpayusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                'payusers' => $payuser_500_999_week['payamountusers']//,
                //'monthpayusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                'payusers' => $payuser_1000_1999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                'payusers' => $payuser_2000_4999_week['payamountusers']//,
                //'monthpayusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                'payusers' => $payuser_5000_above_week['payamountusers']//,
                //'monthpayusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                'payusers' => $payuser_1_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                'payusers' => $payuser_2_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                'payusers' => $payuser_3_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                'payusers' => $payuser_4_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                'payusers' => $payuser_5_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                'payusers' => $payuser_6_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                'payusers' => $payuser_7_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                'payusers' => $payuser_8_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                'payusers' => $payuser_9_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                'payusers' => $payuser_10_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                'payusers' => $payuser_11_20_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                'payusers' => $payuser_21_30_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                'payusers' => $payuser_31_40_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                'payusers' => $payuser_41_50_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_199_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $last_monday,
                'enddate_sk' => $last_sunday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'week',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                'payusers' => $payuser_100_above_week_bycount['paycountusers']//,
                //'monthpayusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

        /**
     * Sum_basic_paydata function
     * count pay data
     * 
     * 
     */
    function sum_basic_payanaly_month($countdate) {
    	//得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
        	// 1-9，10-49，50-99，100-199，200-499，500-999，1000-1999，2000-4999，5000及以上
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$countdate,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version,$lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            $data_byamount_payuser_1_9 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'day',
                'payfield' => '1-9',
                'payusers' => $payuser_1_9_month['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                //'monthpayusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$countdate,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all',$lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
                        $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_p->appId, 'all', 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
                        $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
                        $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version, $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pcv = $params_pcv->next_row();
        }    
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //by payamount
            // $payuser_1_9_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,1,9,0,0);
            // $payuser_10_49_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,10,49,0,0);
            // $payuser_50_99_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,50,99,0,0);
            // $payuser_100_199_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,100,199,0,0);
            // $payuser_200_499_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,200,499,0,0);
            // $payuser_500_999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,500,999,0,0);
            // $payuser_1000_1999_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,1000,1999,0,0);
            // $payuser_5000_above_day=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,5000,100000000,0,0);

            //$payuser_1_9_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,1,9,0,0);
            //$payuser_10_49_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,10,49,0,0);
            //$payuser_50_99_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,50,99,0,0);
            //$payuser_100_199_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,100,199,0,0);
            //$payuser_200_499_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,200,499,0,0);
            //$payuser_500_999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,500,999,0,0);
            //$payuser_1000_1999_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,1000,1999,0,0);
            //$payuser_5000_above_week=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,5000,100000000,0,0);

            $payuser_1_9_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1,9,0,0);
            $payuser_10_49_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,10,49,0,0);
            $payuser_50_99_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,50,99,0,0);
            $payuser_100_199_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,100,199,0,0);
            $payuser_200_499_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,200,499,0,0);
            $payuser_500_999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,500,999,0,0);
            $payuser_1000_1999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,1000,1999,0,0);
            $payuser_2000_4999_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,2000,4999,0,0);
            $payuser_5000_above_month=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,5000,100000000,0,0);

            //by paycount
            // $payuser_1_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,1,1);
            // $payuser_2_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,2,2);
            // $payuser_3_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,3,3);
            // $payuser_4_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,4,4);
            // $payuser_5_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,5,5);
            // $payuser_6_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,6,6);
            // $payuser_7_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,7,7);
            // $payuser_8_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,8,8);
            // $payuser_9_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,9,9);
            // $payuser_10_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,10,10);
            // $payuser_11_20_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,11,20);
            // $payuser_21_30_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,21,30);
            // $payuser_31_40_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,31,40);
            // $payuser_41_50_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,41,50);
            // $payuser_100_199_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,51,100);
            // $payuser_100_above_day_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $countdate ,$countdate,0,0,101,100000000);

            //$payuser_1_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,1,1);
            //$payuser_2_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,2,2);
            //$payuser_3_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,3,3);
            //$payuser_4_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,4,4);
            //$payuser_5_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,5,5);
            //$payuser_6_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,6,6);
            //$payuser_7_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,7,7);
            //$payuser_8_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,8,8);
            //$payuser_9_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,9,9);
            //$payuser_10_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,10,10);
            //$payuser_11_20_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,11,20);
            //$payuser_21_30_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,21,30);
            //$payuser_31_40_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,31,40);
            //$payuser_41_50_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,41,50);
            //$payuser_100_199_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,51,100);
            //$payuser_100_above_week_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $last_monday,$last_sunday,0,0,101,100000000);

            $payuser_1_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,1,1);
            $payuser_2_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,2,2);
            $payuser_3_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,3,3);
            $payuser_4_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,4,4);
            $payuser_5_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,5,5);
            $payuser_6_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,6,6);
            $payuser_7_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,7,7);
            $payuser_8_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,8,8);
            $payuser_9_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,9,9);
            $payuser_10_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,10,10);
            $payuser_11_20_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,11,20);
            $payuser_21_30_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,21,30);
            $payuser_31_40_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,31,40);
            $payuser_41_50_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,41,50);
            $payuser_100_199_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,51,100);
            $payuser_100_above_month_bycount=  $this->getPayusers($paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', $lastmonth_firstday,$lastmonth_lastday,0,0,101,100000000);
			
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data_byamount_payuser_1_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1-9',
                //'payusers' => $payuser_1_9_day['payamountusers']//,
                //'weekpayusers' => $payuser_1_9_week['payamountusers'],
                'payusers' => $payuser_1_9_month['payamountusers']
            );
            $data_byamount_payuser_10_49 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '10-49',
                //'payusers' => $payuser_10_49_day['payamountusers']//,
                //'weekpayusers' => $payuser_10_49_week['payamountusers'],
                'payusers' => $payuser_10_49_month['payamountusers']
            );
            $data_byamount_payuser_50_99 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '50-99',
                //'payusers' => $payuser_50_99_day['payamountusers']//,
                //'weekpayusers' => $payuser_50_99_week['payamountusers'],
                'payusers' => $payuser_50_99_month['payamountusers']
            );
            $data_byamount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '100-199',
                //'payusers' => $payuser_100_199_day['payamountusers']//,
                //'weekpayusers' => $payuser_100_199_week['payamountusers'],
                'payusers' => $payuser_100_199_month['payamountusers']
            );
            $data_byamount_payuser_200_499 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '200-499',
                //'payusers' => $payuser_200_499_day['payamountusers']//,
                //'weekpayusers' => $payuser_200_499_week['payamountusers'],
                'payusers' => $payuser_200_499_month['payamountusers']
            );
            $data_byamount_payuser_500_999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '500-999',
                //'payusers' => $payuser_500_999_day['payamountusers']//,
                //'weekpayusers' => $payuser_500_999_week['payamountusers'],
                'payusers' => $payuser_500_999_month['payamountusers']
            );
            $data_byamount_payuser_1000_1999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '1000-1999',
                //'payusers' => $payuser_1000_1999_day['payamountusers']//,
                //'weekpayusers' => $payuser_1000_1999_week['payamountusers'],
                'payusers' => $payuser_1000_1999_month['payamountusers']
            );
            $data_byamount_payuser_2000_4999 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '2000-4999',
                //'payusers' => $payuser_2000_4999_day['payamountusers']//,
                //'weekpayusers' => $payuser_2000_4999_week['payamountusers'],
                'payusers' => $payuser_2000_4999_month['payamountusers']
            );
            $data_byamount_payuser_5000_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'payfield' => '5000及以上',
                //'payusers' => $payuser_5000_above_day['payamountusers']//,
                //'weekpayusers' => $payuser_5000_above_week['payamountusers'],
                'payusers' => $payuser_5000_above_month['payamountusers']
            );
            $data_bycount_payuser_1 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '1',
                //'payusers' => $payuser_1_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_1_week_bycount['paycountusers'],
                'payusers' => $payuser_1_month_bycount['paycountusers']
            );
            $data_bycount_payuser_2 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '2',
                //'payusers' => $payuser_2_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_2_week_bycount['paycountusers'],
                'payusers' => $payuser_2_month_bycount['paycountusers']
            );
            $data_bycount_payuser_3 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '3',
                //'payusers' => $payuser_3_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_3_week_bycount['paycountusers'],
                'payusers' => $payuser_3_month_bycount['paycountusers']
            );
            $data_bycount_payuser_4 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '4',
                //'payusers' => $payuser_4_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_4_week_bycount['paycountusers'],
                'payusers' => $payuser_4_month_bycount['paycountusers']
            );
            $data_bycount_payuser_5 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '5',
                //'payusers' => $payuser_5_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_5_week_bycount['paycountusers'],
                'payusers' => $payuser_5_month_bycount['paycountusers']
            );
            $data_bycount_payuser_6 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '6',
                //'payusers' => $payuser_6_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_6_week_bycount['paycountusers'],
                'payusers' => $payuser_6_month_bycount['paycountusers']
            );
            $data_bycount_payuser_7 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '7',
                //'payusers' => $payuser_7_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_7_week_bycount['paycountusers'],
                'payusers' => $payuser_7_month_bycount['paycountusers']
            );
            $data_bycount_payuser_8 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '8',
                //'payusers' => $payuser_8_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_8_week_bycount['paycountusers'],
                'payusers' => $payuser_8_month_bycount['paycountusers']
            );
            $data_bycount_payuser_9 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '9',
                //'payusers' => $payuser_9_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_9_week_bycount['paycountusers'],
                'payusers' => $payuser_9_month_bycount['paycountusers']
            );
            $data_bycount_payuser_10 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '10',
                //'payusers' => $payuser_10_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_10_week_bycount['paycountusers'],
                'payusers' => $payuser_10_month_bycount['paycountusers']
            );
            $data_bycount_payuser_11_20 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '11-20',
                //'payusers' => $payuser_11_20_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_11_20_week_bycount['paycountusers'],
                'payusers' => $payuser_11_20_month_bycount['paycountusers']
            );
            $data_bycount_payuser_21_30 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '21-30',
                //'payusers' => $payuser_21_30_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_21_30_week_bycount['paycountusers'],
                'payusers' => $payuser_21_30_month_bycount['paycountusers']
            );
            $data_bycount_payuser_31_40 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '31-40',
                //'payusers' => $payuser_31_40_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_31_40_week_bycount['paycountusers'],
                'payusers' => $payuser_31_40_month_bycount['paycountusers']
            );
            $data_bycount_payuser_41_50 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '41-50',
                //'payusers' => $payuser_41_50_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_41_50_week_bycount['paycountusers'],
                'payusers' => $payuser_41_50_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_199 = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '51-100',
                //'payusers' => $payuser_100_199_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_199_week_bycount['paycountusers'],
                'payusers' => $payuser_100_199_month_bycount['paycountusers']
            );
            $data_bycount_payuser_100_above = array(
				'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'type' => 'month',
                'paycount' => '100及以上',
                //'payusers' => $payuser_100_above_day_bycount['paycountusers']//,
                //'weekpayusers' => $payuser_100_above_week_bycount['paycountusers'],
                'payusers' => $payuser_100_above_month_bycount['paycountusers']
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_10_49);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_50_99);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_200_499);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_500_999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_1000_1999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_2000_4999);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_payfield', $data_byamount_payuser_5000_above);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_1);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_2);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_3);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_4);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_5);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_6);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_7);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_8);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_9);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_10);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_11_20);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_21_30);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_31_40);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_41_50);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_199);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_paycount', $data_bycount_payuser_100_above);
            $paramsRow_pc = $params_pc->next_row();
        }
    }
    
    /**
     * Sum_basic_paydata function
     * count pay data
     * 
     * 
     */
    function sum_basic_payanaly_arpu($countdate) {
            	//得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pc = $params_pc->next_row();
        }
    }


    /**
     * Sum_basic_paydata function
     * count pay data
     * 
     * 
     */
    function sum_basic_payanaly_arpu_month($countdate) {
    	//得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到本周星期一
        $last_monday=$this->common->this_monday(0,false);
        //得到本月一号
        $lastmonth_firstday=$this->common->month_firstday(0,false);
        //得到上周星期一
        $last_monday=$this->common->last_monday(0,false);
        //得到上周星期天
        $last_sunday=$this->common->last_sunday(0,false);
        //得到上月一号
        $lastmonth_firstday=$this->common->lastmonth_firstday(0,false);
        //得到上月最后一天
        $lastmonth_lastday=$this->common->lastmonth_lastday(0,false);
        $dwdb = $this->load->database('dw', true);

        $params_psv = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_psv->appId,
                'version_name' => $paramsRow_psv->version,
                'channel_name' => 'all',
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_ps->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pv->appId,
                'version_name' => $paramsRow_pv->version,
                'channel_name' => 'all',
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_p->appId, 'all', 'all', 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_p->appId, 'all', 'all', 'all');

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_p->appId,
                'version_name' => 'all',
                'channel_name' => 'all',
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcsv->appId,
                'version_name' => $paramsRow_pcsv->version,
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcs->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => $server_name,
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pcv->appId,
                'version_name' => $paramsRow_pcv->version,
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pcv = $params_pcv->next_row();
        }
        
        $params_pc = $this->product-> getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $day_payamount=$this->paydata->getPayamount($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $day_dauuser=$this->dauusers->getDauuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $day_payuser=$this->dauusers->getPayuser($countdate,$countdate,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $month_payamount=$this->paydata->getPayamount($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $month_dauuser=$this->dauusers->getDauuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $month_payuser=$this->dauusers->getPayuser($lastmonth_firstday,$lastmonth_lastday,$paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            
			//get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            //日ARPU=当日充值总额度/当日活跃用户数量
            //日ARPPU=当日充值总额度/当日付费用户数量
            $data_day = array(
                'startdate_sk' => $countdate,
                'enddate_sk' => $countdate,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'dayarpu' => ($day_dauuser==0)?0:($day_payamount/$day_dauuser),
                'dayarppu' => ($day_payuser==0)?0:($day_payamount/$day_payuser)
            );
            //月ARPU=当自然月充值总额度/当月活跃用户数量
            //月ARPPU=当自然月充值总额度/当月付费用户数量
            $data_month = array(
                'startdate_sk' => $lastmonth_firstday,
                'enddate_sk' => $lastmonth_lastday,
                'product_id' => $paramsRow_pc->appId,
                'version_name' => 'all',
                'channel_name' => $channel_name,
                'server_name' => 'all',
                'montharpu' => ($month_dauuser==0)?0:($month_payamount/$month_dauuser),
                'montharppu' => ($month_payuser==0)?0:($month_payamount/$month_payuser)
            );
            // $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_daily', $data_day);
            $dwdb->insert_or_update('razor_sum_basic_payanaly_arpu_monthly', $data_month);
            $paramsRow_pc = $params_pc->next_row();
        }
    }
}
