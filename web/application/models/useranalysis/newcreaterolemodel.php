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
 * Newcreaterolemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Newcreaterolemodel extends CI_Model
{

    /**
     * Construct load
     * Construct function
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("product/productmodel", 'product');
        $this->load->model("channelmodel", 'channel');
        $this->load->model("servermodel", 'server');
        $this->load->model("useranalysis/newusersmodel", 'newusers');
    }

    function getNewcreateroleData($fromTime, $toTime, $channel, $server, $version)
    {
        $list = array();
        $query = $this->NewcreateroleData($fromTime, $toTime, $channel, $server, $version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow["startdate_sk"] = $dauUsersRow->startdate_sk;
            $fRow['newregister'] = $dauUsersRow->newregister;
            $fRow['newuser'] = $dauUsersRow->newuser;
            $fRow['newcreaterole'] = $dauUsersRow->newcreaterole;
            $fRow['newcreaterolerate'] = round((($dauUsersRow->newuser)/($dauUsersRow->newregister)*100),2);
            $fRow['mixcreaterole'] = $dauUsersRow->mixcreaterole;
            $fRow['mixcreaterolerate'] = round((($dauUsersRow->mixcreaterole)/($dauUsersRow->newcreaterole)*100),2);
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }


    function NewcreateroleData($fromTime, $toTime, $channel, $server, $version)
    {
        $currentProduct                     = $this->common->getCurrentProduct();
        $productId                          = $currentProduct->id;
        $dwdb                               = $this->load->database('dw', true);
        ($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
        ($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
        ($server != 'all') ? $server_list   = $this->unescape(implode("','", $server)) : $server_list   = 'all';
        $sql                                = "SELECT
			IFNULL(startdate_sk, 0) startdate_sk,
			IFNULL(newregister, 0) newregister,
			IFNULL(newuser, 0) newuser,
			IFNULL(newcreaterole, 0) newcreaterole,
			IFNULL(mixcreaterole, 0) mixcreaterole
			FROM
				" . $dwdb->dbprefix('sum_basic_newcreaterole') . "
			WHERE
			startdate_sk >= '" . $fromTime . "'
			AND enddate_sk <= '" . $toTime . "'
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
    public function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f) {
                    $ret .= chr($val);
                } else
                if ($val < 0x800) {
                    $ret .= chr(0xc0 | ($val >> 6)) .
                    chr(0x80 | ($val & 0x3f));
                } else {
                    $ret .= chr(0xe0 | ($val >> 12)) .
                    chr(0x80 | (($val >> 6) & 0x3f)) .
                    chr(0x80 | ($val & 0x3f));
                }

                $i += 5;
            } else
            if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else {
                $ret .= $str[$i];
            }

        }
        return $ret;
    }

    /**
     * getNewuser function
     * get new users
     *
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     *
     * @return Int newuser
     */
    public function getNewuser($fromdate, $todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all')
    {
        if ($channelid == 'all' && $serverid != 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
							AND cr.version = '$versionname'
						);";
        } elseif ($channelid == 'all' && $serverid != 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
				#		AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
							#AND cr.version = '$versionname'
						);";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
				#		AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
								AND cr.version = '$versionname'
						);";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
				#		AND c.srvId = '$serverid'
			#			AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
								#AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid != 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
							AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid != 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
					#	AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
							#AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid == 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
					#	AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
								AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(distinct c.userId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
					#	AND c.srvId = '$serverid'
				#		AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_register cr
							WHERE
								cr.register_date >= '$fromdate'
							AND cr.register_date <= '$todate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
								#AND cr.version = '$versionname'
						);";
        }
        $query = $this->db->query($sql);
        $row   = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newuser;
        }
    }

    /**
     * getMixuser function
     * get new users
     *
     * @param Date $date
     * @param String $appid
     * @param String $channelid
     * @param String $serverid
     * @param String $versionname
     *
     * @return Int newuser
     */
    public function getMixuser($fromdate, $todate, $appid, $channelid = 'all', $serverid = 'all', $versionname = 'all')
    {
        if ($channelid == 'all' && $serverid != 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
							AND cr.srvId = '$serverid'
							AND cr.version = '$versionname'
						);";
        } elseif ($channelid == 'all' && $serverid != 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
				#		AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
							AND cr.srvId = '$serverid'
							#AND cr.version = '$versionname'
						);";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
				#		AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
							#AND cr.srvId = '$serverid'
							AND cr.version = '$versionname'
						);";
        } elseif ($channelid == 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
					#	AND c.chId = '$channelid'
				#		AND c.srvId = '$serverid'
			#			AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							#AND cr.chId = '$channelid'
							#AND cr.srvId = '$serverid'
							#AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid != 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
							AND cr.srvId = '$serverid'
							AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid != 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
						AND c.srvId = '$serverid'
					#	AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
							AND cr.srvId = '$serverid'
							#AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid == 'all' && $versionname != 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
					#	AND c.srvId = '$serverid'
						AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
							#AND cr.srvId = '$serverid'
							AND cr.version = '$versionname'
						);";
        } elseif ($channelid != 'all' && $serverid == 'all' && $versionname == 'all') {
            $sql = "SELECT
							count(c.roleId) newuser
						FROM
							razor_createrole c
						WHERE
							c.create_role_date >= '$fromdate'
						AND c.create_role_date <= '$todate'
						AND c.appId = '$appid'
						AND c.chId = '$channelid'
					#	AND c.srvId = '$serverid'
				#		AND c.version = '$versionname'
						AND c.userId IN (
							SELECT DISTINCT
								cr.userId
							FROM
								razor_createrole cr
							WHERE
								cr.create_role_date < '$fromdate'
							AND cr.appId = '$appid'
							AND cr.chId = '$channelid'
							#AND cr.srvId = '$serverid'
							#AND cr.version = '$versionname'
						);";
        }
        $query = $this->db->query($sql);
        $row   = $query->first_row();
        if ($query->num_rows > 0) {
            return $row->newuser;
        }
    }

    /**
     * sum_basic_newcreaterole function
     * count dau users
     *
     *
     */
    public function sum_basic_newcreaterole($countdate)
    {	
    	$this->load->model("useranalysis/dauusersmodel", 'dauusers');
        $dwdb = $this->load->database('dw', true);

        $params_psv    = $this->product->getProductServerVersionOffChannel();
        $paramsRow_psv = $params_psv->first_row();
        for ($i = 0; $i < $params_psv->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->version);
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_psv->appId, 'all', $paramsRow_psv->srvId, $paramsRow_psv->version);

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_psv->srvId);
            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_psv->appId,
                'version_name'  => $paramsRow_psv->version,
                'channel_name'  => 'all',
                'server_name'   => $server_name,
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_psv = $params_psv->next_row();
        }
        $params_ps    = $this->product->getProductServerOffChannelVersion();
        $paramsRow_ps = $params_ps->first_row();
        for ($i = 0; $i < $params_ps->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_ps->appId, 'all', 'all');
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_ps->appId, 'all', $paramsRow_ps->srvId, 'all');

            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_ps->srvId);
            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_ps->appId,
                'version_name'  => 'all',
                'channel_name'  => 'all',
                'server_name'   => $server_name,
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_ps = $params_ps->next_row();
        }
        $params_pv    = $this->product->getProductVersionOffChannelServer();
        $paramsRow_pv = $params_pv->first_row();
        for ($i = 0; $i < $params_pv->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_pv->appId, 'all', $paramsRow_pv->version);
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_pv->appId, 'all', 'all', $paramsRow_pv->version);

            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_pv->appId,
                'version_name'  => $paramsRow_pv->version,
                'channel_name'  => 'all',
                'server_name'   => 'all',
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_pv = $params_pv->next_row();
        }
        $params_p    = $this->product->getProductOffChannelServerVersion();
        $paramsRow_p = $params_p->first_row();
        for ($i = 0; $i < $params_p->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_p->appId, 'all', 'all');
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_p->appId, 'all', 'all', 'all');
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_p->appId, 'all', 'all', 'all');

            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_p->appId,
                'version_name'  => 'all',
                'channel_name'  => 'all',
                'server_name'   => 'all',
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_p = $params_p->next_row();
        }
        $params_pcsv    = $this->product->getProductChannelServerVersion();
        $paramsRow_pcsv = $params_pcsv->first_row();
        for ($i = 0; $i < $params_pcsv->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->version);
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_pcsv->appId, $paramsRow_pcsv->chId, $paramsRow_pcsv->srvId, $paramsRow_pcsv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcsv->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcsv->srvId);
            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_pcsv->appId,
                'version_name'  => $paramsRow_pcsv->version,
                'channel_name'  => $channel_name,
                'server_name'   => $server_name,
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_pcsv = $params_pcsv->next_row();
        }
        $params_pcs    = $this->product->getProductChannelServerOffVersion();
        $paramsRow_pcs = $params_pcs->first_row();
        for ($i = 0; $i < $params_pcs->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, 'all');
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_pcs->appId, $paramsRow_pcs->chId, $paramsRow_pcs->srvId, 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcs->chId);
            //get servername by serverid
            $server_name = $this->server->getServernameById($paramsRow_pcs->srvId);

            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_pcs->appId,
                'version_name'  => 'all',
                'channel_name'  => $channel_name,
                'server_name'   => $server_name,
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_pcs = $params_pcs->next_row();
        }
        $params_pcv    = $this->product->getProductChannelVersionOffServer();
        $paramsRow_pcv = $params_pcv->first_row();
        for ($i = 0; $i < $params_pcv->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, $paramsRow_pcv->version);
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_pcv->appId, $paramsRow_pcv->chId, 'all', $paramsRow_pcv->version);

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pcv->chId);

            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_pcv->appId,
                'version_name'  => $paramsRow_pcv->version,
                'channel_name'  => $channel_name,
                'server_name'   => 'all',
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_pcv = $params_pcv->next_row();
        }

        $params_pc    = $this->product->getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            $Newregister   = $this->newusers->getNewusers($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all');
            $Newuser       = $this->getNewuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $Newcreaterole = $this->dauusers->getNewuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $Mixuser       = $this->getMixuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $data        = array(
                'startdate_sk'  => $countdate,
                'enddate_sk'    => $countdate,
                'product_id'    => $paramsRow_pc->appId,
                'version_name'  => 'all',
                'channel_name'  => $channel_name,
                'server_name'   => 'all',
                'newregister'   => $Newregister,
                'newuser'       => $Newuser,
                'newcreaterole' => $Newcreaterole,
                'mixcreaterole' => $Mixuser,
            );
            $dwdb->insert_or_update('razor_sum_basic_newcreaterole', $data);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

}
