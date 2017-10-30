<?php
class M_main_contents extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}

	//메인 컨텐츠 리스트
	function get_list($main_type, $main_category ){
		if($main_category) $this->china_db->where('main_category =', $main_category);
		$this->china_db->where('main_type =', $main_type);
		$this->china_db->order_by("display_order", "ASC");
		
		$cursor = $this->china_db->get('MAIN_CONTENTS');
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;
	}


	//메인 컨텐츠 등록
	function main_contents_ins($data){
		$result = $this->china_db->insert('MAIN_CONTENTS', $data);
		return $result;
	}

	//메인 컨텐츠 수정
	function main_contents_mod($data, $main_contents_id){
		$this->china_db->where('main_contents_id', $main_contents_id);
		$result = $this->china_db->update('MAIN_CONTENTS', $data);
		
		return $result;
	}
	
	//메인 컨텐츠 삭제
	function main_contents_delete($main_contents_id){
		$this->china_db->where('main_contents_id', $main_contents_id);
		$result = $this->china_db->delete('MAIN_CONTENTS');
	
		return $result;
	}

}