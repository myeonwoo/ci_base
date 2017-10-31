<?php
class M_caption extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->new_china = $this->load->database("new_china", TRUE);
	}
	
	function get_caption_list($type){
		$this->new_china->from('CAPTION AS CAP');
		$this->new_china->join('CAPTION_CATEGORY AS CAP_CG', 'CAP_CG.cap_category_id = CAP.cap_category_id','inner');

		$this->new_china->where('CAP_CG.category_type',$type);

		$cursor = $this->new_china->get();
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}
	
	function get_caption_list_in_page($cap_category_id){
		$this->new_china->from('CAPTION AS CAP');
		$this->new_china->join('CAPTION_CATEGORY AS CAP_CG', 'CAP_CG.cap_category_id = CAP.cap_category_id','inner');
		$this->new_china->where('CAP_CG.sub_category_id',$cap_category_id);
		$this->new_china->where('CAP.cap_use_yn','Y');
		$cursor = $this->new_china->get();
		$result = $cursor->result_array();
	
		$cursor->free_result();
	
		return $result;
	}
	
	function get_caption_info($cap_id){
		$this->new_china->from('CAPTION AS CAP');
		$this->new_china->join('CAPTION_CATEGORY AS CAP_CG','CAP_CG.cap_category_id = CAP.cap_category_id','inner');
		$this->new_china->where('CAP.cap_id', $cap_id);
		$cursor = $this->new_china->get();
		$result = $cursor->row_array();
		
		$cursor->free_result();
		
		return $result;
	}
	
	function get_category_list($cap_category_id, $type){
		$this->new_china->where('cap_category_id', $cap_category_id);
		$this->new_china->where('category_use_yn', 'Y');
		$this->new_china->where('category_type', $type);
		$cursor = $this->new_china->get('CAPTION_CATEGORY');
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}
	
	function get_sub_category_list($cap_category_id, $type){
		$this->new_china->where('sub_category_id', $cap_category_id);
		$this->new_china->where('category_use_yn', 'Y');
		$cursor = $this->new_china->get('CAPTION_CATEGORY');
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}
	
	function insert_caption($data){
		$this->new_china->set($data);
		return $this->new_china->insert('CAPTION');
	}
	
	function update_caption($cap_id, $data){
		$this->new_china->where("cap_id",$cap_id);
		return $this->new_china->update('CAPTION',$data);
	}
}