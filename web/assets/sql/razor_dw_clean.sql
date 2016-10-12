-- razor_dw库清档

-- razor_dw.razor_sum_basic_customremain_daily
DELETE FROM razor_dw.razor_sum_basic_customremain_daily WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_customremain_monthly
DELETE FROM razor_dw.razor_sum_basic_customremain_monthly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_customremain_weekly
DELETE FROM razor_dw.razor_sum_basic_customremain_weekly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_dauusers
DELETE FROM razor_dw.razor_sum_basic_dauusers WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_dayonline
DELETE FROM razor_dw.razor_sum_basic_dayonline WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_deviceremain_daily
DELETE FROM razor_dw.razor_sum_basic_deviceremain_daily WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_firstpayanaly_amount
DELETE FROM razor_dw.razor_sum_basic_firstpayanaly_amount WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_firstpayanaly_level
DELETE FROM razor_dw.razor_sum_basic_firstpayanaly_level WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_firstpayanaly_time
DELETE FROM razor_dw.razor_sum_basic_firstpayanaly_time WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_levelanaly
DELETE FROM razor_dw.razor_sum_basic_levelanaly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_newpay
DELETE FROM razor_dw.razor_sum_basic_newpay WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_newusers
DELETE FROM razor_dw.razor_sum_basic_newusers WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payanaly_arpu_daily
DELETE FROM razor_dw.razor_sum_basic_payanaly_arpu_daily WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payanaly_arpu_monthly
DELETE FROM razor_dw.razor_sum_basic_payanaly_arpu_monthly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payanaly_paycount
DELETE FROM razor_dw.razor_sum_basic_payanaly_paycount WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payanaly_payfield
DELETE FROM razor_dw.razor_sum_basic_payanaly_payfield WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_paydata
DELETE FROM razor_dw.razor_sum_basic_paydata WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payinterval_first
DELETE FROM razor_dw.razor_sum_basic_payinterval_first WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payinterval_second
DELETE FROM razor_dw.razor_sum_basic_payinterval_second WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payinterval_third
DELETE FROM razor_dw.razor_sum_basic_payinterval_third WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_paylevel
DELETE FROM razor_dw.razor_sum_basic_paylevel WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payrank
DELETE FROM razor_dw.razor_sum_basic_payrank WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payrank_trend
DELETE FROM razor_dw.razor_sum_basic_payrank_trend WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payrate_daily
DELETE FROM razor_dw.razor_sum_basic_payrate_daily WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payrate_monthly
DELETE FROM razor_dw.razor_sum_basic_payrate_monthly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_payrate_weekly
DELETE FROM razor_dw.razor_sum_basic_payrate_weekly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_userremain_daily
DELETE FROM razor_dw.razor_sum_basic_userremain_daily WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_leavecount
DELETE FROM razor_dw.razor_sum_basic_leavecount WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_leavecount_levelaly
DELETE FROM razor_dw.razor_sum_basic_leavecount_levelaly WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_levelleave
DELETE FROM razor_dw.razor_sum_basic_levelleave WHERE date_sk>='1970-01-01' AND date_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_start_login_peoplesbycount
DELETE FROM razor_dw.razor_sum_basic_start_login_peoplesbycount WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_start_login_times
DELETE FROM razor_dw.razor_sum_basic_start_login_times WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_time_count_avg
DELETE FROM razor_dw.razor_sum_basic_time_count_avg WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_borderintervaltime
DELETE FROM razor_dw.razor_sum_basic_borderintervaltime WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_frequencytime_countday
DELETE FROM razor_dw.razor_sum_basic_frequencytime_countday WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_frequencytime_gametime
DELETE FROM razor_dw.razor_sum_basic_frequencytime_gametime WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_frequencytime_timefield
DELETE FROM razor_dw.razor_sum_basic_frequencytime_timefield WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_activity
DELETE FROM razor_dw.razor_sum_basic_sa_activity WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_activity_action
DELETE FROM razor_dw.razor_sum_basic_sa_activity_action WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_activity_prop
DELETE FROM razor_dw.razor_sum_basic_sa_activity_prop WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_function
DELETE FROM razor_dw.razor_sum_basic_sa_function WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_function_behaviour
DELETE FROM razor_dw.razor_sum_basic_sa_function_behaviour WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_function_prop
DELETE FROM razor_dw.razor_sum_basic_sa_function_prop WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_nature
DELETE FROM razor_dw.razor_sum_basic_sa_nature WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_taskanalysis_step
DELETE FROM razor_dw.razor_sum_basic_sa_taskanalysis_step WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_taskanalysis_task
DELETE FROM razor_dw.razor_sum_basic_sa_taskanalysis_task WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_tollgateanalysis_big
DELETE FROM razor_dw.razor_sum_basic_sa_tollgateanalysis_big WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_tollgateanalysis_small
DELETE FROM razor_dw.razor_sum_basic_sa_tollgateanalysis_small WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_virtualmoney
DELETE FROM razor_dw.razor_sum_basic_sa_virtualmoney WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_virtualmoney_outputway
DELETE FROM razor_dw.razor_sum_basic_sa_virtualmoney_outputway WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_sa_virtualmoney_viplevel
DELETE FROM razor_dw.razor_sum_basic_sa_virtualmoney_viplevel WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_activity
DELETE FROM razor_dw.razor_sum_basic_activity WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_activity_details
DELETE FROM razor_dw.razor_sum_basic_activity_details WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_activity_distributeddetails
DELETE FROM razor_dw.razor_sum_basic_activity_distributeddetails WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_errorcodeanaly
DELETE FROM razor_dw.razor_sum_basic_errorcodeanaly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_errorcodeanaly_action
DELETE FROM razor_dw.razor_sum_basic_errorcodeanaly_action WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_newuserprogress
DELETE FROM razor_dw.razor_sum_basic_newuserprogress WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_userltv
DELETE FROM razor_dw.razor_sum_basic_userltv WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_userltv_timefield
DELETE FROM razor_dw.razor_sum_basic_userltv_timefield WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_vipremain
DELETE FROM razor_dw.razor_sum_basic_vipremain WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_viprole_leavealany
DELETE FROM razor_dw.razor_sum_basic_viprole_leavealany WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_viprole_leavealany_userlevel
DELETE FROM razor_dw.razor_sum_basic_viprole_leavealany_userlevel WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_viprole_payalany
DELETE FROM razor_dw.razor_sum_basic_viprole_payalany WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_viprole_useralany
DELETE FROM razor_dw.razor_sum_basic_viprole_useralany WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_realtimeroleinfo(注意：必须保留前一天数据，不然图表显示异常)
DELETE FROM razor_dw.razor_sum_basic_realtimeroleinfo WHERE count_date>='1970-01-01' AND count_date<DATE_SUB('2016-05-18',INTERVAL 1 DAY);
-- razor_dw.razor_sum_basic_levelanaly_totalupgradetime
DELETE FROM razor_dw.razor_sum_basic_levelanaly_totalupgradetime WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_start_login_peoplesbytime
DELETE FROM razor_dw.razor_sum_basic_start_login_peoplesbytime WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_newcreaterole
DELETE FROM razor_dw.razor_sum_basic_newcreaterole WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_channelimportamount
DELETE FROM razor_dw.razor_sum_basic_channelimportamount WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_channelquality
DELETE FROM razor_dw.razor_sum_basic_channelquality WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_channelincome
DELETE FROM razor_dw.razor_sum_basic_channelincome WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_propanaly
DELETE FROM razor_dw.razor_sum_basic_propanaly WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_propanaly_vipuser
DELETE FROM razor_dw.razor_sum_basic_propanaly_vipuser WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';
-- razor_dw.razor_sum_basic_propanaly_gainconsume
DELETE FROM razor_dw.razor_sum_basic_propanaly_gainconsume WHERE startdate_sk>='1970-01-01' AND startdate_sk<'2016-05-18';






