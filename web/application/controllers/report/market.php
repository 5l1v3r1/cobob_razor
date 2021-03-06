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
 * Market Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Market extends CI_Controller
{

    /**
     * Data array $data
     */
    private $data = array();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        $this->load->Model('common');
        $this->load->model('channelmodel', 'channel');
        $this->load->model('product/productmodel', 'product');
        $this->load->model('product/newusermodel', 'newusermodel');
        $this->common->requireLogin();
        $this->common->requireProduct();
    }

    /**
     * ViewMarket function
     * View market
     * 
     * @return void
     */
    function viewMarket()
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $data['productId'] = $productId;
        $today = date('Y-m-d', time());
        $count7 = date("w") + 7;
        $yestodayTime = date("Y-m-d", strtotime("-1 day"));
        //get the first day about last week
        $seven_day = date("Y-m-d", strtotime("-" . $count7 . " day"));
        $thirty_day = date("Y-m-d", strtotime("-1 month"));
        //get the first day about last month
        $thirty_day = substr($thirty_day, 0, 8) . '01';
        //get the weekactive
        $sevendayactive = $this->product->getActiveDays($seven_day, 0, $productId);
        $data['sevendayactive'] = $sevendayactive;
        //get the monthactive
        $thirty_day_active = $this->product->getActiveDays($thirty_day, 1, $productId);
        $data['thirty_day_active'] = $thirty_day_active;
        //get newuser|startuser|alluser and so on group by channel to today
        $todayData = $this->product->getAnalyzeDataByDateAndProductID($today, $productId);
        //get newuser|startuser|alluser and so on group by channel to yesterday
        $yestodayData = $this->product->getAnalyzeDataByDateAndProductID($yestodayTime, $productId);
        $data['count'] = $todayData->num_rows();
        $data['todayData'] = $todayData;
        $data['yestodayData'] = $yestodayData;
        $this->common->loadHeaderWithDateControl();
        $this->load->view('overview/productmarket', $data);
    }
    
    /**
     * Addchannelmarketreport function
     * Load channel market report
     * 
     * @param string $delete $delete = null
     * @param string $type   $type = null
     * 
     * @return void
     */
    function addchannelmarketreport($delete = null, $type = null)
    {
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $data['reportTitle'] = array('timePase' => getTimePhaseStr($fromTime, $toTime),'newUser' => getReportTitle(lang("v_rpt_mk_newUserStatistics") . " " . null, $fromTime, $toTime),'activeUser' => getReportTitle(lang("v_rpt_mk_activeuserS") . " " . null, $fromTime, $toTime),'Session' => getReportTitle(lang("v_rpt_mk_sessionS") . " " . null, $fromTime, $toTime),'avgUsageDuration' => getReportTitle(lang("t_averageUsageDuration") . " " . null, $fromTime, $toTime),'activeWeekly' => getReportTitle(lang("t_activeRateW") . " " . null, $fromTime, $toTime),'activeMonthly' => getReportTitle(lang("t_activeRateM") . " " . null, $fromTime, $toTime));
        if ($delete == null) {
            $data['add'] = "add";
        }
        if ($delete == "del") {
            $data['delete'] = "delete";
        }
        if ($type != null) {
            $data['type'] = $type;
        }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/channelmarket', $data);
    }

    /**
     * GetMarketData function
     * Get market data
     * 
     * @param string $type $type = ''
     * 
     * @return json
     */
    function getMarketData($type = '')
    {
        $product = $this->common->getCurrentProduct();
        $productId = $product->id;
        $fromTime = $this->common->getFromTime();
        $toTime = $this->common->getToTime();
        $markets = $this->product->getProductChanelById($productId);
        
        $ret = array();
        if ($markets != null && $markets->num_rows() > 0) {
            foreach ($markets->result() as $row) {
                if ($type == "monthrate") {
                    $data = $this->product->getActiveNumbers($productId, $fromTime, $toTime, 1);
                } else if ($type == "weekrate") {
                        $data = $this->product->getActiveNumbers($productId, $fromTime, $toTime, 0);
                } else {
                        $data = $this->product->getAllMarketData($row->channel_id, $fromTime, $toTime);
                }
            }
            if ($type == "monthrate" || $type == "weekrate") {
                if ($data == null || count($data) == 0) {
                    $content_arr['VersionIsNullActiveRate'] = array();
                    $tmp = array();
                    $tmp['percent'] = 0;
                    $tmp['datevalue'] = "0000-00-00 00:00:00";
                    array_push($content_arr['VersionIsNullActiveRate'], $tmp);
                    $ret['content'] = $content_arr;
                }
            }
        } else {
            $data = "";
        }
        $result = array();
        $result['dataList'] = $data;
        // load markevents
        $mark = array();
        $currentProduct = $this->common->getCurrentProduct();
        $this->load->model('point_mark', 'pointmark');
        $markevnets = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime)->result_array();
        $marklist = $this->pointmark->listPointviewtochart($this->common->getUserId(), $productId, $fromTime, $toTime, 'listcount');
        
        $result['markevents'] = $markevnets;
        if ($type == "weekrate") {
            
            $result['marklist'] = $this->product->getRateVersion($productId, $fromTime, $toTime, 0);
            $result['defdate'] = $this->product->getRatedate($productId, $fromTime, $toTime, 0);
        } else if ($type == "monthrate") {
                $result['marklist'] = $this->product->getRateVersion($productId, $fromTime, $toTime, 1);
                $result['defdate'] = $this->product->getRatedate($productId, $fromTime, $toTime, 1);
        } else {
                $result['marklist'] = $marklist;
                $result['defdate'] = $this->common->getDateList($fromTime, $toTime);
        }
        // end load markevents
        echo json_encode($result);
    }
}

?>