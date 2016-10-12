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
 * Channelqualitymodel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Channelqualitymodel extends CI_Model
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

    /**
     * GetDetailDauUsersDataByDay function
     * get detailed data
     *
     * @param string $fromTime from time
     * @param string $toTime   to time
     * @param string $channel channel
     * @param string $server server
     * @param string $version version
     *
     * @return query list
     */
    function getChannelqualityData($fromTime, $toTime, $channel, $server, $version)
    {
        $list = array();
        $query = $this->ChannelqualityData($fromTime, $toTime, $channel, $server, $version);
        $dauUsersRow = $query->first_row();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $fRow = array();
            $fRow["channel_name"] = $dauUsersRow->channel_name;
            $fRow['dayavgdau'] = round($dauUsersRow->dayavgdau,2);
            $fRow['daudegree'] = round($dauUsersRow->daudegree,2);
            $fRow['day1avgremain'] = round(($dauUsersRow->day1avgremain)*100,2);
            $fRow['day3avgremain'] = round(($dauUsersRow->day3avgremain)*100,2);
            $fRow['day7avgremain'] = round(($dauUsersRow->day7avgremain)*100,2);
            $fRow['day14avgremain'] = round(($dauUsersRow->day14avgremain)*100,2);
            $fRow['day30avgremain'] = round(($dauUsersRow->day30avgremain)*100,2);
            $dauUsersRow = $query->next_row();
            array_push($list, $fRow);
        }
        return $list;
    }

    function ChannelqualityData($fromTime, $toTime, $channel, $version, $server)
    {
        $currentProduct = $this->common->getCurrentProduct();
        $productId = $currentProduct->id;
        $dwdb = $this->load->database('dw', true);
        ($channel != 'all') ? $channel_list = $this->unescape(implode("','", $channel)) : $channel_list = 'all';
        ($version != 'all') ? $version_list = $this->unescape(implode("','", $version)) : $version_list = 'all';
        ($server != 'all') ? $server_list   = $this->unescape(implode("','", $server)) : $server_list   = 'all';
        $sql = "SELECT
				IFNULL(channel_name, 0) channel_name,
				IFNULL(AVG(dayavgdau), 0) dayavgdau,
				IFNULL(AVG(daudegree), 0) daudegree,
				IFNULL(AVG(day1avgremain), 0) day1avgremain,
				IFNULL(AVG(day3avgremain), 0) day3avgremain,
                IFNULL(AVG(day7avgremain), 0) day7avgremain,
                IFNULL(AVG(day14avgremain), 0) day14avgremain,
                IFNULL(AVG(day30avgremain), 0) day30avgremain
				FROM
					 " . $dwdb->dbprefix('sum_basic_channelquality') . "
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
     * sum_basic_channelquality function
     * count user ltv
     *
     *
     */
    public function sum_basic_channelquality($countdate)
    {	
    	// $sevendaysago=date('Y-m-d', strtotime("-7 day", strtotime($countdate)));
 	    $thirtydaysago=date('Y-m-d', strtotime("-30 day", strtotime($countdate)));
        $dwdb = $this->load->database('dw', true);

        $params_pc    = $this->product->getProductChannelOffServerVersion();
        $paramsRow_pc = $params_pc->first_row();
        for ($i = 0; $i < $params_pc->num_rows(); $i++) {
            //get channelname by channelid
            $channel_name = $this->channel->getChannelnameById($paramsRow_pc->chId);

            //日活跃
            $daydau=$this->dauusers->getDauuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //月活跃
            $monthdau=$this->dauusers->getDauuser($thirtydaysago, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all');
            //新增角色
            $newusers=  $this->newcreaterole->getNewuser($countdate, $countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all');
            //次日均留存率
            $day2 = $this->remaincount->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 1);
            //3日均留存率	
            $day4 = $this->remaincount->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 3);
            //7日均留存率
            $day8 = $this->remaincount->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 7);	
            //14日均留存率
            $day14 = $this->remaincount->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 13);
            //30日均留存率
            $day30 = $this->remaincount->getRemainByNewuser($countdate, $paramsRow_pc->appId, $paramsRow_pc->chId, 'all', 'all', 29);


            $data    = array(
                'startdate_sk'     => $countdate,
                'enddate_sk'       => $countdate,
                'product_id'       => $paramsRow_pc->appId,
                'channel_name'     => $channel_name,
                'dayavgdau'        => $daydau,
                'daudegree' => ($monthdau==0)?0:($daydau/$monthdau),
                'day1avgremain'      => ($newusers==0)?0:($day2/$newusers),
                'day3avgremain'          => ($newusers==0)?0:($day4/$newusers),
                'day7avgremain'          => ($newusers==0)?0:($day8/$newusers),
                'day14avgremain'          => ($newusers==0)?0:($day14/$newusers),
                'day30avgremain'          => ($newusers==0)?0:($day30/$newusers)
            );
            $dwdb->insert_or_update('razor_sum_basic_channelquality', $data);
            $paramsRow_pc = $params_pc->next_row();
        }
    }

}
