
<?php
class M_fullservice_text extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}
	
	function get_text(){
		$cursor = $this->china_db->get('HSK_TEXT');
		$result = $cursor->result_array();
	
		$cursor->free_result();
		
		return $result;
	}
	
	function get_prev_text($text_id){
		
		$this->china_db->where('text_id <',$text_id);
		$cursor = $this->china_db->get('HSK_TEXT');
		$result = $cursor->result_array();
	
		$cursor->free_result();

		return $result;
	}
	
	function get_next_text($text_id){
		$this->china_db->where('text_id >',$text_id);
		$cursor = $this->china_db->get('HSK_TEXT');
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}

	//지난 적중특강 수정 
	function mod_text($data){
		$this->china_db->set('created_time',date('Y-m-d H:i:s'));
		$this->china_db->where('text_id', 1);
		$result = $this->china_db->update('HSK_TEXT', $data);
		return $result;
	}
}

