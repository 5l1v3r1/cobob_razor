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
 * Archive Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Archive extends CI_Controller
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

        // useranalysis
        $this->load->model('useranalysis/newusersmodel', 'newusers');
        $this->load->model('useranalysis/dauusersmodel', 'dauusers');
        $this->load->model('useranalysis/remaincountmodel', 'remaincount');
        $this->load->model('useranalysis/levelalymodel','levelaly');
        $this->load->model('useranalysis/newuserprogressmodel','newuserprogress');
        $this->load->model('useranalysis/viprolemodel','viprole');   
        $this->load->model('useranalysis/vipremainmodel','vipremain');  
        $this->load->model('useranalysis/newcreaterolemodel','newcreaterole'); 
        
        // payanaly
        $this->load->model('payanaly/paydatamodel', 'paydata');
        $this->load->model('payanaly/payanalymodel','payanaly');
        $this->load->model('payanaly/paylevelmodel','paylevel');
        $this->load->model('payanaly/payintervalmodel','payinterval');
        $this->load->model('payanaly/firstpayanalymodel','firstpayanaly');
        $this->load->model('payanaly/newpaymodel','newpay');
        $this->load->model('payanaly/payratemodel','payrate');
        $this->load->model('payanaly/payrankmodel','payrank');
        // onlineanaly
        $this->load->model('onlineanaly/dayonlinemodel', 'dayonline');
        $this->load->model('onlineanaly/starttimesmodel','starttimes');
        $this->load->model('onlineanaly/avgtimeorcountmodel','avgtimeorcount');
        $this->load->model('onlineanaly/borderintervaltimemodel','borderintervaltime');
        $this->load->model('onlineanaly/frequencytimemodel','frequencytime');
        // leaveanaly
        $this->load->model('leaveanaly/leavecountmodel','leavecount');
        $this->load->model('leaveanaly/levelleavemodel','levelleave');
        // seeall
        $this->load->model('seeall/realtimeinfomodel','realtimeinfo');
        // sysanaly
        $this->load->model('sysanaly/taskanalysismodel','taskanalysis');
        $this->load->model('sysanaly/tollgateanalysismodel','tollgateanalysis');
        $this->load->model('sysanaly/virtualmoneyanalysismodel','virtualmoneyanalysis');
        $this->load->model('sysanaly/functionanalysismodel','functionanalysis');
        $this->load->model('sysanaly/newserveractivitymodel','newserveractivity');
        $this->load->model('sysanaly/operatingactivitymodel','operatingactivity');
        $this->load->model('sysanaly/propanalymodel','propanaly');
        // exceptionanaly
        $this->load->model('exceptionanaly/errorcodeanalymodel','errorcodeanaly');
        // channelanaly
        $this->load->model('channelanaly/userltvmodel','userltv');
        $this->load->model('channelanaly/channelimportamountmodel','channelimportamount'); 
        $this->load->model('channelanaly/channelqualitymodel','channelquality'); 
        $this->load->model('channelanaly/channelincomemodel','channelincome'); 
        // productarchive
        $this->load->model('product/productarchivemodel','productarchive');
        
    }

    /**
     * ArchiveHourly function
     * Schedule hourly task to do the etl from production databse to data warehouse
     *
     * @return void
     */
    function archiveHourly()
    {
        set_time_limit(0);
        $countdate=date("Y-m-d",strtotime("-0 day"));

        // $this->realtimeinfo->sum_razor_realtimeroleinfo();
        // $logdate = date('Y-m-d H:i:s', time());
        // log_message("debug", "sum_razor_realtimeroleinfo at $logdate"); 
        
        $this->realtimeinfo->sum_basic_realtimeroleinfo();
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_realtimeroleinfo at $logdate"); 

        // sum_basic_newuserprogress
        $this->newuserprogress->sum_basic_newuserprogress($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_newuserprogress at $logdate and date = $countdate");
    }
     

    /**
     * ArchiveWeekly function
     * Schedule weekly task to do the statics,Caculate from sunday to sataday,Must scheduled at sunday.
     *
     * @return void
     */
    function archiveWeekly()
    {
        set_time_limit(0);
        $countdate=date("Y-m-d",strtotime("-0 day"));

        // sum_basic_time_count_avg
        $this->avgtimeorcount->sum_basic_time_count_avg_week($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_time_count_avg_week at $logdate");

        // sum_basic_payrate_week
        $this->payrate->sum_basic_payrate_week($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payrate_week at $logdate");

        // sum_basic_payanaly_week
        $this->payanaly->sum_basic_payanaly_week($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payanaly_week at $logdate");

        // sum_basic_customremain_weekly
        for($d=1;$d<31*7;$d++){
            $this->remaincount->sum_basic_customremain_weekly(date("Y-m-d",strtotime("-$d day")));
        }
        log_message("debug", "sum_basic_customremain_weekly at $logdate");
    }

    /**
     * ArchiveMonthly function
     * Schedule monthly task to do the statics
     *
     * @return void
     */
    function archiveMonthly()
    {
        set_time_limit(0);
        $countdate=date("Y-m-d",strtotime("-0 day"));

        //  sum_basic_time_count_avg
        $this->avgtimeorcount->sum_basic_time_count_avg_month($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_time_count_avg_month at $logdate");

        // sum_basic_payrate_month
        $this->payrate->sum_basic_payrate_month($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payrate_month at $logdate");

        // sum_basic_payanaly_month
        $this->payanaly->sum_basic_payanaly_month($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payanaly_month at $logdate");

        // sum_basic_payanaly_arpu_month
        $this->payanaly->sum_basic_payanaly_arpu_month($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payanaly_arpu_month at $logdate");

        // sum_basic_customremain_monthly
        for($d=1;$d<31*30;$d++){
            $this->remaincount->sum_basic_customremain_monthly(date("Y-m-d",strtotime("-$d day")));
        }
        log_message("debug", "sum_basic_customremain_monthly at $logdate");
    }

    /**
     * ArchiveUsingLog function
     * Schedule daily task for using log page views, yestoday
     *
     * @return void
     */
    function archiveUsingLog()
    {   
        set_time_limit(0);
        $countdate=date("Y-m-d",strtotime("-1 day"));

        $this->productarchive->excute();

        // sum_basic_newusers
        $this->newusers->sum_basic_newusers($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_newusers at $logdate and date = $countdate");
        
        // sum_basic_dauusers
        $this->dauusers->sum_basic_dauusers($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_dauusers at $logdate and date = $countdate");

        // sum_basic_newuserprogress
        $this->newuserprogress->sum_basic_newuserprogress($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_newuserprogress at $logdate and date = $countdate");
        
        // sum_basic_paydata
        $this->paydata->sum_basic_paydata($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_paydata at $logdate and date = $countdate");
        
        // sum_basic_payanaly
        $this->payanaly->sum_basic_payanaly($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payanaly at $logdate and date = $countdate");

        // sum_basic_payanaly_arpu
        $this->payanaly->sum_basic_payanaly_arpu($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payanaly_arpu at $logdate and date = $countdate");
        
        // sum_basic_paylevel
        $this->paylevel->sum_basic_paylevel($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_paylevel at $logdate and date = $countdate");
        
        // sum_basic_payinterval
        $this->payinterval->sum_basic_payinterval_first($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payinterval_first at $logdate and date = $countdate");

        // sum_basic_payinterval_second
        $this->payinterval->sum_basic_payinterval_second($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payinterval_second at $logdate and date = $countdate");

        // sum_basic_payinterval_third        
        $this->payinterval->sum_basic_payinterval_third($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payinterval_third at $logdate and date = $countdate");
        
        // sum_basic_firstpayanaly
        $this->firstpayanaly->sum_basic_firstpayanaly_time($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_firstpayanaly_time at $logdate and date = $countdate");

        // sum_basic_firstpayanaly_level
        $this->firstpayanaly->sum_basic_firstpayanaly_level($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_firstpayanaly_level at $logdate and date = $countdate");

        // sum_basic_firstpayanaly_amount
        $this->firstpayanaly->sum_basic_firstpayanaly_amount($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_firstpayanaly_amount at $logdate and date = $countdate");
        
        // sum_basic_payrate
        $this->payrate->sum_basic_payrate($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payrate at $logdate and date = $countdate");
        
        // sum_basic_payrank
        $this->payrank->sum_basic_payrank($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_payrank at $logdate and date = $countdate");
        
        // sum_basic_dayonline
        $this->dayonline->sum_basic_dayonline($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_dayonline at $logdate and date = $countdate");

        // sum_basic_leavecount_levelaly
        $this->leavecount->sum_basic_leavecount_levelaly($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_leavecount_levelaly at $logdate and date = $countdate");
        
        // sum_basic_levelleave
        $this->levelleave->sum_basic_levelleave($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_levelleave at $logdate and date = $countdate");
        
        // sum_basic_start_login
        $this->starttimes->sum_basic_start_login($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_start_login at $logdate and date = $countdate");
        
        // sum_basic_time_count_avg
        $this->avgtimeorcount->sum_basic_time_count_avg($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_time_count_avg at $logdate and date = $countdate");
        
        // sum_basic_borderintervaltime
        $this->borderintervaltime->sum_basic_borderintervaltime($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_borderintervaltime at $logdate and date = $countdate");

        // sum_basic_sa_tollgateanalysis
        $this->tollgateanalysis->sum_basic_sa_tollgateanalysis($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_sa_tollgateanalysis at $logdate and date = $countdate");

        // sum_basic_sa_virtualmoney
        $this->virtualmoneyanalysis->sum_basic_sa_virtualmoney($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_sa_virtualmoney at $logdate and date = $countdate");

        // sum_basic_sa_function
        $this->functionanalysis->sum_basic_sa_function($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_sa_function at $logdate and date = $countdate");     

        // sum_basic_frequencytime
        $this->frequencytime->sum_basic_frequencytime($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_frequencytime at $logdate and date = $countdate"); 

        // sum_basic_errorcodeanaly
        $this->errorcodeanaly->sum_basic_errorcodeanaly($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_errorcodeanaly at $logdate and date = $countdate"); 

        // sum_basic_activity
        $this->newserveractivity->sum_basic_activity($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_activity at $logdate and date = $countdate"); 

        // sum_basic_activity_operating
        $this->operatingactivity->sum_basic_activity_operating($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_activity_operating at $logdate and date = $countdate"); 

        // sum_basic_viprole
        $this->viprole->sum_basic_viprole($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_viprole at $logdate and date = $countdate");

        // sum_basic_newcreaterole
        $this->newcreaterole->sum_basic_newcreaterole($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_newcreaterole at $logdate and date = $countdate");

        // sum_basic_channelimportamount
        $this->channelimportamount->sum_basic_channelimportamount($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_channelimportamount at $logdate and date = $countdate");

        // sum_basic_channelincome
        $this->channelincome->sum_basic_channelincome($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_channelincome at $logdate and date = $countdate");

        // sum_basic_propanaly
        $this->propanaly->sum_basic_propanaly($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_propanaly at $logdate and date = $countdate");
        
        // remain count        
        for($d=1;$d<31;$d++){
            $date31=date("Y-m-d",strtotime("-$d day"));
            // sum_basic_userremain
            $this->remaincount->sum_basic_userremain($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_userremain at $logdate and date = $date31");
            // sum_basic_deviceremain
            $this->remaincount->sum_basic_deviceremain($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_deviceremain at $logdate and date = $date31");
            // sum_basic_customremain_daily
            $this->remaincount->sum_basic_customremain_daily($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_customremain_daily at $logdate and date = $date31");
            // sum_basic_newpay
            $this->newpay->sum_basic_newpay($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_newpay at $logdate and date = $date31"); 
            // sum_basic_leavecount
            $this->leavecount->sum_basic_leavecount($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_leavecount at $logdate and date = $date31");
            // sum_basic_viprole_leavealany
            $this->viprole->sum_basic_viprole_leavealany($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_viprole_leavealany at $logdate and date = $date31");
            // sum_basic_vipremain
            $this->vipremain->sum_basic_vipremain($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_vipremain at $logdate and date = $date31");
            // sum_basic_vipzeroremain
            $this->vipremain->sum_basic_vipzeroremain($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_vipzeroremain at $logdate and date = $date31");
            // sum_basic_channelquality
            $this->channelquality->sum_basic_channelquality($date31);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_channelquality at $logdate and date = $date31");
            
        }
        
        // ltv
        for($d=1;$d<91;$d++){
            $date91=date("Y-m-d",strtotime("-$d day"));
            // sum_basic_userltv
            $this->userltv->sum_basic_userltv($date91);
            $logdate = date('Y-m-d H:i:s', time());
            log_message("debug", "sum_basic_userltv at $logdate and date = $date91");
        }

        // sum_basic_sa_taskanalysis
        $this->taskanalysis->sum_basic_sa_taskanalysis($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_sa_taskanalysis at $logdate and date = $countdate");

        // sum_basic_sa_newuserguideanalysis
        $this->taskanalysis->sum_basic_sa_newuserguideanalysis($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_sa_newuserguideanalysis at $logdate and date = $countdate");

        // sum_basic_levelanaly
        $this->levelaly->sum_basic_levelanaly($countdate);
        $logdate = date('Y-m-d H:i:s', time());
        log_message("debug", "sum_basic_levelanaly at $logdate and date = $countdate");
    }

}
