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
 * Test Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Test extends CI_Controller
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
        $this->load->model('alert/sendemail', 'sendemail');
        $this->load->model('useranalysis/newusersmodel', 'newusers');
        $this->load->model('useranalysis/dauusersmodel', 'dauusers');
        $this->load->model('useranalysis/remaincountmodel', 'remaincount');
        $this->load->model('useranalysis/levelalymodel','levelaly');
        $this->load->model('payanaly/paydatamodel', 'paydata');
        $this->load->model('payanaly/payanalymodel','payanaly');
        $this->load->model('payanaly/paylevelmodel','paylevel');
        $this->load->model('payanaly/payintervalmodel','payinterval');
        $this->load->model('payanaly/firstpayanalymodel','firstpayanaly');
        $this->load->model('payanaly/newpaymodel','newpay');
        $this->load->model('payanaly/payratemodel','payrate');
        $this->load->model('payanaly/payrankmodel','payrank');
        $this->load->model('onlineanaly/dayonlinemodel', 'dayonline');
        $this->load->model('leaveanaly/leavecountmodel','leavecount');
        $this->load->model('leaveanaly/levelleavemodel','levelleave');
        $this->load->model('seeall/realtimeinfomodel','realtimeinfo');
        $this->load->model('onlineanaly/starttimesmodel','starttimes');
        $this->load->model('onlineanaly/avgtimeorcountmodel','avgtimeorcount');
        $this->load->model('onlineanaly/borderintervaltimemodel','borderintervaltime');
        $this->load->model('onlineanaly/frequencytimemodel','frequencytime');
        $this->load->model('sysanaly/taskanalysismodel','taskanalysis');
        $this->load->model('sysanaly/tollgateanalysismodel','tollgateanalysis');
        $this->load->model('sysanaly/virtualmoneyanalysismodel','virtualmoneyanalysis');
        $this->load->model('sysanaly/functionanalysismodel','functionanalysis');
        $this->load->model('seeall/reportmodel','report');
        $this->load->model('common');
        $this->load->model('useranalysis/newuserprogressmodel','newuserprogress');  
        $this->load->model('exceptionanaly/errorcodeanalymodel','errorcodeanaly');   
        $this->load->model('sysanaly/newserveractivitymodel','newserveractivity');
        $this->load->model('sysanaly/operatingactivitymodel','operatingactivity');
        $this->load->model('useranalysis/viprolemodel','viprole'); 
        $this->load->model('useranalysis/vipremainmodel','vipremain'); 
        $this->load->model('channelanaly/userltvmodel','userltv');
        $this->load->model('seeall/seeallmodel','seeall');
        $this->load->model('useranalysis/newcreaterolemodel','newcreaterole'); 
        $this->load->model('channelanaly/channelimportamountmodel','channelimportamount'); 
        $this->load->model('channelanaly/channelqualitymodel','channelquality'); 
        $this->load->model('channelanaly/channelincomemodel','channelincome'); 
        $this->load->model('sysanaly/propanalymodel','propanaly');
        $this->load->model('product/productarchivemodel','productarchive');
        

        
    }

    function excute()
    {
        set_time_limit(0);
        $countdate=date("Y-m-d",strtotime("-1 day"));

        // remain count        
        for($d=1;$d<20;$d++){
            $date31=date("Y-m-d",strtotime("-$d day"));

            // sum_basic_time_count_avg
            $this->avgtimeorcount->sum_basic_time_count_avg($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_time_count_avg at $logdate and date = $date31");

        }

    }

}
