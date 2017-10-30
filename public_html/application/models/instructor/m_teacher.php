<?php
class M_teacher extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->china_db = $this->load->database("new_china", TRUE);
	}

	//선생님 상세 리스트
	function get_list($active_status=null){
		$this->china_db->order_by("display_order", "ASC");
		if($active_status) $this->china_db->where("active_status", $active_status);
		
		$cursor = $this->china_db->get('TEACHER_DETAIL');
		$result = $cursor->result_array();
		
		$cursor->free_result();

		return $result;
	}

	//선생님 상세 정보
	function get_info($teacher_id=null){
		if($teacher_id) $this->china_db->where("teacher_id", $teacher_id);
		
		$this->china_db->order_by("display_order", "ASC");
		
		$cursor = $this->china_db->get('TEACHER_DETAIL');
		$result = $cursor->row_array();
		
		$cursor->free_result();

		return $result;
	}

	//선생님 상세 등록
	function teacher_detail_ins($data){
		$result = $this->china_db->insert('TEACHER_DETAIL', $data);
		return $result;
	}

	//선생님 상세 수정
	function teacher_detail_mod($data, $teacher_detail_id){
		$this->china_db->where('teacher_detail_id', $teacher_detail_id);
		$result = $this->china_db->update('TEACHER_DETAIL', $data);
		
		return $result;
	}

	// 선생님 리스트 (index:teacher_id)
    public function get_teacher_list_reg($config=array()) {

        if (isset($config['where_active_status'])) $this->china_db->where('active_status', $config['where_active_status']);
        if (isset($config['where_teacher_id'])) $this->china_db->where('teacher_id', $config['where_teacher_id']);
        if (isset($config['wherein_teacher_id'])) $this->china_db->where_in('teacher_id', $config['wherein_teacher_id']);
        if (isset($config['orderby_teacher_name'])) $this->china_db->order_by('teacher_name', $config['orderby_teacher_name']);
        
        $query = $this->china_db->get('TEACHER_DETAIL');
        $tmp = $query->result_array();
        $data = array();
        foreach ($tmp as $key => $item) {
            $data[$item['teacher_id']] = $item;
        }

        return $data;
    }
}