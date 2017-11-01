<?php
class M_content_category extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->db = $this->load->database("global", TRUE);
	}

	

	//배너 카테고리 리스트
	public function get_category_list($parent_category_id=null, $type=null){
		if($parent_category_id){
			$this->db->where('parent_category_id', $parent_category_id);
		}else{
			$this->db->where('parent_category_id = "" || parent_category_id IS  NULL');
		}	
		if($type){
			$this->db->where('category_type', $type);
		}	

		$this->db->where('use_yn','1');

		$cursor = $this->db->get('BANNER_CATEGORY');
		$data = $cursor->result_array();

		if($data){
			foreach($data as $k => $v){
				$return[$v['category_id']]['category_id'] = $v['category_id']; 
				$return[$v['category_id']]['parent_category_id'] = $v['parent_category_id']; 
				$return[$v['category_id']]['subject'] = $v['subject'];
			}
		}else{
			$return = array();
		}

		return $return;
	}

	

}



