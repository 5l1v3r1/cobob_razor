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
 * Channel Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class HeadFilterModel extends CI_Model
{
	/**
	* Getdechannel function
	* through username get self-built channels
	*
	* @param int $userid user id
	*
	* @return query result
	*/

	function getFilterData(){
		//筛选聚到 区服 版本 查询显示
		$product = $this->common->getCurrentProduct();//当前产品ID
		//渠道
		if($product){
			$productId = $product->id;
			$productName = $product->name;
			$this->db->select('id,product_platform');
			$this->db->where("name = '$productName'");
			$platform = $this->db->get('razor_product');
			if($platform != null && $platform->num_rows() > 0){
				foreach ($platform->result() as $row){
					$platform_source = $row->product_platform;
					$platform_id = $row->id;
				}
			}
			$this->db->select('channel_name');
			//$this->db->where("platform = '$platform_source'");
			$this->db->where("active = '1'");
			$query = $this->db->get('razor_channel');
		
			$channels = array();
			$server = array();
			$version = array();
			if($query != null && $query -> num_rows() > 0){
				foreach ($query -> result() as $row){
					$channels[] = $row -> channel_name;
				}
			}
			//区服
			if($product){
				$productId = $product->id;
				$this -> db -> select('server_name');
				$this -> db -> where("active = 1 AND product_id = $productId");
			}
			else{
				$this -> db -> select('server_name');
				$this -> db -> where("active = 1");
			}
			$query = $this -> db -> get('razor_server');
			if($query != null && $query -> num_rows() > 0){
				foreach ($query -> result() as $row){
					$server[] = $row -> server_name;
				}
			}
			//版本
			$query = $this -> db -> query("select distinct version from razor_login where appId = '$platform_id'");
			if($query != null && $query -> num_rows() > 0){
				foreach ($query -> result() as $row){
					$version[] = $row->version;
				}
			}
			$data_arr = array(
				'channels' => $channels,
				'version' => $version,
				'server' => $server
				
			);
		}
		else{
			$data_arr = array();
		}
		return $data_arr;
	}
}