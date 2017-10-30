<?php
class M_freemovie extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}

	//무료강의 리스트
	function get_list($where_arr=null, $start=null, $limit=null, $where_arr_in= null){
		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}

		if($where_arr_in){
			foreach ($where_arr_in as $key => $value) {
				$this->china_db->where_in($value["filed_name"], $value["filed_value"]);
			}
		}
		
		$this->china_db->where_not_in('freemovie_type', 'TYPE_04');
		$this->china_db->order_by("created_time", "DESC");
		$cursor = $this->china_db->get('FREEMOVIE', $limit, $start);

		$this->db->flush_cache();

		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;
	}
	
	//- 어드민 무료강의 리스트
	function adm_get_list(){
		$this->china_db->order_by("created_time", "DESC");
		$cursor = $this->china_db->get('FREEMOVIE', $limit, $start);
		$this->db->flush_cache();
		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}

	//리스트 건수
	function get_list_cnt($where_arr=null){
		$this->china_db->select('count(*) cnt');

		if($where_arr){
			foreach ($where_arr as $key => $value) {
				$this->china_db->where($value["filed_name"], $value["filed_value"]);
			}
		}
		
		$this->china_db->where_not_in('freemovie_type', 'TYPE_04');
		$cursor = $this->china_db->get('FREEMOVIE');
		$result = $cursor->result_array();
		
		$cursor->free_result();
		
		return $result;
	}

	//무료강의 정보
	function get_info($freemovie_id){
		if($freemovie_id) $this->china_db->where("freemovie_id", $freemovie_id);
		
		$this->china_db->order_by("created_time", "ASC");
		
		$cursor = $this->china_db->get('FREEMOVIE');
		$result = $cursor->row_array();
		
		$cursor->free_result();

		return $result;
	}

	//무료강의 등록
	function freemovie_detail_ins($data){
		$result = $this->china_db->insert('FREEMOVIE', $data);
		return $result;
	}

	//무료강의 수정
	function freemovie_detail_mod($data, $freemovie_id){
		$this->china_db->where('freemovie_id', $freemovie_id);
		$result = $this->china_db->update('FREEMOVIE', $data);
		
		return $result;
	}

	//조회수 업데이트
	function update_view_cnt($freemovie_id){
		$this->china_db->where('freemovie_id', $freemovie_id);

		$this->china_db->set('view_cnt', 'view_cnt+1', FALSE);
		$result = $this->china_db->update('FREEMOVIE');
		
		return $result;
	}
	
	//메인 무료자료 리스트
	function main_get_list($freemovie_type=null, $start=null, $limit=null){
		if($freemovie_type){
			$this->china_db->where('freemovie_type', $freemovie_type);
		}
		$this->china_db->where('DEL_YN', 'N');
		if($freemovie_type == 'TYPE_05'){
			$this->china_db->order_by("created_time", "ASC");
		}else{
			$this->china_db->order_by("created_time", "DESC");
		}
		$cursor = $this->china_db->get('FREEMOVIE', $limit, $start);
		$this->china_db->flush_cache();
		$result = $cursor->result_array();
		$cursor->free_result();
		return $result;
	}
}