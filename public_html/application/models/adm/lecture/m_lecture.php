<?php
class M_lecture extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}
	
	// - 카테고리 리스트
	function get_list(){
		$this->china_db->where('use_yn', 'Y');
		$this->china_db->order_by("lecture_category_id", "ASC");
		$cursor = $this->china_db->get('LECTURE_CATEGORY');
		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}
	
	// - 카테고리 update
	function category_update($data = array(), $lecture_category_id){
		$this->china_db->where('lecture_category_id', $lecture_category_id);
		$result = $this->china_db->update('LECTURE_CATEGORY', $data);
		return $result;
	}
	
	//- 카테고리 insert
	function category_insert($data = array()){
		$result = $this->china_db->insert('LECTURE_CATEGORY', $data);
		return $result;
	}
	
	// - 카테고리 상세보기
	function get_view($lecture_category_id){
		$this->china_db->where('use_yn', 'Y');
		$this->china_db->where('lecture_category_id', $lecture_category_id);
		$cursor = $this->china_db->get('LECTURE_CATEGORY');
		$result = $cursor->row();
		return $result;
	}
	
	// - 강좌리스트
	function lecture_list($lecture_category_id = null, $use_yn = null, $type = null, $order = null){
		if($lecture_category_id)$this->china_db->where('lecture_category_id', $lecture_category_id);
		if($use_yn)$this->china_db->where('use_yn', $use_yn);
		if($type)$this->china_db->where('type', $type);
		if($order){
			$this->china_db->order_by("order_num", $order);
		}else{
			$this->china_db->order_by("order_num", "DESC");
		}
		
		$cursor = $this->china_db->get('LECTURE_LIST');
		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}
	
	//- 강좌 리스트 insert
	function list_add($data){
		$result = $this->china_db->insert('LECTURE_LIST', $data);
		return $result;
	}
	
	//- 강좌 리스트 insert
	function list_add_batch($data = array()){
		$result = $this->china_db->insert_batch('LECTURE_LIST', $data);
		return $result;
	}

	// - 강좌 리스트 update
	function lecture_update($data = array(), $lecture_id){
		$this->china_db->where('lecture_id', $lecture_id);
		$result = $this->china_db->update('LECTURE_LIST', $data);
		return $result;
	}

	
}