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
 * Channelincomemodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Channelincomemodel extends CI_Model
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
        $this->load->model("useranalysis/dauusersmodel", 'dauusers');
        $this->load->model("useranalysis/newusersmodel", 'newusers');
        $this->load->model("useranalysis/newcreaterolemodel", 'newcreaterole');
        $this->load->model("useranalysis/remaincountmodel", 'remaincount');
        $this->load->model("payanaly/paydatamodel", 'paydata');
        $this->load->model('common');

    }

    function getChannelincomeData($fromTime, $toTime, $channel, $server, $version)
    {
        $list = array();
        $query = $this->ChannelincomeData($fromTime, $toTime, $channel, $server, $version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow  = array();
            $fRow["channel_name"] = $dauUsersRow->channel_name;
            $fRow['dauuser'] = $dauUsersRow->dauuser;
            $fRow['payuser'] = $dauUsersRow->payuser;
            $fRow['payamount'] = $dauUsersRow->payamount;
            $fRow['arpu'] = round($dauUsersRow->arpu,2);
            $fRow['arppu'] = round($dauUsersRow->arppu,2);
            $fRow['payrate'] = round(($dauUsersRow->payrate)*100,2);
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function ChannelincomeData($fromTime, $toTime, $channel, $server, $version)
    {
        $currentProduct                     = $this->common->getCurrentProduct();
        $productId                          = $currentProduct->id;
        $dwdb                               = $this->load->database('dw', true);
        ($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
        ($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
        ($server != 'all') ? $server_list   = $this->unescape(implode("','", $server)) : $server_list   = 'all';
        $sql                                = "SELECT
				IFNULL(channel_name, 0) channel_name,
				IFNULL(SUM(dauuser), 0) dauuser,
				IFNULL(SUM(payuser), 0) payuser,
				IFNULL(SUM(payamount), 0) payamount,
				IFNULL(AVG(arpu), 0) arpu,
				IFNULL(AVG(arppu), 0) arppu,
				IFNULL(AVG(payrate), 0) payrate
				FROM
					 " . $dwdb->dbprefix('sum_basic_channelincome') . "
				WHERE
				startdate_sk >= '" . $fromTime . "'
                AND enddate_sk <= '" . $toTime . "'
				AND product_id = '" . $productId . "'
                Group By channel_name
				Order By rid ASC";
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
     * sum_basic_channelincome function
     * count channel income
     *
     *
     */
    public function sum_basic_channelincome($countdate)
    {	
    	// $sevendaysago=date('Y-m-d', strtotime("-7 day", strtotime($countdate)));
 	    $thirtydaysago=date('Y-m-d', strtotime("-30 day", strtotime($countdate)));
        $dwdb = $this->load->database('dw', true);

        $params_pc    = $this->product->getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            $daydau=$this->dauusers->getDauuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $Payuser=$this->dauusers->getPayuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            $Payamount=$this->paydata->getPayamount($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');

            $data    = array(
                'startdate_sk'     => $countdate,
                'enddate_sk'       => $countdate,
                'product_id'       => $paramsRow_pc->appId,
                'channel_name'     => $channel_name,
                'dauuser'        => $daydau,
                'payuser' => $Payuser,
                'payamount'      => $Payamount,
                'arpu'          => ($daydau==0)?0:($Payamount/$daydau),
                'arppu'          => ($Payuser==0)?0:($Payamount/$Payuser),
                'payrate'          => ($daydau==0)?0:($Payuser/$daydau)
            );
            $dwdb->insert_or_update('razor_sum_basic_channelincome', $data);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

}
