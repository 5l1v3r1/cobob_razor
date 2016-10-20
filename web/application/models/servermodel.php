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
 * Server Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class ServerModel extends CI_Model
{
	/**
	 * Construct funciton, to pre-load database configuration
	 *
	 * @return void
	 */
	function __construct()
	{
		$this->load->database();
		$this->load->model('common');
	}

	/**
	 * Getserver function
	 * through username get servers
	 *
	 * @param int $userid user id
	 *
	 * @return query result
	 */
	function getserver($userid)
	{
		$sql = "select * from ".$this->db->dbprefix('server')."  where user_id = $userid and active=1 ";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0){
			return $query->result_array();
		}
		return null;
	}
	/*
		*get product name
	*/
	function getproductname($userid){
		$sql = "select * from ".$this->db->dbprefix('product')."  where user_id = $userid and active=1 ";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0){
			return $query->result_array();
		}
		return null;
	}
	/*
		*get pruduct list
	*/
	function getproductlist($userid){
		$sql = "select * from ".$this->db->dbprefix('product')."  where user_id = $userid and active=1 ";
		$query = $this->db->query($sql);
		if ($query!=null && $query->num_rows()>0) {
			return $query->result_array();
		}
	}
	/**
	 * Getallserver function
	 * get All servers
	 *
	 * @return query result
	 */
	function getallserver($userid)
	{
			$sql = "select * from  ".$this->db->dbprefix('server')."  where user_id = $userid and active=1;";
			$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
				return $query->result_array();
		}
			return null;
	}

	/**
	 * GetServerList function 
	 * Get server list by id
	 * 
	 * @param int $product_id product id
	 * 
	 * @return query result
	 */
	function getServerList($userid)
	{
			$sql = "select distinct server_name from  ".$this->db->dbprefix('server')."  where user_id = $userid and active=1 order by server_name asc;";
			$query = $this->db->query($sql);
			return $query->result();
	}
	
	/**
	 * GetServerType function
	 * Get server type
	 *
	 *@param int $server_id server id
	 *
	 * @return query type
	 */
	function getServerType($server_id)
	{
		$sql = 'select type from '.$this->db->dbprefix('server').'
			where server_id="'.$server_id.'"' ;
			$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
				return $query->row()->type;
		}
		return null;
	}


	/**
	 * GetServerType function
	 * Get server type
	 *
	 *@param int $server_id server id
	 *
	 * @return query type
	 */
	function getServerCapacity($server_id)
	{
		$sql = 'select server_capacity from '.$this->db->dbprefix('server').'
			where server_id="'.$server_id.'"' ;
			$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
				return $query->row()->server_capacity;
		}
		return null;
	}


	/**
	 * GetServerTotalCapacity function
	 * Get server type
	 *
	 *@param int $product_id product id
	 * @return query type
	 */
	function getServerTotalCapacity($appid)
	{
		$sql = 'SELECT
                                IFNULL(SUM(s.server_capacity), 0) sum_capacity
                        FROM
                                razor_server s
                        WHERE
                                s.product_id = "'.$appid.'"
                        AND s.active = 1;';
                $query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
				return $query->row()->sum_capacity;
		}
		return null;
	}

	/**
	 * IsUniqueServer function
	 * is unique server
	 *
	 * @param int    $userid      user id
	 * @param string $servername server name
	 *
	 * @return query result
	 */
	function isUniqueServer($userid,$server_id,$servername,$product_name,$server_contain)
	{
			$sql = 'select * from '.$this->db->dbprefix('server').' 
			where user_id = "'.$userid.'" and active=1 and product_id = "'.$product_name.'"
			and server_name="'.$servername.'" and server_capacity = "'.$server_contain.'" and server_id = "'.$server_id.'"' ;
			$query = $this->db->query($sql);
			return $query->result();
	}
	
	/**
	 * IsUniqueSystemchannel function
	 * is unique system chanel
	 *
	 * @param string $channelname channel name
	 * @param string $platform    platform
	 *
	 * @return query result
	 */
	function isUniqueSystemchannel($channelname,$platform)
	{
			$sql = 'select * from  '.$this->db->dbprefix('channel').' 
			where active=1 and channel_name="'.$channelname.'" and 
			platform="'.$platform.'"';
			$query = $this->db->query($sql);
			return $query->result();
	}

	/**
	 * Getproductkey function
	 * get the appkey of system channels
	 *
	 * @param int    $user_id    user id
	 * @param int    $product_id product id
	 * @param string $platform   platform
	 *
	 * @return query result
	 */
	function getproductkey($user_id,$product_id,$platform)
	{
		$sql="
		select 
			cp.cp_id, 
			c.channel_name,
			cp.productkey,
			c.channel_id 
		from  
			".$this->db->dbprefix('channel')." c  
		inner join  
			".$this->db->dbprefix('channel_product')."  cp  
		on 
			c.channel_id = cp.channel_id 
		where 
			c.type='system' and 
			c.active=1 and 
			cp.product_id=$product_id and 
			c.platform=$platform";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
			return $query->result_array();
		}
		   return null; 
	}

	/**
	 * Getdefinechannel function
	 * get self-built channels
	 *
	 * @param int    $user_id    user id
	 * @param int    $product_id product id
	 * @param string $platform   platform
	 *
	 * @return query result
	 */
	function getdefinechannel($user_id,$product_id,$platform)
	{
			$sql = "select channel_name,channel_id from  ".$this->db->dbprefix('channel')."   where channel_id not in (select channel_id from  ".$this->db->dbprefix('channel_product')."  where product_id=$product_id and user_id=$user_id )and type='user' and active=1 and user_id=$user_id and platform=$platform";
			$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
			return $query->result_array();
		}
			   return null; 
	}
	
	/**
	 * Getdefineproductkey function
	 * get the appkey of self-built channels
	 *
	 * @param int    $user_id    user id
	 * @param int    $product_id product id
	 * @param string $platform   platform
	 *
	 * @return query result
	 */
	function getdefineproductkey($user_id,$product_id,$platform)
	{
		$sql="select cp.cp_id, c.channel_name ,cp.productkey,c.channel_id from   ".$this->db->dbprefix('channel')."  c  inner join ".$this->db->dbprefix('channel_product')."   cp  on c.channel_id = cp.channel_id and c.user_id=cp.user_id where c.type='user' and c.active=1  and cp.product_id=$product_id and cp.user_id=$user_id and c.platform=$platform";            
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
			  return $query->result_array();
		}
			   return null; 
	}

	 /**
	  * Getservernum function
	  * get the num of servers
	  *
	  * @param int $userid user id
	  *
	  * @return query num_rows
	  */
	function getservernum($userid)
	{
		$sql = "select * from  ".$this->db->dbprefix('server')."  where user_id = $userid and active=1 ";
		$query = $this->db->query($sql);
		return $query->num_rows(); 
	}

	/**
	 * Getplatform function
	 * get platform
	 *
	 * @return query result_array
	 */
	function getplatform()
	{
		$sql = "select * from  ".$this->db->dbprefix('platform')."  ";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
			return $query->result_array();
		}
			return null; 
	}

	/**
	 * Addserver function
	 * add server
	 *
	 * @param string $server_name server name
	 * @param int    $userid       user id
	 *
	 * @return void
	 */
	function addserver($server_name,$server_id,$product_name,$server_contain,$userid)
	{
			$create_date=date('Y-m-d H:i:s');            
			$data = array (
			'user_id'=>$userid,
			'server_name' => $server_name,
			'server_id' => $server_id,
			'product_id' => $product_name,
			'server_capacity' => $server_contain,
			'create_date'=>$create_date
			);        
			 $this->db->insert('server', $data);
	}
	
	/**
	 * Addsychannel function
	 * add self-built system channel
	 *
	 * @param string $channel_name channel name
	 * @param string $platform     platform
	 * @param int    $userid       user id
	 *
	 * @return void
	 */
	function addsychannel($channel_name, $platform,$userid)
	{
			$create_date=date('Y-m-d H:i:s');
			$data = array (
					'user_id'=>$userid,
					'channel_name' => $channel_name,
					'platform' => $platform ,
					'create_date'=>$create_date,
					'type'=>'system'    
			);
			$this->db->insert('channel', $data);
	}
	
	/**
	 * Getserverinfo function
	 * get server information
	 *
	 * @param int $userid     user id
	 * @param int $server_id server id
	 *
	 * @return query row_array
	 */
	function getserverinfo($userid,$server_id)
	{
		$sql = "select * from  ".$this->db->dbprefix('server')." where user_id =$userid and server_id=$server_id and active=1 ";            
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
			   return $query->row_array();
		}
			   return null; 
	}
	
	/**
	 * Updateserver function
	 * the information of updating server
	 *
	 * @param string $server_name server name
	 * @param int    $server_id   server id
	 *
	 * @return void
	 */
	function updateserver($server_name,$server_id,$product_name,$server_contain,$id)
	{
		$data = array(
			'server_id' => $server_id,
			'server_name' => $server_name,
			'product_id' => $product_name,
			'server_capacity' => $server_contain
		);
		$this->db->where('id', $id);
		$this->db->where('active', 1);
		$this->db->update('server', $data);
	}
	
	/**
	 * Deleteserver function
	 * detele server
	 *
	 * @param int $server_id server id
	 *
	 * @return void
	 */
	function deleteserver($server_id)
	{
			$data=array(
			'active'=>0
			);
			$this->db->where('server_id', $server_id);
			$this->db->update('server', $data);
	}

	/**
	 * Getchanbyplat function
	 * through platform get channel
	 *
	 * @param string $platform platform
	 *
	 * @return query result_array
	 */
	function getchanbyplat($platform)
	{
		$userid=$this->common->getUserId();    
		$sql="select * from  ".$this->db->dbprefix('channel')."  where active=1 and platform='$platform' and type='system' union 
		select * from  ".$this->db->dbprefix('channel')."  where active=1 and platform='$platform' and type='user'and user_id=$userid"; 
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
				return $query->result_array();
		}
		  return null; 
	}

	/**
	 * Updateapk function
	 * insert android apk url
	 *
	 * @param int    $userid      user id
	 * @param int    $cp_id       cp id
	 * @param string $description description
	 * @param string $updateurl   update url
	 * @param int    $versionid   version id
	 * @param string $upinfo      upinfo
	 *
	 * @return boolean
	 */
	function updateapk($userid,$cp_id,$description,$updateurl,$versionid,$upinfo)
	{
		$date=date('Y-m-d H:i:s');     
		if ($upinfo==0) {
				 $query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."  where cp_id=$cp_id");
				$row = $query->row();
				$time= $row->date;
			   
				$queryid = $this->db->query("select id from  ".$this->db->dbprefix('product_version')."  where product_channel_id=$cp_id and updatetime='$time'");
				$rel = $queryid->row();
				$id= $rel->id;
				
				$queryactive = $this->db->query("select active from  ".$this->db->dbprefix('product_version')." where id=$id");
				$acrel = $queryactive->row();
				$active= $acrel->active;
			if ($active==1) {
					$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date'
					where id = $id ";
			} else {
					$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date', active=1
					where id = $id ";
			}
				
									   
			   $this->db->query($sql);
		} else {            
					$data = array (
					
					'product_channel_id' => $cp_id, 
					'version' => $versionid ,
					'updateurl'=>$updateurl,
					'updatetime'=>$date    ,
					'description'=>$description                    
					);        
					 $this->db->insert('product_version', $data);
		}
			$sql = "update ".$this->db->dbprefix('channel_product')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',date='$date' 
			where cp_id = $cp_id and user_id = $userid";                    
			$this->db->query($sql);
			$affect = $this->db->affected_rows();
		if ($affect>0) {
				return true;
		}
			return false;
			
	}
	
	/**
	 * Updateapp function
	 * insert iphone apk url
	 *
	 * @param int    $userid      user id
	 * @param int    $cp_id       cp id
	 * @param string $description description
	 * @param string $updateurl   update url
	 * @param int    $versionid   version id
	 * @param string $upinfo      upinfo
	 *
	 * @return boolean
	 */
	function updateapp($userid,$cp_id,$description,$updateurl,$versionid,$upinfo)
	{
			$date=date('Y-m-d H:i:s');    
		if ($upinfo==0) {
				 $query = $this->db->query("select date from ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
				$row = $query->row();
				$time= $row->date;
				$queryid = $this->db->query("select id from  ".$this->db->dbprefix('product_version')."  where product_channel_id=$cp_id and updatetime='$time'");
				$rel = $queryid->row();
				$id= $rel->id;
				$queryactive = $this->db->query("select active from  ".$this->db->dbprefix('product_version')." where id=$id");
				$acrel = $queryactive->row();
				$active= $acrel->active;
				
			if ($active==1) {
					$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date'
					where id = $id ";
			} else {
					$sql = "update ".$this->db->dbprefix('product_version')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',updatetime='$date', active=1
					where id = $id ";
			}
			   $this->db->query($sql);
		} else {                
			$data = array (
			'product_channel_id' => $cp_id, 
			'version' => $versionid ,
			'updateurl'=>$updateurl,
			'updatetime'=>$date    ,
			'description'=>$description    
			);        
			 $this->db->insert('product_version', $data);
			 
		}
			$sql = "update ".$this->db->dbprefix('channel_product')." set updateurl ='$updateurl' , description='$description' ,version='$versionid',date='$date' 
			where cp_id = $cp_id and user_id = $userid";                    
			$this->db->query($sql);
			$affect = $this->db->affected_rows();
		if ($affect>0) {
				return true;
		}
			return false;
	}
	
	/**
	 * Judgeupdate function
	 * To determine whether the automatic updates
	 *
	 * @param int $cp_id cp id
	 *
	 * @return boolean
	 */
	function judgeupdate($cp_id)
	{
		$sql="select updateurl from ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
			foreach ($query->result() as $row) {
				if ($row->updateurl!="") {
						   return true;
				}
			}
		}
				   return false;
	}

	/**
	 * Getupdatehistory function
	 * Get automatically update history information
	 *
	 * @param int $cp_id cp id
	 *
	 * @return boolean
	 */
	function getupdatehistory($cp_id)
	{
			$sql="select pv.id,pv.product_channel_id,cp.channel_id,pv.version,pv.updateurl,pv.updatetime,c.channel_name from
			 ".$this->db->dbprefix('product_version')."   pv, ".$this->db->dbprefix('channel_product')."  cp, ".$this->db->dbprefix('channel')."  c where pv.product_channel_id=cp.cp_id 
			 and c.channel_id=cp.channel_id and pv.product_channel_id=$cp_id and pv.active=1";
			$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
					  return $query->result_array();
		}
			   return null; 
	}

	/**
	 * Getuapkplatform function
	 * Get platform information
	 *
	 * @param int $channel_id channel id
	 *
	 * @return query row_array
	 */
	function getuapkplatform($channel_id)
	{
			$sql="select platform from   ".$this->db->dbprefix('channel')."  where channel_id =$channel_id";                            
			$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
					  return $query->row_array();
		}
			   return null; 
	}
	
	/**
	 * Getakpinfo function
	 * Get updated apk with the app information
	 *
	 * @param int $userid user id
	 * @param int $cp_id  cp id
	 *
	 * @return query result_array
	 */
	function getakpinfo($userid,$cp_id)
	{                
			$sql="select cp.*,c.channel_name from  ".$this->db->dbprefix('channel_product')."  cp  inner join  ".$this->db->dbprefix('channel')."  c on 
			c.channel_id=cp.channel_id where cp.cp_id=$cp_id and cp.user_id=$userid";             
			$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
					  return $query->result_array();
		}
			   return null; 
	}
	
	/**
	 * Getversionid function
	 * Get automatically update the version number information
	 *
	 * @param int    $cp_id     cp id
	 * @param int    $versionid version id
	 * @param string $upinfo    upinfo
	 *
	 * @return boolean
	 */
	function getversionid($cp_id,$versionid,$upinfo)
	{
		$query = $this->db->query("select date from  ".$this->db->dbprefix('channel_product')."   where cp_id=$cp_id");
		$row = $query->row();
		$time= $row->date;
		$channelcount = $this->getchannelversioncount($cp_id);            
		if ($upinfo==0) {
			if ($channelcount==1) {
						$sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and active=1";
			} else {
						$sql="select  distinct version from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <'$time'  and active=1";
			}
		} else {
				  $sql="select  distinct version from  ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and updatetime <='$time'  and active=1";
		}
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
				  $dataversion= $query->result_array();
			foreach ($dataversion as $data) {
					  $result=strcmp($data['version'], $versionid);
				if ($channelcount==1 && $upinfo==0) {
						  $comversion=true;
				} else {
					if ($result>=0) {
						 $comversion=false;
						 break;
					} else {
						 $comversion=true;
					}
				}
			}
			return $comversion;
		} else {
			if ($sql!=""&& $upinfo==1) {
				return true;
			}
		}
		if ($sql!="" && $upinfo==0 && $channelcount==1) {
			 return true;
		} 
		  return false; 
	}
	
	/**
	 * Getchannelversioncount function
	 * get channel version count from product_version
	 *
	 * @param int $cp_id cp id
	 *
	 * @return int count
	 */
	function getchannelversioncount($cp_id)
	{
		$sql="select count(*) channelcount  from ".$this->db->dbprefix('product_version')."   where product_channel_id=$cp_id and active=1";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
				$row = $query->row();
				$count=  $row->channelcount;
			if ($count==1||$count==0) {
					return 1;
			} else {
					return $count;
			}
		}
			return 1;
			
	}
	
	/**
	 * Getupdatelistinfo function
	 * Get updated automatically update the list of history
	 *
	 * @param int $vp_id vp id
	 *
	 * @return query row_array
	 */
	function getupdatelistinfo($vp_id)
	{
		$sql="select * from   ".$this->db->dbprefix('product_version')."   where id=$vp_id";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
					  return $query->row_array();
		}
			   return null; 
	}
	
	/**
	 * Getnewlistinfo function
	 * Get automatic updates of the information in the new list
	 *
	 * @param int $cp_id cp id
	 *
	 * @return query row_array
	 */
	function getnewlistinfo($cp_id)
	{
		$sql="select * from  ".$this->db->dbprefix('channel_product')."    where cp_id=$cp_id";
		$query = $this->db->query($sql);
		if ($query!=null&&$query->num_rows()>0) {
					  return $query->row_array();
		}
			   return null; 
	}
	
	/**
	 * Deleteupdate function
	 * Delete automatically update in the history list
	 *
	 * @param int $vp_id vp id
	 *
	 * @return void
	 */
	function deleteupdate($vp_id)
	{
			$data=array(
			'active'=>0
			);
			$this->db->where('id', $vp_id);
			$this->db->update('product_version', $data);
	}

    /**
     * GetServernameById function 
     * Get server name by id
     * 
     * @param int $server_id server id
     * 
     * @return query server_name
     */
    function getServernameById($server_id)
    {
        $this->db->from($this->db->dbprefix('server'));
        $this->db->where('server_id', $server_id);
        $this->db->where('active', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
                return $query->row()->server_name;
        } else {
                return '';
        }
    }

    /**
     * GetServernameById function 
     * Get server name by id
     * 
     * @param int $server_id server id
     * 
     * @return query server_name
     */
    function getServeridByName($appid,$server_name)
    {
    	$server_name = $this->unescape(implode("','", $server_name));
        $sql = "select server_id from razor_server where product_id ='$appid' and server_name in ('" . $server_name . "');";
		$query = $this->db->query($sql);
		$list = array();
		foreach ($query->result_array() as $row) {
			$list[] =  $row['server_id'];
		}
		return $list;
    }
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
}
