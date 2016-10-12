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
 * Productarchive Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class ProductarchiveModel extends CI_Model
{
	/**
	 * Construct funciton, to pre-load database configuration
	 *
	 * @return void
	 */
	function __construct() 
	{
		$this -> load -> database();
	}

	/**
	 * GetProductChannelVersion function
	 * get productkey and channelid and version_name
	 * 
	 * @return Array
	 */
	function getProductChannelVersion() {
		$sql = "SELECT
					cp1.product_id appId,
					cp1.channel_id chId,
					IFNULL(r1.version,0) version
				FROM
					(
						SELECT
							cp.product_id,
							cp.channel_id
						FROM
							razor_channel_product cp
						WHERE
							cp.channel_id IN (
								SELECT
									c.channel_id
								FROM
									razor_channel c
								WHERE
									c.active = 1
							)
					) cp1
				LEFT JOIN (
					SELECT DISTINCT
						r.appId,
						r.version
					FROM
						razor_login r
				) r1 ON cp1.product_id = r1.appId;";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductOffChannelVersion() {
		$sql = "SELECT
					p.id appId
				FROM
					razor_product p
				WHERE
					p.active = 1;";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductChannelOffVersion() {
		$sql = "						SELECT
							cp.product_id appId,
							cp.channel_id chId
						FROM
							razor_channel_product cp
						WHERE
							cp.channel_id IN (
								SELECT
									c.channel_id
								FROM
									razor_channel c
								WHERE
									c.active = 1
							)";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductVersionOffChannel() {
		$sql = "SELECT
					p1.id appId,
					IFNULL(r1.version,0) version
				FROM
					(
						SELECT
							p.id
						FROM
							razor_product p
						WHERE
							p.active = 1
					) p1
				LEFT JOIN (
					SELECT DISTINCT
						r.appId,
						r.version
					FROM
						razor_login r
				) r1 ON p1.id = r1.appId;";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductServerVersionOffChannel() {
		$sql = "SELECT
					pr1.id appId,
					s1.server_id srvId,
					IFNULL(pr1.version,0) version
				FROM
					(
						SELECT
							p1.id,
							r1.version
						FROM
							(
								SELECT
									p.id
								FROM
									razor_product p
								WHERE
									p.active = 1
							) p1
						LEFT JOIN (
							SELECT DISTINCT
								r.appId,
								r.version
							FROM
								razor_login r
						) r1 ON p1.id = r1.appId
					) pr1
				LEFT JOIN (
					SELECT
						s.server_id,
						s.product_id
					FROM
						razor_server s
					WHERE
						s.active = 1
				) s1 ON pr1.id = s1.product_id;";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductServerOffChannelVersion() {
		$sql = "SELECT
					s.product_id appId,
					s.server_id srvId
				FROM
					razor_server s
				WHERE
					s.active = 1;";
		$query = $this->db->query($sql);
		return $query;
	}
   
    /**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductVersionOffChannelServer() {
		$sql = "SELECT
					p1.id appId,
					IFNULL(r1.version,0) version
				FROM
					(
						SELECT
							p.id
						FROM
							razor_product p
						WHERE
							p.active = 1
					) p1
				LEFT JOIN (
					SELECT DISTINCT
						r.appId,
						r.version
					FROM
						razor_login r
				) r1 ON p1.id = r1.appId;";
		$query = $this->db->query($sql);
		return $query;
	}
    
    /**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductOffChannelServerVersion() {
		$sql = "SELECT
					p.id appId
				FROM
					razor_product p
				WHERE
					p.active = 1;";
		$query = $this->db->query($sql);
		return $query;
	}
   
    /**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductChannelServerVersion() {
		$sql = "SELECT
					cpr1.product_id appId,
					cpr1.channel_id chId,
					s1.server_id srvId,
					IFNULL(cpr1.version,0) version
				FROM
					(
						SELECT
							cp1.product_id,
							cp1.channel_id,
							r1.version
						FROM
							(
								SELECT
									cp.product_id,
									cp.channel_id
								FROM
									razor_channel_product cp
								WHERE
									cp.channel_id IN (
										SELECT
											c.channel_id
										FROM
											razor_channel c
										WHERE
											c.active = 1
									)
							) cp1
						LEFT JOIN (
							SELECT DISTINCT
								r.appId,
								r.version
							FROM
								razor_login r
						) r1 ON cp1.product_id = r1.appId
					) cpr1
				LEFT JOIN (
					SELECT
						s.server_id,
						s.product_id
					FROM
						razor_server s
					WHERE
						s.active = 1
				) s1 ON cpr1.product_id = s1.product_id;";
		$query = $this->db->query($sql);
		return $query;
	}
	   
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductChannelServerOffVersion() {
		$sql = "SELECT
					cp1.product_id appId,
					cp1.channel_id chId,
					s1.server_id srvId
				FROM
					(
							SELECT
								cp.product_id,
								cp.channel_id
							FROM
								razor_channel_product cp
							WHERE
								cp.channel_id IN (
									SELECT
										c.channel_id
									FROM
										razor_channel c
									WHERE
										c.active = 1
								)
					) cp1
				LEFT JOIN (
					SELECT
						s.server_id,
						s.product_id
					FROM
						razor_server s
					WHERE
						s.active = 1
				) s1 ON cp1.product_id = s1.product_id;";
		$query = $this->db->query($sql);
		return $query;
	}
	   
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductChannelVersionOffServer() {
		$sql = "SELECT
					cp1.product_id appId,
					cp1.channel_id chId,
					IFNULL(r1.version,0) version
				FROM
					(
							SELECT
								cp.product_id,
								cp.channel_id
							FROM
								razor_channel_product cp
							WHERE
								cp.channel_id IN (
									SELECT
										c.channel_id
									FROM
										razor_channel c
									WHERE
										c.active = 1
								)
					) cp1
				LEFT JOIN (
					SELECT DISTINCT
						r.appId,
						r.version
					FROM
						razor_login r
				) r1 ON cp1.product_id = r1.appId;";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**
	 * GetProductChannelServerVersion function
	 * get productkey and channelid serverid and version_name
	 * 
	 * @return Array
	 */
	function getProductChannelOffServerVersion() {
		$sql = "						SELECT
							cp.product_id appId,
							cp.channel_id chId
						FROM
							razor_channel_product cp
						WHERE
							cp.channel_id IN (
								SELECT
									c.channel_id
								FROM
									razor_channel c
								WHERE
									c.active = 1
							)";
		$query = $this->db->query($sql);
		return $query;
	}

	function excute(){
		$dwdb = $this->load->database('dw', true);

		$ProductChannelVersion=$this->getProductChannelVersion();
		// $dwdb->delete('razor_sum_basic_archive_pcv', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_pcv');
		foreach ($ProductChannelVersion->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'chId' => $row->chId,
                    'version' => $row->version
            );
            $dwdb->insert('razor_sum_basic_archive_pcv', $data);
        }

		$ProductOffChannelVersion=$this->getProductOffChannelVersion();
		// $dwdb->delete('razor_sum_basic_archive_p', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_p');
		foreach ($ProductOffChannelVersion->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId
            );
            $dwdb->insert('razor_sum_basic_archive_p', $data);
        }

		$ProductChannelOffVersion=$this->getProductChannelOffVersion();
		// $dwdb->delete('razor_sum_basic_archive_pc', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_pc');
		foreach ($ProductChannelOffVersion->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'chId' => $row->chId
            );
            $dwdb->insert('razor_sum_basic_archive_pc', $data);
        }

		$ProductVersionOffChannel=$this->getProductVersionOffChannel();
		// $dwdb->delete('razor_sum_basic_archive_pv', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_pv');
		foreach ($ProductVersionOffChannel->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'version' => $row->version
            );
            $dwdb->insert('razor_sum_basic_archive_pv', $data);
        }

		$ProductServerVersionOffChannel=$this->getProductServerVersionOffChannel();
		// $dwdb->delete('razor_sum_basic_archive_psv', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_psv');
		foreach ($ProductServerVersionOffChannel->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'srvId' => $row->srvId,
                    'version' => $row->version
            );
            $dwdb->insert('razor_sum_basic_archive_psv', $data);
        }

		$ProductServerOffChannelVersion=$this->getProductServerOffChannelVersion();
		// $dwdb->delete('razor_sum_basic_archive_ps', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_ps');
		foreach ($ProductServerOffChannelVersion->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'srvId' => $row->srvId
            );
            $dwdb->insert('razor_sum_basic_archive_ps', $data);
        }

		$ProductChannelServerVersion=$this->getProductChannelServerVersion();
		// $dwdb->delete('razor_sum_basic_archive_pcsv', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_pcsv');
		foreach ($ProductChannelServerVersion->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'chId' => $row->chId,
                    'srvId' => $row->srvId,
                    'version' => $row->version
            );
            $dwdb->insert('razor_sum_basic_archive_pcsv', $data);
        }

		$ProductChannelServerOffVersion=$this->getProductChannelServerOffVersion();
		// $dwdb->delete('razor_sum_basic_archive_pcs', array('id' => $id));
		$dwdb->empty_table('razor_sum_basic_archive_pcs');
		foreach ($ProductChannelServerOffVersion->result() as $row)
        {
        	$data = array(
                    'appId' => $row->appId,
                    'chId' => $row->chId,
                    'srvId' => $row->srvId
            );
            $dwdb->insert('razor_sum_basic_archive_pcs', $data);
        }

	}
}