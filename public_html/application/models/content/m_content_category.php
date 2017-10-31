<?php
class M_content_category extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("eng", TRUE);
	}

	//컨텐츠 카테고리 리스트 전체
	public function get_list_all($config=array()){

		if (isset($config['yn_used'])) $this->china_db->where('yn_used', $config['yn_used']);

		$query = $this->china_db->get('CONTENT_CATEGORY_NEW');
		$data = $query->result_array();

		$return = array();
		foreach($data as $k => $v){
			$return[$v['content_category_id']] = $v;
		}
		
		return $return;
	}

	//배너 카테고리 리스트
	public function get_list($parent_category_id=null, $type=null){
		if($parent_category_id){
			$this->china_db->where('parent_category_id', $parent_category_id);
		}else{
			$this->china_db->where('parent_category_id = "" || parent_category_id IS  NULL');
		}	
		if($type){
			$this->china_db->where('category_type', $type);
		}	

		$this->china_db->where('use_yn','1');

		$cursor = $this->china_db->get('BANNER_CATEGORY');
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



