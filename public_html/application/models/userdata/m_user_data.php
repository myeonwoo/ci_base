<?php
class M_user_data extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}
	
	function get_user_info($user_id,$data_type){
		if(!$user_id){
			return;
		}
		
		$this->china_db->where('user_id',$user_id);
		$this->china_db->where('data_type',$data_type);
		
		$cursor = $this->china_db->get('USER_DATA');
        $result = $cursor->result_array();
        
        $cursor->free_result();
        
		return $result;
	}
}	
?>