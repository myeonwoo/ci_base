<?php
class M_notice extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("new_china", TRUE);
	}

	//유의사항 리스트
	function get_list($notice_id=null){
		
		$this->db->select('notice_id,url,notice_txt,use_yn,title,device_type');
		
		if($notice_id) $this->db->where("notice_id",$notice_id);
		
		$cursor = $this->db->get('NOTICE');		
		
		$return = $cursor->result_array();
		return $return;
	}
	
	function proc_notice($notice_id=null,$title,$url,$notice_txt,$use_yn,$user_id,$device_type){
		if(!$notice_id){
			$this->db->set("url",$url);
			$this->db->set("notice_txt",$notice_txt);
			$this->db->set("use_yn",$use_yn);
			$this->db->set("title",$title);
			$this->db->set("create_date","NOW()",false);
			$this->db->set("create_user",$user_id);		
			$this->db->set("device_type",$device_type);	
			$result = $this->db->insert("NOTICE");
		}else{
			$this->db->set("url",$url);
			$this->db->set("notice_txt",$notice_txt);
			$this->db->set("use_yn",$use_yn);
			$this->db->set("title",$title);
			$this->db->set("device_type",$device_type);
			$this->db->where("notice_id",$notice_id);			
			$result = $this->db->update("NOTICE");
		}

		return $result;
	}
	
	function get_notice_information($url,$devic_type){		
		if(!$url) $url = ""; 		
		$this->db->select("notice_txt");
		$this->db->where("url",$url);
		$this->db->where("device_type",$devic_type);
		$this->db->where("use_yn","Y");
		$cursor = $this->db->get('NOTICE');
		
		$return = $cursor->row_array();
		return $return;
	}
}