<?php
class M_link extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("new_china", TRUE);
	}

	//유의사항 리스트
	function get_list($link_id=null){

		$this->db->select('link_id,url,notice_txt,use_yn,title');

		if($link_id) $this->db->where("link_id",$link_id);

		$cursor = $this->db->get('LANDING_LINK');

		$return = $cursor->result_array();
		return $return;
	}

	function proc_link($link_id=null,$title,$url,$notice_txt,$use_yn,$user_id){
		if(!$link_id){
			$this->db->set("url",$url);
			$this->db->set("notice_txt",$notice_txt);
			$this->db->set("use_yn",$use_yn);
			$this->db->set("title",$title);
			$this->db->set("create_date","NOW()",false);
			$this->db->set("create_user",$user_id);
			$result = $this->db->insert("LANDING_LINK");
		}else{
			$this->db->set("url",$url);
			$this->db->set("notice_txt",$notice_txt);
			$this->db->set("use_yn",$use_yn);
			$this->db->set("title",$title);
			$this->db->where("notice_id",$notice_id);
			$result = $this->db->update("LANDING_LINK");
		}

		return $result;
	}

	function get_link_information($url){
		if(!$url) $url = "";
		$this->db->select("notice_txt");
		$this->db->where("url",$url);
		$this->db->where("use_yn","Y");
		$cursor = $this->db->get('LANDING_LINK');

		$return = $cursor->row_array();
		return $return;
	}
	
	function landing_del($link_id){
		$this->db->where("link_id",$link_id);
		$this->db->delete("LANDING_LINK");

	}
}